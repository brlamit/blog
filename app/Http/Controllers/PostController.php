<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use App\Models\Tag;

class PostController extends Controller
{
    public function home(Request $request)
    {
        $q = $request->query('q');

        if (auth()->check()) {
            // Logged-in user: show their dashboard with their posts (support search)
            $myPosts = auth()->user()->posts()->with('tags')
                ->when($q, function ($query, $q) {
                    $query->where('title', 'like', "%{$q}%")
                          ->orWhere('content', 'like', "%{$q}%")
                          ->orWhere('summary', 'like', "%{$q}%");
                })->latest()->get();

            // Also provide the public feed (like welcome) so dashboard shows all blogs
            $allPosts = Post::whereNotNull('published_at')
                ->with('tags')
                ->when($q, function ($query, $q) {
                    $query->where('title', 'like', "%{$q}%")
                          ->orWhere('content', 'like', "%{$q}%")
                          ->orWhere('summary', 'like', "%{$q}%");
                })
                ->latest('published_at')
                ->paginate(12)
                ->withQueryString();

            return view('dashboard', compact('myPosts', 'allPosts'));
        } else {
            // Guest user: show welcome page with all published blogs (support search)
            $allPosts = Post::whereNotNull('published_at')
                ->with('tags')
                ->when($q, function ($query, $q) {
                    $query->where('title', 'like', "%{$q}%")
                          ->orWhere('content', 'like', "%{$q}%")
                          ->orWhere('summary', 'like', "%{$q}%");
                })
                ->latest('published_at')
                ->paginate(12)
                ->withQueryString();

            return view('welcome', compact('allPosts'));
        }
    }

    public function index(Request $request)
    {
        $q = $request->query('q');

        $author = $request->query('author');

        // If asking for the current user's posts
        if ($author === 'me') {
            if (!auth()->check()) {
                return redirect()->route('login');
            }

            $postsQuery = Post::with('tags')->where('user_id', auth()->id());
        } else {
            // public listing only shows published posts
            $postsQuery = Post::whereNotNull('published_at')->with('tags');
        }

        $posts = $postsQuery
            ->when($q, function ($query, $q) {
                $query->where('title', 'like', "%{$q}%")
                      ->orWhere('content', 'like', "%{$q}%")
                      ->orWhere('summary', 'like', "%{$q}%");
            })
            ->orderByDesc('published_at')
            ->paginate(12)
            ->withQueryString();

        return view('posts.index', compact('posts'));
    }

    public function show(Post $post)
    {
        $post->load('tags');
        return view('posts.show', compact('post'));
    }

    public function create()
    {
        $tags = Tag::all();
        return view('posts.create', compact('tags'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'summary' => 'nullable|string|max:500',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,gif,webp|max:2048',
            'published' => 'nullable|boolean',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
            'new_tags' => 'nullable|string',
        ]);

        $slugBase = Str::slug($request->title);
        $slug = $slugBase;
        $i = 1;
        while (Post::where('slug', $slug)->exists()) {
            $slug = $slugBase.'-'.time().'-'.$i;
            $i++;
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('posts', 'public');
        }

        $post = Post::create([
            'title' => $request->title,
            'slug' => $slug,
            'summary' => $request->summary,
            'content' => $request->content,
            'image' => $imagePath,
            'user_id' => Auth::id(),
            'published_at' => ($request->has('published') ? now() : null),
        ]);

        // Handle existing tags
        if ($request->has('tags')) {
            $post->tags()->attach($request->tags);
        }

        // Handle new tags
        if ($request->filled('new_tags')) {
            $newTags = array_map('trim', explode(',', $request->new_tags));
            foreach ($newTags as $newTagName) {
                if (!empty($newTagName)) {
                    $tag = Tag::firstOrCreate(
                        ['slug' => Str::slug($newTagName)],
                        ['name' => $newTagName]
                    );
                    $post->tags()->attach($tag->id);
                }
            }
        }

        return redirect()->route('dashboard')->with('status', 'Post created successfully!');
    }

    public function edit(Post $post)
    {
        $this->authorize('update', $post);
        $tags = Tag::all();
        return view('posts.edit', compact('post', 'tags'));
    }

    public function update(Request $request, Post $post)
    {
        $this->authorize('update', $post);

        $request->validate([
            'title' => 'required|string|max:255',
            'summary' => 'nullable|string|max:500',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,gif,webp|max:2048',
            'published' => 'nullable|boolean',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
            'new_tags' => 'nullable|string',
        ]);

        $imagePath = $post->image;
        if ($request->hasFile('image')) {
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }
            $imagePath = $request->file('image')->store('posts', 'public');
        }

        $post->update([
            'title' => $request->title,
            'summary' => $request->summary,
            'content' => $request->content,
            'image' => $imagePath,
            'published_at' => ($request->has('published') && is_null($post->published_at) ? now() : ($request->has('published') ? $post->published_at : null)),
        ]);

        // Sync tags
        $tagIds = $request->input('tags', []);
        
        // Handle new tags
        if ($request->filled('new_tags')) {
            $newTags = array_map('trim', explode(',', $request->new_tags));
            foreach ($newTags as $newTagName) {
                if (!empty($newTagName)) {
                    $tag = Tag::firstOrCreate(
                        ['slug' => Str::slug($newTagName)],
                        ['name' => $newTagName]
                    );
                    $tagIds[] = $tag->id;
                }
            }
        }

        $post->tags()->sync($tagIds);

        return redirect()->route('dashboard')->with('status', 'Post updated successfully!');
    }

    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);
        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }
        $post->tags()->detach();
        $post->delete();
        return redirect()->route('dashboard')->with('status', 'Post deleted successfully!');
    }
}
