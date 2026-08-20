<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Partner Login — Qafila Loyalty</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-slate-100 text-slate-800 antialiased">
    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="max-w-sm w-full">
            <h1 class="text-2xl font-bold text-center text-slate-900">Qafila Partner Portal</h1>
            <p class="text-center text-sm text-slate-500 mt-1">Sign in to verify members and redeem vouchers</p>

            <form method="POST" action="{{ route('partner.login.attempt') }}" class="mt-8 bg-white border border-slate-200 rounded-xl p-6 space-y-5">
                @csrf

                @if ($errors->any())
                    <div class="rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm p-3">
                        {{ $errors->first() }}
                    </div>
                @endif

                <div>
                    <label for="username" class="block text-sm font-medium text-slate-700">Username</label>
                    <input type="text" name="username" id="username" value="{{ old('username') }}" required autofocus
                        class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-slate-700">Password</label>
                    <input type="password" name="password" id="password" required
                        class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <label class="flex items-center gap-2 text-sm text-slate-600">
                    <input type="checkbox" name="remember" class="rounded border-slate-300">
                    Remember me
                </label>

                <button type="submit" class="w-full bg-blue-700 text-white font-semibold py-2.5 rounded-lg hover:bg-blue-800 transition">
                    Sign In
                </button>
            </form>

            <p class="text-center text-xs text-slate-400 mt-4">Have a secure login link from Qafila? Open it directly to sign in without a password.</p>
        </div>
    </div>
</body>
</html>
