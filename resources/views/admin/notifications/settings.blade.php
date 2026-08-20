@extends('admin.layout')

@section('title', 'Notifications — Qafila Admin')

@section('content')
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-slate-900">Automated Notifications</h1>
        <div class="flex gap-3">
            <a href="{{ route('admin.notifications.manual.create') }}" class="text-sm font-semibold text-blue-700 hover:underline self-center">+ Send Manual Message</a>
            <a href="{{ route('admin.notifications.log.index') }}" class="text-sm font-semibold text-blue-700 hover:underline self-center">View Log</a>
            <form method="POST" action="{{ route('admin.notifications.run-now') }}" onsubmit="return confirm('Run the birthday and renewal reminder sweep now?');">
                @csrf
                <button type="submit" class="bg-slate-800 text-white text-sm font-semibold px-4 py-2 rounded-lg hover:bg-slate-900 transition">
                    Run Sweep Now
                </button>
            </form>
        </div>
    </div>

    <div class="mt-2 rounded-lg bg-amber-50 border border-amber-200 text-amber-800 text-xs p-3">
        <strong>Demo mode:</strong> Email is sent through the app's real mail system (logged locally). WhatsApp and SMS have no connected gateway yet, so those sends are simulated and recorded in the log — wire up a WhatsApp Cloud API / SMS provider to make them live.
    </div>

    <div class="mt-6 grid lg:grid-cols-2 gap-6">
        {{-- Birthday --}}
        <form method="POST" action="{{ route('admin.notifications.birthday.update') }}" class="bg-white border border-slate-200 rounded-xl p-6 space-y-4 h-fit">
            @csrf
            @method('PUT')
            <div class="flex items-center justify-between">
                <h2 class="font-semibold text-slate-900">🎂 Birthday Greeting</h2>
                <label class="flex items-center gap-2 text-xs text-slate-600">
                    <input type="hidden" name="is_enabled" value="0">
                    <input type="checkbox" name="is_enabled" value="1" class="rounded border-slate-300" @checked($birthday->is_enabled)>
                    Enabled
                </label>
            </div>

            <div class="flex flex-wrap gap-4 text-sm">
                <label class="flex items-center gap-1.5">
                    <input type="hidden" name="channel_whatsapp" value="0">
                    <input type="checkbox" name="channel_whatsapp" value="1" class="rounded border-slate-300" @checked($birthday->channel_whatsapp)> WhatsApp
                </label>
                <label class="flex items-center gap-1.5">
                    <input type="hidden" name="channel_sms" value="0">
                    <input type="checkbox" name="channel_sms" value="1" class="rounded border-slate-300" @checked($birthday->channel_sms)> SMS
                </label>
                <label class="flex items-center gap-1.5">
                    <input type="hidden" name="channel_email" value="0">
                    <input type="checkbox" name="channel_email" value="1" class="rounded border-slate-300" @checked($birthday->channel_email)> Email
                </label>
            </div>

            <div>
                <label class="block text-xs font-medium text-slate-500">Subject (email only)</label>
                <input type="text" name="subject" value="{{ old('subject', $birthday->subject) }}"
                    class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-xs font-medium text-slate-500">Message Template</label>
                <textarea name="body" rows="6" required
                    class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm font-mono focus:border-blue-500 focus:ring-blue-500">{{ old('body', $birthday->body) }}</textarea>
                <p class="mt-1 text-xs text-slate-400">Placeholders: @{{customer_name}}, @{{company_name}}</p>
            </div>

            <button type="submit" class="w-full bg-blue-700 text-white text-sm font-semibold py-2.5 rounded-lg hover:bg-blue-800 transition">
                Save Birthday Settings
            </button>
        </form>

        {{-- Renewal --}}
        <form method="POST" action="{{ route('admin.notifications.renewal.update') }}" class="bg-white border border-slate-200 rounded-xl p-6 space-y-4 h-fit">
            @csrf
            @method('PUT')
            <div class="flex items-center justify-between">
                <h2 class="font-semibold text-slate-900">🔔 Policy Renewal Reminder</h2>
                <label class="flex items-center gap-2 text-xs text-slate-600">
                    <input type="hidden" name="is_enabled" value="0">
                    <input type="checkbox" name="is_enabled" value="1" class="rounded border-slate-300" @checked($renewal->is_enabled)>
                    Enabled
                </label>
            </div>
            <p class="text-xs text-slate-400">Applies to Motor and Medical insurance policies only.</p>

            <div class="flex flex-wrap gap-4 text-sm">
                <label class="flex items-center gap-1.5">
                    <input type="hidden" name="channel_whatsapp" value="0">
                    <input type="checkbox" name="channel_whatsapp" value="1" class="rounded border-slate-300" @checked($renewal->channel_whatsapp)> WhatsApp
                </label>
                <label class="flex items-center gap-1.5">
                    <input type="hidden" name="channel_sms" value="0">
                    <input type="checkbox" name="channel_sms" value="1" class="rounded border-slate-300" @checked($renewal->channel_sms)> SMS
                </label>
                <label class="flex items-center gap-1.5">
                    <input type="hidden" name="channel_email" value="0">
                    <input type="checkbox" name="channel_email" value="1" class="rounded border-slate-300" @checked($renewal->channel_email)> Email
                </label>
            </div>

            <div>
                <label class="block text-xs font-medium text-slate-500">Reminder Stages (days before expiry)</label>
                <div class="mt-2 flex flex-wrap gap-3">
                    @foreach($stages as $stage)
                        <label class="flex items-center gap-1.5 border border-slate-200 rounded-lg px-2.5 py-1.5 text-xs cursor-pointer hover:border-blue-400">
                            <input type="checkbox" name="stages[{{ $stage->days_before }}]" value="1" class="rounded border-slate-300" @checked($stage->is_enabled)>
                            {{ $stage->days_before }} day{{ $stage->days_before > 1 ? 's' : '' }}
                        </label>
                    @endforeach
                </div>
            </div>

            <div>
                <label class="block text-xs font-medium text-slate-500">Renewal Payment Link</label>
                <input type="text" name="renewal_payment_link" value="{{ old('renewal_payment_link', $renewal->renewal_payment_link) }}"
                    class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-xs font-medium text-slate-500">Subject (email only)</label>
                <input type="text" name="subject" value="{{ old('subject', $renewal->subject) }}"
                    class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-xs font-medium text-slate-500">Message Template</label>
                <textarea name="body" rows="6" required
                    class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm font-mono focus:border-blue-500 focus:ring-blue-500">{{ old('body', $renewal->body) }}</textarea>
                <p class="mt-1 text-xs text-slate-400">
                    Placeholders: @{{customer_name}}, @{{company_name}}, @{{policy_number}}, @{{insurance_type}},
                    @{{policy_expiry_date}}, @{{days_remaining}}, @{{renewal_link}}
                </p>
            </div>

            <button type="submit" class="w-full bg-blue-700 text-white text-sm font-semibold py-2.5 rounded-lg hover:bg-blue-800 transition">
                Save Renewal Settings
            </button>
        </form>
    </div>
@endsection
