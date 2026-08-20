@extends('admin.layout')

@section('title', 'Loyalty Card — ' . $loyaltyMember->full_name)

@section('content')
    @if($loyaltyMember->policy)
        <a href="{{ route('admin.policies.show', $loyaltyMember->policy) }}" class="text-sm text-slate-500 hover:text-slate-700">&larr; Back to policy</a>
    @else
        <a href="{{ route('admin.loyalty-cards.manual.index') }}" class="text-sm text-slate-500 hover:text-slate-700">&larr; Back to loyalty cards</a>
    @endif

    <div class="mt-4 max-w-lg">
        <div class="bg-gradient-to-br from-blue-700 to-blue-900 text-white rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs uppercase tracking-wide text-blue-200">Qafila Loyalty Card</p>
                    <h1 class="text-xl font-bold mt-1">{{ $loyaltyMember->full_name }}</h1>
                    <p class="text-sm text-blue-100 mt-1">{{ $loyaltyMember->membership_number }}</p>
                </div>
                <span class="inline-block px-3 py-1 rounded-full bg-white/20 text-sm font-semibold capitalize">{{ $loyaltyMember->loyalty_package }}</span>
            </div>
            <div class="mt-4 text-xs text-blue-200">
                Issued {{ $loyaltyMember->card_issued_at->format('d M Y, H:i') }}
                @if($loyaltyMember->expires_at) &middot; Expires {{ $loyaltyMember->expires_at->format('d M Y') }} @endif
            </div>
        </div>

        @if($package)
            <div class="mt-4 bg-white border border-slate-200 rounded-xl p-4">
                <h2 class="text-sm font-semibold text-slate-900">{{ $package->title }} Benefits</h2>
                @if($package->discount_percentage)
                    <p class="text-xs text-blue-700 mt-1">{{ $package->discount_percentage }}% partner discount</p>
                @endif
                @if($package->benefits)
                    <p class="text-xs text-slate-600 mt-2 whitespace-pre-line">{{ $package->benefits }}</p>
                @endif
            </div>
        @endif

        <div class="mt-4 bg-white border border-slate-200 rounded-xl p-4">
            <p class="text-xs text-slate-500">Public card link</p>
            <div class="mt-1 flex gap-2">
                <input id="card-link" type="text" readonly value="{{ $portalUrl }}" class="flex-1 rounded-lg border border-slate-300 bg-slate-50 px-3 py-2 text-xs text-slate-600">
                <button type="button" onclick="navigator.clipboard.writeText(document.getElementById('card-link').value); this.textContent='Copied!'; setTimeout(() => this.textContent='Copy Link', 1500);"
                    class="shrink-0 bg-slate-800 text-white text-xs font-semibold px-3 rounded-lg hover:bg-slate-900 transition">
                    Copy Link
                </button>
            </div>
            <p class="mt-1 text-xs text-slate-400">If WhatsApp isn't available, copy this link and share it via SMS or Email.</p>
        </div>

        <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener"
            class="mt-4 flex items-center justify-center gap-2 w-full bg-green-600 text-white font-semibold py-3 rounded-lg hover:bg-green-700 transition">
            Send Card via WhatsApp
        </a>

        @if($loyaltyMember->isManual())
            <div class="mt-4 bg-white border border-slate-200 rounded-xl p-4">
                <h2 class="text-sm font-semibold text-slate-900">Delivery Status</h2>
                <p class="mt-1 text-xs text-slate-500">Record how — and whether — this card reached the customer.</p>
                <form method="POST" action="{{ route('admin.loyalty-cards.delivery', $loyaltyMember) }}" class="mt-3 flex gap-2">
                    @csrf
                    @method('PUT')
                    <select name="delivery_method" class="flex-1 rounded-lg border border-slate-300 px-2 py-2 text-xs focus:border-blue-500 focus:ring-blue-500">
                        <option value="whatsapp" @selected($loyaltyMember->delivery_method === 'whatsapp')>WhatsApp</option>
                        <option value="sms" @selected($loyaltyMember->delivery_method === 'sms')>SMS</option>
                        <option value="email" @selected($loyaltyMember->delivery_method === 'email')>Email</option>
                    </select>
                    <select name="delivery_status" class="flex-1 rounded-lg border border-slate-300 px-2 py-2 text-xs focus:border-blue-500 focus:ring-blue-500">
                        <option value="sent" @selected($loyaltyMember->delivery_status === 'sent')>Sent</option>
                        <option value="pending" @selected($loyaltyMember->delivery_status === 'pending')>Pending</option>
                        <option value="failed" @selected($loyaltyMember->delivery_status === 'failed')>Failed</option>
                    </select>
                    <button type="submit" class="shrink-0 bg-slate-800 text-white text-xs font-semibold px-3 rounded-lg hover:bg-slate-900 transition">
                        Save
                    </button>
                </form>
            </div>
        @endif
    </div>
@endsection
