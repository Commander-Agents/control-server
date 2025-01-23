<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use App\Models\Agent;
use App\Models\TaskAgent;
use App\Services\MqttService;
use App\Services\HMACService;

class UpdateLostTasksCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'agents:update-lost-tasks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix incoherent states of tasks';


    public function __construct(MqttService $mqttService, HMACService $hmacService)
    {
        parent::__construct();
    }

    public function handle()
    {
        $tasksUpdated = 0;

        $tasksUpdated += TaskAgent::whereIn('status', [TaskAgent::STATUS_ACKNOWLEDGE, TaskAgent::STATUS_INPROGRESS])
            ->whereRaw('TIMESTAMPDIFF(SECOND, updated_at, ?) > inactive_after', [now()])
            ->update([
                'status' => TaskAgent::STATUS_PENDING,
            ]
        );

        $this->info($tasksUpdated." planified tasks status have been fixed to '" . TaskAgent::STATUS_PENDING . "'");
    }
}
