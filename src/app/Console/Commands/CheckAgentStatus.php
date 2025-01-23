<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Agent;
use Illuminate\Support\Carbon;

class CheckAgentStatus extends Command
{
    protected $signature = 'agents:check-status';

    protected $description = 'Check & update agents if they are not connected for more than their inactive time';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $threshold = Carbon::now()->subSeconds(Agent::INACTIVE_TIME);
        $updated = Agent::where('last_contact', '<', $threshold)
            ->where('status', strval(Agent::STATUS_CONNECTED))
            ->update(['status' => strval(Agent::STATUS_DISCONNECTED)]);

        $this->info("$updated agents marked as inactive.");
    }
}
