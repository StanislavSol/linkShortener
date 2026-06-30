<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LinkShortener</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-3xl mx-auto px-4">
        <div class="text-center mb-12">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-indigo-500/20 mb-6">
                <svg class="w-10 h-10 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                </svg>
            </div>
            <h1 class="text-5xl md:text-6xl font-extrabold text-white mb-4 tracking-tight">
                Link<span class="text-indigo-400">Shortener</span>
            </h1>
            <p class="text-lg text-slate-400 max-w-xl mx-auto">
                Укорачивайте ссылки, отслеживайте переходы и управляйте всем из удобной панели.
            </p>
        </div>

        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            @auth
                <a href="{{ url('/admin') }}"
                   class="inline-flex items-center justify-center px-8 py-4 text-lg font-semibold text-white bg-indigo-600 rounded-xl hover:bg-indigo-500 transition-all duration-200 shadow-lg shadow-indigo-500/25 hover:shadow-indigo-500/40">
                    Панель управления
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </a>
            @else
                <a href="/admin/login"
                   class="inline-flex text-emerald-400s-center justify-center px-8 py-4 text-lg font-semibold text-white bg-indigo-600 rounded-xl hover:bg-indigo-500 transition-all duration-200 shadow-lg shadow-indigo-500/25 hover:shadow-indigo-500/40">
                    Войти
                </a>
                <a href="/admin/register"
                   class="inline-flex items-center justify-center px-8 py-4 text-lg font-semibold text-indigo-300 border-2 border-indigo-500/30 rounded-xl hover:bg-indigo-500/10 transition-all duration-200">
                    Регистрация
                </a>
            @endauth
        </div>

        <div class="mt-16 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-6 border border-white/10">
                <div class="w-10 h-10 bg-indigo-500/20 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                </div>
                <h3 class="text-white font-semibold mb-2">Мгновенное сокращение</h3>
                <p class="text-slate-400 text-sm">Создавайте короткие ссылки в один клик. Никакой магии, только скорость.</p>
            </div>
            <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-6 border border-white/10">
                <div class="w-10 h-10 bg-emerald-500/20 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
                <h3 class="text-white font-semibold mb-2">Детальная статистика</h3>
                <p class="text-slate-400 text-sm">Отслеживайте каждый клик: IP, дату и время перехода.</p>
            </div>
            <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-6 border border-white/10">
                <div class="w-10 h-10 bg-amber-500/20 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <h3 class="text-white font-semibold mb-2">Личный кабинет</h3>
                <p class="text-slate-400 text-sm">Управляйте ссылками и смотрите аналитику в удобной панели.</p>
            </div>
        </div>
    </div>
</body>
</html>
