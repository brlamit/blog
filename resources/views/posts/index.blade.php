<x-app-layout>


    <div class="min-h-screen bg-slate-50 py-12">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6 flex justify-center">
                <form method="GET" action="{{ route('posts.index') }}" class="w-full sm:w-2/3 lg:w-1/2">
                    <div class="flex">
                        <input name="q" value="{{ request('q') }}" type="text" placeholder="Search blogs..." class="w-full rounded-l-md border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 px-3 py-2">
                        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-r-md">Search</button>
                    </div>
                </form>
            </div>
            <!-- Header -->
            <div class="mb-12">
                <h1 class="text-5xl md:text-6xl font-bold mb-4 text-slate-900">
                    Latest Updates
                </h1>
                <div class="flex items-center gap-2">
                    <div class="w-12 h-1 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-full"></div>
                    <p class="text-lg text-slate-600">All Blogs</p>
                    <span class="inline-block px-3 py-1 bg-blue-100 text-blue-600 text-sm font-semibold rounded-full">
                        {{ $posts->count() }}
                    </span>
                </div>
            </div>

            @if($posts->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8 justify-center mx-auto">
                    @foreach($posts as $post)
                        <article class="group bg-white rounded-xl overflow-hidden border border-slate-200 hover:border-blue-400 hover:shadow-lg transition flex flex-col max-w-md mx-auto w-full">
                            <div class="relative h-40 overflow-hidden bg-slate-100">
                                @if($post->image)
                                    <img src="{{ Storage::url($post->image) }}" alt="{{ $post->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" style="max-width:100%; max-height:16rem;">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-blue-100 to-indigo-100 flex items-center justify-center"><span class="text-4xl">📝</span></div>
                                @endif
                                <div class="absolute top-4 left-4 flex flex-wrap gap-2">
                                    @foreach($post->tags->take(2) as $tag)
                                        <span class="bg-white/90 backdrop-blur-sm text-blue-600 text-xs font-bold px-2 py-1 rounded-md shadow-sm">{{ strtoupper($tag->name) }}</span>
                                    @endforeach
                                </div>
                            </div>

                            <div class="p-4 flex flex-col flex-grow">
                                <div class="text-sm text-slate-400 font-medium mb-2 flex items-center">
                                    <span>{{ optional($post->published_at)->format('M d, Y') ?? 'Unpublished' }}</span>
                                    <span class="mx-2">•</span>
                                    <span>{{ $post->user->name ?? 'Unknown' }}</span>
                                </div>

                                <h3 class="text-base font-bold text-slate-900 mb-2 line-clamp-2"><a href="{{ route('posts.show', $post->slug) }}">{{ $post->title }}</a></h3>

                                <p class="text-slate-600 text-sm line-clamp-3 mb-4 flex-grow">{{ $post->summary ?? Str::limit(strip_tags($post->content), 120) }}</p>

                                <div class="mt-auto text-sm"><a href="{{ route('posts.show', $post->slug) }}" class="text-blue-600 font-semibold">Read More</a></div>
                            </div>
                        </article>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-12">
                    {{ $posts->links() }}
                </div>
            @else
                <div class="text-center py-16">
                    <div class="text-7xl mb-4">📚</div>
                    <h3 class="text-2xl font-bold text-slate-900 mb-2">No blogs yet</h3>
                    <p class="text-slate-600 mb-6">Be the first to share your thoughts and ideas</p>
                    <a href="{{ route('posts.create') }}" class="inline-block px-8 py-4 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold rounded-xl hover:shadow-lg hover:shadow-blue-500/30 transform hover:scale-105 transition duration-200">
                        ✨ Create First Post
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
