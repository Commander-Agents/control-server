<x-app-layout>
    @section('title') - Results ({{ $taskAgentId }}) @endsection

    <div class="container mx-auto p-6">
        <!-- Composant Livewire -->
        @livewire('tasks.results', ['taskAgentId' => request()->route('taskAgentId')])
    </div>
</x-app-layout>
