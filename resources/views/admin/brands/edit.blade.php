@extends('admin.layout')

@section('title', 'Edit ' . $brand->name . ' — Qafila Loyalty Admin')

@section('content')
    <a href="{{ route('admin.brands.index') }}" class="text-sm text-slate-500 hover:text-slate-700">&larr; Back to brands</a>
    <h1 class="text-2xl font-bold text-slate-900 mt-2">Edit {{ $brand->name }}</h1>

    <div class="mt-6 grid lg:grid-cols-2 gap-8">
        <form method="POST" action="{{ route('admin.brands.update', $brand) }}" enctype="multipart/form-data"
            class="bg-white border border-slate-200 rounded-xl p-6 space-y-5 h-fit">
            @csrf
            @method('PUT')
            @include('admin.brands._form')

            <button type="submit" class="w-full bg-blue-700 text-white font-semibold py-2.5 rounded-lg hover:bg-blue-800 transition">
                Save Changes
            </button>
        </form>

        <div>
            <h2 class="font-semibold text-slate-900">Voucher Packages / Offers</h2>

            <div class="mt-4 space-y-3">
                @forelse($brand->voucherPackages as $voucher)
                    <div class="bg-white border border-slate-200 rounded-xl p-4">
                        <form method="POST" action="{{ route('admin.brands.vouchers.update', [$brand, $voucher]) }}" class="space-y-3">
                            @csrf
                            @method('PUT')
                            <input type="text" name="title" value="{{ $voucher->title }}" required
                                class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm font-medium focus:border-blue-500 focus:ring-blue-500">
                            <textarea name="description" rows="2"
                                class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500"
                                placeholder="Offer details">{{ $voucher->description }}</textarea>
                            <div class="flex items-center justify-between">
                                <label class="flex items-center gap-2 text-sm text-slate-600">
                                    <input type="hidden" name="is_available" value="0">
                                    <input type="checkbox" name="is_available" value="1" class="rounded border-slate-300" @checked($voucher->is_available)>
                                    Available
                                </label>
                                <div class="space-x-3">
                                    <button type="submit" class="text-blue-700 text-sm font-medium hover:underline">Save</button>
                                </div>
                            </div>
                        </form>
                        <form method="POST" action="{{ route('admin.brands.vouchers.destroy', [$brand, $voucher]) }}"
                            onsubmit="return confirm('Remove this voucher package?');" class="mt-2 text-right">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 text-xs hover:underline">Remove</button>
                        </form>
                    </div>
                @empty
                    <p class="text-sm text-slate-500">No voucher packages yet.</p>
                @endforelse
            </div>

            <form method="POST" action="{{ route('admin.brands.vouchers.store', $brand) }}"
                class="mt-4 bg-white border border-dashed border-slate-300 rounded-xl p-4 space-y-3">
                @csrf
                <h3 class="text-sm font-semibold text-slate-700">Add Voucher Package</h3>
                <input type="text" name="title" placeholder="e.g. 20% Off Silver Package" required
                    class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
                <textarea name="description" rows="2" placeholder="Offer details"
                    class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                <label class="flex items-center gap-2 text-sm text-slate-600">
                    <input type="hidden" name="is_available" value="0">
                    <input type="checkbox" name="is_available" value="1" class="rounded border-slate-300" checked>
                    Available
                </label>
                <button type="submit" class="w-full bg-slate-800 text-white text-sm font-semibold py-2 rounded-lg hover:bg-slate-900 transition">
                    Add Package
                </button>
            </form>
        </div>
    </div>
@endsection
