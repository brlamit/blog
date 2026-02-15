<x-app-layout>
  

    <div class="py-12">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Your Profile</h1>
                <p class="text-gray-600 mt-2">Manage your profile information, security settings, and more.</p>
            </div>
            <!-- Profile Stats Section -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="relative group">
                    <div class="absolute -inset-0.5 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl blur opacity-0 group-hover:opacity-10 transition duration-300"></div>
                    <div class="relative bg-white rounded-2xl p-8 border border-slate-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-slate-600 text-sm font-medium">Total Posts</p>
                                <p class="text-3xl font-bold text-slate-900 mt-2">{{ auth()->user()->posts()->count() }}</p>
                            </div>
                            <div class="w-12 h-12 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v2.605A7.969 7.969 0 015.5 6c1.255 0 2.443.29 3.5.804V4.804z"/><path d="M9 15.675V9.97c-1.224.584-2.549.876-4 .876a7.97 7.97 0 01-3.5-.804v2.605A7.968 7.968 0 015 13c1.255 0 2.443.29 3.5.804v1.87a7.968 7.968 0 01-3.5-.804V15a7.97 7.97 0 013.5.804z"/></svg>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="relative group">
                    <div class="absolute -inset-0.5 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg blur opacity-0 group-hover:opacity-10 transition duration-300"></div>
                    <div class="relative bg-white rounded-2xl p-8 border border-slate-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-blue-600 text-sm font-medium">Published</p>
                                <p class="text-3xl font-bold text-slate-900 mt-2">{{ auth()->user()->posts()->whereNotNull('published_at')->count() }}</p>
                            </div>
                            <div class="w-12 h-12 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="relative group">
                    <div class="absolute -inset-0.5 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg blur opacity-0 group-hover:opacity-10 transition duration-300"></div>
                    <div class="relative bg-white rounded-2xl p-8 border border-slate-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-slate-600 text-sm font-medium">Drafts</p>
                                <p class="text-3xl font-bold text-slate-900 mt-2">{{ auth()->user()->posts()->whereNull('published_at')->count() }}</p>
                            </div>
                            <div class="w-12 h-12 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/></svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-8">
                <!-- Update Profile Information -->
                <div class="group relative">
                    <div class="absolute -inset-1 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl blur-xl opacity-0 group-hover:opacity-10 transition duration-300"></div>
                    <div class="relative bg-white rounded-2xl p-8 border border-slate-200">
                        <h3 class="text-2xl font-bold text-blue-600 mb-6 flex items-center gap-2">
                            <span class="text-2xl">👤</span> Profile Information
                        </h3>
                        <div class="max-w-xl">
                            @include('profile.partials.update-profile-information-form')
                        </div>
                    </div>
                </div>

                <!-- Update Password -->
                <div class="group relative">
                    <div class="absolute -inset-1 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl blur-xl opacity-0 group-hover:opacity-10 transition duration-300"></div>
                    <div class="relative bg-white rounded-2xl p-8 border border-slate-200">
                        <h3 class="text-2xl font-bold text-blue-600 mb-6 flex items-center gap-2">
                            <span class="text-2xl">🔐</span> Security Settings
                        </h3>
                        <div class="max-w-xl">
                            @include('profile.partials.update-password-form')
                        </div>
                    </div>
                </div>

                <!-- Delete Account -->
                <div class="group relative">
                    <div class="absolute -inset-1 bg-gradient-to-r from-red-600 to-pink-600 rounded-2xl blur-xl opacity-0 group-hover:opacity-10 transition duration-300"></div>
                    <div class="relative bg-white rounded-2xl p-8 border border-red-200">
                        <h3 class="text-2xl font-bold text-red-600 mb-6 flex items-center gap-2">
                            <span class="text-2xl">⚠️</span> Danger Zone
                        </h3>
                        <div class="max-w-xl">
                            @include('profile.partials.delete-user-form')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

