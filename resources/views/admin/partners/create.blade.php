@extends('admin.layout')

@section('title', 'Add Partner — Qafila Loyalty Admin')

@section('content')
    <a href="{{ route('admin.partners.index') }}" class="text-sm text-slate-500 hover:text-slate-700">&larr; Back to partners</a>
    <h1 class="text-2xl font-bold text-slate-900 mt-2">Add Partner Account</h1>

    <form method="POST" action="{{ route('admin.partners.store') }}" class="mt-6 max-w-xl bg-white border border-slate-200 rounded-xl p-6 space-y-5">
        @csrf

        @if ($errors->any())
            <div class="rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm p-4">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div>
            <label for="name" class="block text-sm font-medium text-slate-700">Partner / Contact Name</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
        </div>

        <div>
            <label for="brand_id" class="block text-sm font-medium text-slate-700">Brand</label>
            <select name="brand_id" id="brand_id" required
                class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
                <option value="">Select a brand</option>
                @foreach($brands as $brand)
                    <option value="{{ $brand->id }}" @selected(old('brand_id') == $brand->id)>{{ $brand->name }}</option>
                @endforeach
            </select>
            <p class="mt-1 text-xs text-slate-400">The partner will only be able to verify members and redeem vouchers for this brand.</p>
        </div>

        <div>
            <label for="username" class="block text-sm font-medium text-slate-700">Username</label>
            <input type="text" name="username" id="username" value="{{ old('username') }}" required
                class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-slate-700">Password</label>
            <input type="text" name="password" id="password" value="{{ old('password') }}" required
                class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
            <p class="mt-1 text-xs text-slate-400">Share this with the partner securely. Minimum 8 characters.</p>
        </div>

        <button type="submit" class="w-full bg-blue-700 text-white font-semibold py-2.5 rounded-lg hover:bg-blue-800 transition">
            Create Partner Account
        </button>
    </form>
@endsection
