<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ActivityLog;

class JournalController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::with('user')->orderBy('created_at', 'desc');

        // Only include logs belonging to normal users (exclude admins/managers)
        $query->whereHas('user', function ($q) {
            $q->where('role', 'user');
        });

        // optional filters
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->input('user_id'));
        }
        if ($request->filled('action')) {
            $query->where('action', $request->input('action'));
        }

        $logs = $query->paginate(50);

        return view('admin.journal', compact('logs'));
    }
}
