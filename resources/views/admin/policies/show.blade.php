@extends('admin.layout')

@section('title', $policy->policy_number . ' — Qafila Admin')

@section('content')
    <a href="{{ route('admin.policies.index') }}" class="text-sm text-slate-500 hover:text-slate-700">&larr; Back to policies</a>

    <div class="mt-2 flex items-center justify-between">
        <h1 class="text-2xl font-bold text-slate-900">{{ $policy->customer_name }} — {{ $policy->policy_number }}</h1>
        <div class="space-x-3">
            <a href="{{ route('admin.policies.edit', $policy) }}" class="text-sm text-blue-700 hover:underline">Edit</a>
            <form method="POST" action="{{ route('admin.policies.destroy', $policy) }}" class="inline" onsubmit="return confirm('Delete this policy and all payment records?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-sm text-red-600 hover:underline">Delete</button>
            </form>
        </div>
    </div>

    <div class="mt-6 grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white border border-slate-200 rounded-xl p-6">
            <h2 class="font-semibold text-slate-900">Customer & Policy Details</h2>
            <dl class="mt-4 grid sm:grid-cols-2 gap-x-6 gap-y-3 text-sm">
                <div><dt class="text-slate-500">Customer Name</dt><dd class="text-slate-800 font-medium">{{ $policy->customer_name }}</dd></div>
                <div><dt class="text-slate-500">Birthday</dt><dd class="text-slate-800">{{ optional($policy->birthday)->format('d M Y') ?? '—' }}</dd></div>
                <div><dt class="text-slate-500">Contact Number</dt><dd class="text-slate-800">{{ $policy->contact_number }}</dd></div>
                <div><dt class="text-slate-500">Insurance Type</dt><dd class="text-slate-800">{{ $policy->insuranceType->name ?? '—' }}</dd></div>
                <div><dt class="text-slate-500">Insurance Company</dt><dd class="text-slate-800">{{ $policy->insurance_company }}</dd></div>
                <div><dt class="text-slate-500">Policy Number</dt><dd class="text-slate-800">{{ $policy->policy_number }}</dd></div>
                <div><dt class="text-slate-500">Date of Issue</dt><dd class="text-slate-800">{{ $policy->date_of_issue->format('d M Y') }}</dd></div>
                <div><dt class="text-slate-500">Policy Start</dt><dd class="text-slate-800">{{ $policy->policy_start_date->format('d M Y') }}</dd></div>
                <div><dt class="text-slate-500">Policy Expiry</dt><dd class="text-slate-800">{{ $policy->policy_expiry_date->format('d M Y') }}</dd></div>
                <div><dt class="text-slate-500">Premium</dt><dd class="text-slate-800">{{ number_format($policy->premium, 3) }}</dd></div>
                <div><dt class="text-slate-500">Commission</dt><dd class="text-slate-800">{{ number_format($policy->commission ?? 0, 3) }}</dd></div>
                <div><dt class="text-slate-500">Agent Name</dt><dd class="text-slate-800">{{ $policy->agent_name }}</dd></div>
            </dl>
            <div class="mt-4 pt-4 border-t border-slate-100">
                <a href="{{ asset('storage/' . $policy->policy_document) }}" target="_blank" class="text-sm text-blue-700 hover:underline">
                    View Policy Document (PDF)
                </a>

                @if(auth()->user()->isAdmin())
                    <div class="mt-4 pt-4 border-t border-slate-100">
                        @if($policy->loyaltyMember)
                            <p class="text-sm text-slate-600">
                                Qafila Loyalty Card issued —
                                <span class="capitalize font-medium text-slate-800">{{ $policy->loyaltyMember->loyalty_package }}</span>
                                ({{ $policy->loyaltyMember->membership_number }})
                            </p>
                            <a href="{{ route('admin.loyalty-cards.show', $policy->loyaltyMember) }}" class="text-sm text-blue-700 hover:underline">
                                View Loyalty Card
                            </a>
                        @elseif($policy->isExpired())
                            <p class="text-sm text-amber-600">This policy has expired and is not eligible for a Qafila Loyalty Card.</p>
                        @else
                            <a href="{{ route('admin.loyalty-cards.create', $policy) }}" class="inline-block bg-blue-700 text-white text-sm font-semibold px-4 py-2 rounded-lg hover:bg-blue-800 transition">
                                Generate Qafila Loyalty Card
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <div class="bg-white border border-slate-200 rounded-xl p-6 h-fit">
            <h2 class="font-semibold text-slate-900">Record a Payment</h2>
            <form method="POST" action="{{ route('admin.policies.payments.store', $policy) }}" enctype="multipart/form-data" class="mt-4 space-y-3">
                @csrf
                <select name="payment_method" required class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="payment_link">Payment Link</option>
                    <option value="qpay">QPay</option>
                </select>
                <select name="payment_type" required class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="extra_payment">Extra Payment</option>
                    <option value="policy_payment">Policy Payment</option>
                </select>
                <input type="number" step="0.001" min="0" name="amount" placeholder="Amount"
                    class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
                <input type="date" name="paid_at" value="{{ now()->format('Y-m-d') }}" required
                    class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
                <input type="file" name="document" accept="application/pdf,image/*"
                    class="block w-full text-xs text-slate-600 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:bg-blue-50 file:text-blue-700 file:text-xs file:font-medium hover:file:bg-blue-100">
                <button type="submit" class="w-full bg-slate-800 text-white text-sm font-semibold py-2 rounded-lg hover:bg-slate-900 transition">
                    Add Payment
                </button>
            </form>
        </div>
    </div>

    <div class="mt-6">
        <h2 class="font-semibold text-slate-900">Payment History</h2>
        <div class="mt-4 bg-white border border-slate-200 rounded-xl overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 text-slate-500 text-left">
                    <tr>
                        <th class="px-4 py-2 font-medium">Type</th>
                        <th class="px-4 py-2 font-medium">Method</th>
                        <th class="px-4 py-2 font-medium">Amount</th>
                        <th class="px-4 py-2 font-medium">Date</th>
                        <th class="px-4 py-2 font-medium">Document</th>
                        <th class="px-4 py-2 font-medium">Recorded By</th>
                        <th class="px-4 py-2 font-medium"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($policy->payments as $payment)
                        <tr>
                            <td class="px-4 py-2 text-slate-800 capitalize">{{ str_replace('_', ' ', $payment->payment_type) }}</td>
                            <td class="px-4 py-2 text-slate-600 uppercase text-xs">{{ $payment->payment_method === 'qpay' ? 'QPay' : 'Payment Link' }}</td>
                            <td class="px-4 py-2 text-slate-600">{{ $payment->amount !== null ? number_format($payment->amount, 3) : '—' }}</td>
                            <td class="px-4 py-2 text-slate-600">{{ $payment->paid_at->format('d M Y') }}</td>
                            <td class="px-4 py-2 text-slate-600">
                                @if($payment->document)
                                    <a href="{{ asset('storage/' . $payment->document) }}" target="_blank" class="text-blue-700 hover:underline">View</a>
                                @else
                                    <span class="text-slate-400">Same as policy document</span>
                                @endif
                            </td>
                            <td class="px-4 py-2 text-slate-600">{{ $payment->recordedBy->name ?? '—' }}</td>
                            <td class="px-4 py-2 text-right">
                                <form method="POST" action="{{ route('admin.policies.payments.destroy', [$policy, $payment]) }}" onsubmit="return confirm('Remove this payment record?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 text-xs hover:underline">Remove</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-4 py-6 text-center text-slate-500">No payments recorded yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
