<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $query = Task::query();

        if ($request->search) {
            $query->where('title', 'like', "%{$request->search}%")
                  ->orWhere('description', 'like', "%{$request->search}%");
        }

        if ($request->category && $request->category != 'all') {
            $query->where('category', $request->category);
        }

        if ($request->priority && $request->priority != 'all') {
            $query->where('priority', $request->priority);
        }

        if ($request->status == 'completed') {
            $query->where('completed', true);
        } elseif ($request->status == 'pending') {
            $query->where('completed', false);
        } elseif ($request->status == 'overdue') {
            $query->where('due_date', '<', today())->where('completed', false);
        }

        $sort = $request->sort ?? 'created_at';
        $direction = $request->direction ?? 'desc';
        $query->orderBy($sort, $direction);

        $tasks = $query->paginate(6);

        $categories = Task::select('category')
            ->distinct()
            ->whereNotNull('category')
            ->pluck('category')
            ->toArray();

        $stats = [
            'total' => Task::count(),
            'completed' => Task::where('completed', true)->count(),
            'pending' => Task::where('completed', false)->count(),
            'overdue' => Task::where('due_date', '<', today())->where('completed', false)->count(),
        ];

        return view('tasks.index', compact('tasks', 'categories', 'stats'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|min:3|max:255',
            'description' => 'nullable|string|max:1000',
            'category' => 'nullable|string|max:50',
            'priority' => 'required|in:low,medium,high',
            'due_date' => 'nullable|date|after_or_equal:today',
            'reminder_date' => 'nullable|date|after_or_equal:today',
            'notes' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            if (request()->header('HX-Request')) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category ?? 'general',
            'priority' => $request->priority,
            'due_date' => $request->due_date,
            'reminder_date' => $request->reminder_date,
            'notes' => $request->notes
        ]);

        session()->flash('success', 'Task added successfully! ✅');

        return $this->renderTasks();
    }

    public function update(Request $request, Task $task)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|min:3|max:255',
            'description' => 'nullable|string|max:1000',
            'category' => 'nullable|string|max:50',
            'priority' => 'required|in:low,medium,high',
            'due_date' => 'nullable|date|after_or_equal:today',
            'reminder_date' => 'nullable|date|after_or_equal:today',
            'notes' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            if (request()->header('HX-Request')) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        $task->update([
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category ?? 'general',
            'priority' => $request->priority,
            'due_date' => $request->due_date,
            'reminder_date' => $request->reminder_date,
            'notes' => $request->notes
        ]);

        session()->flash('success', 'Task updated successfully! ✏️');

        return $this->renderTasks();
    }

    public function delete(Task $task)
    {
        $task->delete();
        session()->flash('success', 'Task deleted successfully! 🗑️');
        return $this->renderTasks();
    }

    public function toggle(Task $task)
    {
        $task->update(['completed' => !$task->completed]);
        session()->flash('success', $task->completed ? 'Task completed! 🎉' : 'Task reopened! 🔄');
        return $this->renderTasks();
    }

    public function export(Request $request)
    {
        $tasks = Task::all();
        
        $csv = "ID,Title,Description,Category,Priority,Status,Due Date,Created At\n";
        
        foreach ($tasks as $task) {
            $csv .= implode(',', [
                $task->id,
                '"' . str_replace('"', '""', $task->title) . '"',
                '"' . str_replace('"', '""', $task->description) . '"',
                $task->category,
                $task->priority,
                $task->completed ? 'Completed' : 'Pending',
                $task->due_date ?? '',
                $task->created_at
            ]) . "\n";
        }
        
        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="tasks_' . date('Y-m-d') . '.csv"');
    }

    public function getStats()
    {
        return response()->json([
            'total' => Task::count(),
            'completed' => Task::where('completed', true)->count(),
            'pending' => Task::where('completed', false)->count(),
            'overdue' => Task::where('due_date', '<', today())->where('completed', false)->count(),
            'high_priority' => Task::where('priority', 'high')->where('completed', false)->count()
        ]);
    }

    private function renderTasks()
    {
        $tasks = Task::latest()->paginate(6);
        
        if (request()->header('HX-Request')) {
            return response()->view('tasks.partials.task-list', compact('tasks'))
                ->header('HX-Trigger', json_encode([
                    "showMessage" => session('success')
                ]));
        }
        
        return redirect('/');
    }
}