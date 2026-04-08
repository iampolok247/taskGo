<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\TaskSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $query = Task::available()
            ->with(['submissions' => function($q) use ($user) {
                $q->where('user_id', $user->id)->whereDate('created_at', today());
            }])
            ->orderByDesc('priority')
            ->orderByDesc('created_at');

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Filter by difficulty
        if ($request->filled('difficulty')) {
            $query->where('difficulty', $request->difficulty);
        }

        $tasks = $query->paginate(12);

        // Get user's submissions for these tasks today
        $userSubmissions = TaskSubmission::where('user_id', $user->id)
            ->whereIn('task_id', $tasks->pluck('id'))
            ->whereDate('created_at', today())
            ->pluck('status', 'task_id');

        $categories = ['social', 'survey', 'video', 'app', 'website', 'other'];

        // Today's progress stats
        $todayCompleted = TaskSubmission::where('user_id', $user->id)
            ->whereDate('created_at', today())
            ->where('status', 'approved')
            ->count();
        
        $dailyLimit = 50; // Default daily limit
        
        $todayEarnings = TaskSubmission::where('user_id', $user->id)
            ->whereDate('created_at', today())
            ->where('status', 'approved')
            ->sum('reward_amount');

        return view('user.tasks.index', compact('tasks', 'userSubmissions', 'categories', 'todayCompleted', 'dailyLimit', 'todayEarnings'));
    }

    public function show(Task $task)
    {
        $user = Auth::user();
        
        if (!$task->isAvailable()) {
            return redirect()->route('user.tasks.index')
                ->with('error', 'This task is no longer available.');
        }

        // Check if user already submitted today
        $submission = TaskSubmission::where('user_id', $user->id)
            ->where('task_id', $task->id)
            ->whereDate('created_at', today())
            ->first();

        $canSubmit = $task->canUserSubmit($user);

        return view('user.tasks.show', compact('task', 'submission', 'canSubmit'));
    }

    public function submit(Request $request, Task $task)
    {
        $user = Auth::user();

        if (!$task->canUserSubmit($user)) {
            return back()->with('error', 'You cannot submit this task.');
        }

        $rules = [
            'notes' => 'nullable|string|max:1000',
            'proof_url' => 'nullable|url|max:500',
            'proof_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ];

        // Make proof_image required for screenshot type
        if ($task->proof_type === 'screenshot') {
            $rules['proof_image'] = 'required|image|mimes:jpeg,png,jpg,gif|max:5120';
        }
        
        // Make proof_url required for url type
        if ($task->proof_type === 'url') {
            $rules['proof_url'] = 'required|url|max:500';
        }

        $validated = $request->validate($rules);

        // Handle image upload
        $proofImagePath = null;
        if ($request->hasFile('proof_image')) {
            $proofImagePath = $request->file('proof_image')
                ->store('task-proofs/' . $user->id, 'public');
        }

        // Create submission
        $submission = TaskSubmission::create([
            'user_id' => $user->id,
            'task_id' => $task->id,
            'proof_text' => $validated['notes'] ?? null,
            'proof_url' => $validated['proof_url'] ?? null,
            'proof_image' => $proofImagePath,
            'reward_amount' => $task->reward,
            'status' => 'pending',
        ]);

        return redirect()->route('user.tasks.index')
            ->with('success', 'Task submitted successfully! Waiting for approval.');
    }

    public function submissions(Request $request)
    {
        $user = Auth::user();

        $query = TaskSubmission::with('task')
            ->where('user_id', $user->id)
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $submissions = $query->paginate(15);

        return view('user.tasks.submissions', compact('submissions'));
    }
}
