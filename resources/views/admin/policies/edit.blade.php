@extends('admin.layout')

@section('title', 'Edit Policy — Qafila Admin')

@section('content')
    <a href="{{ route('admin.policies.show', $policy) }}" class="text-sm text-slate-500 hover:text-slate-700">&larr; Back to policy</a>
    <h1 class="text-2xl font-bold text-slate-900 mt-2">Edit Policy — {{ $policy->policy_number }}</h1>

    <form method="POST" action="{{ route('admin.policies.update', $policy) }}" enctype="multipart/form-data" class="mt-6 max-w-3xl bg-white border border-slate-200 rounded-xl p-6 space-y-6">
        @csrf
        @method('PUT')

        @if ($errors->any())
            <div class="rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm p-4">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700">Customer Name</label>
                <input type="text" name="customer_name" value="{{ old('customer_name', $policy->customer_name) }}" required
                    class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Birthday</label>
                <input type="date" name="birthday" value="{{ old('birthday', optional($policy->birthday)->format('Y-m-d')) }}"
                    class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Contact Number</label>
                <input type="text" name="contact_number" value="{{ old('contact_number', $policy->contact_number) }}" required
                    class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Insurance Type</label>
                <select name="insurance_type_id" required
                    class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
                    @foreach($insuranceTypes as $type)
                        <option value="{{ $type->id }}" @selected(old('insurance_type_id', $policy->insurance_type_id) == $type->id)>{{ $type->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Insurance Company</label>
                <input type="text" name="insurance_company" value="{{ old('insurance_company', $policy->insurance_company) }}" required
                    class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Policy Number</label>
                <input type="text" name="policy_number" value="{{ old('policy_number', $policy->policy_number) }}" required
                    class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Date of Issue</label>
                <input type="date" name="date_of_issue" value="{{ old('date_of_issue', $policy->date_of_issue->format('Y-m-d')) }}" required
                    class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Policy Start Date</label>
                <input type="date" name="policy_start_date" value="{{ old('policy_start_date', $policy->policy_start_date->format('Y-m-d')) }}" required
                    class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Policy Expiry Date</label>
                <input type="date" name="policy_expiry_date" value="{{ old('policy_expiry_date', $policy->policy_expiry_date->format('Y-m-d')) }}" required
                    class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Premium</label>
                <input type="number" step="0.001" min="0" name="premium" value="{{ old('premium', $policy->premium) }}" required
                    class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Commission</label>
                <input type="number" step="0.001" min="0" name="commission" value="{{ old('commission', $policy->commission) }}"
                    class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Agent Name</label>
                <input type="text" name="agent_name" value="{{ old('agent_name', $policy->agent_name) }}" required
                    class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700">Policy Document (PDF)</label>
            <p class="mt-1 text-xs text-slate-500">
                Current: <a href="{{ asset('storage/' . $policy->policy_document) }}" target="_blank" class="text-blue-700 hover:underline">view document</a>
            </p>
            <input type="file" name="policy_document" accept="application/pdf"
                class="mt-2 block w-full text-sm text-slate-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-blue-50 file:text-blue-700 file:text-sm file:font-medium hover:file:bg-blue-100">
            <p class="mt-1 text-xs text-slate-400">Leave blank to keep the current document.</p>
        </div>

        <button type="submit" class="w-full bg-blue-700 text-white font-semibold py-2.5 rounded-lg hover:bg-blue-800 transition">
            Save Changes
        </button>
    </form>
@endsection
