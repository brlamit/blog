@php $isFromHomeGuest = !auth()->check() && request()->query('from') === 'home'; @endphp

@if($isFromHomeGuest)
    <!-- Render welcome-style header for guests coming from home -->
    <header class="sticky top-0 z-50 bg-white/90 backdrop-blur-md border-b border-slate-200 shadow-sm">
        <nav class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between h-16">
            <a href="/" class="flex items-center space-x-2 group">
                <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center text-white font-black text-xl group-hover:scale-105 transition-transform">
                            B
                        </div>
                <span class="text-xl font-black bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">BLOG </span>
            </a>

            <div class="flex items-center gap-4">
                @auth
                    <a href="{{ url('/dashboard') }}" class="text-slate-600 hover:text-blue-600 font-bold transition text-sm uppercase">Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline" data-loading-message="Logging out...">@csrf<button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-bold text-sm">Logout</button></form>
                @else
                    @if (Route::has('login'))
                        <a href="{{ route('login') }}" class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-bold text-sm hover:shadow-lg">+ Create Blog</a>
                    @endif
                @endauth
            </div>
        </nav>
    </header>
@else
    <nav x-data="{ open: false }" class="sticky top-0 z-50 bg-white/90 backdrop-blur-md border-b border-slate-200 shadow-sm">
    <!-- Primary Navigation Menu -->
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-2 group">
                        <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center text-white font-black text-xl group-hover:scale-105 transition-transform">
                            B
                        </div>
                        <span class="text-xl font-black bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">BLOG </span>
                    </a>
                </div>
            </div>

            <!-- Navigation Links -->
            <div class="hidden space-x-6 sm:-my-px sm:flex sm:items-center">
                <a href="{{ route('dashboard') }}" class="text-slate-600 hover:text-blue-600 font-bold transition-colors text-sm uppercase tracking-wider hidden sm:block">Dashboard</a>
                <a href="{{ route('posts.index') }}" class="text-slate-600 hover:text-blue-600 font-bold transition-colors text-sm uppercase tracking-wider hidden sm:block">All Blogs</a>
            </div>

            <!-- Settings Dropdown + Write Button -->
            <div class="hidden sm:flex sm:items-center sm:space-x-4">
                <a href="{{ route('posts.create') }}" class="bg-blue-600 text-white px-5 py-2 rounded-xl font-bold hover:bg-blue-700 transition-all shadow-md hover:shadow-lg flex items-center space-x-2 text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                    </svg>
                    <span class="hidden sm:inline">Write Post</span>
                </a>

                @auth
                    <div class="ml-3 relative group">
                        <button class="w-10 h-10 rounded-full border-2 border-slate-100 hover:border-blue-400 transition-all overflow-hidden bg-slate-200 font-bold text-slate-600 flex items-center justify-center">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </button>
                        
                        <div class="absolute right-0 top-full mt-2 w-48 bg-white rounded-2xl shadow-xl border border-slate-100 py-2 invisible group-hover:visible opacity-0 group-hover:opacity-100 transition-all duration-200 translate-y-2 group-hover:translate-y-0">
                            <div class="px-4 py-2 border-b border-slate-50 mb-1">
                                <div class="text-xs font-bold text-slate-400 uppercase">Signed in as</div>
                                <div class="text-sm font-bold text-slate-900 truncate">{{ Auth::user()->name }}</div>
                            </div>
                            <a href="{{ route('profile.edit') }}" class="block w-full px-4 py-2 text-left text-sm text-slate-700 hover:bg-slate-50 transition-colors">
                                👤 Profile Details
                            </a>
                            <form method="POST" action="{{ route('logout') }}" data-loading-message="Logging out...">
                                @csrf
                                <button type="submit" class="w-full px-4 py-2 text-left text-sm text-red-600 hover:bg-red-50 font-bold transition-colors">
                                    🚪 Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="space-x-3 flex items-center">
                        <a href="{{ route('login') }}" class="text-slate-600 hover:text-blue-600 font-bold transition-colors text-sm">Log in</a>
                        <a href="{{ route('register') }}" class="px-4 py-2 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 transition-all text-sm">Sign up</a>
                    </div>
                @endauth
            </div>

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-slate-500 hover:text-slate-900 hover:bg-slate-100 focus:outline-none focus:bg-slate-100 focus:text-slate-900 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{hidden: !open}" class="sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded-md text-base font-medium text-slate-700 hover:text-blue-600 hover:bg-slate-50">
                📊 Dashboard
            </a>
            <a href="{{ route('posts.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-slate-700 hover:text-blue-600 hover:bg-slate-50">
                📖 All Blogs
            </a>
            @auth
                <a href="{{ route('posts.create') }}" class="block px-3 py-2 rounded-md text-base font-medium text-slate-700 hover:text-blue-600 hover:bg-slate-50">
                    ✏️ Write Post
                </a>
            @endauth
        </div>

        <!-- Responsive Settings Options -->
        @auth
            <div class="pt-4 pb-1 border-t border-slate-200">
                <div class="flex items-center px-4 py-2">
                    <div class="w-10 h-10 rounded-full bg-slate-200 flex items-center justify-center text-slate-600 font-bold">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div class="ml-3">
                        <div class="text-base font-semibold text-slate-900">{{ Auth::user()->name }}</div>
                        <div class="text-sm font-medium text-slate-500">{{ Auth::user()->email }}</div>
                    </div>
                </div>

                <div class="mt-3 space-y-1">
                    <a href="{{ route('profile.edit') }}" class="block px-3 py-2 rounded-md text-base font-medium text-slate-700 hover:text-blue-600 hover:bg-slate-50">
                        👤 Profile
                    </a>
                    <form method="POST" action="{{ route('logout') }}" data-loading-message="Logging out...">
                        @csrf
                        <button type="submit" class="w-full text-left px-3 py-2 rounded-md text-base font-medium text-red-600 hover:bg-red-50">
                            🚪 Logout
                        </button>
                    </form>
                </div>
            </div>
        @else
            <div class="pt-4 pb-1 border-t border-slate-200 space-y-1">
                <a href="{{ route('login') }}" class="block px-3 py-2 rounded-md text-base font-medium text-slate-700 hover:text-blue-600 hover:bg-slate-50">
                    Log in
                </a>
                <a href="{{ route('register') }}" class="block px-3 py-2 rounded-md text-base font-medium text-slate-700 hover:text-blue-600 hover:bg-slate-50">
                    Sign up
                </a>
            </div>
        @endauth
    </div>
</nav>
@endif
