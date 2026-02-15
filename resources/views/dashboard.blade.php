<x-app-layout>
   
    @php
        $myPosts = $myPosts ?? collect();
        if (!isset($allPosts)) {
            $allPosts = isset($posts) ? $posts : collect();
        }
    @endphp

    <div class="py-12">
      
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
                <div class="bg-white rounded-xl p-2 border border-slate-200 shadow-sm text-center">
                    <div class="w-7 h-7  rounded-full flex items-center justify-center text-lg mx-auto mb-1">📝</div>
                    <p class="text-slate-500 text-[10px] font-medium">Total</p>
                    <p class="text-lg font-bold text-blue-600 mt-0.5">{{ auth()->user()->posts()->count() }}</p>
                </div>
                <div class="bg-white rounded-xl p-2 border border-slate-200 shadow-sm text-center">
                    <div class="w-7 h-7 bg-green-100 rounded-full flex items-center justify-center text-lg mx-auto mb-1">📖</div>
                    <p class="text-slate-500 text-[10px] font-medium">Published</p>
                    <p class="text-lg font-bold text-green-600 mt-0.5">{{ auth()->user()->posts()->whereNotNull('published_at')->count() }}</p>
                </div>
                <div class="bg-white rounded-xl p-2 border border-slate-200 shadow-sm text-center">
                    <div class="w-7 h-7 bg-orange-100 rounded-full flex items-center justify-center text-lg mx-auto mb-1">✏️</div>
                    <p class="text-slate-500 text-[10px] font-medium">Drafts</p>
                    <p class="text-lg font-bold text-orange-600 mt-0.5">{{ auth()->user()->posts()->whereNull('published_at')->count() }}</p>
                </div>
            </div>

            <div class="mb-12 text-center">
                <a href="{{ route('posts.create') }}" class="inline-block px-8 py-4 text-lg font-bold text-white bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl shadow-lg hover:shadow-xl hover:scale-105 transform transition duration-200">✨ Create New Blog Post</a>
            </div>

            <div class="bg-white rounded-2xl p-8 border border-slate-200 mb-8">
                <div class="mb-6 flex items-center justify-between">
                    <div>
                        <h2 class="text-3xl font-bold text-slate-900">All Blogs</h2>
                        <p class="text-sm text-slate-500">Explore blogs from the community</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <form method="GET" action="{{ route('home') }}" class="w-full sm:w-64">
                            <div class="flex">
                                <input name="q" value="{{ request('q') }}" type="text" placeholder="Search blogs..." class="w-full rounded-l-md border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 px-3 py-2">
                                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-r-md">Search</button>
                            </div>
                        </form>
                        <a href="{{ route('posts.index') }}" class="text-sm text-slate-500 hover:text-blue-600">View all</a>
                    </div>
                </div>

                @if(isset($allPosts) && $allPosts->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                        @foreach($allPosts->take(3) as $post)
                            <article class="group bg-white rounded-xl overflow-hidden border border-slate-200 hover:border-blue-400 hover:shadow-lg transition flex flex-col">
                                <div class="relative h-40 overflow-hidden bg-slate-100">
                                    @if($post->image)
                                        <img src="{{ Storage::url($post->image) }}" alt="{{ $post->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" style="max-width: 100%; max-height: 16rem;">
                                    @else
                                        <div class="w-full h-full bg-gradient-to-br from-blue-100 to-indigo-100 flex items-center justify-center"><span class="text-4xl">📝</span></div>
                                    @endif
                                </div>
                                <div class="p-4 flex flex-col flex-grow">
                                    <h3 class="text-base font-bold text-slate-900 mb-2 line-clamp-2"><a href="{{ route('posts.show', $post->slug) }}">{{ $post->title }}</a></h3>
                                    <p class="text-slate-600 text-sm line-clamp-3 mb-4 flex-grow">{{ $post->summary ?? Str::limit(strip_tags($post->content), 120) }}</p>
                                    <div class="mt-auto text-sm"><a href="{{ route('posts.show', $post->slug) }}" class="text-blue-600 font-semibold">Read More</a></div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12"><div class="text-6xl mb-4">📚</div><p class="text-slate-600">No public posts yet.</p></div>
                @endif
            </div>

            <div class="bg-white rounded-2xl p-8 border border-slate-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-3xl font-bold text-slate-900 mb-6">My Blogs</h2>
                    <a href="{{ route('posts.index', ['author' => 'me']) }}" class="text-sm text-slate-500 hover:text-blue-600">View all</a>
                </div>

                @php
                    $myPostsAvailable = isset($myPosts) && $myPosts instanceof \Illuminate\Support\Collection ? $myPosts : (isset($myPosts) ? collect($myPosts) : collect());
                @endphp

                @if($myPostsAvailable->isEmpty())
                    <div class="text-center py-16">
                        <div class="text-6xl mb-4">🫥</div>
                        <h3 class="text-2xl font-bold text-slate-900 mb-2">You haven't written anything yet</h3>
                        <p class="text-slate-600 mb-6">Start writing to see your posts listed here.</p>
                        <a href="{{ route('posts.create') }}" class="inline-block px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition duration-200">Create Your First Post</a>
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                        @foreach($myPostsAvailable->take(3) as $post)
                            <article class="group bg-white rounded-xl overflow-hidden border border-slate-200 hover:border-blue-400 hover:shadow-lg transition flex flex-col">
                                <div class="relative h-40 overflow-hidden bg-slate-100">
                                    @if($post->image)
                                        <img src="{{ Storage::url($post->image) }}" alt="{{ $post->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" style="max-width: 100%; max-height: 16rem;">
                                    @else
                                        <div class="w-full h-full bg-gradient-to-br from-blue-100 to-indigo-100 flex items-center justify-center"><span class="text-4xl">📝</span></div>
                                    @endif
                                </div>
                                <div class="p-4 flex flex-col flex-grow">
                                    <div class="flex items-center gap-3 mb-3 flex-wrap">
                                        @if($post->published_at)
                                            <span class="px-3 py-1 text-xs font-bold bg-green-50 text-green-600 rounded-full border border-green-200">✓ Published</span>
                                        @else
                                            <span class="px-3 py-1 text-xs font-bold bg-orange-50 text-orange-600 rounded-full border border-orange-200">✏️ Draft</span>
                                        @endif
                                        <span class="text-xs text-slate-600">{{ optional($post->published_at)->format('M d, Y') ?? 'Not published' }}</span>
                                        @foreach($post->tags->take(2) as $tag)
                                            <span class="bg-indigo-50 text-indigo-600 text-[10px] font-bold px-2 py-0.5 rounded-md border border-indigo-100">{{ strtoupper($tag->name) }}</span>
                                        @endforeach
                                    </div>
                                    <h3 class="font-bold text-slate-900 text-lg group-hover:text-blue-600 transition"><a href="{{ route('posts.show', $post->slug) }}">{{ $post->title }}</a></h3>
                                    <p class="text-slate-600 text-sm mt-2 line-clamp-3 mb-4 flex-grow">{{ Str::limit(strip_tags($post->content), 120) }}</p>
                                    <div class="mt-auto flex items-center justify-between">
                                        <a href="{{ route('posts.show', $post->slug) }}" class="text-blue-600 font-semibold">Read More</a>
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('posts.edit', $post) }}" class="p-2 bg-blue-50 hover:bg-blue-100 border border-blue-200 rounded-lg transition text-blue-600 hover:text-blue-700 font-semibold" title="Edit">✏️</a>
                                            <form method="POST" action="{{ route('posts.destroy', $post) }}" style="display:none;" id="delete-form-{{ $post->id }}">@csrf @method('DELETE')</form>
                                                <button data-delete-form="#delete-form-{{ $post->id }}" data-delete-message="Permanently delete this blog post?" class="p-2 bg-red-50 hover:bg-red-100 border border-red-200 rounded-lg transition text-red-600 hover:text-red-700 font-semibold" title="Delete">🗑️</button>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>