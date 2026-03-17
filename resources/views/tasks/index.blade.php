@extends('layouts.app')

@section('content')

<!-- Task Input Form -->
<div class="bg-white shadow rounded-lg p-6 mb-6">
    <form 
        hx-post="/tasks" 
        hx-target="#task-list" 
        hx-swap="innerHTML"
        hx-headers='{"X-CSRF-TOKEN": "{{ csrf_token() }}"}'
        class="flex gap-3"
    >
        <input 
            type="text" 
            name="title" 
            class="border rounded-lg p-3 w-full focus:outline-none focus:ring-2 focus:ring-blue-400"
            placeholder="Enter a new task..."
            required
        >
        <button 
            type="submit" 
            class="bg-blue-500 hover:bg-blue-600 text-white font-semibold px-6 py-3 rounded-lg transition"
        >
            Add Task
        </button>
    </form>
</div>

<!-- Task List -->
<div id="task-list" class="space-y-3">
    @include('tasks.partials.task-list')
</div>

@endsection