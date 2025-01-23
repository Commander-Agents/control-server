<div class="overflow-x-auto relative" x-data="{ showTooltip: false, tooltipText: '', tooltipX: 0, tooltipY: 0 }">
    <table id="agents-list" class="min-w-full bg-white border border-gray-300">
        <thead>
            <tr class="bg-gray-100 text-gray-700 uppercase text-sm leading-normal">
                <th class="py-3 px-6 text-left">Name</th>
                <th class="py-3 px-6 text-left">Group(s)</th>
                <th class="py-3 px-6 text-left">Operating system</th>
                <th class="py-3 px-6 text-left">Status</th>
                <th class="py-3 px-6 text-left">Last contact</th>
                <th class="py-3 px-6 text-center">Actions</th>
            </tr>
        </thead>
        <tbody class="text-gray-600 text-sm font-light" wire:poll.5s>
            @foreach ($agents as $agent)
                <tr class="border-b border-gray-200 hover:bg-gray-100">
                    <td class="py-3 px-6 text-left whitespace-nowrap">
                        <div class="flex items-center">
                            <span class="font-medium">{{ $agent->name }}</span>
                        </div>
                    </td>
                    <td class="py-3 px-6 text-left max-w-16">
                        @forelse($agent->groups as $group)
                            <x-badges.white>{{ $group->name }}</x-badges.white>
                        @empty
                            <x-badges.white>default</x-badges.white>
                        @endforelse
                    </td>
                    <td class="py-3 px-6 text-left">
                        {{ $agent->operating_system ? $agent->operating_system->name : 'N/A' }}
                    </td>
                    <td class="py-3 px-6 text-left">
                        <span class="px-2 py-1 text-xs font-medium rounded bg-{{ $agent->getColor() }}-200 text-{{ $agent->getColor() }}-800">
                            {{ ucfirst($agent->getStatus()) }}
                        </span>
                    </td>
                    <td class="py-3 px-6 text-left relative group">
                        <span @mouseenter="showTooltip = true; tooltipText = '{{ $agent->last_contact ? $agent->last_contact->format('d/m/Y H:i:s (e)') : '' }}'"
                              @mouseleave="showTooltip = false"
                              @mousemove="tooltipX = $event.clientX; tooltipY = $event.clientY">
                            {{ $agent->last_contact ? $agent->last_contact->diffForHumans() : 'N/A' }}
                        </span>
                    </td>
                    <td class="py-3 px-6 text-center">
                        <a href="{{ route('agents.execute-playbook', ['agentId' => $agent->id]) }}">
                            <button class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">
                                Deploy
                            </button>
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        {{ $agents->links() }}
    </div>

    <!-- Tooltip en dehors du tableau -->
    <div x-show="showTooltip"
         x-cloak
         x-transition
         class="fixed z-50 bg-gray-800 text-white text-xs rounded px-2 py-1 opacity-90"
         :style="'top: ' + (tooltipY + 15) + 'px; left: ' + (tooltipX + 15) + 'px;'">
        <span x-text="tooltipText"></span>
    </div>

    <!-- Load tailwind classes -->
    <span class="hidden">
        <span class="bg-green-200 text-green-800"></span>
        <span class="bg-red-200 text-red-800"></span>
        <span class="bg-orange-200 text-orange-800"></span>
        <span class="bg-gray-200 text-gray-800"></span>
        <span class="bg-purple-200 text-purple-800"></span>
    </span>
</div>