@extends('layouts.app')

@section('content')

<!-- Search -->

<form method="GET" class="mb-5">

    <input
        type="text"
        name="search"
        value="{{ request('search') }}"
        placeholder="Search tasks..."
        class="w-full border p-3 rounded-lg focus:ring-2 focus:ring-blue-400 outline-none"
    >

</form>

<!-- Add Task -->

<form
    hx-post="/tasks"
    hx-target="#task-list"
    hx-swap="innerHTML"
    hx-on::after-request="this.reset()"
    hx-headers='{"X-CSRF-TOKEN":"{{ csrf_token() }}"}'
    class="flex gap-3 mb-6"
>

    <input
        type="text"
        name="title"
        placeholder="Enter task..."
        class="w-full border p-3 rounded-lg"
        required
    >

    <button
        type="submit"
        class="bg-blue-500 hover:bg-blue-600 text-white px-6 rounded-lg"
    >
        Add
    </button>

</form>

<!-- Task List -->

<div id="task-list">

    @include('tasks.partials.task-list')

</div>

@endsection