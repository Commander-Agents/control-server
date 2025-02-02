<x-app-layout>
    @section('title') - Agents list @endsection

    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Agents list</h1>
        
        <!-- Composant Livewire -->
        @livewire('agents.agent-table')
    </div>
</x-app-layout>
