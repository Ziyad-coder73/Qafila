@extends('admin.layout')

@section('title', 'Add Brand — Qafila Loyalty Admin')

@section('content')
    <a href="{{ route('admin.brands.index') }}" class="text-sm text-slate-500 hover:text-slate-700">&larr; Back to brands</a>
    <h1 class="text-2xl font-bold text-slate-900 mt-2">Add Partner Brand</h1>

    <form method="POST" action="{{ route('admin.brands.store') }}" enctype="multipart/form-data"
        class="mt-6 max-w-xl bg-white border border-slate-200 rounded-xl p-6 space-y-5">
        @csrf
        @include('admin.brands._form')

        <button type="submit" class="w-full bg-blue-700 text-white font-semibold py-2.5 rounded-lg hover:bg-blue-800 transition">
            Create Brand
        </button>
    </form>
@endsection
