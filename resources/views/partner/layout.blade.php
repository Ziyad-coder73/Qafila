<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Qafila Partner Portal')</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-slate-100 text-slate-800 antialiased">
    <div class="min-h-screen flex flex-col">
        <header class="bg-slate-900 text-white">
            <div class="mx-auto max-w-3xl px-4 py-4 flex items-center justify-between">
                <a href="{{ route('partner.portal') }}" class="font-bold text-lg">Qafila Partner Portal</a>
                <div class="flex items-center gap-4 text-sm">
                    @auth
                        <span class="text-slate-300">{{ auth()->user()->name }}@if(auth()->user()->brand) &middot; {{ auth()->user()->brand->name }}@endif</span>
                        <form method="POST" action="{{ route('partner.logout') }}">
                            @csrf
                            <button type="submit" class="text-slate-300 hover:text-white">Logout</button>
                        </form>
                    @endauth
                </div>
            </div>
        </header>

        <main class="flex-1">
            <div class="mx-auto max-w-3xl px-4 py-10">
                @if (session('status'))
                    <div class="mb-6 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-6 rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm p-4">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>
