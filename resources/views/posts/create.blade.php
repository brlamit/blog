<x-app-layout>


    <div class="py-12 flex items-center justify-center min-h-screen">
        <div class="w-full max-w-4xl px-4 sm:px-6 lg:px-8">
            <div class="relative group">
                <div class="absolute -inset-1 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl blur-xl opacity-0 group-hover:opacity-10 transition duration-300"></div>
                
                <div class="relative bg-white rounded-2xl p-8 sm:p-12 border border-slate-200">
                    <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data" class="space-y-8">
                        @csrf

                        <div class="text-center mb-8">
                            <h2 class="text-4xl font-bold text-blue-600 mb-2">Share Your Story</h2>
                            <p class="text-slate-600">Express yourself and inspire others with your thoughts</p>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-900 mb-3">📝 Post Title</label>
                            <input name="title" value="{{ old('title') }}" required placeholder="Give your post an engaging title..." class="w-full px-5 py-4 bg-white border border-slate-200 rounded-xl focus:border-blue-400 focus:ring-2 focus:ring-blue-400/50 text-slate-900 placeholder-slate-400 transition text-lg" />
                            @error('title')<p class="text-red-600 text-sm mt-2">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-900 mb-3">📑 Summary (Optional)</label>
                            <textarea name="summary" rows="3" placeholder="A brief summary of your post to caption the readers..." class="w-full px-5 py-4 bg-white border border-slate-200 rounded-xl focus:border-blue-400 focus:ring-2 focus:ring-blue-400/50 text-slate-900 placeholder-slate-400 transition text-sm">{{ old('summary') }}</textarea>
                            @error('summary')<p class="text-red-600 text-sm mt-2">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-900 mb-3">🖼️ Featured Image</label>
                            <div class="relative">
                                <input type="file" name="image" accept="image/*" class="hidden" id="image-upload">
                                <label for="image-upload" class="flex items-center justify-center w-full p-8 border-2 border-dashed border-slate-300 rounded-xl cursor-pointer hover:border-blue-400 hover:bg-blue-50 transition group">
                                    <div class="text-center">
                                        <div class="text-4xl mb-2">🖼️</div>
                                        <p class="text-slate-600 font-semibold">Click to upload image</p>
                                        <p class="text-xs text-slate-500 mt-1">PNG, JPG, GIF or WebP (Max 2MB)</p>
                                    </div>
                                </label>
                                <div id="image-preview" class="mt-4 hidden">
                                    <img id="preview-img" src="" alt="Preview" class="w-full h-64 object-cover rounded-xl">
                                    <button type="button" onclick="document.getElementById('image-upload').value=''; document.getElementById('image-preview').classList.add('hidden');" class="mt-2 text-sm text-red-600 hover:text-red-700 font-semibold">Remove image</button>
                                </div>
                            </div>
                            @error('image')<p class="text-red-600 text-sm mt-2">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-900 mb-3">🏷️ Tags</label>
                            <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 mb-4">
                                <div class="flex flex-wrap gap-2">
                                    @foreach($tags as $tag)
                                        <label class="inline-flex items-center bg-white border border-slate-200 rounded-full px-3 py-1 cursor-pointer hover:border-blue-400 transition">
                                            <input type="checkbox" name="tags[]" value="{{ $tag->id }}" class="form-checkbox h-4 w-4 text-blue-600 rounded focus:ring-blue-400 border-gray-300" {{ in_array($tag->id, old('tags', [])) ? 'checked' : '' }}>
                                            <span class="ml-2 text-sm text-slate-700">{{ $tag->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                                <div class="mt-4">
                                    <label class="block text-xs font-bold text-slate-500 mb-1">Add New Tags (comma separated)</label>
                                    <input name="new_tags" value="{{ old('new_tags') }}" placeholder="e.g. AI, Technology, Future" class="w-full px-4 py-2 bg-white border border-slate-200 rounded-lg focus:border-blue-400 focus:ring-2 focus:ring-blue-400/50 text-slate-900 placeholder-slate-400 transition text-sm" />
                                </div>
                            </div>
                            @error('tags')<p class="text-red-600 text-sm mt-2">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-900 mb-3">✍️ Content</label>
                            <textarea name="content" rows="12" required placeholder="Write your amazing content here... Tell your story, share your ideas, inspire your readers." class="w-full px-5 py-4 bg-white border border-slate-200 rounded-xl focus:border-blue-400 focus:ring-2 focus:ring-blue-400/50 text-slate-900 placeholder-slate-400 transition font-mono text-sm">{{ old('content') }}</textarea>
                            @error('content')<p class="text-red-600 text-sm mt-2">{{ $message }}</p>@enderror
                        </div>

                        <div class="bg-blue-50 p-6 rounded-xl border border-blue-200">
                            <label class="flex items-center cursor-pointer group/checkbox">
                                <input type="checkbox" name="published" value="1" class="w-5 h-5 rounded border-blue-400 text-blue-600 focus:ring-blue-400" {{ old('published') ? 'checked' : '' }}>
                                <span class="ml-3">
                                    <span class="font-bold text-slate-900 block">🚀 Publish immediately</span>
                                    <span class="text-slate-600 text-sm">Make your post visible to all readers</span>
                                </span>
                            </label>
                            <p class="text-xs text-slate-600 mt-3 ml-8">Leave unchecked to save as draft</p>
                        </div>

                        <div class="flex flex-col sm:flex-row items-center justify-center gap-4 pt-6 border-t border-slate-200">
                            <a href="{{ route('dashboard') }}" class="text-slate-600 hover:text-slate-900 underline transition font-semibold">Cancel</a>
                            <button type="submit" class="w-full sm:w-auto px-8 py-4 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold rounded-xl shadow-lg hover:shadow-blue-500/30 hover:shadow-2xl transform hover:scale-105 transition duration-200 text-lg">
                                ✨ Publish & Share
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('image-upload').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    document.getElementById('preview-img').src = event.target.result;
                    document.getElementById('image-preview').classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
</x-app-layout>
