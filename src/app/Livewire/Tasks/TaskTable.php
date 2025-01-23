<?php

namespace App\Livewire\Tasks;

use App\Models\TaskAgent;
use Livewire\Component;
use Livewire\WithPagination;

class TaskTable extends Component
{
    use WithPagination;

    protected $listeners = ['refreshAgents' => '$refresh'];

    public function render()
    {
        $tasks = TaskAgent::with(['task', 'agent'])->orderBy('uid', 'desc')->paginate();
        return view('livewire.tasks.task-table', compact('tasks'));
    }
}
