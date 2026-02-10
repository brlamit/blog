<section class="space-y-6">
    <p class="text-red-600 mb-6">
        {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
    </p>

    <button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="px-6 py-3 bg-gradient-to-r from-red-600 to-pink-600 text-white font-bold rounded-lg hover:shadow-lg hover:shadow-red-500/30 transform hover:scale-105 transition duration-200"
    >
        🗑️ {{ __('Delete Account') }}
    </button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <div class="p-8 bg-white rounded-2xl">
            <form method="post" action="{{ route('profile.destroy') }}" class="space-y-6">
                @csrf
                @method('delete')

                <div>
                    <h2 class="text-2xl font-bold text-red-600 mb-3">
                        ⚠️ {{ __('Permanently Delete Account?') }}
                    </h2>

                    <p class="text-slate-600">
                        {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm.') }}
                    </p>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-900 mb-3">🔐 Password</label>
                    <input
                        id="password"
                        name="password"
                        type="password"
                        class="w-full px-5 py-3 bg-white border border-red-200 rounded-lg focus:border-red-400 focus:ring-2 focus:ring-red-400/50 text-slate-900 placeholder-slate-400 transition"
                        placeholder="{{ __('Enter your password') }}"
                    />
                    @error('password', 'userDeletion')<p class="text-red-600 text-sm mt-2">{{ $message }}</p>@enderror
                </div>

                <div class="flex justify-end gap-4 pt-4 border-t border-slate-200">
                    <button type="button" x-on:click="$dispatch('close')" class="px-6 py-2 text-slate-600 hover:text-slate-900 font-semibold transition">
                        {{ __('Cancel') }}
                    </button>

                    <button type="submit" class="px-6 py-3 bg-gradient-to-r from-red-600 to-pink-600 text-white font-bold rounded-lg hover:shadow-lg hover:shadow-red-500/30 transform hover:scale-105 transition duration-200">
                        🗑️ {{ __('Delete Account') }}
                    </button>
                </div>
            </form>
        </div>
    </x-modal>
</section>

