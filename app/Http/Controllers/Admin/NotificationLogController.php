<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NotificationLog;
use Illuminate\Http\Request;

class NotificationLogController extends Controller
{
    public function index(Request $request)
    {
        $logs = NotificationLog::with(['sentBy'])
            ->when($request->filled('search'), function ($query) use ($request) {
                $like = '%'.$request->string('search').'%';
                $query->where(function ($q) use ($like) {
                    $q->where('customer_name', 'like', $like)
                        ->orWhere('policy_number', 'like', $like)
                        ->orWhere('contact', 'like', $like);
                });
            })
            ->when($request->filled('notification_type'), fn ($q) => $q->where('notification_type', $request->input('notification_type')))
            ->when($request->filled('channel'), fn ($q) => $q->where('channel', $request->input('channel')))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->input('status')))
            ->latest('sent_at')
            ->paginate(20)
            ->withQueryString();

        return view('admin.notifications.log', compact('logs'));
    }
}
