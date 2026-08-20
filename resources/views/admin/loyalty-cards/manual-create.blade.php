@extends('admin.layout')

@section('title', 'Manual Loyalty Card — Qafila Admin')

@section('content')
    <a href="{{ route('admin.loyalty-cards.manual.index') }}" class="text-sm text-slate-500 hover:text-slate-700">&larr; Back to loyalty cards</a>
    <h1 class="text-2xl font-bold text-slate-900 mt-2">Manually Add Loyalty Card</h1>
    <p class="mt-1 text-sm text-slate-600">For customers who aren't imported automatically from the insurance policy system.</p>

    <form method="POST" action="{{ route('admin.loyalty-cards.manual.store') }}" class="mt-6 max-w-xl bg-white border border-slate-200 rounded-xl p-6 space-y-5">
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
            <label class="block text-sm font-medium text-slate-700">Customer Name</label>
            <input type="text" name="full_name" value="{{ old('full_name') }}" required
                class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700">Contact Number</label>
            <input type="text" name="phone" value="{{ old('phone') }}" required
                class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700">ID Number (Civil ID / Passport)</label>
            <input type="text" name="id_number" value="{{ old('id_number') }}" required
                class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700">Insurance Company</label>
            <input type="text" name="insurance_company" value="{{ old('insurance_company') }}" required
                class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700">Insurance Type <span class="text-slate-400 font-normal">(optional)</span></label>
            <select name="insurance_type_id" class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
                <option value="">Not specified</option>
                @foreach($insuranceTypes as $type)
                    <option value="{{ $type->id }}" @selected(old('insurance_type_id') == $type->id)>{{ $type->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700">Loyalty Package</label>
            <div class="mt-2 space-y-2">
                @foreach($packages as $package)
                    <label class="flex items-center gap-3 border border-slate-200 rounded-lg p-3 cursor-pointer hover:border-blue-400">
                        <input type="radio" name="loyalty_package" value="{{ $package->slug }}" required @checked(old('loyalty_package', $packages->first()?->slug) === $package->slug)>
                        <span class="font-medium text-slate-900">{{ $package->title }}</span>
                        @if($package->discount_percentage)
                            <span class="text-xs text-blue-700">{{ $package->discount_percentage }}% discount</span>
                        @endif
                    </label>
                @endforeach
            </div>
        </div>

        <button type="submit" class="w-full bg-blue-700 text-white font-semibold py-2.5 rounded-lg hover:bg-blue-800 transition">
            Generate Card
        </button>
    </form>
@endsection
