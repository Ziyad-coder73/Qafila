@extends('admin.layout')

@section('title', 'Send Manual Message — Qafila Admin')

@section('content')
    <a href="{{ route('admin.notifications.index') }}" class="text-sm text-slate-500 hover:text-slate-700">&larr; Back to notification settings</a>
    <h1 class="text-2xl font-bold text-slate-900 mt-2">Send a Manual Message</h1>
    <p class="mt-1 text-sm text-slate-600">Compose and send a one-off message to a customer, outside the automatic schedule.</p>

    <form method="POST" action="{{ route('admin.notifications.manual.store') }}" class="mt-6 max-w-xl bg-white border border-slate-200 rounded-xl p-6 space-y-5">
        @csrf

        @if (session('status'))
            <div class="rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm p-3">{{ session('status') }}</div>
        @endif

        @if ($errors->any())
            <div class="rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm p-4">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div>
            <label class="block text-sm font-medium text-slate-700">Link to Policy <span class="text-slate-400 font-normal">(optional)</span></label>
            <select id="policy_id" name="policy_id" class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
                <option value="">— No policy —</option>
                @foreach($policies as $policy)
                    <option value="{{ $policy->id }}" data-name="{{ $policy->customer_name }}" data-contact="{{ $policy->contact_number }}">
                        {{ $policy->customer_name }} ({{ $policy->policy_number }})
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700">Customer Name</label>
            <input type="text" id="customer_name" name="customer_name" value="{{ old('customer_name') }}" required
                class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700">Channel</label>
            <select id="channel" name="channel" required class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
                <option value="whatsapp" @selected(old('channel', 'whatsapp') === 'whatsapp')>WhatsApp</option>
                <option value="sms" @selected(old('channel') === 'sms')>SMS</option>
                <option value="email" @selected(old('channel') === 'email')>Email</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700" id="contact-label">Phone Number</label>
            <input type="text" id="contact" name="contact" value="{{ old('contact') }}" required
                class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
        </div>

        <div id="subject-wrap" class="hidden">
            <label class="block text-sm font-medium text-slate-700">Subject</label>
            <input type="text" name="subject" value="{{ old('subject') }}"
                class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700">Message</label>
            <textarea name="message" rows="5" required
                class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">{{ old('message') }}</textarea>
        </div>

        <button type="submit" class="w-full bg-blue-700 text-white font-semibold py-2.5 rounded-lg hover:bg-blue-800 transition">
            Send Message
        </button>
    </form>

    <script>
        const policySelect = document.getElementById('policy_id');
        const nameInput = document.getElementById('customer_name');
        const contactInput = document.getElementById('contact');
        const channelSelect = document.getElementById('channel');
        const subjectWrap = document.getElementById('subject-wrap');
        const contactLabel = document.getElementById('contact-label');

        policySelect.addEventListener('change', () => {
            const option = policySelect.options[policySelect.selectedIndex];
            if (option.value) {
                nameInput.value = option.dataset.name;
                if (channelSelect.value !== 'email') {
                    contactInput.value = option.dataset.contact;
                }
            }
        });

        channelSelect.addEventListener('change', () => {
            const isEmail = channelSelect.value === 'email';
            subjectWrap.classList.toggle('hidden', !isEmail);
            contactLabel.textContent = isEmail ? 'Email Address' : 'Phone Number';
        });
    </script>
@endsection
