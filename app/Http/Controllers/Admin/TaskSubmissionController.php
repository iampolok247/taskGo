<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TaskSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskSubmissionController extends Controller
{
    public function index(Request $request)
    {
        $query = TaskSubmission::with(['user', 'task']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('task_id')) {
            $query->where('task_id', $request->task_id);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })->orWhereHas('task', function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%");
            });
        }

        $submissions = $query->latest()->paginate(20);

        // Stats
        $stats = [
            'pending' => TaskSubmission::pending()->count(),
            'approved_today' => TaskSubmission::approved()->whereDate('reviewed_at', today())->count(),
            'rejected_today' => TaskSubmission::rejected()->whereDate('reviewed_at', today())->count(),
            'total' => TaskSubmission::count(),
        ];

        // Tasks for filter
        $tasks = \App\Models\Task::orderBy('title')->get(['id', 'title']);

        return view('admin.submissions.index', compact('submissions', 'stats', 'tasks'));
    }

    public function show(TaskSubmission $submission)
    {
        $submission->load(['user', 'task', 'reviewer']);

        return view('admin.submissions.show', compact('submission'));
    }

    public function approve(TaskSubmission $submission)
    {
        if (!$submission->isPending()) {
            return back()->with('error', 'This submission has already been reviewed.');
        }

        $admin = Auth::guard('admin')->user();
        $submission->approve($admin);

        return back()->with('success', 'Submission approved successfully!');
    }

    public function reject(Request $request, TaskSubmission $submission)
    {
        if (!$submission->isPending()) {
            return back()->with('error', 'This submission has already been reviewed.');
        }

        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $admin = Auth::guard('admin')->user();
        $submission->reject($admin, $validated['rejection_reason']);

        return back()->with('success', 'Submission rejected.');
    }

    public function bulkApprove(Request $request)
    {
        $validated = $request->validate([
            'submission_ids' => 'required|array',
            'submission_ids.*' => 'exists:task_submissions,id',
        ]);

        $admin = Auth::guard('admin')->user();
        $count = 0;

        foreach ($validated['submission_ids'] as $id) {
            $submission = TaskSubmission::find($id);
            if ($submission && $submission->isPending()) {
                $submission->approve($admin);
                $count++;
            }
        }

        return back()->with('success', "{$count} submissions approved successfully!");
    }

    public function bulkReject(Request $request)
    {
        $validated = $request->validate([
            'submission_ids' => 'required|array',
            'submission_ids.*' => 'exists:task_submissions,id',
            'rejection_reason' => 'required|string|max:500',
        ]);

        $admin = Auth::guard('admin')->user();
        $count = 0;

        foreach ($validated['submission_ids'] as $id) {
            $submission = TaskSubmission::find($id);
            if ($submission && $submission->isPending()) {
                $submission->reject($admin, $validated['rejection_reason']);
                $count++;
            }
        }

        return back()->with('success', "{$count} submissions rejected.");
    }
}
