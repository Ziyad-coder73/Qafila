@extends('layouts.app')

@section('title', 'Qafila Loyalty — ' . ($settings['company_name'] ?? 'Qafila Insurance'))

@section('content')
    <header class="bg-white border-b border-slate-200">
        <div class="mx-auto max-w-6xl px-4 py-4 flex items-center justify-between">
            <a href="{{ route('home') }}" class="text-xl font-bold text-blue-700">
                {{ $settings['company_name'] ?? 'Qafila Insurance' }}
            </a>
            <a href="{{ route('home') }}" class="text-sm font-medium text-slate-600 hover:text-blue-700">&larr; Back to home</a>
        </div>
    </header>

    <section class="bg-gradient-to-br from-blue-700 to-blue-900 text-white">
        <div class="mx-auto max-w-6xl px-4 py-14 text-center">
            <h1 class="text-3xl font-bold">Qafila Loyalty</h1>
            <p class="mt-3 text-blue-100 max-w-2xl mx-auto">Exclusive vouchers and discounts from our partner brands.</p>
        </div>
    </section>

    <section class="mx-auto max-w-6xl px-4 py-14">
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($brands as $brand)
                <div class="border border-slate-200 rounded-xl overflow-hidden bg-white">
                    <div class="p-6">
                        <div class="flex items-center gap-3">
                            @if($brand->logo)
                                <img src="{{ asset('storage/' . $brand->logo) }}" alt="{{ $brand->name }}" class="h-12 w-12 rounded-lg object-cover border border-slate-200">
                            @else
                                <div class="h-12 w-12 rounded-lg bg-slate-100 border border-slate-200"></div>
                            @endif
                            <div>
                                <h3 class="font-semibold text-slate-900">{{ $brand->name }}</h3>
                                @if($brand->location)
                                    <p class="text-xs text-slate-500">{{ $brand->location }}</p>
                                @endif
                            </div>
                        </div>

                        @if($brand->contact_info)
                            <p class="mt-3 text-sm text-slate-600">{{ $brand->contact_info }}</p>
                        @endif

                        <div class="mt-4 space-y-2">
                            @forelse($brand->voucherPackages as $voucher)
                                <div class="flex items-start justify-between gap-2 rounded-lg bg-slate-50 px-3 py-2">
                                    <div>
                                        <p class="text-sm font-medium text-slate-800">{{ $voucher->title }}</p>
                                        @if($voucher->description)
                                            <p class="text-xs text-slate-500 mt-0.5">{{ $voucher->description }}</p>
                                        @endif
                                    </div>
                                    @if($voucher->is_available)
                                        <span class="shrink-0 inline-block px-2 py-0.5 rounded-full bg-green-100 text-green-700 text-xs font-medium">Available</span>
                                    @else
                                        <span class="shrink-0 inline-block px-2 py-0.5 rounded-full bg-slate-200 text-slate-500 text-xs font-medium">Unavailable</span>
                                    @endif
                                </div>
                            @empty
                                <p class="text-xs text-slate-400">No voucher packages yet.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-slate-500 col-span-full text-center py-10">No partner brands available yet. Check back soon!</p>
            @endforelse
        </div>
    </section>
@endsection
