<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $tasks = Task::when($search, function ($query) use ($search) {

            $query->where('title', 'like', "%{$search}%");

        })->orderBy('id', 'asc')->paginate(4);
        return view('tasks.index', compact('tasks'));
    }

    // CREATE
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|min:3'
        ]);

        Task::create([
            'title' => $request->title
        ]);

        session()->flash('success', 'Task added successfully.');

        return $this->renderTasks();
    }

    // UPDATE
    public function update(Request $request, Task $task)
    {
        $request->validate([
            'title' => 'required|min:3'
        ]);

        $task->update([
            'title' => $request->title
        ]);

        session()->flash('success', 'Task updated successfully.');

        return $this->renderTasks();
    }

    // DELETE
    public function delete(Task $task)
    {
        $task->delete();

        session()->flash('success', 'Task deleted successfully.');

        return $this->renderTasks();
    }

    // TOGGLE
    public function toggle(Task $task)
    {
        $task->update([
            'completed' => !$task->completed
        ]);

        session()->flash('success', 'Task status updated.');

        return $this->renderTasks();
    }

    // RENDER TASKS
    private function renderTasks()
    {
        $tasks = Task::latest()->paginate(4);

        if (request()->header('HX-Request')) {

            return response()->view(
                'tasks.partials.task-list',
                compact('tasks')
            )->header(
                    'HX-Trigger',
                    json_encode([
                        "showMessage" => session('success')
                    ])
                );
        }

        return redirect('/');
    }
}