<x-app-layout>
    <x-slot name="header">
        <button onclick="openModal()"
            class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
            Create Task
        </button>

    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form method="GET" action="{{ route('dashboard') }}">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-4">
                    <select name="status" onchange="this.form.submit()"
                        class="bg-gray-50 border border-gray-300 w-40 text-gray-900 text-sm rounded-lg block p-2.5">
                        <option value="">All</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In
                            Progress</option>
                        <option value="done" {{ request('status') == 'done' ? 'selected' : '' }}>Done</option>
                    </select>
                </div>
            </form>


            <x-todo :todos="$todos" />

            <x-modal-create />
        </div>
    </div>
</x-app-layout>
<script>
    function openModal() {
        document.getElementById('modalOverlay').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('modalOverlay').classList.add('hidden');
    }
</script>
