@extends('admin.layout')

@section('title', 'Brands — Qafila Loyalty Admin')

@section('content')
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-slate-900">Partner Brands</h1>
        <a href="{{ route('admin.brands.create') }}" class="bg-blue-700 text-white text-sm font-semibold px-4 py-2 rounded-lg hover:bg-blue-800 transition">
            + Add Brand
        </a>
    </div>

    <div class="mt-6 bg-white border border-slate-200 rounded-xl overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-slate-500 text-left">
                <tr>
                    <th class="px-4 py-3 font-medium">Logo</th>
                    <th class="px-4 py-3 font-medium">Brand Name</th>
                    <th class="px-4 py-3 font-medium">Location</th>
                    <th class="px-4 py-3 font-medium">Owner</th>
                    <th class="px-4 py-3 font-medium">Vouchers</th>
                    <th class="px-4 py-3 font-medium">Status</th>
                    <th class="px-4 py-3 font-medium"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($brands as $brand)
                    <tr>
                        <td class="px-4 py-3">
                            @if($brand->logo)
                                <img src="{{ asset('storage/' . $brand->logo) }}" alt="{{ $brand->name }}" class="h-10 w-10 rounded-lg object-cover border border-slate-200">
                            @else
                                <div class="h-10 w-10 rounded-lg bg-slate-100 border border-slate-200"></div>
                            @endif
                        </td>
                        <td class="px-4 py-3 font-medium text-slate-900">{{ $brand->name }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $brand->location }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $brand->owner_name }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $brand->voucher_packages_count }}</td>
                        <td class="px-4 py-3">
                            @if($brand->is_active)
                                <span class="inline-block px-2 py-0.5 rounded-full bg-green-100 text-green-700 text-xs font-medium">Active</span>
                            @else
                                <span class="inline-block px-2 py-0.5 rounded-full bg-slate-100 text-slate-500 text-xs font-medium">Hidden</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right space-x-3">
                            <a href="{{ route('admin.brands.edit', $brand) }}" class="text-blue-700 hover:underline">Edit</a>
                            <form method="POST" action="{{ route('admin.brands.destroy', $brand) }}" class="inline" onsubmit="return confirm('Delete this brand and all its vouchers?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-slate-500">No brands yet. Add your first partner brand.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
