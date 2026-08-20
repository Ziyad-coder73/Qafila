@extends('partner.layout')

@section('title', $member->full_name . ' — Qafila Partner Portal')

@section('content')
    <a href="{{ route('partner.portal') }}" class="text-sm text-slate-500 hover:text-slate-700">&larr; Verify another member</a>

    <div class="mt-4 bg-gradient-to-br from-blue-700 to-blue-900 text-white rounded-xl p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs uppercase tracking-wide text-blue-200">Qafila Loyalty Card</p>
                <h1 class="text-xl font-bold mt-1">{{ $member->full_name }}</h1>
                <p class="text-sm text-blue-100 mt-1">{{ $member->membership_number }}</p>
            </div>
            <span class="inline-block px-3 py-1 rounded-full bg-white/20 text-sm font-semibold capitalize">{{ $member->loyalty_package }}</span>
        </div>

        <div class="mt-4 flex items-center gap-2">
            @if($member->isValid())
                <span class="inline-block px-2 py-0.5 rounded-full bg-green-400/20 text-green-200 text-xs font-medium">Valid</span>
            @else
                <span class="inline-block px-2 py-0.5 rounded-full bg-red-400/20 text-red-200 text-xs font-medium">Invalid / Expired</span>
            @endif
            @if($member->phone)
                <span class="text-xs text-blue-200">{{ $member->phone }}</span>
            @endif
            @if($member->expires_at)
                <span class="text-xs text-blue-200">Expires {{ $member->expires_at->format('d M Y') }}</span>
            @endif
        </div>
    </div>

    <div class="mt-8">
        <h2 class="font-semibold text-slate-900">Available Voucher Packages</h2>

        <div class="mt-4 space-y-3">
            @forelse($voucherPackages as $voucher)
                <div class="bg-white border border-slate-200 rounded-xl p-4 flex items-center justify-between gap-4">
                    <div>
                        <p class="font-medium text-slate-800">{{ $voucher->title }}</p>
                        @if($voucher->description)
                            <p class="text-xs text-slate-500 mt-0.5">{{ $voucher->description }}</p>
                        @endif
                        @if($voucher->brand)
                            <p class="text-xs text-slate-400 mt-0.5">{{ $voucher->brand->name }}</p>
                        @endif
                    </div>
                    <form method="POST" action="{{ route('partner.members.redeem', $member) }}">
                        @csrf
                        <input type="hidden" name="voucher_package_id" value="{{ $voucher->id }}">
                        <button type="submit" @disabled(! $member->isValid())
                            class="bg-green-600 text-white text-sm font-semibold px-4 py-2 rounded-lg hover:bg-green-700 transition disabled:opacity-40 disabled:cursor-not-allowed">
                            Redeem
                        </button>
                    </form>
                </div>
            @empty
                <p class="text-sm text-slate-500">No available voucher packages for your brand.</p>
            @endforelse
        </div>
    </div>

    <div class="mt-8">
        <h2 class="font-semibold text-slate-900">Redemption History</h2>
        <div class="mt-4 bg-white border border-slate-200 rounded-xl overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 text-slate-500 text-left">
                    <tr>
                        <th class="px-4 py-2 font-medium">Voucher</th>
                        <th class="px-4 py-2 font-medium">Brand</th>
                        <th class="px-4 py-2 font-medium">Redeemed At</th>
                        <th class="px-4 py-2 font-medium">Redeemed By</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($redemptions as $redemption)
                        <tr>
                            <td class="px-4 py-2 text-slate-800">{{ $redemption->voucherPackage->title ?? '—' }}</td>
                            <td class="px-4 py-2 text-slate-600">{{ $redemption->brand->name ?? '—' }}</td>
                            <td class="px-4 py-2 text-slate-600">{{ $redemption->redeemed_at->format('d M Y, H:i') }}</td>
                            <td class="px-4 py-2 text-slate-600">{{ $redemption->partner->name ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-6 text-center text-slate-500">No redemptions yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
