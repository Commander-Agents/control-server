<x-app-layout>
    <div class="container mx-auto p-6">
        <span class="flex justify-between">
            <h1 class="text-2xl font-bold mb-4">Tasks list</h1>
            <a href="{{ route('agents.execute-playbook') }}">
                <x-primary-button>New task</x-primary-button>
            </a>
        </span>
        
        <!-- Composant Livewire -->
        @livewire('tasks.task-table')
    </div>
</x-app-layout>
