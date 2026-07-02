@forelse($tasks as $task)
<div class="task-card bg-white border rounded-xl p-4 mb-3 shadow-sm hover:shadow-md transition">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3">
        <div class="flex items-start gap-3 flex-1 w-full">
            <input type="checkbox"
                   hx-patch="/tasks/{{ $task->id }}/toggle"
                   hx-target="#task-list"
                   hx-swap="innerHTML"
                   hx-headers='{"X-CSRF-TOKEN":"{{ csrf_token() }}"}'
                   {{ $task->completed ? 'checked' : '' }}
                   class="w-5 h-5 rounded border-gray-300 mt-1 cursor-pointer">

            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="{{ $task->completed ? 'line-through text-gray-400' : 'font-medium text-gray-800' }}">
                        {{ $task->title }}
                    </span>
                </div>

                @if($task->description)
                    <p class="text-sm text-gray-500 mt-1">{{ Str::limit($task->description, 60) }}</p>
                @endif

                <div class="flex flex-wrap gap-2 mt-2">
                    <span class="text-xs px-2 py-0.5 rounded-full bg-gray-100 text-gray-600">
                        <i class="fas fa-tag mr-1"></i>{{ $task->category }}
                    </span>
                    <span class="text-xs px-2 py-0.5 rounded-full priority-{{ $task->priority }}">
                        <i class="fas fa-flag mr-1"></i>{{ ucfirst($task->priority) }}
                    </span>
                    @if($task->due_date)
                        <span class="text-xs px-2 py-0.5 rounded-full bg-gray-100 text-gray-600">
                            <i class="far fa-calendar-alt mr-1"></i>{{ $task->due_date->format('d M Y') }}
                        </span>
                    @endif
                    <span class="text-xs px-2 py-0.5 rounded-full status-{{ $task->status_text == 'Overdue' ? 'overdue' : ($task->completed ? 'completed' : 'pending') }}">
                        <i class="fas fa-circle mr-1 text-[6px]"></i>{{ $task->status_text }}
                    </span>
                </div>
            </div>
        </div>

        <div class="flex gap-2 w-full md:w-auto justify-end">
            <button onclick="showEditTaskModal(
                {{ $task->id }},
                '{{ addslashes($task->title) }}',
                '{{ addslashes($task->description) }}',
                '{{ $task->category }}',
                '{{ $task->priority }}',
                '{{ $task->due_date ? $task->due_date->format('Y-m-d') : '' }}',
                '{{ addslashes($task->notes) }}'
            )" class="btn-edit btn-icon text-sm">
                <i class="fas fa-edit"></i> Edit
            </button>

            <button hx-delete="/tasks/{{ $task->id }}"
                    hx-confirm="Are you sure you want to delete this task?"
                    hx-target="#task-list"
                    hx-swap="innerHTML"
                    hx-headers='{"X-CSRF-TOKEN":"{{ csrf_token() }}"}'
                    class="btn-delete btn-icon text-sm">
                <i class="fas fa-trash"></i> Delete
            </button>
        </div>
    </div>
</div>
@empty
<div class="text-center py-16">
    <i class="fas fa-inbox text-5xl text-gray-300 mb-4"></i>
    <p class="text-gray-500 text-lg">No tasks found</p>
    <p class="text-gray-400 text-sm">Click "Add New Task" to create one</p>
</div>
@endforelse

<div class="mt-4">
    {{ $tasks->links() }}
</div>