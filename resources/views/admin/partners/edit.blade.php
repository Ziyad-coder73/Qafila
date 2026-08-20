@extends('admin.layout')

@section('title', 'Edit ' . $partner->name . ' — Qafila Loyalty Admin')

@section('content')
    <a href="{{ route('admin.partners.index') }}" class="text-sm text-slate-500 hover:text-slate-700">&larr; Back to partners</a>
    <h1 class="text-2xl font-bold text-slate-900 mt-2">Edit {{ $partner->name }}</h1>

    <div class="mt-6 grid lg:grid-cols-2 gap-8">
        <form method="POST" action="{{ route('admin.partners.update', $partner) }}" class="bg-white border border-slate-200 rounded-xl p-6 space-y-5 h-fit">
            @csrf
            @method('PUT')

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
                <input type="text" name="name" id="name" value="{{ old('name', $partner->name) }}" required
                    class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            <div>
                <label for="brand_id" class="block text-sm font-medium text-slate-700">Brand</label>
                <select name="brand_id" id="brand_id" required
                    class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
                    @foreach($brands as $brand)
                        <option value="{{ $brand->id }}" @selected(old('brand_id', $partner->brand_id) == $brand->id)>{{ $brand->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="username" class="block text-sm font-medium text-slate-700">Username</label>
                <input type="text" name="username" id="username" value="{{ old('username', $partner->username) }}" required
                    class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-slate-700">New Password</label>
                <input type="text" name="password" id="password"
                    class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500"
                    placeholder="Leave blank to keep current password">
            </div>

            <label class="flex items-center gap-2 text-sm text-slate-700">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" class="rounded border-slate-300" @checked(old('is_active', $partner->is_active))>
                Account active
            </label>

            <button type="submit" class="w-full bg-blue-700 text-white font-semibold py-2.5 rounded-lg hover:bg-blue-800 transition">
                Save Changes
            </button>
        </form>

        <div class="space-y-4">
            <div class="bg-white border border-slate-200 rounded-xl p-6">
                <h2 class="font-semibold text-slate-900">Secure Login Link</h2>
                <p class="mt-1 text-xs text-slate-500">The partner can use this link to sign in instantly, without a username or password.</p>
                <div class="mt-3 flex items-center gap-2">
                    <input type="text" readonly value="{{ route('partner.login.magic', $partner->login_token) }}"
                        class="flex-1 rounded-lg border border-slate-300 bg-slate-50 px-3 py-2 text-xs text-slate-600">
                </div>
                <form method="POST" action="{{ route('admin.partners.regenerate-link', $partner) }}" class="mt-3" onsubmit="return confirm('Regenerating will invalidate the current link.');">
                    @csrf
                    <button type="submit" class="text-sm text-blue-700 hover:underline">Regenerate link</button>
                </form>
            </div>

            <div class="bg-white border border-slate-200 rounded-xl p-6">
                <h2 class="font-semibold text-slate-900">Login Credentials</h2>
                <dl class="mt-3 text-sm space-y-1">
                    <div class="flex justify-between"><dt class="text-slate-500">Username</dt><dd class="text-slate-800">{{ $partner->username }}</dd></div>
                    <div class="flex justify-between"><dt class="text-slate-500">Brand</dt><dd class="text-slate-800">{{ $partner->brand->name ?? '—' }}</dd></div>
                    <div class="flex justify-between"><dt class="text-slate-500">Portal URL</dt><dd class="text-slate-800">{{ route('partner.login') }}</dd></div>
                </dl>
            </div>
        </div>
    </div>
@endsection
