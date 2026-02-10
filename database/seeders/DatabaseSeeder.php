<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create a test user
        $user = User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'email_verified_at' => now(),
                'password' => bcrypt('password'),
            ]
        );

        // Create multiple sample posts
        $posts = [
            [
                'title' => 'Getting Started with Blog Hub',
                'slug' => 'getting-started-with-blog-hub',
                'content' => 'Welcome to Blog Hub! This is your first step into the world of modern blogging. 

Blog Hub is designed to make blogging simple, beautiful, and enjoyable. Whether you\'re a seasoned writer or just starting out, our platform provides all the tools you need to share your thoughts with the world.

Key features include:
- Beautiful, responsive design
- Easy image uploads
- Seamless publishing
- User-friendly dashboard
- Community of writers

Start creating your first blog post today and share your unique voice with thousands of readers around the world. Happy writing!',
            ],
            [
                'title' => 'The Art of Digital Storytelling',
                'slug' => 'art-of-digital-storytelling',
                'content' => 'In today\'s digital world, storytelling has become more important than ever. People don\'t just want information—they want stories. They want to connect with real experiences and real emotions.

Whether you\'re writing about personal experiences, professional insights, or creative fiction, the ability to tell a compelling story can make all the difference.

Good storytelling has power. It can inspire, educate, and transform. On platforms like Blog Hub, you have the perfect space to share your unique perspective with the world.',
            ],
            [
                'title' => 'Tips for Writing Better Blog Posts',
                'slug' => 'tips-for-writing-better-blog-posts',
                'content' => 'Writing a good blog post takes more than just putting words on a page. Here are some tips to help you create content that resonates with your readers:

1. Know Your Audience - Write for people you want to reach
2. Write Clear Headlines - Make it easy to understand what\'s inside
3. Keep It Organized - Use sections and bullet points
4. Be Authentic - Share your real voice and perspective
5. Edit and Revise - Always proofread before publishing
6. Add Value - Make sure every post teaches or entertains
7. Call to Action - Tell readers what you want them to do next

Remember, the best blog posts are those that provide real value to readers. Focus on quality over quantity, and your audience will grow.',
            ],
            [
                'title' => 'Building a Successful Blogging Habit',
                'slug' => 'building-successful-blogging-habit',
                'content' => 'Starting a blog is easy. Maintaining it is the real challenge. Here\'s how to build a successful blogging habit:

Set a Schedule - Decide how often you\'ll publish and stick to it. Whether it\'s weekly or monthly, consistency is key.

Create an Editorial Calendar - Plan your posts in advance so you always have something to write about.

Find Your Voice - Don\'t try to sound like someone else. Be authentic and unique.

Engage with Your Community - Respond to comments and engage with other bloggers.

Track Your Progress - Monitor which posts get the most engagement and learn from them.

The key to success is consistency and dedication. Start small, stay committed, and watch your blog grow over time.',
            ],
            [
                'title' => 'How to Overcome Writer\'s Block',
                'slug' => 'how-to-overcome-writers-block',
                'content' => 'Writer\'s block is a common challenge faced by bloggers. Here are some proven strategies to overcome it:

1. Freewrite - Just start writing without worrying about perfection
2. Change Your Environment - Sometimes a new location helps spark creativity
3. Read Inspiring Content - Reading other great writers can inspire you
4. Take a Break - Step away and come back refreshed
5. Use Writing Prompts - Let prompts guide your writing
6. Talk It Out - Discuss your ideas with friends or family
7. Start Small - Don\'t aim for perfection on your first draft

Remember, every writer faces blocks. The key is to keep pushing through and remember why you started writing in the first place.',
            ],
            [
                'title' => 'The Future of Blogging',
                'slug' => 'future-of-blogging',
                'content' => 'Blogging has evolved significantly over the years, and it continues to change. Here\'s what the future might hold:

Video Integration - More blogs are adding video content alongside text.

AI Assistance - AI tools are helping writers create better content faster.

Personalization - Readers will see content tailored to their interests.

Interactive Content - Polls, quizzes, and interactive elements will become more common.

Community Focus - Building genuine communities will be more important than ever.

Authenticity - In a sea of content, authentic voices will stand out.

The future of blogging is bright. Those who adapt and stay true to their voice will continue to thrive in this evolving landscape.',
            ],
        ];

        foreach ($posts as $post) {
            \App\Models\Post::firstOrCreate(
                ['slug' => $post['slug']],
                [
                    'title' => $post['title'],
                    'content' => $post['content'],
                    'user_id' => $user->id,
                    'published_at' => now(),
                ]
            );
        }
    }
}
