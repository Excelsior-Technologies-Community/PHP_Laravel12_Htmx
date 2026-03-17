<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::latest()->get();
        return view('tasks.index', compact('tasks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required'
        ]);

        Task::create([
            'title' => $request->title
        ]);

        // Return updated task list for HTMX
        return $this->renderTasks();
    }

    public function delete(Task $task)
    {
        $task->delete();

        // Return updated task list for HTMX
        return $this->renderTasks();
    }

    private function renderTasks()
    {
        $tasks = Task::latest()->get();

        // If the request comes via HTMX, return the partial
        if (request()->header('HX-Request')) {
            return view('tasks.partials.task-list', compact('tasks'));
        }

        // Fallback for normal request
        return redirect('/');
    }
}