<?php

namespace App\Livewire\Tasks;

use Livewire\Component;
use App\Models\TaskAgent;
use App\Models\Task;
use App\Models\Agent;

class Results extends Component
{
    public $taskAgent;
    public $shouldPoll = true;
    
    protected $listeners = ['refreshTask' => 'fetchTaskData'];
    
    public function mount($taskAgentId)
    {
        $this->taskAgent = TaskAgent::with(['task', 'agent'])->where('uid', $taskAgentId)->firstOrFail();
        $this->checkPollingStatus();
    }
    
    public function fetchTaskData()
    {
        $this->taskAgent->refresh();
        $this->checkPollingStatus();

        if (!$this->shouldPoll) {
            $this->dispatch('stopPolling');
        }
    }
    
    public function checkPollingStatus()
    {
        if (in_array($this->taskAgent->status, [TaskAgent::STATUS_COMPLETED, TaskAgent::STATUS_FAILED])) {
            $this->shouldPoll = false;
        }
    }

    public function render()
    {
        return view('livewire.tasks.results', ['taskAgent' => $this->taskAgent]);
    }
}
