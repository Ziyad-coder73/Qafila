@extends('admin.layout')

@section('title', 'Generate Loyalty Card — Qafila Admin')

@section('content')
    <a href="{{ route('admin.policies.show', $policy) }}" class="text-sm text-slate-500 hover:text-slate-700">&larr; Back to policy</a>
    <h1 class="text-2xl font-bold text-slate-900 mt-2">Generate Qafila Loyalty Card</h1>

    <div class="mt-6 grid lg:grid-cols-2 gap-6">
        <div class="bg-white border border-slate-200 rounded-xl p-6">
            <h2 class="font-semibold text-slate-900">Review Customer Information</h2>
            <p class="mt-1 text-xs text-slate-400">Imported automatically from the policy — no manual entry needed.</p>
            <dl class="mt-4 space-y-2 text-sm">
                <div class="flex justify-between"><dt class="text-slate-500">Customer Name</dt><dd class="text-slate-800 font-medium">{{ $policy->customer_name }}</dd></div>
                <div class="flex justify-between"><dt class="text-slate-500">Contact Number</dt><dd class="text-slate-800">{{ $policy->contact_number }}</dd></div>
                <div class="flex justify-between"><dt class="text-slate-500">Insurance Company</dt><dd class="text-slate-800">{{ $policy->insurance_company }}</dd></div>
                <div class="flex justify-between"><dt class="text-slate-500">Insurance Type</dt><dd class="text-slate-800">{{ $policy->insuranceType->name ?? '—' }}</dd></div>
                <div class="flex justify-between"><dt class="text-slate-500">Policy Number</dt><dd class="text-slate-800">{{ $policy->policy_number }}</dd></div>
                <div class="flex justify-between"><dt class="text-slate-500">Policy Start</dt><dd class="text-slate-800">{{ $policy->policy_start_date->format('d M Y') }}</dd></div>
                <div class="flex justify-between"><dt class="text-slate-500">Policy Expiry</dt><dd class="text-slate-800">{{ $policy->policy_expiry_date->format('d M Y') }}</dd></div>
                <div class="flex justify-between"><dt class="text-slate-500">Premium</dt><dd class="text-slate-800">{{ number_format($policy->premium, 3) }}</dd></div>
                <div class="flex justify-between"><dt class="text-slate-500">Agent Name</dt><dd class="text-slate-800">{{ $policy->agent_name }}</dd></div>
            </dl>

            <div class="mt-4 pt-4 border-t border-slate-100">
                @if($policy->isExpired())
                    <p class="text-sm text-red-600 font-medium">⚠ Not eligible — this policy has expired.</p>
                @else
                    <p class="text-sm text-green-700 font-medium">✓ Eligible — policy is active.</p>
                @endif
            </div>
        </div>

        <div class="bg-white border border-slate-200 rounded-xl p-6 h-fit">
            <h2 class="font-semibold text-slate-900">Select Loyalty Package</h2>

            @if ($errors->any())
                <div class="mt-3 rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm p-3">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.loyalty-cards.store', $policy) }}" class="mt-4 space-y-4">
                @csrf
                <div class="space-y-3">
                    @foreach($packages as $package)
                        <label class="flex items-start gap-3 border border-slate-200 rounded-lg p-3 cursor-pointer hover:border-blue-400">
                            <input type="radio" name="loyalty_package" value="{{ $package->slug }}" required class="mt-1" @checked($loop->first)>
                            <span>
                                <span class="block font-medium text-slate-900">{{ $package->title }}</span>
                                @if($package->discount_percentage)
                                    <span class="block text-xs text-blue-700">{{ $package->discount_percentage }}% partner discount</span>
                                @endif
                                @if($package->benefits)
                                    <span class="block text-xs text-slate-500 mt-1 whitespace-pre-line">{{ $package->benefits }}</span>
                                @endif
                            </span>
                        </label>
                    @endforeach
                </div>

                <button type="submit" @disabled($policy->isExpired())
                    class="w-full bg-blue-700 text-white font-semibold py-2.5 rounded-lg hover:bg-blue-800 transition disabled:opacity-40 disabled:cursor-not-allowed">
                    Proceed &amp; Generate Card
                </button>
            </form>
        </div>
    </div>
@endsection
