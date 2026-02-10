<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-slate-900 antialiased bg-slate-50">
        <div class="relative min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-lg sm:rounded-3xl border border-slate-200 rounded-2xl">
                <div class="flex justify-center mb-8">
                    <a href="/" class="flex items-center space-x-2 group">
                        <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center text-white font-black text-xl group-hover:scale-105 transition-transform">
                            B
                        </div>
                        <span class="text-xl font-black bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">BLOG </span>
                    </a>
                </div>

                {{ $slot }}
            </div>
        </div>
        <!-- Global full-page loader for guest layouts -->
        <div id="global-loader" class="fixed inset-0 z-50 hidden items-center justify-center bg-white/60">
            <div class="flex flex-col items-center gap-3">
                <svg class="w-12 h-12 animate-spin text-blue-600" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-opacity="0.15" stroke-width="4"/><path d="M22 12a10 10 0 00-10-10" stroke="currentColor" stroke-width="4" stroke-linecap="round"/></svg>
                <div id="global-loader-text" class="text-sm text-slate-700 font-semibold">Working...</div>
            </div>
        </div>

        <script>
            (function(){
                var globalLoader = document.getElementById('global-loader');
                function showGlobalLoader(){
                    if (!globalLoader) return;
                    globalLoader.classList.remove('hidden');
                    globalLoader.classList.add('flex');
                    document.documentElement.style.overflow = 'hidden';
                    document.body.style.overflow = 'hidden';
                }

                document.addEventListener('submit', function(e){
                    var form = e.target;
                    if (!(form instanceof HTMLFormElement)) return;
                    if (form.classList.contains('js-no-loader')) return;

                    var btn = form.querySelector('button[type=submit]');
                    if (btn) {
                        try { btn.disabled = true; } catch (err) {}
                        var spinner = document.createElement('span');
                        spinner.className = 'ml-2 loader';
                        spinner.innerHTML = '⏳';
                        btn.appendChild(spinner);
                    }

                    var message = 'Working...';
                    try {
                        if (btn && btn.dataset && btn.dataset.loadingMessage) message = btn.dataset.loadingMessage;
                        else if (btn && btn.innerText && btn.innerText.trim()) message = btn.innerText.trim();
                        else if (form && form.dataset && form.dataset.loadingMessage) message = form.dataset.loadingMessage;
                    } catch (err) {}

                    var glText = document.getElementById('global-loader-text');
                    if (glText) glText.textContent = message;

                    showGlobalLoader();
                }, true);
            })();
        </script>
    </body>
</html>
