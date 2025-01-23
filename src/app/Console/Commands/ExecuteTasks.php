<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Agent;
use App\Models\TaskAgent;
use App\Services\MqttService;
use App\Services\HMACService;

class ExecuteTasks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'agents:execute-tasks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send planned tasks';

    protected $mqttService;
    protected $hmacService;

    public function __construct(MqttService $mqttService, HMACService $hmacService)
    {
        parent::__construct();

        $this->mqttService = $mqttService;
        $this->hmacService = $hmacService;
    }

    public function handle()
    {
        $tasksSent = 0;
        $schedules = TaskAgent::where('scheduled_at', '<=', now())->where('status', TaskAgent::STATUS_PENDING)->get();

        foreach ($schedules as $schedule) {
            foreach ($schedule->task->agents as $agent) {
                if($agent->status != strval(Agent::STATUS_CONNECTED)) {
                    continue;
                }

                $agent->tasks()->updateExistingPivot($schedule->task->id, [
                    'status' => TaskAgent::STATUS_INPROGRESS,
                ]);

                $payload = [
                    "command" => $schedule->task->command,
                    "signature" => $this->hmacService->generateSignature($agent->secret_key, $schedule->task->command),
                    "uid" => $schedule->uid
                ];
                $this->info(json_encode($payload));
                
                $topic = "agents/".$agent->uid;
                $this->mqttService->sendMessage($topic, json_encode($payload), 0, false);
                $tasksSent++;
            }
        }

        $this->info($tasksSent.' planified tasks have been sent to agents');
    }
}
