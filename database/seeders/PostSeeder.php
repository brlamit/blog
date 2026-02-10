<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Post;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get or create a test user
        $user = User::first() ?? User::create([
            'name' => 'Demo User',
            'email' => 'demo@example.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);

        // Sample blog posts
        $posts = [
            [
                'title' => 'Getting Started with Blog Hub',
                'content' => 'Welcome to Blog Hub! This is your first step into the world of modern blogging. 

Blog Hub is designed to make blogging simple, beautiful, and enjoyable. Whether you\'re a seasoned writer or just starting out, our platform provides all the tools you need to share your thoughts with the world.

Key features include:
- Beautiful, responsive design
- Easy image uploads
- Seamless publishing
- User-friendly dashboard
- Community of writers

Start creating your first blog post today and share your unique voice with thousands of readers around the world. Happy writing!',
                'published' => true,
            ],
            [
                'title' => 'The Art of Storytelling in the Digital Age',
                'content' => 'In today\'s fast-paced digital world, storytelling has become more important than ever. 

People don\'t just want information—they want stories. They want to connect with real experiences, real emotions, and real people behind the words.

Whether you\'re writing about personal experiences, professional insights, or creative fiction, the ability to tell a compelling story can make all the difference.

On Blog Hub, we believe everyone has a story to tell. Our platform is built to help you share yours in the most beautiful and engaging way possible.

Remember:
- Your story matters
- Your voice is unique
- Your thoughts deserve to be heard

So what are you waiting for? Start writing today!',
                'published' => true,
            ],
            [
                'title' => 'Top 5 Tips for Better Writing',
                'content' => 'Writing is a skill that improves with practice. Here are five essential tips to help you become a better writer:

1. **Write Regularly** - Make writing a daily habit. The more you write, the better you become.

2. **Read Widely** - Expose yourself to different writing styles and perspectives. Reading is the foundation of good writing.

3. **Know Your Audience** - Always write with your readers in mind. What do they want to know? What will resonate with them?

4. **Edit Without Mercy** - Your first draft is never your final draft. Edit ruthlessly to remove unnecessary words and clarify your message.

5. **Find Your Voice** - Don\'t try to write like someone else. Develop your unique voice and writing style. That\'s what will make your writing stand out.

Apply these tips to your next blog post and watch your writing improve dramatically!',
                'published' => true,
            ],
            [
                'title' => 'Building a Community Through Blogging',
                'content' => 'One of the most rewarding aspects of blogging is building a community of like-minded individuals.

When you share your authentic thoughts and experiences, you create connections with readers who resonate with your message. These connections can lead to:

- Meaningful friendships
- Professional opportunities
- Collaborative projects
- A sense of belonging

Here\'s how to build community through your blog:

**Be Authentic** - Share your real thoughts and experiences. People connect with authenticity.

**Engage with Readers** - Respond to comments, answer questions, and create a dialogue with your audience.

**Be Consistent** - Regular posts keep your community engaged and coming back for more.

**Share Value** - Provide something of value in each post—insights, entertainment, education, or inspiration.

Start your blogging journey today and be part of our growing community!',
                'published' => true,
            ],
            [
                'title' => 'The Power of Visual Storytelling',
                'content' => 'Words are powerful, but images can tell a story even more effectively. 

In Blog Hub, we\'ve made it easy to add featured images to your posts. These images don\'t just make your blog look beautiful—they enhance your storytelling and engage your readers at a deeper level.

When choosing images for your posts:
- Pick images that relate to your content
- Use high-quality, clear images
- Consider the mood and emotion you want to convey
- Ensure the image adds value to your message

Visual storytelling combines:
- Images that capture attention
- Text that provides context
- Design that guides the reader
- Emotion that resonates with the audience

Master the art of visual storytelling and watch your engagement soar!',
                'published' => true,
            ],
            [
                'title' => 'Your Journey as a Creator Starts Here',
                'content' => 'Thank you for joining Blog Hub. Whether you\'re here to share your expertise, document your journey, or simply express yourself, you\'ve made the right choice.

This platform is designed with you in mind:
- Simple and intuitive interface
- Beautiful templates
- Easy publishing
- Mobile-friendly design

As a creator, your voice matters. Your perspective is unique. Your stories have the power to inspire, educate, and connect.

We\'re excited to see what you\'ll create. The community is waiting to hear from you.

Start writing your first blog post now and begin your journey as a creator!',
                'published' => true,
            ],
        ];

        foreach ($posts as $postData) {
            $published = $postData['published'];
            unset($postData['published']);

            $postData['user_id'] = $user->id;
            $postData['slug'] = Str::slug($postData['title']) . '-' . Str::random(5);
            $postData['published_at'] = $published ? now() : null;

            Post::create($postData);
        }
    }
}
