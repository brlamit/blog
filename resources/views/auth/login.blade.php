<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4 bg-green-50 text-green-600 rounded-lg" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-blue-600 mb-2">Welcome Back</h2>
            <p class="text-slate-600 text-sm">Sign in to continue your blogging journey</p>
        </div>

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-bold text-slate-900 mb-2">Email Address</label>
            <input id="email" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-lg focus:border-blue-400 focus:ring-2 focus:ring-blue-400/50 text-slate-900 placeholder-slate-400 transition" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="you@example.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-600" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-bold text-slate-900 mb-2">Password</label>
            <input id="password" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-lg focus:border-blue-400 focus:ring-2 focus:ring-blue-400/50 text-slate-900 placeholder-slate-400 transition" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-600" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-slate-300 text-blue-600 shadow-sm focus:ring-blue-400" name="remember">
                <span class="ms-2 text-sm text-slate-600">{{ __('Remember me') }}</span>
            </label>
            @if (Route::has('password.request'))
                <a class="text-sm text-blue-600 hover:text-blue-700 underline transition" href="{{ route('password.request') }}">
                    {{ __('Forgot password?') }}
                </a>
            @endif
        </div>

        <div class="flex flex-col gap-4 pt-4">
            <button type="submit" class="w-full px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold rounded-lg shadow-lg hover:shadow-blue-500/30 hover:shadow-2xl transform hover:scale-105 transition duration-200">
                {{ __('Sign in') }}
            </button>
            <a class="text-center text-sm text-blue-600 hover:text-blue-700 underline transition" href="{{ route('register') }}">
                {{ __("Don't have an account? Create one") }}
            </a>
        </div>
    </form>
</x-guest-layout>
