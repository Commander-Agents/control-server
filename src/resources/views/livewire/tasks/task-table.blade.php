<div class="overflow-x-auto relative" x-data="{ showTooltip: false, tooltipText: '', tooltipX: 0, tooltipY: 0 }">
    <table id="agents-list" class="min-w-full bg-white border border-gray-300">
        <thead>
            <tr class="bg-gray-100 text-gray-700 uppercase text-sm leading-normal">
                <th class="py-3 px-6 text-left">Task UID</th>
                <th class="py-3 px-6 text-left">Name</th>
                <th class="py-3 px-6 text-left">Agent</th>
                <th class="py-3 px-6 text-left">Type</th>
                <th class="py-3 px-6 text-left">Command</th>
                <th class="py-3 px-6 text-left">Status</th>
                <th class="py-3 px-6 text-left">Updated at</th>
                <th class="py-3 px-6 text-center">Actions</th>
            </tr>
        </thead>
        <tbody class="text-gray-600 text-sm font-light" wire:poll.5s>
            @foreach ($tasks as $task)
                <tr class="border-b border-gray-200 hover:bg-gray-100">
                    <td class="py-3 px-6 text-left whitespace-nowrap">
                        <div class="flex items-center">
                            <span class="font-medium">{{ $task->uid }}</span>
                        </div>
                    </td>
                    <td class="py-3 px-6 text-left whitespace-nowrap">
                        <div class="flex items-center">
                            <span class="font-medium">{{ $task->task->name }}</span>
                        </div>
                    </td>
                    <td class="py-3 px-6 text-left whitespace-nowrap">
                        <div class="flex items-center">
                            <span class="font-medium">{{ $task->agent->name }}</span>
                        </div>
                    </td>
                    <td class="py-3 px-6 text-left whitespace-nowrap">
                        <div class="flex items-center">
                            <span class="font-medium">{{ $task->task->type }}</span>
                        </div>
                    </td>
                    <td class="py-3 px-6 text-left whitespace-nowrap">
                        <div class="flex items-center">
                            <span class="font-medium">{{ $task->task->command }}</span>
                        </div>
                    </td>
                    <td class="py-3 px-6 text-left">
                        <span class="px-2 py-1 text-xs font-medium rounded bg-{{ $task->getColor() }}-200 text-{{ $task->getColor() }}-800">
                            {{ ucfirst($task->getStatus()) }}
                        </span>
                    </td>
                    <td class="py-3 px-6 text-left relative group">
                        <span @mouseenter="showTooltip = true; tooltipText = '{{ $task->updated_at ? $task->updated_at->format('d/m/Y H:i:s (e)') : '' }}'"
                              @mouseleave="showTooltip = false"
                              @mousemove="tooltipX = $event.clientX; tooltipY = $event.clientY">
                            {{ $task->updated_at ? $task->updated_at->diffForHumans() : 'N/A' }}
                        </span>
                    </td>
                    <td class="py-3 px-6 text-center">
                        <a href="{{ route('tasks.results', ['taskAgentId' => $task->uid]) }}">
                            <button class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">
                                View logs
                            </button>
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        {{ $tasks->links() }}
    </div>

    <!-- Tooltip en dehors du tableau -->
    <div x-show="showTooltip"
         x-cloak
         x-transition
         class="fixed z-50 bg-gray-800 text-white text-xs rounded px-2 py-1 opacity-90"
         :style="'top: ' + (tooltipY + 15) + 'px; left: ' + (tooltipX + 15) + 'px;'">
        <span x-text="tooltipText"></span>
    </div>
</div>
