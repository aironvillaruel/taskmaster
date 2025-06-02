@props(['todo'])

<div id="modalUpdate-{{ $todo->id }}"
    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md relative">
        <h2 class="text-xl font-semibold mb-4">Update To Do</h2>

        <div class="mb-6">
            <label for="todo-title-{{ $todo->id }}" class="block mb-2 text-sm font-medium text-gray-900">Title</label>
            <input type="text" id="todo-title-{{ $todo->id }}" value="{{ $todo->title }}"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
        </div>

        <div class="mb-6">
            <label for="todo-description-{{ $todo->id }}"
                class="block mb-2 text-sm font-medium text-gray-900">Description <span
                    class="text-xs text-gray-400">(Optional)</span></label>
            <input type="text" id="todo-description-{{ $todo->id }}" value="{{ $todo->description }}"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
        </div>

        <div class="mb-6">
            <label for="todo-status-{{ $todo->id }}"
                class="block mb-2 text-sm font-medium text-gray-900">Status</label>
            <select id="todo-status-{{ $todo->id }}"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                <option value="pending" {{ $todo->status === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="in_progress" {{ $todo->status === 'in_progress' ? 'selected' : '' }}>In Progress
                </option>
                <option value="done" {{ $todo->status === 'done' ? 'selected' : '' }}>Done</option>
            </select>
        </div>

        <div class="mb-6">
            <label for="todo-due-{{ $todo->id }}" class="block mb-2 text-sm font-medium text-gray-900">Due
                Date</label>
            <input type="date" id="todo-due-{{ $todo->id }}"
                value="{{ \Carbon\Carbon::parse($todo->due_date)->format('Y-m-d') }}"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
        </div>

        <button onclick="closeUpdate({{ $todo->id }})"
            class="absolute top-2 right-2 text-gray-600 hover:text-black text-2xl leading-none">&times;</button>
        <button onclick="updateTodo({{ $todo->id }})" class="mt-4 px-4 py-2 bg-indigo-500 text-white rounded">
            Save
        </button>
    </div>
</div>

<script>
    function updateTodo(id) {
        const title = document.getElementById(`todo-title-${id}`).value;
        const description = document.getElementById(`todo-description-${id}`).value;
        const status = document.getElementById(`todo-status-${id}`).value;
        const dueDate = document.getElementById(`todo-due-${id}`).value;
        console.log(`Updating todo with ID: ${id}`);

        const data = {
            title,
            description,
            status,
            due_date: dueDate
        };
        console.log('Submitting data:', data);

        fetch(`/update/todo/${id}`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(result => {
                console.log('Success:', result);
                closeUpdate(id);
                showSuccess(result.message);

            })
            .catch(error => {
                console.error('Error:', error);
                alert('Something went wrong');
            });
    }

    const showSuccess = (message) => {
        const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            },
            didClose: () => {
                // Reload the page after the toast closes
                window.location.reload();
            }
        });
        Toast.fire({
            icon: "success",
            title: message,
        });

    };
</script>
