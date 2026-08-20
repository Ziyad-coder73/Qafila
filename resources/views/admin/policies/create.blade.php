@extends('admin.layout')

@section('title', 'Upload Policy — Qafila Admin')

@section('content')
    <a href="{{ route('admin.policies.index') }}" class="text-sm text-slate-500 hover:text-slate-700">&larr; Back to policies</a>
    <h1 class="text-2xl font-bold text-slate-900 mt-2">Upload Policy</h1>

    <form method="POST" action="{{ route('admin.policies.store') }}" enctype="multipart/form-data" class="mt-6 max-w-3xl bg-white border border-slate-200 rounded-xl p-6 space-y-6">
        @csrf

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
            <h2 class="font-semibold text-slate-900">Customer & Policy Details</h2>
            <div class="mt-4 grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700">Customer Name</label>
                    <input type="text" name="customer_name" value="{{ old('customer_name') }}" required
                        class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Birthday</label>
                    <input type="date" name="birthday" value="{{ old('birthday') }}"
                        class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Contact Number</label>
                    <input type="text" name="contact_number" value="{{ old('contact_number') }}" required
                        class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Insurance Type</label>
                    <select name="insurance_type_id" required
                        class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Select type</option>
                        @foreach($insuranceTypes as $type)
                            <option value="{{ $type->id }}" @selected(old('insurance_type_id') == $type->id)>{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Insurance Company</label>
                    <input type="text" name="insurance_company" value="{{ old('insurance_company') }}" required
                        class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Policy Number</label>
                    <input type="text" name="policy_number" value="{{ old('policy_number') }}" required
                        class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Date of Issue</label>
                    <input type="date" name="date_of_issue" value="{{ old('date_of_issue') }}" required
                        class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Policy Start Date</label>
                    <input type="date" name="policy_start_date" value="{{ old('policy_start_date') }}" required
                        class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Policy Expiry Date</label>
                    <input type="date" name="policy_expiry_date" value="{{ old('policy_expiry_date') }}" required
                        class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Premium</label>
                    <input type="number" step="0.001" min="0" name="premium" value="{{ old('premium') }}" required
                        class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Commission</label>
                    <input type="number" step="0.001" min="0" name="commission" value="{{ old('commission') }}"
                        class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Agent Name</label>
                    <input type="text" name="agent_name" value="{{ old('agent_name', auth()->user()->name) }}" required
                        class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
            </div>

            <div class="mt-4">
                <label class="block text-sm font-medium text-slate-700">Policy Document (PDF)</label>
                <input type="file" name="policy_document" accept="application/pdf" required
                    class="mt-2 block w-full text-sm text-slate-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-blue-50 file:text-blue-700 file:text-sm file:font-medium hover:file:bg-blue-100">
            </div>
        </div>

        <div class="border-t border-slate-200 pt-6">
            <h2 class="font-semibold text-slate-900">Payment</h2>
            <div class="mt-4 grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700">Payment Method</label>
                    <select name="payment_method" required
                        class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="payment_link" @selected(old('payment_method') == 'payment_link')>Payment Link</option>
                        <option value="qpay" @selected(old('payment_method') == 'qpay')>QPay</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Payment Status</label>
                    <select name="payment_type" required
                        class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="policy_payment" @selected(old('payment_type', 'policy_payment') == 'policy_payment')>Policy Payment</option>
                        <option value="extra_payment" @selected(old('payment_type') == 'extra_payment')>Extra Payment</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Payment Amount</label>
                    <input type="number" step="0.001" min="0" name="payment_amount" value="{{ old('payment_amount') }}"
                        class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Payment Document</label>
                    <input type="file" name="payment_document" accept="application/pdf,image/*"
                        class="mt-2 block w-full text-sm text-slate-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-blue-50 file:text-blue-700 file:text-sm file:font-medium hover:file:bg-blue-100">
                    <p class="mt-1 text-xs text-slate-400">Leave blank if the payment proof is the same as the uploaded policy document.</p>
                </div>
            </div>
        </div>

        <button type="submit" class="w-full bg-blue-700 text-white font-semibold py-2.5 rounded-lg hover:bg-blue-800 transition">
            Upload Policy
        </button>
    </form>
@endsection
