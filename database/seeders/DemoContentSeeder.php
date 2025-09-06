<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;

class DemoContentSeeder extends Seeder
{
    public function run(): void
    {
        // ---- SETTINGS (safe if columns exist) --------------------------------
        if (Schema::hasTable('settings')) {
            $settingsData = [];

            if (Schema::hasColumn('settings', 'site_name')) {
                $settingsData['site_name'] = 'My Portfolio';
            }
            if (Schema::hasColumn('settings', 'theme')) {
                $settingsData['theme'] = 'light';
            }
            if (Schema::hasColumn('settings', 'social_links')) {
                $settingsData['social_links'] = json_encode([
                    ['url' => 'https://www.linkedin.com/in/demo'],
                    ['url' => 'https://www.facebook.com/demo'],
                    ['url' => 'https://github.com/demo'],
                ]);
            }
            if (Schema::hasColumn('settings', 'contact_email')) {
                $settingsData['contact_email'] = 'contact@email.com';
            }
            if (Schema::hasColumn('settings', 'footer_copyright')) {
                $settingsData['footer_copyright'] = 'Portfolio by Alex david du';
            }

            if ($settingsData !== []) {
                DB::table('settings')->updateOrInsert(['id' => 1], $settingsData);
            }
        }

        // ---- USER (user@example.com / password) ------------------------------
        if (Schema::hasTable('users') && Schema::hasColumn('users', 'email')) {
            $userData = [];

            // core identity
            $userData['email'] = 'user@example.com';
            if (Schema::hasColumn('users', 'name')) {
                $userData['name'] = 'Joe Doe';
            }
            if (Schema::hasColumn('users', 'first_name')) {
                $userData['first_name'] = 'Joe';
            }
            if (Schema::hasColumn('users', 'last_name')) {
                $userData['last_name'] = 'Doe';
            }

            // profile extras (only if columns exist)
            if (Schema::hasColumn('users', 'country')) {
                $userData['country'] = 'United States';
            }
            if (Schema::hasColumn('users', 'nationality')) {
                $userData['nationality'] = 'American';
            }
            if (Schema::hasColumn('users', 'date_of_birth')) {
                $userData['date_of_birth'] = '1996-09-26';
            }
            if (Schema::hasColumn('users', 'avatar_url')) {
                $userData['avatar_url'] = null;
            }

            // security & roles
            if (Schema::hasColumn('users', 'password')) {
                // Use app's configured hasher (bcrypt/argon) to avoid algorithm mismatch errors
                $userData['password'] = Hash::make('password');
            }
            if (Schema::hasColumn('users', 'email_verified_at')) {
                $userData['email_verified_at'] = now();
            }
            if (Schema::hasColumn('users', 'is_admin')) {
                $userData['is_admin'] = true;
            }

            DB::table('users')->updateOrInsert(
                ['email' => 'user@example.com'], // unique key
                $userData
            );

            $userId = DB::table('users')->where('email', 'user@example.com')->value('id');

            // ---- BLOG POST -------------------------------------------------
            if (
                Schema::hasTable('blog_categories') &&
                Schema::hasTable('blog_posts')
            ) {
                $blogCategoryId = DB::table('blog_categories')->insertGetId([
                    'name' => 'General',
                    'slug' => 'general',
                    'description' => 'General updates',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                DB::table('blog_posts')->insert([
                    [
                        'title' => 'Sample Blog Post',
                        'slug' => 'sample-blog-post',
                        'category_id' => $blogCategoryId,
                        'excerpt' => 'This is a sample blog post.',
                        'body' => '<p>Hello world!</p>',
                        'status' => 'published',
                        'published_at' => now(),
                        'cover_image_url' => null,
                        'meta_title' => null,
                        'meta_description' => null,
                        'reading_time_minutes' => 1,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'title' => 'Another Blog Post',
                        'slug' => 'another-blog-post',
                        'category_id' => $blogCategoryId,
                        'excerpt' => 'Second example blog entry.',
                        'body' => '<p>More sample content.</p>',
                        'status' => 'published',
                        'published_at' => now(),
                        'cover_image_url' => null,
                        'meta_title' => null,
                        'meta_description' => null,
                        'reading_time_minutes' => 1,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'title' => 'Third Blog Post',
                        'slug' => 'third-blog-post',
                        'category_id' => $blogCategoryId,
                        'excerpt' => 'Third sample blog post.',
                        'body' => '<p>Yet more demo text.</p>',
                        'status' => 'published',
                        'published_at' => now(),
                        'cover_image_url' => null,
                        'meta_title' => null,
                        'meta_description' => null,
                        'reading_time_minutes' => 1,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                ]);
            }

            // ---- PORTFOLIO -------------------------------------------------
            if (
                Schema::hasTable('portfolio_categories') &&
                Schema::hasTable('portfolios')
            ) {
                $portfolioCategoryId = DB::table('portfolio_categories')->insertGetId([
                    'name' => 'Web Development',
                    'slug' => 'web-development',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                DB::table('portfolios')->insert([
                    [
                        'title' => 'Sample Portfolio Item',
                        'slug' => 'sample-portfolio-item',
                        'category_id' => $portfolioCategoryId,
                        'short_description' => 'Example portfolio item.',
                        'description' => 'This is a sample portfolio entry.',
                        'tech_stack' => json_encode(['Laravel', 'Tailwind']),
                        'thumbnail_url' => null,
                        'gallery_urls' => null,
                        'featured' => false,
                        'status' => 'published',
                        'published_at' => now(),
                        'sort_order' => 1,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'title' => 'Another Portfolio Item',
                        'slug' => 'another-portfolio-item',
                        'category_id' => $portfolioCategoryId,
                        'short_description' => 'Second portfolio example.',
                        'description' => 'Another sample portfolio entry.',
                        'tech_stack' => json_encode(['Laravel', 'Tailwind']),
                        'thumbnail_url' => null,
                        'gallery_urls' => null,
                        'featured' => false,
                        'status' => 'published',
                        'published_at' => now(),
                        'sort_order' => 2,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'title' => 'Third Portfolio Item',
                        'slug' => 'third-portfolio-item',
                        'category_id' => $portfolioCategoryId,
                        'short_description' => 'Third portfolio example.',
                        'description' => 'Yet another sample portfolio entry.',
                        'tech_stack' => json_encode(['Laravel', 'Tailwind']),
                        'thumbnail_url' => null,
                        'gallery_urls' => null,
                        'featured' => false,
                        'status' => 'published',
                        'published_at' => now(),
                        'sort_order' => 3,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                ]);
            }

            // ---- SERVICES --------------------------------------------------
            if (Schema::hasTable('services_page')) {
                DB::table('services_page')->updateOrInsert(
                    ['id' => 1],
                    [
                        'title' => 'Services',
                        'description' => 'What I can do for you.',
                        'meta_description' => 'List of services offered.',
                        'feature_image_url' => null,
                        'active' => 1,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }

            if (Schema::hasTable('services') && DB::table('services')->count() === 0) {
                DB::table('services')->insert([
                    [
                        'service_title' => 'Content Creation',
                        'service_description' => "Blog posts\nSEO articles\nProofreading",
                        'price' => '$100',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'service_title' => 'Web Development',
                        'service_description' => "Landing pages\nFull websites\nMaintenance",
                        'price' => '$500',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'service_title' => 'Consulting',
                        'service_description' => "Strategy sessions\nTechnical audits\nQ&A",
                        'price' => '$200',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                ]);
            }

            // ---- EXPERIENCES -----------------------------------------------
            if (Schema::hasTable('experiences')) {
                DB::table('experiences')->insert([
                    [
                        'company_name' => 'Company SEO',
                        'role_title' => 'Content Writer',
                        'location' => 'Remote',
                        'start_date' => '2020-01-01',
                        'end_date' => '2021-12-31',
                        'is_current' => false,
                        'summary' => 'Created SEO optimized content.',
                        'responsibilities' => json_encode([]),
                        'logo_url' => null,
                        'sort_order' => 1,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'company_name' => 'Web Solutions',
                        'role_title' => 'Junior Developer',
                        'location' => 'New York',
                        'start_date' => '2018-06-01',
                        'end_date' => '2019-12-31',
                        'is_current' => false,
                        'summary' => 'Built websites for small businesses.',
                        'responsibilities' => json_encode([]),
                        'logo_url' => null,
                        'sort_order' => 2,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'company_name' => 'Startup XYZ',
                        'role_title' => 'Intern',
                        'location' => 'San Francisco',
                        'start_date' => '2017-01-01',
                        'end_date' => '2018-05-31',
                        'is_current' => false,
                        'summary' => 'Assisted with product development.',
                        'responsibilities' => json_encode([]),
                        'logo_url' => null,
                        'sort_order' => 3,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                ]);
            }

            // ---- SKILLS ----------------------------------------------------
            if (Schema::hasTable('skills')) {
                DB::table('skills')->insert([
                    [
                        'name' => 'PHP',
                        'category' => 'Programming',
                        'level' => 'advanced',
                        'years_experience' => 5,
                        'icon_key' => 'php',
                        'sort_order' => 1,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'name' => 'Laravel',
                        'category' => 'Framework',
                        'level' => 'expert',
                        'years_experience' => 4,
                        'icon_key' => 'laravel',
                        'sort_order' => 2,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'name' => 'Content Writing',
                        'category' => 'Writing',
                        'level' => 'intermediate',
                        'years_experience' => 3,
                        'icon_key' => 'pencil',
                        'sort_order' => 3,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                ]);
            }

            // ---- LANGUAGES -------------------------------------------------
            if ($userId && Schema::hasTable('languages')) {
                DB::table('languages')->insert([
                    [
                        'user_id' => $userId,
                        'name' => 'Portuguese',
                        'level' => 'native',
                        'sort_order' => 1,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'user_id' => $userId,
                        'name' => 'English',
                        'level' => 'fluent',
                        'sort_order' => 2,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'user_id' => $userId,
                        'name' => 'Spanish',
                        'level' => 'conversational',
                        'sort_order' => 3,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                ]);
            }
        }

        // ---- MESSAGES -------------------------------------------------
        if (Schema::hasTable('messages')) {
            DB::table('messages')->updateOrInsert(
                ['id' => 1],
                [
                    'first_name' => 'Alex',
                    'last_name' => 'Du',
                    'email' => 'contact@byalexdavid.com',
                    'message' => "Hey there,\n\nThis is just a test message to kick things off. Really glad you are checking out the portfolio. Thank you very much for the support, it means a lot! 🚀",
                    'created_at' => '2025-08-27 10:15:00',
                    'updated_at' => '2025-08-27 10:15:00',
                ]
            );
        }

        // ---- CONTACT PAGE --------------------------------------------
        if (Schema::hasTable('contacts')) {
            DB::table('contacts')->updateOrInsert(
                ['id' => 1],
                [
                    'title' => 'Contact Page Title',
                    'description' => 'Description',
                    'meta_description' => 'SEO meta description',
                ]
            );
        }
    }
}
