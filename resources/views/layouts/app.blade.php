<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Blog') }}</title>

            {{-- SEO / Meta: per-page overrides via @section('meta_title'), @section('meta_description'), @section('meta_image') --}}
            @php
                $metaTitle = View::hasSection('meta_title') ? trim($__env->yieldContent('meta_title')) : config('app.name', 'BLOG');
                $metaDescription = View::hasSection('meta_description') ? trim($__env->yieldContent('meta_description')) : 'A personal blog about technology, code and life.';
                $metaImage = View::hasSection('meta_image') ? trim($__env->yieldContent('meta_image')) : url('/favicon.svg');
                $metaUrl = url()->current();
                $isIndexable = app()->environment('production') ? 'index,follow' : 'noindex,nofollow';
            @endphp

            <meta name="description" content="{{ $metaDescription }}">
            <meta name="robots" content="{{ $isIndexable }}">
            <link rel="canonical" href="{{ $metaUrl }}">

            <!-- Open Graph -->
            <meta property="og:site_name" content="{{ config('app.name', 'BLOG') }}">
            <meta property="og:title" content="{{ $metaTitle }}">
            <meta property="og:description" content="{{ $metaDescription }}">
            <meta property="og:url" content="{{ $metaUrl }}">
            <meta property="og:image" content="{{ $metaImage }}">
            <meta property="og:type" content="website">

            <!-- Twitter -->
            <meta name="twitter:card" content="summary_large_image">
            <meta name="twitter:title" content="{{ $metaTitle }}">
            <meta name="twitter:description" content="{{ $metaDescription }}">
            <meta name="twitter:image" content="{{ $metaImage }}">

        <!-- Favicons -->
        <link rel="icon" type="image/svg+xml" href="/favicon.svg">
        <link rel="shortcut icon" href="/favicon.svg" />

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-slate-50 text-slate-900 min-h-screen flex flex-col">
        <div class="relative flex-1 overflow-x-hidden">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white border-b border-slate-200 shadow-sm">
                    <div class="max-w-6xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        <h1 class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">{{ $header }}</h1>
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="relative">
                {{ $slot ?? '' }}
            </main>
        </div>
        <!-- Global full-page loader -->
        <div id="global-loader" class="fixed inset-0 z-50 hidden items-center justify-center bg-white/60">
            <div class="flex flex-col items-center gap-3">
                <svg class="w-12 h-12 animate-spin text-blue-600" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-opacity="0.15" stroke-width="4"/><path d="M22 12a10 10 0 00-10-10" stroke="currentColor" stroke-width="4" stroke-linecap="round"/></svg>
                <div id="global-loader-text" class="text-sm text-slate-700 font-semibold">Working...</div>
            </div>
        </div>

        <!-- Delete confirmation modal -->
        <div id="delete-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 px-4">
            <div class="bg-white rounded-2xl p-6 mx-auto" style="width: min(90vw,48rem);">
                <h3 class="text-lg font-bold text-slate-900 mb-2">Confirm delete</h3>
                <p id="delete-modal-message" class="text-sm text-slate-600 mb-4">Are you sure you want to delete this item? This action cannot be undone.</p>
                <div class="flex items-center justify-end gap-3">
                    <button id="delete-modal-cancel" class="px-4 py-2 bg-slate-100 rounded-lg hover:bg-slate-200">Cancel</button>
                    <button id="delete-modal-confirm" class="px-4 py-2 bg-red-600 text-white rounded-lg flex items-center gap-2">
                        <svg id="delete-modal-spinner" class="w-4 h-4 animate-spin hidden" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="12" cy="12" r="10" stroke="white" stroke-opacity="0.25" stroke-width="4"/><path d="M22 12a10 10 0 00-10-10" stroke="white" stroke-width="4" stroke-linecap="round"/></svg>
                        <span id="delete-modal-confirm-text">Delete</span>
                    </button>
                </div>
            </div>
        </div>

        <script>
            (function(){
                // Show loader on any form submit (global overlay + button spinner)
                var globalLoader = document.getElementById('global-loader');
                function showGlobalLoader(){
                    if (!globalLoader) return;
                    globalLoader.classList.remove('hidden');
                    globalLoader.classList.add('flex');
                    // prevent background scroll
                    document.documentElement.style.overflow = 'hidden';
                    document.body.style.overflow = 'hidden';
                }

                document.addEventListener('submit', function(e){
                    var form = e.target;
                    if (!(form instanceof HTMLFormElement)) return;
                    // If form has class js-no-loader skip
                    if (form.classList.contains('js-no-loader')) return;

                        // Append small spinner to submit button if present
                        var btn = form.querySelector('button[type=submit]');
                        if (btn) {
                            btn.disabled = true;
                            var spinner = document.createElement('span');
                            spinner.className = 'ml-2 loader';
                            spinner.innerHTML = '⏳';
                            btn.appendChild(spinner);
                        }

                        // Determine message to show in global loader (button dataset -> button text -> form dataset -> default)
                        var message = 'Working...';
                        try {
                            if (btn && btn.dataset && btn.dataset.loadingMessage) message = btn.dataset.loadingMessage;
                            else if (btn && btn.innerText && btn.innerText.trim()) message = btn.innerText.trim();
                            else if (form && form.dataset && form.dataset.loadingMessage) message = form.dataset.loadingMessage;
                        } catch (err) {}

                        var glText = document.getElementById('global-loader-text');
                        if (glText) glText.textContent = message;

                        // Show the global overlay loader as well
                        showGlobalLoader();
                }, true);

                // Delete modal behavior
                var currentDeleteForm = null;
                var deleteModal = document.getElementById('delete-modal');
                var btnCancel = document.getElementById('delete-modal-cancel');
                var btnConfirm = document.getElementById('delete-modal-confirm');
                var spinner = document.getElementById('delete-modal-spinner');
                var confirmText = document.getElementById('delete-modal-confirm-text');
                var msgEl = document.getElementById('delete-modal-message');

                function openModal(message, form){
                    msgEl.textContent = message || msgEl.textContent;
                    currentDeleteForm = form;
                    // prevent background scroll
                    document.documentElement.style.overflow = 'hidden';
                    document.body.style.overflow = 'hidden';
                    deleteModal.classList.remove('hidden');
                    deleteModal.classList.add('flex');
                }
                function closeModal(){
                    deleteModal.classList.add('hidden');
                    deleteModal.classList.remove('flex');
                    currentDeleteForm = null;
                    spinner.classList.add('hidden');
                    confirmText.style.opacity = 1;
                    // restore scroll
                    document.documentElement.style.overflow = '';
                    document.body.style.overflow = '';
                }

                // Intercept clicks on elements with data-delete-form
                document.addEventListener('click', function(e){
                    var el = e.target.closest('[data-delete-form]');
                    if (!el) return;
                    e.preventDefault();
                    var formSelector = el.getAttribute('data-delete-form');
                    var message = el.getAttribute('data-delete-message') || 'Are you sure you want to delete this item? This action cannot be undone.';
                    var form = document.querySelector(formSelector);
                    if (!form) return console.warn('Delete form not found for selector', formSelector);
                    openModal(message, form);
                });

                btnCancel.addEventListener('click', function(){ closeModal(); });

                btnConfirm.addEventListener('click', function(){
                    if (!currentDeleteForm) return closeModal();
                    // show spinner
                    spinner.classList.remove('hidden');
                    confirmText.style.opacity = 0.6;
                    // set loader text for deleting and show global loader
                    try {
                        var glText = document.getElementById('global-loader-text');
                        var delMsg = currentDeleteForm && currentDeleteForm.dataset && currentDeleteForm.dataset.loadingMessage ? currentDeleteForm.dataset.loadingMessage : 'Deleting...';
                        if (glText) glText.textContent = delMsg;
                    } catch (e) {}
                    showGlobalLoader();
                    // submit form programmatically
                    currentDeleteForm.submit();
                });

                // Close modal on Escape
                document.addEventListener('keydown', function(e){ if (e.key === 'Escape') closeModal(); });
            })();
        </script>
        <!-- Toast container & server-flash handling (positioned below sticky navbar) -->
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
                    // animate in
                    requestAnimationFrame(function(){ toast.classList.remove('translate-y-2'); toast.classList.add('translate-y-0'); toast.style.opacity = 1; });
                    // close handler
                    toast.querySelector('button').addEventListener('click', function(){ removeToast(toast); });
                    // auto remove
                    setTimeout(function(){ removeToast(toast); }, 5000);
                }
                function removeToast(el){ if (!el) return; el.style.opacity = 0; el.classList.add('translate-y-2'); setTimeout(function(){ try{ el.remove(); }catch(e){} }, 300); }

                // Expose global helper
                window.showToast = createToast;

                // Server flash messages
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
