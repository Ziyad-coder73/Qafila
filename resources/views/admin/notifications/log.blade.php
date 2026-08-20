@extends('admin.layout')

@section('title', 'Notification Log — Qafila Admin')

@section('content')
    <a href="{{ route('admin.notifications.index') }}" class="text-sm text-slate-500 hover:text-slate-700">&larr; Back to notification settings</a>
    <h1 class="text-2xl font-bold text-slate-900 mt-2">Notification Log</h1>

    <form method="GET" action="{{ route('admin.notifications.log.index') }}" class="mt-6 bg-white border border-slate-200 rounded-xl p-4 grid sm:grid-cols-5 gap-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Customer, policy #, contact"
            class="rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500 sm:col-span-2">
        <select name="notification_type" class="rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
            <option value="">All types</option>
            <option value="birthday" @selected(request('notification_type') === 'birthday')>Birthday</option>
            <option value="renewal" @selected(request('notification_type') === 'renewal')>Renewal Reminder</option>
            <option value="manual" @selected(request('notification_type') === 'manual')>Manual</option>
        </select>
        <select name="channel" class="rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
            <option value="">All channels</option>
            <option value="whatsapp" @selected(request('channel') === 'whatsapp')>WhatsApp</option>
            <option value="sms" @selected(request('channel') === 'sms')>SMS</option>
            <option value="email" @selected(request('channel') === 'email')>Email</option>
        </select>
        <select name="status" class="rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
            <option value="">All statuses</option>
            <option value="sent" @selected(request('status') === 'sent')>Sent</option>
            <option value="delivered" @selected(request('status') === 'delivered')>Delivered</option>
            <option value="failed" @selected(request('status') === 'failed')>Failed</option>
        </select>
        <div class="sm:col-span-5 flex gap-3">
            <button type="submit" class="bg-slate-800 text-white text-sm font-semibold px-4 py-2 rounded-lg hover:bg-slate-900 transition">Filter</button>
            <a href="{{ route('admin.notifications.log.index') }}" class="text-sm text-slate-500 hover:text-slate-700 self-center">Clear</a>
        </div>
    </form>

    <div class="mt-6 bg-white border border-slate-200 rounded-xl overflow-hidden overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-slate-500 text-left">
                <tr>
                    <th class="px-4 py-3 font-medium">Customer</th>
                    <th class="px-4 py-3 font-medium">Contact</th>
                    <th class="px-4 py-3 font-medium">Policy #</th>
                    <th class="px-4 py-3 font-medium">Type</th>
                    <th class="px-4 py-3 font-medium">Stage</th>
                    <th class="px-4 py-3 font-medium">Channel</th>
                    <th class="px-4 py-3 font-medium">Status</th>
                    <th class="px-4 py-3 font-medium">Date &amp; Time</th>
                    <th class="px-4 py-3 font-medium">Sent By</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($logs as $log)
                    <tr>
                        <td class="px-4 py-3 font-medium text-slate-900">{{ $log->customer_name }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $log->contact }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $log->policy_number ?? '—' }}</td>
                        <td class="px-4 py-3 text-slate-600 capitalize">{{ str_replace('_', ' ', $log->notification_type) }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $log->reminder_stage ? $log->reminder_stage.' day(s)' : '—' }}</td>
                        <td class="px-4 py-3 text-slate-600 capitalize">{{ $log->channel }}</td>
                        <td class="px-4 py-3">
                            @php
                                $statusColors = ['sent' => 'bg-green-100 text-green-700', 'delivered' => 'bg-blue-100 text-blue-700', 'failed' => 'bg-red-100 text-red-700'];
                            @endphp
                            <span class="inline-block px-2 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$log->status] ?? 'bg-slate-100 text-slate-500' }}">
                                {{ ucfirst($log->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-slate-600">{{ $log->sent_at->format('d M Y, H:i') }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $log->sentBy->name ?? 'System (Automatic)' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="px-4 py-8 text-center text-slate-500">No notifications sent yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $logs->links() }}
    </div>
@endsection
