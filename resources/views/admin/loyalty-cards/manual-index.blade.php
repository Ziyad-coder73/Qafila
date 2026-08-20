@extends('admin.layout')

@section('title', 'Manual Loyalty Cards — Qafila Admin')

@section('content')
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-slate-900">Manually Issued Loyalty Cards</h1>
        <a href="{{ route('admin.loyalty-cards.manual.create') }}" class="bg-blue-700 text-white text-sm font-semibold px-4 py-2 rounded-lg hover:bg-blue-800 transition">
            + Add Manually
        </a>
    </div>

    <form method="GET" action="{{ route('admin.loyalty-cards.manual.index') }}" class="mt-6 bg-white border border-slate-200 rounded-xl p-4 flex gap-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Customer, card #, phone, ID number"
            class="flex-1 rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
        <button type="submit" class="bg-slate-800 text-white text-sm font-semibold px-4 py-2 rounded-lg hover:bg-slate-900 transition">Search</button>
        <a href="{{ route('admin.loyalty-cards.manual.index') }}" class="text-sm text-slate-500 hover:text-slate-700 self-center">Clear</a>
    </form>

    <div class="mt-6 bg-white border border-slate-200 rounded-xl overflow-hidden overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-slate-500 text-left">
                <tr>
                    <th class="px-4 py-3 font-medium">Customer</th>
                    <th class="px-4 py-3 font-medium">Contact</th>
                    <th class="px-4 py-3 font-medium">ID Number</th>
                    <th class="px-4 py-3 font-medium">Insurance Co.</th>
                    <th class="px-4 py-3 font-medium">Package</th>
                    <th class="px-4 py-3 font-medium">Card #</th>
                    <th class="px-4 py-3 font-medium">Date Issued</th>
                    <th class="px-4 py-3 font-medium">Delivery</th>
                    <th class="px-4 py-3 font-medium">Status</th>
                    <th class="px-4 py-3 font-medium">Created By</th>
                    <th class="px-4 py-3 font-medium"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($members as $member)
                    <tr>
                        <td class="px-4 py-3 font-medium text-slate-900">{{ $member->full_name }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $member->phone }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $member->id_number }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $member->insurance_company }}</td>
                        <td class="px-4 py-3 text-slate-600 capitalize">{{ $member->loyalty_package }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $member->membership_number }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $member->card_issued_at->format('d M Y') }}</td>
                        <td class="px-4 py-3 text-slate-600 capitalize">{{ $member->delivery_method ?? '—' }}</td>
                        <td class="px-4 py-3">
                            @php
                                $statusColors = ['sent' => 'bg-green-100 text-green-700', 'pending' => 'bg-amber-100 text-amber-700', 'failed' => 'bg-red-100 text-red-700'];
                            @endphp
                            <span class="inline-block px-2 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$member->delivery_status] ?? 'bg-slate-100 text-slate-500' }}">
                                {{ ucfirst($member->delivery_status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-slate-600">{{ $member->issuedBy->name ?? '—' }}</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.loyalty-cards.show', $member) }}" class="text-blue-700 hover:underline">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" class="px-4 py-8 text-center text-slate-500">No manually issued loyalty cards yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $members->links() }}
    </div>
@endsection
