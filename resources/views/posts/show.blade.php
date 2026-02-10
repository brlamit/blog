<x-app-layout>
    {{-- removed use statement (cannot use namespace imports inside compiled view method) --}}

    <div class="min-h-screen bg-slate-50 py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Navigation Header -->
            <div class="flex justify-between items-center mb-12">
                <a href="{{ route('home') }}" class="flex items-center text-slate-500 hover:text-blue-600 font-bold transition-all group px-4 py-2 hover:bg-blue-50 rounded-xl">
                    <svg class="w-5 h-5 mr-2 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Feed
                </a>

                @auth
                    @if(auth()->id() === $post->user_id)
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('posts.edit', $post) }}" class="px-5 py-2 bg-slate-100 text-slate-700 rounded-xl font-bold hover:bg-slate-200 transition-all flex items-center space-x-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                <span>Edit</span>
                            </a>
                            <form method="POST" action="{{ route('posts.destroy', $post) }}" id="delete-form-{{ $post->id }}" style="display:none">@csrf @method('DELETE')</form>
                            <button data-delete-form="#delete-form-{{ $post->id }}" data-delete-message="Permanently delete this blog post?" class="px-5 py-2 bg-red-50 text-red-600 rounded-xl font-bold hover:bg-red-100 transition-all flex items-center space-x-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                <span>Delete</span>
                            </button>
                        </div>
                    @endif
                @endauth
            </div>

            <!-- Main Article Container -->
            <article class="max-w-4xl mx-auto">
                <div class="bg-white rounded-[5rem] shadow-2xl overflow-hidden border border-slate-100">
                    <!-- Featured Image -->
                        @if($post->image)
                            <div class="w-full h-56 sm:h-72 overflow-hidden">
                                <img src="{{ \Illuminate\Support\Facades\Storage::url($post->image) }}" alt="{{ $post->title }}" class="w-full h-full object-cover" style="max-width: 100%; max-height: 30rem;">
                            </div>
                        @endif

                    <div class="p-8 md:p-16">
                        <!-- Header Section -->
                        <header class="mb-12 text-center">
                            <!-- Tags/Category -->
                            <div class="flex justify-center flex-wrap gap-2 mb-8">
                                @foreach($post->tags as $tag)
                                    <span class="text-indigo-600 bg-indigo-50 px-4 py-1.5 rounded-full text-xs font-black uppercase tracking-widest border border-indigo-100">
                                        {{ $tag->name }}
                                    </span>
                                @endforeach
                            </div>

                            <!-- Title -->
                            <h1 class="text-4xl md:text-6xl font-black text-slate-900 mb-8 leading-tight tracking-tight">
                                {{ $post->title }}
                            </h1>

                            <!-- Author & Date -->
                            <div class="flex items-center justify-center flex-wrap gap-4 text-slate-400 font-bold uppercase tracking-widest text-xs md:text-[10px]">
                                <div class="flex items-center bg-slate-50 px-4 py-2 rounded-full border border-slate-100">
                                    <div class="w-6 h-6 rounded-full bg-blue-600 flex items-center justify-center text-white mr-2 text-[8px] font-black">
                                        {{ substr($post->user->name, 0, 1) }}
                                    </div>
                                    <span class="text-slate-900">{{ $post->user->name ?? 'Unknown' }}</span>
                                </div>
                                <time class="bg-slate-50 px-4 py-2 rounded-full border border-slate-100">{{ optional($post->published_at)->format('F d, Y') }}</time>
                            </div>
                        </header>

                        @if($post->summary)
                            <div class="relative mb-16 p-8 bg-blue-50/50 rounded-3xl border border-blue-100 text-blue-900 text-xl font-medium leading-relaxed italic text-center">
                                <svg class="absolute -top-4 left-8 w-10 h-10 text-blue-200 fill-current opacity-50" viewBox="0 0 24 24">
                                    <path d="M14.017 21L14.017 18C14.017 16.8954 14.9124 16 16.017 16H19.017V14H15.017C13.9124 14 13.017 13.1046 13.017 12V6C13.017 4.89543 13.9124 4 15.017 4H20.017C21.1216 4 22.017 4.89543 22.017 6V12C22.017 13.1046 21.1216 14 20.017 14H21.017L21.017 16C21.017 18.7614 18.7784 21 16.017 21H14.017ZM3.01709 21L3.01709 18C3.01709 16.8954 3.91252 16 5.01709 16H8.01709V14H4.01709C2.91252 14 2.01709 13.1046 2.01709 12V6C2.01709 4.89543 2.91252 4 4.01709 4H9.01709C10.1217 4 11.0171 4.89543 11.0171 6V12C11.0171 13.1046 10.1217 14 9.01709 14H10.0171L10.0171 16C10.0171 18.7614 7.77851 21 5.01709 21H3.01709Z" />
                                </svg>
                                "{{ $post->summary }}"
                            </div>
                        @endif

                        <!-- Content Section -->
                        <div class="prose prose-slate max-w-none text-slate-700 leading-relaxed space-y-8 font-serif">
                            <div class="text-lg md:text-xl font-medium space-y-6">
                                {!! nl2br(e($post->content)) !!}
                            </div>
                        </div>

                        <!-- Author Bio Footer -->
                        <div class="mt-24 pt-10 border-t border-slate-100 flex flex-col md:flex-row justify-between items-center gap-6">
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 rounded-2xl bg-slate-900 text-white flex items-center justify-center font-black text-lg">
                                    {{ substr($post->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="text-sm font-black text-slate-900">About the Author</div>
                                    <div class="text-xs font-bold text-slate-400">{{ $post->user->name ?? 'Unknown' }} • Content Creator</div>
                                </div>
                            </div>

                          
                        </div>
                    </div>
                </div>
            </article>
        </div>
    </div>
</x-app-layout>
