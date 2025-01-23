<?php

namespace App\Livewire\Agents;

use App\Models\Agent;
use Livewire\Component;
use Livewire\WithPagination;

class AgentTable extends Component
{
    use WithPagination;

    protected $listeners = ['refreshAgents' => '$refresh'];

    public function render()
    {
        $agents = Agent::with(['operating_system', 'groups'])->orderBy('name')->paginate();

        return view('livewire.agents.agent-table', compact('agents'));
    }
}
