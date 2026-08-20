@extends('layouts.app')

@section('title', $member->full_name . ' — Qafila Loyalty Card')

@section('content')
    <header class="bg-white border-b border-slate-200">
        <div class="mx-auto max-w-2xl px-4 py-4 flex items-center justify-between">
            <span class="text-xl font-bold text-blue-700">{{ $settings['company_name'] ?? 'Qafila Insurance' }}</span>
            <a href="{{ route('loyalty.index') }}" class="text-sm font-medium text-slate-600 hover:text-blue-700">Loyalty Portal &rarr;</a>
        </div>
    </header>

    <div class="mx-auto max-w-lg px-4 py-10">
        <div class="bg-gradient-to-br from-blue-700 to-blue-900 text-white rounded-xl p-6">
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
                @if($member->expires_at)
                    <span class="text-xs text-blue-200">Expires {{ $member->expires_at->format('d M Y') }}</span>
                @endif
            </div>
        </div>

        @if($package)
            <div class="mt-6 bg-white border border-slate-200 rounded-xl p-6">
                <h2 class="font-semibold text-slate-900">{{ $package->title }} Benefits</h2>
                @if($package->discount_percentage)
                    <p class="text-sm text-blue-700 mt-1">{{ $package->discount_percentage }}% discount at Qafila partner brands</p>
                @endif
                @if($package->benefits)
                    <p class="text-sm text-slate-600 mt-3 whitespace-pre-line">{{ $package->benefits }}</p>
                @endif
            </div>
        @endif

        <div class="mt-6 bg-white border border-slate-200 rounded-xl p-6">
            <h2 class="font-semibold text-slate-900">How to Use Your Card</h2>
            <ol class="mt-3 text-sm text-slate-600 space-y-2 list-decimal list-inside">
                <li>Visit any Qafila partner brand listed on the <a href="{{ route('loyalty.index') }}" class="text-blue-700 hover:underline">Loyalty Portal</a>.</li>
                <li>Show this page or your membership number ({{ $member->membership_number }}) to the staff.</li>
                <li>The partner will verify your card and apply your available voucher or discount.</li>
            </ol>
        </div>

        <a href="{{ route('loyalty.index') }}" class="mt-6 block text-center bg-blue-700 text-white font-semibold py-3 rounded-lg hover:bg-blue-800 transition">
            Browse Partner Brands &amp; Offers
        </a>
    </div>
@endsection
