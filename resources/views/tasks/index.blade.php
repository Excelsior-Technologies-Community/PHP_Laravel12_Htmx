@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto">

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl p-4 shadow border-l-4 border-blue-500">
            <p class="text-sm text-gray-500">Total Tasks</p>
            <p class="text-2xl font-bold">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow border-l-4 border-green-500">
            <p class="text-sm text-gray-500">Completed</p>
            <p class="text-2xl font-bold text-green-600">{{ $stats['completed'] }}</p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow border-l-4 border-yellow-500">
            <p class="text-sm text-gray-500">Pending</p>
            <p class="text-2xl font-bold text-yellow-600">{{ $stats['pending'] }}</p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow border-l-4 border-red-500">
            <p class="text-sm text-gray-500">Overdue</p>
            <p class="text-2xl font-bold text-red-600">{{ $stats['overdue'] }}</p>
        </div>
    </div>

    <form method="GET" class="mb-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Search tasks..."
                   class="w-full border p-3 rounded-lg focus:ring-2 focus:ring-blue-400 outline-none">

            <select name="category" class="w-full border p-3 rounded-lg">
                <option value="all">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>
                        {{ ucfirst($cat) }}
                    </option>
                @endforeach
            </select>

            <select name="priority" class="w-full border p-3 rounded-lg">
                <option value="all">All Priority</option>
                <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
            </select>

            <select name="status" class="w-full border p-3 rounded-lg">
                <option value="all">All Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Overdue</option>
            </select>

            <button type="submit" class="bg-blue-500 text-white px-4 rounded-lg hover:bg-blue-600 transition">
                <i class="fas fa-filter"></i> Filter
            </button>
        </div>
    </form>

    <button onclick="showAddTaskModal()" class="btn-add btn-icon mb-6">
        <i class="fas fa-plus"></i> Add New Task
    </button>

    <div id="task-list">
        @include('tasks.partials.task-list')
    </div>

    <div class="mt-6 flex flex-wrap justify-between items-center gap-4">
        <a href="{{ route('tasks.export') }}" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition">
            <i class="fas fa-download"></i> Export CSV
        </a>
        <div class="flex-1">
            {{ $tasks->links() }}
        </div>
    </div>
</div>

@include('tasks.partials.add-task-modal')
@include('tasks.partials.edit-task-modal')

<script>
    function showAddTaskModal() {
        document.getElementById('addTaskModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeAddTaskModal() {
        document.getElementById('addTaskModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    function showEditTaskModal(taskId, title, description, category, priority, dueDate, notes) {
        document.getElementById('editTaskId').value = taskId;
        document.getElementById('editTitle').value = title || '';
        document.getElementById('editDescription').value = description || '';
        document.getElementById('editCategory').value = category || 'general';
        document.getElementById('editPriority').value = priority || 'medium';
        document.getElementById('editDueDate').value = dueDate || '';
        document.getElementById('editNotes').value = notes || '';

        const form = document.getElementById('editTaskForm');
        const actionUrl = '/tasks/' + taskId;
        form.setAttribute('hx-put', actionUrl);

        document.getElementById('editTaskModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeEditTaskModal() {
        document.getElementById('editTaskModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('modal-overlay')) {
            closeAddTaskModal();
            closeEditTaskModal();
        }
    });
</script>
@endsection