<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Qafila Loyalty Admin')</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-slate-100 text-slate-800 antialiased">
    <div class="min-h-screen flex flex-col">
        <header class="bg-slate-900 text-white">
            <div class="mx-auto max-w-6xl px-4 py-4 flex items-center justify-between">
                <a href="{{ auth()->user()->isAdmin() ? route('admin.brands.index') : route('admin.policies.index') }}" class="font-bold text-lg">Qafila Admin</a>
                <div class="flex items-center gap-4 text-sm">
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.brands.index') }}" class="text-slate-300 hover:text-white">Brands</a>
                        <a href="{{ route('admin.partners.index') }}" class="text-slate-300 hover:text-white">Partners</a>
                        <a href="{{ route('admin.agents.index') }}" class="text-slate-300 hover:text-white">Agents</a>
                        <a href="{{ route('admin.loyalty-packages.index') }}" class="text-slate-300 hover:text-white">Loyalty Packages</a>
                        <a href="{{ route('admin.loyalty-cards.manual.index') }}" class="text-slate-300 hover:text-white">Loyalty Cards</a>
                        <a href="{{ route('admin.notifications.index') }}" class="text-slate-300 hover:text-white">Notifications</a>
                    @endif
                    <a href="{{ route('admin.policies.index') }}" class="text-slate-300 hover:text-white">Policies</a>
                    <a href="{{ route('admin.reports.index') }}" class="text-slate-300 hover:text-white">Reports</a>
                    <span class="text-slate-500">|</span>
                    <span class="text-slate-300">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" class="text-slate-300 hover:text-white">Logout</button>
                    </form>
                </div>
            </div>
        </header>

        <main class="flex-1">
            <div class="mx-auto max-w-6xl px-4 py-10">
                @if (session('status'))
                    <div class="mb-6 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3">
                        {{ session('status') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>
