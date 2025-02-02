<div class="bg-white p-6 shadow-md rounded-lg">
    <!-- Conteneur flex pour aligner le titre à gauche et le statut à droite -->
    <div class="flex justify-between items-center">
        <h2 class="text-xl font-bold text-gray-800">{{ $taskAgent->task->name }}</h2>
        <span class="inline-block px-3 py-1 rounded-full text-sm bg-{{ $taskAgent->getColor() }}-200 text-{{ $taskAgent->getColor() }}-800">
            {{ ucfirst($taskAgent->getStatus()) }}
        </span>
    </div>

    <p class="text-gray-600"><strong>Command :</strong> {{ $taskAgent->task->command }}</p>
    <p class="text-gray-600"><strong>Agent :</strong> {{ $taskAgent->agent->name }}</p>

    @if ($taskAgent->output)
        <div class="mt-4 p-3 bg-green-100 text-green-700 rounded">
            <strong>Success logs :</strong>
            <pre class="text-sm">{{ $taskAgent->output }}</pre>
        </div>
    @endif

    @if ($taskAgent->error)
        <div class="mt-4 p-3 bg-red-100 text-red-700 rounded">
            <strong>Error logs :</strong>
            <pre class="text-sm">{{ $taskAgent->error }}</pre>
        </div>
    @endif

    @if ($shouldPoll)
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                if (!window.taskPolling) {
                    window.taskPolling = setInterval(() => {
                        Livewire.dispatch('refreshTask');
                    }, 5000);
                }

                Livewire.on('stopPolling', status => {
                    clearInterval(window.taskPolling);
                    window.taskPolling = null;
                });
            });
        </script>
    @endif


    <!-- Load tailwind classes -->
    <span class="hidden">
        <span class="bg-green-200 text-green-800"></span>
        <span class="bg-red-200 text-red-800"></span>
        <span class="bg-orange-200 text-orange-800"></span>
        <span class="bg-gray-200 text-gray-800"></span>
        <span class="bg-purple-200 text-purple-800"></span>
    </span>
</div>
