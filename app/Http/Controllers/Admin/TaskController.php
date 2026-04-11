<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $query = Task::withCount(['submissions', 'approvedSubmissions', 'pendingSubmissions']);

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $tasks = $query->latest()->paginate(15);

        $categories = ['social', 'survey', 'video', 'app', 'website', 'other'];

        $stats = [
            'total' => Task::count(),
            'active' => Task::where('status', 'active')->count(),
            'inactive' => Task::where('status', 'inactive')->count(),
            'completed' => Task::where('status', 'completed')->count(),
            'total_paid' => \App\Models\TaskSubmission::where('status', 'approved')->sum('reward_amount')
        ];

        return view('admin.tasks.index', compact('tasks', 'categories', 'stats'));
    }

    public function create()
    {
        return view('admin.tasks.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'instructions' => 'required|string',
            'task_url' => 'nullable|url|max:500',
            'reward' => 'required|numeric|min:0.01',
            'category' => 'required|in:social,survey,video,app,website,other',
            'daily_limit' => 'required|integer|min:0',
            'total_limit' => 'required|integer|min:0',
            'difficulty' => 'required|in:easy,medium,hard',
            'estimated_time' => 'required|integer|min:1',
            'proof_type' => 'required|in:screenshot,url,text',
            'proof_instructions' => 'nullable|string',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'priority' => 'required|integer|min:0',
            'expires_at' => 'nullable|date|after:now',
            'status' => 'required|in:active,inactive',
        ]);

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')
                ->store('task-thumbnails', 'public');
        }

        Task::create($validated);

        return redirect()->route('admin.tasks.index')
            ->with('success', 'Task created successfully!');
    }

    public function show(Task $task)
    {
        $task->loadCount(['submissions', 'approvedSubmissions', 'pendingSubmissions']);

        $recentSubmissions = $task->submissions()
            ->with('user')
            ->latest()
            ->take(10)
            ->get();

        return view('admin.tasks.show', compact('task', 'recentSubmissions'));
    }

    public function edit(Task $task)
    {
        return view('admin.tasks.edit', compact('task'));
    }

    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'instructions' => 'required|string',
            'task_url' => 'nullable|url|max:500',
            'reward' => 'required|numeric|min:0.01',
            'category' => 'required|in:social,survey,video,app,website,other',
            'daily_limit' => 'required|integer|min:0',
            'total_limit' => 'required|integer|min:0',
            'difficulty' => 'required|in:easy,medium,hard',
            'estimated_time' => 'required|integer|min:1',
            'proof_type' => 'required|in:screenshot,url,text',
            'proof_instructions' => 'nullable|string',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'priority' => 'required|integer|min:0',
            'expires_at' => 'nullable|date',
            'status' => 'required|in:active,inactive,completed',
        ]);

        if ($request->hasFile('thumbnail')) {
            if ($task->thumbnail) {
                Storage::disk('public')->delete($task->thumbnail);
            }
            $validated['thumbnail'] = $request->file('thumbnail')
                ->store('task-thumbnails', 'public');
        }

        $task->update($validated);

        return redirect()->route('admin.tasks.show', $task)
            ->with('success', 'Task updated successfully!');
    }

    public function destroy(Task $task)
    {
        if ($task->thumbnail) {
            Storage::disk('public')->delete($task->thumbnail);
        }

        $task->delete();

        return redirect()->route('admin.tasks.index')
            ->with('success', 'Task deleted successfully!');
    }

    public function toggleStatus(Task $task)
    {
        $newStatus = $task->status === 'active' ? 'inactive' : 'active';
        $task->update(['status' => $newStatus]);

        return back()->with('success', 'Task status updated to ' . $newStatus);
    }

    public function resetDailyCount()
    {
        Task::query()->update(['today_completed' => 0]);

        return back()->with('success', 'Daily task counts have been reset.');
    }
}
