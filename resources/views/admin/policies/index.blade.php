@extends('admin.layout')

@section('title', 'Policies — Qafila Admin')

@section('content')
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-slate-900">Policies</h1>
        <a href="{{ route('admin.policies.create') }}" class="bg-blue-700 text-white text-sm font-semibold px-4 py-2 rounded-lg hover:bg-blue-800 transition">
            + Upload Policy
        </a>
    </div>

    <form method="GET" action="{{ route('admin.policies.index') }}" class="mt-6 bg-white border border-slate-200 rounded-xl p-4 grid sm:grid-cols-4 gap-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Customer, policy #, phone, agent"
            class="rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500 sm:col-span-2">
        <select name="insurance_type_id" class="rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
            <option value="">All insurance types</option>
            @foreach($insuranceTypes as $type)
                <option value="{{ $type->id }}" @selected(request('insurance_type_id') == $type->id)>{{ $type->name }}</option>
            @endforeach
        </select>
        <div class="flex gap-2">
            <input type="date" name="date_from" value="{{ request('date_from') }}"
                class="flex-1 rounded-lg border border-slate-300 px-2 py-2 text-xs focus:border-blue-500 focus:ring-blue-500">
            <input type="date" name="date_to" value="{{ request('date_to') }}"
                class="flex-1 rounded-lg border border-slate-300 px-2 py-2 text-xs focus:border-blue-500 focus:ring-blue-500">
        </div>
        <div class="sm:col-span-4 flex gap-3">
            <button type="submit" class="bg-slate-800 text-white text-sm font-semibold px-4 py-2 rounded-lg hover:bg-slate-900 transition">Filter</button>
            <a href="{{ route('admin.policies.index') }}" class="text-sm text-slate-500 hover:text-slate-700 self-center">Clear</a>
        </div>
    </form>

    <div class="mt-6 bg-white border border-slate-200 rounded-xl overflow-hidden overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-slate-500 text-left">
                <tr>
                    <th class="px-4 py-3 font-medium">Customer</th>
                    <th class="px-4 py-3 font-medium">Insurance Type</th>
                    <th class="px-4 py-3 font-medium">Policy #</th>
                    <th class="px-4 py-3 font-medium">Start</th>
                    <th class="px-4 py-3 font-medium">Expiry</th>
                    <th class="px-4 py-3 font-medium">Premium</th>
                    <th class="px-4 py-3 font-medium">Agent</th>
                    <th class="px-4 py-3 font-medium"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($policies as $policy)
                    <tr>
                        <td class="px-4 py-3 font-medium text-slate-900">{{ $policy->customer_name }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $policy->insuranceType->name ?? '—' }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $policy->policy_number }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $policy->policy_start_date->format('d M Y') }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $policy->policy_expiry_date->format('d M Y') }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ number_format($policy->premium, 3) }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $policy->agent_name }}</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.policies.show', $policy) }}" class="text-blue-700 hover:underline">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-8 text-center text-slate-500">No policies found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $policies->links() }}
    </div>
@endsection
