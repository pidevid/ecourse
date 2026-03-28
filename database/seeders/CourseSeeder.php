<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        // Create author user if not exists
        $author = User::firstOrCreate(
            ['email' => 'author@ecourse.com'],
            [
                'name'     => 'Author Demo',
                'username' => 'author_demo',
                'password' => bcrypt('author123'),
            ]
        );

        $authorRole = Role::where('name', 'author')->first();
        if ($authorRole && !$author->hasRole('author')) {
            $author->assignRole($authorRole);
        }

        $categoryIds = Category::pluck('id', 'slug');

        $courses = [
            [
                'name'        => 'Belajar Laravel dari Nol',
                'category'    => 'web-development',
                'description' => 'Pelajari Laravel 10 dari dasar hingga mahir. Mulai dari routing, controller, model, hingga deployment.',
                'price'       => 199000,
                'discount'    => 20,
                'status'      => 'approved',
            ],
            [
                'name'        => 'React JS untuk Pemula',
                'category'    => 'web-development',
                'description' => 'Kuasai React JS modern dengan hooks, state management, dan integrasi REST API.',
                'price'       => 179000,
                'discount'    => null,
                'status'      => 'approved',
            ],
            [
                'name'        => 'Flutter Complete Course',
                'category'    => 'mobile-development',
                'description' => 'Bangun aplikasi Android dan iOS dengan Flutter dan Dart dari nol hingga publish ke Play Store.',
                'price'       => 249000,
                'discount'    => 15,
                'status'      => 'approved',
            ],
            [
                'name'        => 'Python untuk Data Science',
                'category'    => 'data-science',
                'description' => 'Analisis data menggunakan Python, Pandas, Numpy, dan visualisasi data dengan Matplotlib.',
                'price'       => 229000,
                'discount'    => null,
                'status'      => 'approved',
            ],
            [
                'name'        => 'Machine Learning dengan Python',
                'category'    => 'machine-learning',
                'description' => 'Pahami konsep supervised dan unsupervised learning serta implementasinya menggunakan scikit-learn.',
                'price'       => 299000,
                'discount'    => 10,
                'status'      => 'approved',
            ],
            [
                'name'        => 'UI/UX Design dengan Figma',
                'category'    => 'ui-ux-design',
                'description' => 'Rancang desain aplikasi yang menarik dan user-friendly menggunakan Figma dari nol.',
                'price'       => 159000,
                'discount'    => null,
                'status'      => 'approved',
            ],
            [
                'name'        => 'Docker & Kubernetes Fundamentals',
                'category'    => 'devops',
                'description' => 'Pelajari containerisasi dengan Docker dan orkestrasi container dengan Kubernetes untuk production.',
                'price'       => 279000,
                'discount'    => 25,
                'status'      => 'approved',
            ],
            [
                'name'        => 'Ethical Hacking & Penetration Testing',
                'category'    => 'cybersecurity',
                'description' => 'Pelajari teknik ethical hacking, vulnerability assessment, dan penetration testing secara legal.',
                'price'       => 319000,
                'discount'    => null,
                'status'      => 'approved',
            ],
            [
                'name'        => 'AWS Cloud Practitioner',
                'category'    => 'cloud-computing',
                'description' => 'Persiapan sertifikasi AWS Cloud Practitioner dengan materi lengkap dan latihan soal.',
                'price'       => 349000,
                'discount'    => 30,
                'status'      => 'approved',
            ],
            [
                'name'        => 'Unity Game Development 2D',
                'category'    => 'game-development',
                'description' => 'Buat game 2D menggunakan Unity dan C# dari konsep dasar hingga publish ke berbagai platform.',
                'price'       => 199000,
                'discount'    => null,
                'status'      => 'approved',
            ],
            [
                'name'        => 'Digital Marketing Masterclass',
                'category'    => 'digital-marketing',
                'description' => 'Strategi pemasaran digital lengkap: SEO, Google Ads, Social Media Marketing, dan Email Marketing.',
                'price'       => 149000,
                'discount'    => 20,
                'status'      => 'approved',
            ],
            [
                'name'        => 'Node.js & Express REST API',
                'category'    => 'web-development',
                'description' => 'Bangun REST API yang scalable menggunakan Node.js, Express, dan MongoDB.',
                'price'       => 189000,
                'discount'    => null,
                'status'      => 'approved',
            ],
        ];

        foreach ($courses as $data) {
            $categorySlug = $data['category'];
            $categoryId = $categoryIds[$categorySlug] ?? Category::first()?->id;

            if (!$categoryId) continue;

            Course::firstOrCreate(
                ['slug' => \Illuminate\Support\Str::slug($data['name'])],
                [
                    'name'        => $data['name'],
                    'user_id'     => $author->id,
                    'category_id' => $categoryId,
                    'description' => $data['description'],
                    'price'       => $data['price'],
                    'discount'    => $data['discount'],
                    'image'       => 'default.jpg',
                    'status'      => $data['status'],
                ]
            );
        }
    }
}
