@forelse($tasks as $task)
<div class="flex justify-between items-center bg-white p-4 rounded-lg shadow hover:shadow-md transition">
    <span class="text-gray-800 font-medium">{{ $task->title }}</span>

    <button 
        hx-delete="/tasks/{{ $task->id }}"
        hx-target="#task-list"
        hx-swap="innerHTML"
        hx-headers='{"X-CSRF-TOKEN": "{{ csrf_token() }}"}'
        class="text-red-500 hover:text-red-600 font-bold px-3 py-1 border border-red-500 rounded-lg transition"
        title="Delete Task"
    >
        Delete
    </button>
</div>
@empty
<div class="text-gray-500 italic text-center py-6">
    No tasks yet. Add your first task above! 🚀
</div>
@endforelse