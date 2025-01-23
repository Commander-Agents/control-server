<div class="p-6 bg-white rounded shadow-md">
    <h1 class="text-xl font-bold mb-4">Create new task on an agent</h1>

    @if (session()->has('success'))
        <div class="mb-4 text-green-600">
            {{ session('success') }}
        </div>
    @endif

    <form wire:submit.prevent="submit">
        <div class="mb-4">
            <label for="selectedAgent" class="block text-gray-700 font-medium mb-2">Select an agent :</label>
            <select id="selectedAgent" wire:model="selectedAgent" class="w-full border border-gray-300 rounded px-3 py-2 focus:ring focus:ring-blue-300">
                @foreach ($agents as $agent)
                    <option value="{{ $agent->id }}">{{ $agent->name }}</option>
                @endforeach
            </select>
            @error('selectedAgent') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label for="name" class="block text-gray-700 font-medium mb-2">Task name :</label>
            <input type="text" id="name" wire:model="name" class="w-full border border-gray-300 rounded px-3 py-2 focus:ring focus:ring-blue-300" placeholder="Name of this task">
            @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="flex space-x-4 mb-4">
            <button type="button" wire:click="$set('taskType', 'command')" class="px-6 py-3 rounded font-medium {{ $taskType === 'command' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700' }}">
                Command
            </button>
            <button type="button" wire:click="$set('taskType', 'playbook')" class="px-6 py-3 rounded font-medium {{ $taskType === 'playbook' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700' }}">
                Playbook
            </button>
        </div>

        @if ($taskType === 'command')
            <div class="mb-4">
                <label for="command" class="block text-gray-700 font-medium mb-2">Command :</label>
                <input type="text" id="command" wire:model="command" class="w-full border border-gray-300 rounded px-3 py-2 focus:ring focus:ring-blue-300" placeholder="Enter your commands">
                @error('command') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
        @elseif ($taskType === 'playbook')
            <div class="mb-4">
                <label for="playbook" class="block text-gray-700 font-medium mb-2">Playbook :</label>
                <select id="playbook" wire:model="playbook" class="w-full border border-gray-300 rounded px-3 py-2 focus:ring focus:ring-blue-300">
                    <option value="">Select a playbook</option>
                    <!-- Ajouter ici vos playbooks disponibles -->
                    <option value="playbook1.yml">playbook1.yml</option>
                    <option value="playbook2.yml">playbook2.yml</option>
                </select>
                @error('playbook') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
        @endif

        <div class="mb-4 max-w-48">
            <label for="scheduledTime" class="block text-sm font-medium text-gray-700">Execute at</label>
            <input 
                type="datetime-local" 
                id="scheduledTime" 
                wire:model="scheduledTime" 
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
            />
            @error('scheduledTime') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <button type="submit" class="px-6 py-3 bg-green-600 text-white font-medium rounded hover:bg-green-700">
            Soumettre
        </button>
    </form>
</div>
