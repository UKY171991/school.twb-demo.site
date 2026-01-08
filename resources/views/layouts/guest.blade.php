<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased bg-slate-50 selection:bg-indigo-500 selection:text-white">
        <div class="min-h-screen flex flex-col sm:justify-center items-center py-12 px-6 relative overflow-hidden">
            <!-- Background Decorative Elements -->
            <div class="absolute top-0 left-0 w-full h-full pointer-events-none z-0">
                <div class="absolute -top-24 -left-24 w-96 h-96 bg-indigo-500/10 rounded-full blur-3xl"></div>
                <div class="absolute top-1/2 -right-24 w-80 h-80 bg-emerald-500/10 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-24 left-1/2 w-64 h-64 bg-blue-500/10 rounded-full blur-3xl"></div>
            </div>

            <div class="relative z-10 w-full flex flex-col items-center">
                <div class="mb-10 transform transition-transform hover:scale-110 duration-500">
                    <a href="/" class="flex items-center space-x-3 bg-white p-4 rounded-3xl shadow-xl shadow-indigo-100/50 border border-slate-100 italic">
                        <div class="w-12 h-12 gradient-primary rounded-2xl flex items-center justify-center text-white shadow-lg shadow-indigo-200">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                        <span class="text-xl font-black text-slate-800 tracking-tightest uppercase">SMS<span class="text-indigo-600">Pro</span></span>
                    </a>
                </div>

                <div class="w-full sm:max-w-md glass-card rounded-[2.5rem] shadow-2xl shadow-indigo-100/50 border border-white overflow-hidden p-1">
                    <div class="bg-white/40 backdrop-blur-xl rounded-[2.25rem] px-10 py-12">
                        {{ $slot }}
                    </div>
                </div>

                <div class="mt-8 text-center">
                    <p class="text-[11px] font-black text-slate-400 uppercase tracking-[0.3em]">Institutional Access Protocol</p>
                </div>
            </div>
        </div>
    </body>
</html>
