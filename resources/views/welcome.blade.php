<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'BLOG') }} — Personal Blog</title>

        {{-- SEO / Meta --}}
        @php
            $metaDescription = 'A personal blog about technology, code and life.';
            $metaImage = url('/favicon.svg');
            $metaUrl = url()->current();
            $isIndexable = app()->environment('production') ? 'index,follow' : 'noindex,nofollow';
        @endphp

        <meta name="description" content="{{ $metaDescription }}">
        <meta name="robots" content="{{ $isIndexable }}">
        <link rel="canonical" href="{{ $metaUrl }}">

        <!-- Open Graph -->
        <meta property="og:site_name" content="{{ config('app.name', 'BLOG') }}">
        <meta property="og:title" content="{{ config('app.name', 'BLOG') }} — Personal Blog">
        <meta property="og:description" content="{{ $metaDescription }}">
        <meta property="og:url" content="{{ $metaUrl }}">
        <meta property="og:image" content="{{ $metaImage }}">
        <meta property="og:type" content="website">

        <!-- Twitter -->
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="{{ config('app.name', 'BLOG') }} — Personal Blog">
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
        @php
            use Illuminate\Support\Facades\Storage;
        @endphp
    </head>
    <body class="font-sans antialiased bg-slate-50 text-slate-900">
        <!-- Navigation -->
        <header class="sticky top-0 z-50 bg-white/90 backdrop-blur-md border-b border-slate-200 shadow-sm">
            <nav class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-3 sm:py-4 flex items-center justify-between min-h-16 flex-wrap gap-2 sm:gap-4">
                <a href="/" class="flex items-center space-x-2 group">
                     <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center text-white font-black text-xl group-hover:scale-105 transition-transform">
                            B
                        </div>
                    <span class="text-base sm:text-xl font-black bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">BLOG </span>
                </a>
                
                <div class="flex items-center gap-2 sm:gap-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-slate-600 hover:text-blue-600 font-bold transition text-xs sm:text-sm uppercase">Dashboard</a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="px-3 sm:px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-bold text-xs sm:text-sm">Logout</button>
                        </form>
                    @else
                        {{-- <a href="{{ route('login') }}" class="text-slate-600 hover:text-blue-600 font-bold transition text-sm">Login</a> --}}
                        @if (Route::has('login'))
                            <a href="{{ route('login') }}" class="px-3 sm:px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-bold text-xs sm:text-sm hover:shadow-lg whitespace-nowrap">+ Create Blog</a>
                        @endif
                    @endauth
                </div>
            </nav>
        </header>

        <!-- Hero Section -->
        <main class="flex-1">
           
            <!-- Recent Posts Section -->
            <section id="recent-posts" class="bg-white border-t border-slate-200 py-12 sm:py-20">
                <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="mb-8 sm:mb-12 flex flex-col sm:flex-row sm:items-start sm:justify-between gap-6 sm:gap-8">
                        <div class="flex-1">
                            <h2 class="text-2xl sm:text-4xl md:text-5xl font-bold mb-2 sm:mb-4 text-slate-900">
                                All Blogs
                            </h2>
                            <p class="text-base sm:text-lg text-slate-600">Explore blogs from our community</p>
                        </div>
                        <div class="w-full sm:w-auto flex flex-col sm:flex-row gap-3 sm:gap-4 items-stretch sm:items-center">
                            <form method="GET" action="{{ route('home') }}" class="flex-1 sm:flex-none">
                                <div class="flex gap-2">
                                    <input name="q" value="{{ request('q') }}" type="text" placeholder="Search blogs..." class="flex-1 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 px-3 sm:px-5 py-2 sm:py-3 text-sm sm:text-base">
                                    <button type="submit" class="bg-indigo-600 text-white px-4 sm:px-8 py-2 sm:py-3 rounded-lg font-semibold hover:bg-indigo-700 transition text-sm sm:text-base whitespace-nowrap">Search</button>
                                </div>
                            </form>
                            @if(auth()->check())
                                <a href="{{ route('posts.create') }}" class="px-4 sm:px-6 py-2 sm:py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition duration-200 text-sm sm:text-base text-center">
                                    ✍️ Write Blog
                                </a>
                            @endif
                        </div>
                    </div>

                    @if($allPosts->count() > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
                            @foreach($allPosts as $post)
                                <article class="group bg-white rounded-2xl overflow-hidden border border-slate-200 hover:border-blue-400 hover:shadow-xl transition-all cursor-pointer flex flex-col">
                                    <!-- Image Section -->
                                    <div class="relative h-32 sm:h-40 overflow-hidden bg-slate-100">
                                        @if($post->image)
                                            <img src="{{ Storage::url($post->image) }}" alt="{{ $post->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                        @else
                                            <div class="w-full h-full bg-gradient-to-br from-blue-100 to-indigo-100 flex items-center justify-center">
                                                <span class="text-3xl sm:text-5xl">📝</span>
                                            </div>
                                        @endif
                                        <div class="absolute top-2 sm:top-4 left-2 sm:left-4 flex flex-wrap gap-2">
                                            <span class="bg-white/90 backdrop-blur-sm text-blue-600 text-xs font-bold px-2 py-1 rounded-md shadow-sm">
                                                BLOG
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Content Section -->
                                    <div class="p-4 sm:p-6 flex flex-col flex-grow">
                                        <!-- Date & Author -->
                                        <div class="text-xs sm:text-sm text-slate-400 font-medium mb-2 flex items-center flex-wrap gap-1">
                                            <span>{{ optional($post->published_at)->format('M d, Y') }}</span>
                                            <span class="hidden sm:inline">•</span>
                                            <span>{{ Str::limit($post->user->name ?? 'Unknown', 15) }}</span>
                                        </div>

                                        <!-- Title -->
                                        <h3 class="text-base sm:text-xl font-bold text-slate-900 mb-2 sm:mb-3 group-hover:text-blue-600 transition-colors line-clamp-2">
                                            @php $postUrl = auth()->check() ? route('posts.show', $post->slug) : route('posts.show', ['post' => $post->slug, 'from' => 'home']); @endphp
                                            <a href="{{ $postUrl }}">
                                                {{ $post->title }}
                                            </a>
                                        </h3>

                                        <!-- Summary -->
                                        <p class="text-slate-600 text-xs sm:text-sm line-clamp-3 mb-4 sm:mb-6 flex-grow">
                                            {{ Str::limit(strip_tags($post->content), 120) }}
                                        </p>

                                        <!-- Read More Link -->
                                        @php $postUrl = auth()->check() ? route('posts.show', $post->slug) : route('posts.show', ['post' => $post->slug, 'from' => 'home']); @endphp
                                        <a href="{{ $postUrl }}" class="flex items-center text-blue-600 font-semibold text-xs sm:text-sm hover:text-blue-700 transition">
                                            Read More
                                            <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                            </svg>
                                        </a>
                                    </div>
                                </article>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="mt-8 sm:mt-12 overflow-x-auto">
                            {{ $allPosts->links() }}
                        </div>
                    @else
                        <div class="text-center py-12 sm:py-16 bg-slate-50 rounded-2xl border-2 border-dashed border-slate-200 px-4">
                            <div class="text-5xl sm:text-6xl mb-4">📚</div>
                            <h3 class="text-xl sm:text-2xl font-bold text-slate-900 mb-2">No blogs yet</h3>
                            <p class="text-sm sm:text-base text-slate-600 mb-6">Be the first to share your thoughts and ideas</p>
                            @auth
                                <a href="{{ route('posts.create') }}" class="inline-block px-6 sm:px-8 py-3 sm:py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition duration-200 text-sm sm:text-base">
                                    Create First Blog
                                </a>
                            @else
                                <a href="{{ route('register') }}" class="inline-block px-6 sm:px-8 py-3 sm:py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition duration-200 text-sm sm:text-base">
                                    Sign Up to Create
                                </a>
                            @endauth
                        </div>
                    @endif
                </div>
            </section>

           
           
        </main>

        <!-- Footer -->
        <footer class="bg-slate-900 text-slate-400 py-6 sm:py-8">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <p class="text-xs sm:text-sm">&copy; 2026 Blog . All rights reserved. </p>
            </div>
        </footer>
    </body>
</html>
