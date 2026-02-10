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
        <!-- Toast container & server-flash handling for guest layout (below navbar) -->
        <div id="toast-area" class="fixed top-16 right-6 z-50 space-y-3"></div>
        <script>
            (function(){
                function createToast(message, type){
                    if (!message) return;
                    var area = document.getElementById('toast-area');
                    if (!area) return;
                    var toast = document.createElement('div');
                    var style = '';
                    if (type === 'error') style = 'bg-red-50 border-red-200 text-red-700';
                    else if (type === 'info') style = 'bg-amber-50 border-amber-200 text-amber-700';
                    else style = 'bg-emerald-50 border-emerald-200 text-emerald-700';
                    toast.className = 'max-w-sm w-full shadow-lg rounded-xl border px-4 py-3 flex items-start gap-3 transition transform translate-y-2 opacity-0 '+style;
                    toast.setAttribute('role','status');
                    var icon = type === 'error' ? '⚠️' : (type === 'info' ? 'ℹ️' : '✅');
                    toast.innerHTML = '<div class="flex-shrink-0 mt-0.5">'+icon+'</div><div class="flex-1 text-sm">'+(message||'')+'</div><button class="ml-3 text-slate-400 hover:text-slate-600">&times;</button>';
                    area.appendChild(toast);
                    requestAnimationFrame(function(){ toast.classList.remove('translate-y-2'); toast.classList.add('translate-y-0'); toast.style.opacity = 1; });
                    toast.querySelector('button').addEventListener('click', function(){ removeToast(toast); });
                    setTimeout(function(){ removeToast(toast); }, 5000);
                }
                function removeToast(el){ if (!el) return; el.style.opacity = 0; el.classList.add('translate-y-2'); setTimeout(function(){ try{ el.remove(); }catch(e){} }, 300); }
                window.showToast = createToast;

                document.addEventListener('DOMContentLoaded', function(){
                    var msg = null; var type = 'success';
                    @if(session()->has('status'))
                        msg = @json(session('status'));
                        type = 'success';
                    @elseif(session()->has('success'))
                        msg = @json(session('success'));
                        type = 'success';
                    @elseif(session()->has('error'))
                        msg = @json(session('error')); type = 'error';
                    @elseif(session()->has('info'))
                        msg = @json(session('info')); type = 'info';
                    @elseif($errors->any())
                        msg = @json($errors->first()); type = 'error';
                    @endif

                    if (msg) createToast(msg, type);
                });
            })();
        </script>
    </body>
</html>
