@forelse($tasks as $task)

<div class="bg-gray-50 border rounded-xl p-4 mb-3">

    <div class="flex justify-between items-center">

        <div class="flex items-center gap-3">

            <!-- Toggle Complete -->

            <input
                type="checkbox"
                hx-patch="/tasks/{{ $task->id }}/toggle"
                hx-target="#task-list"
                hx-swap="innerHTML"
                hx-headers='{"X-CSRF-TOKEN":"{{ csrf_token() }}"}'
                {{ $task->completed ? 'checked' : '' }}
            >

            <span class="{{ $task->completed ? 'line-through text-gray-400' : '' }}">
                {{ $task->title }}
            </span>

        </div>

        <div class="flex gap-2">

            <!-- Edit -->

            <form
                hx-put="/tasks/{{ $task->id }}"
                hx-target="#task-list"
                hx-swap="innerHTML"
                hx-headers='{"X-CSRF-TOKEN":"{{ csrf_token() }}"}'
                class="flex gap-2"
            >

                <input
                    type="text"
                    name="title"
                    value="{{ $task->title }}"
                    class="border px-2 py-1 rounded"
                >

                <button
                    class="bg-yellow-400 text-white px-3 rounded"
                >
                    Update
                </button>

            </form>

            <!-- Delete -->

            <button
                hx-delete="/tasks/{{ $task->id }}"
                hx-confirm="Are you sure?"
                hx-target="#task-list"
                hx-swap="innerHTML"
                hx-headers='{"X-CSRF-TOKEN":"{{ csrf_token() }}"}'
                class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded"
            >
                Delete
            </button>

        </div>

    </div>

</div>

@empty

<div class="text-center text-gray-500 py-10">

    No tasks found 🚀

</div>

@endforelse

<!-- Pagination -->

<div class="mt-6">

    {{ $tasks->links() }}

</div>