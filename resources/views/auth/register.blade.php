<x-guest-layout>
    <form method="POST" action="{{ route('register') }}" class="space-y-6">
        @csrf

        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-blue-600 mb-2">Create Account</h2>
            <p class="text-slate-600 text-sm">Join our community of bloggers</p>
        </div>

        <!-- Name -->
        <div>
            <label for="name" class="block text-sm font-bold text-slate-900 mb-2">Full Name</label>
            <input id="name" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-lg focus:border-blue-400 focus:ring-2 focus:ring-blue-400/50 text-slate-900 placeholder-slate-400 transition" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="John Doe" />
            <x-input-error :messages="$errors->get('name')" class="mt-2 text-red-600" />
        </div>

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-bold text-slate-900 mb-2">Email Address</label>
            <input id="email" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-lg focus:border-blue-400 focus:ring-2 focus:ring-blue-400/50 text-slate-900 placeholder-slate-400 transition" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="you@example.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-600" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-bold text-slate-900 mb-2">Password</label>
            <input id="password" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-lg focus:border-blue-400 focus:ring-2 focus:ring-blue-400/50 text-slate-900 placeholder-slate-400 transition" type="password" name="password" required autocomplete="new-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-600" />
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-sm font-bold text-slate-900 mb-2">Confirm Password</label>
            <input id="password_confirmation" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-lg focus:border-blue-400 focus:ring-2 focus:ring-blue-400/50 text-slate-900 placeholder-slate-400 transition" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-red-600" />
        </div>

        <div class="flex flex-col sm:flex-row items-center justify-between mt-8 gap-4">
            <a class="text-sm text-blue-600 hover:text-blue-700 underline transition" href="{{ route('login') }}">
                {{ __('Already have an account?') }}
            </a>
            <button type="submit" class="w-full sm:w-auto px-8 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold rounded-lg shadow-lg hover:shadow-blue-500/30 hover:shadow-2xl transform hover:scale-105 transition duration-200">
                {{ __('Create Account') }}
            </button>
        </div>
    </form>
</x-guest-layout>
