<x-app-layout>
    <div class="container mx-auto p-6">
        @livewire('agents.agent-deploy-task', ['agentId' => request()->route('agentId')])
    </div>
</x-app-layout>
