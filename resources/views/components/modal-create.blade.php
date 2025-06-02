<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<div id="modalOverlay" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md relative">
        <h2 class="text-xl font-semibold mb-4">Create To Do</h2>
        <div class="mb-6">
            <label for="default-input" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Title</label>
            <input type="text" id="todo-title"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                required>
        </div>
        <div class="mb-6">
            <label for="default-input"
                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Description<span
                    class="text-xs text-gray-400"> (Optional)</span></label>
            <input type="text" id="todo-description"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
        </div>
        <div class="mb-6">
            <label for="default-input"
                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Status</label>
            <select id="todo-status"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <option value="pending" selected>Pending</option>
                <option value="in_progress">In Progress</option>
                <option value="done">Done</option>
            </select>
        </div>
        <div class="mb-6">
            <label for="default-input" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Due
                Date</label>
            <input type="date" id="todo-due"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                required>
        </div>
        <button onclick="closeModal()"
            class="absolute top-2 right-2 text-gray-600 hover:text-black text-2xl leading-none">&times;</button>
        <button onclick="submitTodo()" class="mt-4 px-4 py-2 bg-indigo-500 text-white rounded">
            Submit
        </button>
    </div>
</div>
<script>
    function submitTodo() {
        const title = document.getElementById('todo-title').value;
        const description = document.getElementById('todo-description').value;
        const status = document.getElementById('todo-status').value;
        const dueDate = document.getElementById('todo-due').value;

        if (!title) {
            showCreateError('Title is required.');

        } else if (!dueDate) {
            showCreateError('Due date is required.');
        }
        const data = {
            title: title,
            description: description,
            status: status,
            due_date: dueDate
        };
        fetch('/create/todo', { // Update to match your Laravel route
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(result => {
                console.log('Success:', result);
                showCreateSuccess(result.message); // Show success message
                closeModal(); // Optional: close modal after success
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }

    const showCreateSuccess = (message) => {
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

    const showCreateError = (message) => {
        const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 3000,
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
            icon: "error",
            title: message,
        });

    };
</script>
