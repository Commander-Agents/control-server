<?php

namespace App\Http\Controllers\Agents;

use App\Http\Controllers\Controller;
use App\Models\TaskAgent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;
use \Str;
use phpseclib3\Crypt\PublicKeyLoader;
use App\Models\Agent;
use App\Models\AgentTask;
use App\Models\OperatingSystem;
use App\Services\MqttService;
use App\Services\HMACService;

class AgentController extends Controller
{
    protected $mqttService;
    protected $hmacService;

    public function __construct(MqttService $mqttService, HMACService $hmacService)
    {
        $this->mqttService = $mqttService;
        $this->hmacService = $hmacService;
    }

    public function enroll(Request $request)
    {
        $validated = $request->validate([
            'agent_port' => 'required|integer|min:0|max:65534',
            'agent_protocol' => 'required|string|in:tcp,udp',
            'agent_hostname' => 'required|string|max:255',
            'agent_public_key' => 'required|string|max:1000',
            'agent_operating_system' => 'required|string|max:255',
            'agent_uid' => 'required|string|max:256',
        ]);

        // Check if the public key received is valid
        try {
            $key = PublicKeyLoader::load($validated['agent_public_key']);
            if (!$key instanceof \phpseclib3\Crypt\RSA\PublicKey) {
                return response()->json(['error' => 'Invalid public key format.'], 400);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid public key format.'], 400);
        }

        $operatingSystem = OperatingSystem::firstOrCreate([
            'name' => $validated['agent_operating_system']
        ]);

        // Everything is valid from the agent, now generate the secret key
        $secretKeyForHMAC = Str::random(Agent::HMAC_KEY_LENGTH);
        $agent = Agent::firstOrCreate(
            ['uid' => $validated['agent_uid']],
            [
                'name' => $validated['agent_hostname'],
                'operating_system_id' => $operatingSystem->id,
                'hostname' => $validated['agent_hostname'],
                'port' => $validated['agent_port'],
                'protocol' => $validated['agent_protocol'],
                'secret_key' => $secretKeyForHMAC,
                'secret_key_hash' => hash('sha256', $secretKeyForHMAC),
                'uid' => $validated['agent_uid']
            ]
        );

        // Handle secret_key for HMAC not decrypted on first creation
        $secret_key = $agent->secret_key;
        if(strlen($secret_key) > Agent::HMAC_KEY_LENGTH) {
            $secret_key  = Crypt::decryptString($secret_key);
        }

        // Encrypt HMAC key with public key from agent
        try {
            $encrypted_hmac_signing_key = $key->encrypt($secret_key);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to encrypt the HMAC signing key.'], 500);
        }

        // Create MQTT user for Agent
        if (!$this->mqttService->addUser($agent->uid, $secret_key)) {
            return response()->json(['error' => 'Unable to create MQTT user'], 500);
        }

        // Saving public key from agent
        Storage::put("keys/agents/{$agent->id}.pub", $validated['agent_public_key']);

        return response()->json([
            'success' => true,
            'encrypted_hmac_signing_key' => base64_encode($encrypted_hmac_signing_key),
            'server_public_key' => $this->retrieveServerPublicKey(),
        ], 200);
    }

    private function retrieveServerPublicKey() {
        $publicKeyPath = 'keys/server/public_key.pem';
        if (Storage::exists($publicKeyPath)) {
            $publicKey = Storage::get($publicKeyPath);
        } else {
            throw new \Exception("Public key file not found at: $publicKeyPath");
        }
        return $publicKey;
    }

    public function keepAlive(Request $request)
    {
        $validated = $request->validate([
            'agent_uid' => 'required|string|max:256',
            'status' => 'required|boolean'
        ]);

        $agent = Agent::where('uid', $validated["agent_uid"])->firstOrFail();

        // Check signature of the payload
        $clientSignature = $request->header('X-Signature');
        $secretKey = $agent->secret_key;
        if (!$this->hmacService->checkSignature($secretKey, $request->all(), $clientSignature)) {
            return response()->json(['error' => 'Invalid signature'], 403);
        }

        // Signature validated, update last_contact for the agent
        $agent->status = ($validated["status"] ? strval(Agent::STATUS_CONNECTED) : strval(Agent::STATUS_DISCONNECTED));
        $agent->last_contact = now();
        $agent->save();

        return response()->json(['status' => 'OK'], 200);
    }

    public function commandAcknowledged(Request $request)
    {
        $validated = $request->validate([
            'agent_uid' => 'required|string|max:256',
            'command_uid' => 'required|string|max:50'
        ]);

        $agent = Agent::where('uid', $validated["agent_uid"])->firstOrFail();
        $task = TaskAgent::where('uid', $validated['command_uid'])->firstOrFail();

        // Check signature of the payload
        $clientSignature = $request->header('X-Signature');
        $secretKey = $agent->secret_key;
        if (!$this->hmacService->checkSignature($secretKey, $request->all(), $clientSignature)) {
            return response()->json(['error' => 'Invalid signature'], 403);
        }

        // Signature validated, update status for the task & avoid rolling back status if already completed
        if($task->status == TaskAgent::STATUS_INPROGRESS || $task->status == TaskAgent::STATUS_PENDING) {
            TaskAgent::where('uid', $validated['command_uid'])
                ->update([
                    'status' => TaskAgent::STATUS_ACKNOWLEDGE,
                ]
            );
        }

        // Also update agent last contact date
        $agent->status = strval(Agent::STATUS_CONNECTED);
        $agent->last_contact = now();
        $agent->save();

        return response()->json(['status' => 'OK'], 200);
    }

    public function commandOutput(Request $request)
    {
        $validated = $request->validate([
            'agent_uid' => 'required|string|max:256',
            'command' => 'required|array',
            'command.stdout' => 'nullable|string',
            'command.stderr' => 'nullable|string',
            'command.uid' => 'required|string|max:50',
        ]);
        
        $agent = Agent::where('uid', $validated["agent_uid"])->firstOrFail();
        $task = TaskAgent::where('uid', $validated['command']['uid'])->firstOrFail();

        // Check signature of the payload
        $clientSignature = $request->header('X-Signature');
        if (!$this->hmacService->checkSignature($agent->secret_key, $request->all(), $clientSignature)) {
            return response()->json(['error' => 'Invalid signature'], 403);
        }

        // Signature validated, update last_contact for the agent
        $agent->last_contact = now();
        $agent->save();

        // Using the $task->save() with the arguments doesn't work here, don't know why
        TaskAgent::where('uid', $validated['command']['uid'])
            ->update([
                'status' => $validated['command']['stderr'] != null ? TaskAgent::STATUS_FAILED : TaskAgent::STATUS_COMPLETED,
                'output' => $validated['command']['stdout'] ?? null,
                'error' => $validated['command']['stderr'] ?? null,
            ]
        );

        return response()->json(['status' => 'OK'], 200);
    }

    public function test() 
    {
        $topic = "agents/f536f214bfd0664f73bb3828b8d3211ae767822c967c092ab8eefd1122420ccd";
        $message = 'echo . > C:/Users/enzor/Downloads/test_py';
        $message = 'tree';

        $this->mqttService->sendMessage($topic, $message, 0, false);

        return response()->json(['message' => 'Message sent successfully!']);
    }
}
