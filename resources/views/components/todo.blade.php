@props(['todos'])

<div class="bg-white p-6 lg:p-8 border-b border-gray-200">
    @forelse ($todos as $todo)
        @php
            // Determine the background color based on status
            $statusClasses = match ($todo->status) {
                'pending' => 'bg-orange-500 border-orange-400',
                'in_progress' => 'bg-indigo-500 border-indigo-400',
                'done' => 'bg-green-500 border-green-400',
                default => 'bg-gray-300 border-gray-300',
            };
        @endphp
        <div class="bg-indigo-50 p-6 rounded-lg shadow-md mb-6 flex flex-col gap-5">
            <div class="flex justify-between">
                <div class="flex flex-row items-center gap-3">
                    <h2 class="text-2xl font-semibold text-gray-900">{{ $todo->title }}</h2>
                    <p class="text-gray-500 text-md">{{ \Carbon\Carbon::parse($todo->due_date)->format('m-d-Y') }}</p>
                </div>
                <div class="relative">
                    <button onclick="openOption('{{ $todo->id }}')" class=" p-1">⋮</button>
                    <div id="{{ $todo->id }}"
                        class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-10 hidden">
                        <ul class="py-1">
                            <li onclick="openUpdate({{ $todo->id }})"
                                class="px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 cursor-pointer">Edit</li>

                            <li onclick="confirmDelete({{ $todo->id }})"
                                class="px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 cursor-pointer">Delete</li>
                        </ul>
                    </div>
                    <x-modal-update :todo="$todo" />
                </div>
            </div>

            <div class="flex flex-col gap-4">
                <p class="text-gray-700 text-md">{{ $todo->description }}</p>
                <div class="p-1 w-28 rounded-md shadow-sm text-white text-center {{ $statusClasses }}">
                    {{ ucfirst(str_replace('_', ' ', $todo->status)) }}
                </div>
            </div>
        </div>
    @empty
        <p class="text-gray-500">No todos found.</p>
    @endforelse
</div>
<script>
    function confirmDelete(id) {
        Swal.fire({
            title: "Do you want to remove this task?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#6366f1",
            cancelButtonColor: "#fff",
            confirmButtonText: "Delete",
            cancelButtonText: '<span style="color:#6366f1">Cancel</span>',
        }).then((result) => {
            if (result.isConfirmed) {
                removeTodo(id);
                Swal.close(); // Close the SweetAlert modal after deletion
            }
        });
    }
    const showRemoveSuccess = (message) => {
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
        });
        Toast.fire({
            icon: "success",
            title: message,
        });
        window.location.reload();

    };

    function removeTodo(id) {

        fetch(`/remove/todo/${id}`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                // No need to send the id in body, can send empty body or omit it:
                body: JSON.stringify({})
            })
            .then(response => response.json())
            .then(result => {
                console.log('Success:', result);
                showRemoveSuccess(result.message);
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Something went wrong');
            });

    }



    function openUpdate(id) {
        document.getElementById('modalUpdate-' + id).classList.remove('hidden');
    }

    function closeUpdate(id) {
        document.getElementById('modalUpdate-' + id).classList.add('hidden');
    }

    function openOption(id) {
        // Close all open dropdowns
        document.querySelectorAll('[id^="optionDiv-"]').forEach(el => {
            if (el.id !== id) el.classList.add('hidden');
        });

        // Toggle the clicked one
        const optionDiv = document.getElementById(id);
        optionDiv.classList.toggle('hidden');
    }

    // Optional: Click outside to close any open dropdown
    document.addEventListener('click', function(event) {
        const isDropdownButton = event.target.closest('button[onclick^="openOption"]');
        const isDropdownMenu = event.target.closest('[id^="optionDiv-"]');

        if (!isDropdownButton && !isDropdownMenu) {
            document.querySelectorAll('[id^="optionDiv-"]').forEach(el => el.classList.add('hidden'));
        }
    });
</script>
