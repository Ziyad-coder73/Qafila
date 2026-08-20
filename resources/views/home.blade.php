@extends('layouts.app')

@section('title', ($settings['company_name'] ?? 'Qafila Insurance') . ' — Insurance Made Simple')

@section('content')

    {{-- Header --}}
    <header class="bg-white border-b border-slate-200">
        <div class="mx-auto max-w-6xl px-4 py-4 flex items-center justify-between">
            <a href="{{ route('home') }}" class="text-xl font-bold text-blue-700">
                {{ $settings['company_name'] ?? 'Qafila Insurance' }}
            </a>
            <nav class="hidden sm:flex items-center gap-6 text-sm font-medium text-slate-600">
                <a href="#about" class="hover:text-blue-700">About</a>
                <a href="#insurance-types" class="hover:text-blue-700">Insurance</a>
                <a href="#payment-methods" class="hover:text-blue-700">Payment</a>
                <a href="{{ route('loyalty.index') }}" class="hover:text-blue-700">Loyalty</a>
                <a href="#request-form" class="hover:text-blue-700">Get a Quote</a>
            </nav>
        </div>
    </header>

    {{-- Hero --}}
    <section class="bg-gradient-to-br from-blue-700 to-blue-900 text-white">
        <div class="mx-auto max-w-6xl px-4 py-16 text-center">
            <h1 class="text-3xl sm:text-4xl font-bold">{{ $settings['company_tagline'] ?? 'Your trusted insurance partner' }}</h1>
            <p class="mt-4 text-blue-100 max-w-2xl mx-auto">{{ $settings['company_about'] ?? '' }}</p>
            <a href="#request-form" class="inline-block mt-8 bg-white text-blue-800 font-semibold px-6 py-3 rounded-lg hover:bg-blue-50 transition">
                Request Insurance Now
            </a>
        </div>
    </section>

    {{-- About --}}
    <section id="about" class="mx-auto max-w-6xl px-4 py-14">
        <h2 class="text-2xl font-bold text-slate-900">About {{ $settings['company_name'] ?? 'Qafila Insurance' }}</h2>
        <p class="mt-4 text-slate-600 leading-relaxed max-w-3xl">{{ $settings['company_about'] ?? '' }}</p>
        <div class="mt-6 grid sm:grid-cols-3 gap-4 text-sm text-slate-600">
            @if(!empty($settings['company_phone']))
                <div class="flex items-center gap-2">
                    <span class="font-semibold text-slate-900">Phone:</span> {{ $settings['company_phone'] }}
                </div>
            @endif
            @if(!empty($settings['company_email']))
                <div class="flex items-center gap-2">
                    <span class="font-semibold text-slate-900">Email:</span> {{ $settings['company_email'] }}
                </div>
            @endif
            @if(!empty($settings['company_address']))
                <div class="flex items-center gap-2">
                    <span class="font-semibold text-slate-900">Address:</span> {{ $settings['company_address'] }}
                </div>
            @endif
        </div>
    </section>

    {{-- Insurance Types --}}
    <section id="insurance-types" class="bg-white border-y border-slate-200">
        <div class="mx-auto max-w-6xl px-4 py-14">
            <h2 class="text-2xl font-bold text-slate-900">Insurance Types</h2>
            <div class="mt-8 grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($insuranceTypes as $type)
                    <div class="border border-slate-200 rounded-xl p-6 hover:shadow-md transition">
                        <h3 class="font-semibold text-lg text-slate-900">{{ $type->name }}</h3>
                        <p class="mt-2 text-sm text-slate-600">{{ $type->description }}</p>
                    </div>
                @empty
                    <p class="text-slate-500">No insurance types available yet.</p>
                @endforelse
            </div>
        </div>
    </section>

    {{-- Payment Methods --}}
    <section id="payment-methods" class="mx-auto max-w-6xl px-4 py-14">
        <h2 class="text-2xl font-bold text-slate-900">Payment Methods</h2>
        <div class="mt-8 grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
            @forelse($paymentMethods as $method)
                <div class="border border-slate-200 rounded-xl p-5 text-center">
                    <h3 class="font-semibold text-slate-900">{{ $method->name }}</h3>
                    <p class="mt-1 text-xs text-slate-500">{{ $method->description }}</p>
                </div>
            @empty
                <p class="text-slate-500">No payment methods available yet.</p>
            @endforelse
        </div>
    </section>

    {{-- Request Form --}}
    <section id="request-form" class="bg-white border-y border-slate-200">
        <div class="mx-auto max-w-2xl px-4 py-14">
            <h2 class="text-2xl font-bold text-slate-900">Request Insurance</h2>
            <p class="mt-2 text-sm text-slate-600">Fill in your details and we'll open WhatsApp with your request ready to send to our team.</p>

            @if ($errors->any())
                <div class="mt-6 rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm p-4">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('insurance-requests.store') }}" class="mt-6 space-y-5">
                @csrf

                <div>
                    <label for="full_name" class="block text-sm font-medium text-slate-700">Full Name</label>
                    <input type="text" name="full_name" id="full_name" value="{{ old('full_name') }}" required
                        class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-slate-700">Phone Number</label>
                    <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" required
                        class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label for="civil_id" class="block text-sm font-medium text-slate-700">Civil ID Number</label>
                    <input type="text" name="civil_id" id="civil_id" value="{{ old('civil_id') }}" required
                        class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label for="insurance_type_id" class="block text-sm font-medium text-slate-700">Required Insurance Type</label>
                    <select name="insurance_type_id" id="insurance_type_id" required
                        class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Select an insurance type</option>
                        @foreach($insuranceTypes as $type)
                            <option value="{{ $type->id }}" @selected(old('insurance_type_id') == $type->id)>{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="w-full bg-blue-700 text-white font-semibold py-3 rounded-lg hover:bg-blue-800 transition">
                    Send Request via WhatsApp
                </button>
            </form>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="bg-slate-900 text-slate-300">
        <div class="mx-auto max-w-6xl px-4 py-10 flex flex-col sm:flex-row items-center justify-between gap-6">
            <div>
                <p class="font-semibold text-white">{{ $settings['company_name'] ?? 'Qafila Insurance' }}</p>
                <p class="text-sm text-slate-400 mt-1">{{ $settings['company_address'] ?? '' }}</p>
            </div>
            <div class="flex items-center gap-4">
                @foreach($socialLinks as $link)
                    <a href="{{ $link->url }}" target="_blank" rel="noopener" class="text-sm hover:text-white transition">
                        {{ $link->platform }}
                    </a>
                @endforeach
            </div>
        </div>
    </footer>

@endsection
