<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Console\Commands\ExecuteTasks;
use App\Console\Commands\UpdateLostTasksCommand;
use App\Console\Commands\CheckAgentStatus;

// Artisan::command('inspire', function () {
//     $this->comment(Inspiring::quote());
// })->purpose('Display an inspiring quote')->hourly();


// Schedule::command(ExecuteTasks::class)->everyMinute()->runInBackground();
Schedule::command(ExecuteTasks::class)->everyMinute();
Schedule::command(UpdateLostTasksCommand::class)->everyMinute();
Schedule::command(CheckAgentStatus::class)->everyMinute();
