@extends('layouts.app')

@section('title', 'Request Received — Qafila Insurance')

@section('content')
    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="max-w-md w-full text-center">
            <div class="mx-auto h-14 w-14 rounded-full bg-green-100 flex items-center justify-center text-green-600 text-2xl font-bold">
                ✓
            </div>
            <h1 class="mt-6 text-2xl font-bold text-slate-900">Almost done, {{ $insuranceRequest->full_name }}!</h1>
            <p class="mt-3 text-slate-600">
                Tap the button below to send your request to our team on WhatsApp.
            </p>

            <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener"
                class="mt-8 inline-flex items-center justify-center gap-2 w-full bg-green-600 text-white font-semibold py-3 rounded-lg hover:bg-green-700 transition">
                Open WhatsApp &amp; Send
            </a>

            <a href="{{ route('home') }}" class="mt-4 inline-block text-sm text-slate-500 hover:text-slate-700">
                &larr; Back to home
            </a>
        </div>
    </div>
@endsection
