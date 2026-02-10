<section>
    <p class="text-slate-600 mb-6">
        {{ __('Ensure your account is using a long, random password to stay secure.') }}
    </p>

    <form method="post" action="{{ route('password.update') }}" class="space-y-6">
        @csrf
        @method('put')

        <div>
            <label class="block text-sm font-bold text-slate-900 mb-3">🔑 Current Password</label>
            <input id="update_password_current_password" name="current_password" type="password" class="w-full px-5 py-3 bg-white border border-slate-200 rounded-lg focus:border-blue-400 focus:ring-2 focus:ring-blue-400/50 text-slate-900 placeholder-slate-400 transition" autocomplete="current-password" />
            @error('current_password', 'updatePassword')<p class="text-red-600 text-sm mt-2">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-sm font-bold text-slate-900 mb-3">🆕 New Password</label>
            <input id="update_password_password" name="password" type="password" class="w-full px-5 py-3 bg-white border border-slate-200 rounded-lg focus:border-blue-400 focus:ring-2 focus:ring-blue-400/50 text-slate-900 placeholder-slate-400 transition" autocomplete="new-password" />
            @error('password', 'updatePassword')<p class="text-red-600 text-sm mt-2">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-sm font-bold text-slate-900 mb-3">✓ Confirm Password</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="w-full px-5 py-3 bg-white border border-slate-200 rounded-lg focus:border-blue-400 focus:ring-2 focus:ring-blue-400/50 text-slate-900 placeholder-slate-400 transition" autocomplete="new-password" />
            @error('password_confirmation', 'updatePassword')<p class="text-red-600 text-sm mt-2">{{ $message }}</p>@enderror
        </div>

        <div class="flex items-center justify-between pt-4 border-t border-slate-200">
            <button type="submit" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold rounded-lg hover:shadow-lg hover:shadow-blue-500/30 transform hover:scale-105 transition duration-200">
                🔐 Update Password
            </button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-green-600 font-semibold"
                >✓ {{ __('Updated successfully!') }}</p>
            @endif
        </div>
    </form>
</section>

