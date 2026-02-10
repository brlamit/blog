<section>
    <p class="text-slate-600 mb-6">
        {{ __("Update your account's profile information and email address.") }}
    </p>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
        @csrf
        @method('patch')

        <div>
            <label class="block text-sm font-bold text-slate-900 mb-3">👤 Name</label>
            <input id="name" name="name" type="text" class="w-full px-5 py-3 bg-white border border-slate-200 rounded-lg focus:border-blue-400 focus:ring-2 focus:ring-blue-400/50 text-slate-900 placeholder-slate-400 transition" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            @error('name')<p class="text-red-600 text-sm mt-2">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-sm font-bold text-slate-900 mb-3">✉️ Email</label>
            <input id="email" name="email" type="email" class="w-full px-5 py-3 bg-white border border-slate-200 rounded-lg focus:border-blue-400 focus:ring-2 focus:ring-blue-400/50 text-slate-900 placeholder-slate-400 transition" :value="old('email', $user->email)" required autocomplete="username" />
            @error('email')<p class="text-red-600 text-sm mt-2">{{ $message }}</p>@enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-4 p-4 bg-amber-50 border border-amber-200 rounded-lg">
                    <p class="text-sm text-amber-900">
                        {{ __('Your email address is unverified.') }}
                        <button form="send-verification" class="font-semibold text-amber-600 hover:text-amber-700 transition underline">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center justify-between pt-4 border-t border-slate-200">
            <button type="submit" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold rounded-lg hover:shadow-lg hover:shadow-blue-500/30 transform hover:scale-105 transition duration-200">
                💾 Save Changes
            </button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-green-600 font-semibold"
                >✓ {{ __('Saved successfully!') }}</p>
            @endif
        </div>
    </form>
</section>

