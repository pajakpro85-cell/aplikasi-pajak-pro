<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::with('user')
            ->orderByDesc('created_at');

        if ($request->filled('aktivitas')) {
            $query->where('aktivitas', $request->aktivitas);
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('created_at', $request->tanggal);
        }

        $activityLogs = $query->get();

        return view('activity_logs.index', compact('activityLogs'));
    }

    public function show(ActivityLog $activityLog)
    {
        $activityLog->load('user');

        return view(
            'activity_logs.show',
            compact('activityLog')
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'aktivitas' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'ip_address' => 'nullable|ip',
        ]);

        $validated['user_id'] = Auth::id();

        ActivityLog::create($validated);

        return redirect()
            ->route('activity-logs.index')
            ->with('success', 'Aktivitas berhasil dicatat.');
    }
}