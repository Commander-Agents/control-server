<?php

namespace App\Livewire\Agents;

use App\Models\Agent;
use App\Models\Task;
use App\Models\TaskAgent;
use Livewire\Component;
use Illuminate\Support\Str;

class AgentDeployTask extends Component
{
    public $agents;
    public $selectedAgent;
    public $taskType = 'command'; // Default to Command block
    public $name = '';
    public $command = '';
    public $playbook = '';
    public $scheduledTime;

    public function mount($agentId = null)
    {
        $this->agents = Agent::all();
        $this->scheduledTime = now()->format('Y-m-d\TH:i');
        $this->selectedAgent = $agentId ?? $this->agents->first()->id ?? null;
    }

    public function updatedTaskType($value)
    {
        // Reset inputs when task type changes
        $this->command = '';
        $this->playbook = '';
    }

    public function submit()
    {
        $this->validate([
            'selectedAgent' => 'required|exists:agents,id',
            'name' => 'required|string|max:255',
            'taskType' => 'required|in:command,playbook',
            'command' => 'required_if:taskType,command|string|max:255',
            'playbook' => 'required_if:taskType,playbook|string|max:255',
            // 'scheduledTime' => 'required|date|after_or_equal:now',
            'scheduledTime' => 'required|date',
        ]);

        try {
            $task = Task::create([
                'name' => $this->name,
                'command' => $this->taskType == "command" ? $this->command : $this->playbook,
                'type' => $this->taskType,
            ]);
            $taskSchedule = TaskAgent::create([
                'uid' => Str::orderedUuid(),
                'task_id' => $task->id,
                'agent_id' => $this->selectedAgent,
                'scheduled_at' => $this->scheduledTime
            ]);

            $this->name = '';
            $this->command = '';
            $this->playbook = '';
            $this->scheduledTime = now()->format('Y-m-d\TH:i');
            $this->selectedAgent = $agentId ?? $this->agents->first()->id ?? null;
    
            $this->dispatch('show-toast', [
                "type" => "success",
                "message" => "Task submitted successfully"
            ]);
        } catch(\Exception $ex) {
            if($task) {
                $task->delete();
            }
            if($taskSchedule) {
                $taskSchedule->delete();
            }
            
            $this->dispatch('show-toast', [
                "type" => "error",
                "message" => "Error during the creation of the task"
            ]);
            throw new \Exception('Task creation failed.');
        }
    }

    public function render()
    {
        return view('livewire.agents.agent-deploy-task');
    }
}
