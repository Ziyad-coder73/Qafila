@extends('admin.layout')

@section('title', 'Reports — Qafila Admin')

@section('content')
    <h1 class="text-2xl font-bold text-slate-900">Monthly Policy Report</h1>

    <form method="GET" action="{{ route('admin.reports.index') }}" class="mt-6 bg-white border border-slate-200 rounded-xl p-4 grid sm:grid-cols-4 gap-3">
        <input type="month" name="month" value="{{ $month }}"
            class="rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Customer, policy #, agent"
            class="rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500 sm:col-span-2">
        <select name="insurance_type_id" class="rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
            <option value="">All insurance types</option>
            @foreach($insuranceTypes as $type)
                <option value="{{ $type->id }}" @selected(request('insurance_type_id') == $type->id)>{{ $type->name }}</option>
            @endforeach
        </select>
        <div class="sm:col-span-4 flex gap-3">
            <button type="submit" class="bg-slate-800 text-white text-sm font-semibold px-4 py-2 rounded-lg hover:bg-slate-900 transition">View Report</button>
        </div>
    </form>

    <div class="mt-6 grid sm:grid-cols-3 gap-4">
        <div class="bg-white border border-slate-200 rounded-xl p-4">
            <p class="text-xs text-slate-500">Policies</p>
            <p class="text-xl font-bold text-slate-900">{{ $totals['count'] }}</p>
        </div>
        <div class="bg-white border border-slate-200 rounded-xl p-4">
            <p class="text-xs text-slate-500">Total Premium</p>
            <p class="text-xl font-bold text-slate-900">{{ number_format($totals['premium'], 3) }}</p>
        </div>
        <div class="bg-white border border-slate-200 rounded-xl p-4">
            <p class="text-xs text-slate-500">Total Commission</p>
            <p class="text-xl font-bold text-slate-900">{{ number_format($totals['commission'], 3) }}</p>
        </div>
    </div>

    <div class="mt-6 flex flex-wrap items-center gap-3">
        <a href="{{ route('admin.reports.export.csv', request()->query()) }}" class="bg-green-600 text-white text-sm font-semibold px-4 py-2 rounded-lg hover:bg-green-700 transition">
            Export CSV (Excel)
        </a>
        <a href="{{ route('admin.reports.export.pdf', request()->query()) }}" class="bg-slate-800 text-white text-sm font-semibold px-4 py-2 rounded-lg hover:bg-slate-900 transition">
            Export PDF
        </a>

        <form method="POST" action="{{ route('admin.reports.email') }}" class="flex items-center gap-2 ml-auto">
            @csrf
            <input type="hidden" name="month" value="{{ $month }}">
            <input type="email" name="email" required placeholder="Send report to email"
                class="rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
            <button type="submit" class="bg-blue-700 text-white text-sm font-semibold px-4 py-2 rounded-lg hover:bg-blue-800 transition">
                Email Report
            </button>
        </form>
    </div>

    <div class="mt-6 bg-white border border-slate-200 rounded-xl overflow-hidden overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-slate-500 text-left">
                <tr>
                    <th class="px-4 py-3 font-medium">Customer</th>
                    <th class="px-4 py-3 font-medium">Insurance Type</th>
                    <th class="px-4 py-3 font-medium">Company</th>
                    <th class="px-4 py-3 font-medium">Policy #</th>
                    <th class="px-4 py-3 font-medium">Start</th>
                    <th class="px-4 py-3 font-medium">Expiry</th>
                    <th class="px-4 py-3 font-medium">Premium</th>
                    <th class="px-4 py-3 font-medium">Commission</th>
                    <th class="px-4 py-3 font-medium">Agent</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($policies as $policy)
                    <tr>
                        <td class="px-4 py-3 font-medium text-slate-900">{{ $policy->customer_name }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $policy->insuranceType->name ?? '—' }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $policy->insurance_company }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $policy->policy_number }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $policy->policy_start_date->format('d M Y') }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $policy->policy_expiry_date->format('d M Y') }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ number_format($policy->premium, 3) }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ number_format($policy->commission ?? 0, 3) }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $policy->agent_name }}</td>
                    </tr>
                @empty
                    <tr><td colspan="9" class="px-4 py-8 text-center text-slate-500">No policies found for this month.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
