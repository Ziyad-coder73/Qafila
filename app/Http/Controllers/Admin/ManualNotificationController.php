<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NotificationLog;
use App\Models\Policy;
use App\Services\NotificationDispatcher;
use Illuminate\Http\Request;

class ManualNotificationController extends Controller
{
    public function create()
    {
        $policies = Policy::orderBy('customer_name')->get(['id', 'customer_name', 'contact_number', 'policy_number']);

        return view('admin.notifications.manual', compact('policies'));
    }

    public function store(Request $request, NotificationDispatcher $dispatcher)
    {
        $data = $request->validate([
            'policy_id' => ['nullable', 'exists:policies,id'],
            'customer_name' => ['required', 'string', 'max:255'],
            'contact' => ['required', 'string', 'max:255'],
            'channel' => ['required', 'in:whatsapp,sms,email'],
            'subject' => ['nullable', 'string', 'max:255'],
            'message' => ['required', 'string'],
        ]);

        $policy = $data['policy_id'] ? Policy::find($data['policy_id']) : null;

        $status = $dispatcher->dispatch($data['channel'], $data['contact'], $data['subject'] ?? null, $data['message']);

        NotificationLog::create([
            'policy_id' => $policy?->id,
            'customer_name' => $data['customer_name'],
            'contact' => $data['contact'],
            'policy_number' => $policy?->policy_number,
            'insurance_type' => $policy?->insuranceType?->name,
            'notification_type' => 'manual',
            'reminder_stage' => null,
            'channel' => $data['channel'],
            'status' => $status,
            'message' => $data['message'],
            'sent_by' => $request->user()->id,
            'sent_at' => now(),
        ]);

        return redirect()->route('admin.notifications.manual.create')->with('status', 'Message '.($status === 'sent' ? 'sent' : 'failed').' via '.ucfirst($data['channel']).'.');
    }
}
