@extends('admin.layout')

@section('title', 'Loyalty Package Management — Qafila Admin')

@section('content')
    <h1 class="text-2xl font-bold text-slate-900">Loyalty Package Management</h1>
    <p class="mt-1 text-sm text-slate-600">Define the benefits and discounts included in each Qafila Loyalty tier. These are automatically applied whenever a card is issued.</p>

    <div class="mt-6 grid sm:grid-cols-3 gap-6">
        @foreach($packages as $package)
            <form method="POST" action="{{ route('admin.loyalty-packages.update', $package) }}" class="bg-white border border-slate-200 rounded-xl p-6 space-y-4">
                @csrf
                @method('PUT')
                <div class="flex items-center justify-between">
                    <input type="text" name="title" value="{{ old('title', $package->title) }}" required
                        class="font-semibold text-slate-900 text-lg border-0 border-b border-transparent hover:border-slate-200 focus:border-blue-500 focus:ring-0 px-0 w-full">
                </div>

                <div>
                    <label class="block text-xs font-medium text-slate-500">Discount %</label>
                    <input type="number" step="0.01" min="0" max="100" name="discount_percentage" value="{{ old('discount_percentage', $package->discount_percentage) }}"
                        class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-xs font-medium text-slate-500">Benefits (one per line)</label>
                    <textarea name="benefits" rows="6"
                        class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">{{ old('benefits', $package->benefits) }}</textarea>
                </div>

                <button type="submit" class="w-full bg-slate-800 text-white text-sm font-semibold py-2 rounded-lg hover:bg-slate-900 transition">
                    Save {{ $package->title }}
                </button>
            </form>
        @endforeach
    </div>
@endsection
