<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Aplikasi Parkir</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-100 flex items-center justify-center min-h-screen">

    <div class="w-full max-w-sm bg-white border border-slate-300 rounded shadow-sm p-6">
        <div class="text-center mb-6">
            <h1 class="text-xl font-bold text-slate-800">APLIKASI PARKIR</h1>
            <p class="text-xs text-slate-500 mt-1">Silakan masuk dengan akun Anda</p>
        </div>

        @if ($errors->any())
            <div class="mb-4 bg-slate-100 border border-slate-400 text-slate-700 px-3 py-2 rounded text-xs">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Username</label>
                <input type="text" name="username" value="{{ old('username') }}" required autofocus
                    class="w-full border border-slate-300 rounded px-3 py-2 text-sm focus:outline-none focus:border-slate-600">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Password</label>
                <input type="password" name="password" required
                    class="w-full border border-slate-300 rounded px-3 py-2 text-sm focus:outline-none focus:border-slate-600">
            </div>

            <button type="submit"
                class="w-full bg-slate-800 hover:bg-slate-900 text-white text-sm font-semibold py-2 rounded transition">
                MASUK
            </button>
        </form>
    </div>

</body>

</html>
