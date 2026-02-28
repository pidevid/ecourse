<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\PersonalWebsite;
use App\Models\WebsiteProfile;
use App\Models\WebsiteSocialLink;
use App\Models\WebsiteSkill;
use App\Models\WebsiteService;
use App\Models\WebsiteExperience;
use App\Models\WebsiteEducation;
use App\Models\WebsitePortfolio;
use App\Models\WebsiteTestimonial;
use Illuminate\Database\Seeder;

class PersonalWebsiteSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('email', 'saeful2026027@gmail.com')->firstOrFail();

        // Fix username (no spaces for clean URL)
        $user->update([
            'username'           => 'saeful',
            'has_website_access' => true,
        ]);

        // ── Personal Website ────────────────────────────────────────
        $website = PersonalWebsite::updateOrCreate(
            ['user_id' => $user->id],
            [
                'theme'            => 'minimal',
                'accent_color'     => '#14b8a6',
                'font_family'      => 'sans',
                'meta_title'       => 'Saeful Muminin — Full Stack Developer',
                'meta_description' => 'Portfolio Saeful Muminin, Full Stack Developer yang berpengalaman di Laravel, Vue.js, dan teknologi web modern.',
                'is_published'     => true,
            ]
        );

        // ── Profile ─────────────────────────────────────────────────
        WebsiteProfile::updateOrCreate(
            ['personal_website_id' => $website->id],
            [
                'full_name'  => 'Saeful Muminin',
                'role_title' => 'Full Stack Web Developer',
                'short_bio'  => 'Developer passionate dalam membangun aplikasi web yang modern, cepat, dan scalable.',
                'about_me'   => 'Halo! Saya Saeful Muminin, seorang Full Stack Web Developer dengan pengalaman membangun aplikasi web menggunakan Laravel, Vue.js, dan berbagai teknologi modern. Saya senang belajar hal baru, berkolaborasi dalam tim, dan menghasilkan produk digital yang berdampak nyata. Saat ini saya aktif mengembangkan platform e-learning dan berminat pada proyek-proyek yang menggabungkan pendidikan dengan teknologi.',
                'email'      => 'saeful2026027@gmail.com',
                'phone'      => '+62 812-3456-7890',
                'location'   => 'Bandung, Jawa Barat, Indonesia',
            ]
        );

        // ── Social Links ─────────────────────────────────────────────
        $socialLinks = [
            ['platform' => 'github',    'url' => 'https://github.com/saeful'],
            ['platform' => 'linkedin',  'url' => 'https://linkedin.com/in/saeful-muminin'],
            ['platform' => 'instagram', 'url' => 'https://instagram.com/saeful.dev'],
        ];

        foreach ($socialLinks as $link) {
            WebsiteSocialLink::updateOrCreate(
                ['personal_website_id' => $website->id, 'platform' => $link['platform']],
                ['url' => $link['url']]
            );
        }

        // ── Skills ───────────────────────────────────────────────────
        $skills = [
            ['name' => 'Laravel (PHP)',  'level' => 'expert',       'percentage' => 90],
            ['name' => 'Vue.js',         'level' => 'intermediate', 'percentage' => 75],
            ['name' => 'MySQL',          'level' => 'expert',       'percentage' => 85],
            ['name' => 'Tailwind CSS',   'level' => 'expert',       'percentage' => 88],
            ['name' => 'JavaScript',     'level' => 'intermediate', 'percentage' => 78],
            ['name' => 'Git & GitHub',   'level' => 'expert',       'percentage' => 85],
            ['name' => 'Docker',         'level' => 'intermediate', 'percentage' => 65],
            ['name' => 'REST API',       'level' => 'expert',       'percentage' => 88],
        ];

        WebsiteSkill::where('personal_website_id', $website->id)->delete();
        foreach ($skills as $skill) {
            WebsiteSkill::create(array_merge($skill, ['personal_website_id' => $website->id]));
        }

        // ── Services ─────────────────────────────────────────────────
        $services = [
            [
                'title'       => 'Web Development',
                'icon'        => 'fas fa-laptop-code',
                'description' => 'Membangun aplikasi web full stack yang modern, responsif, dan performant menggunakan Laravel dan Vue.js.',
            ],
            [
                'title'       => 'API Development',
                'icon'        => 'fas fa-code',
                'description' => 'Merancang dan mengembangkan REST API yang aman, terdokumentasi dengan baik, dan mudah diintegrasikan.',
            ],
            [
                'title'       => 'Database Design',
                'icon'        => 'fas fa-database',
                'description' => 'Desain skema database yang optimal, normalisasi data, dan optimasi query untuk performa terbaik.',
            ],
        ];

        WebsiteService::where('personal_website_id', $website->id)->delete();
        foreach ($services as $service) {
            WebsiteService::create(array_merge($service, ['personal_website_id' => $website->id]));
        }

        // ── Experience ───────────────────────────────────────────────
        $experiences = [
            [
                'company'     => 'Unu Course Platform',
                'position'    => 'Lead Full Stack Developer',
                'start_date'  => '2024-01-01',
                'end_date'    => null,
                'is_current'  => true,
                'description' => 'Memimpin pengembangan platform e-learning berbasis Laravel, mulai dari arsitektur sistem, integrasi payment gateway Midtrans, hingga fitur personal website generator.',
            ],
            [
                'company'     => 'Freelance Developer',
                'position'    => 'Web Developer',
                'start_date'  => '2022-06-01',
                'end_date'    => '2023-12-31',
                'is_current'  => false,
                'description' => 'Mengerjakan berbagai proyek web untuk klien UMKM dan startup, termasuk company profile, sistem manajemen, dan toko online.',
            ],
        ];

        WebsiteExperience::where('personal_website_id', $website->id)->delete();
        foreach ($experiences as $exp) {
            WebsiteExperience::create(array_merge($exp, ['personal_website_id' => $website->id]));
        }

        // ── Education ────────────────────────────────────────────────
        $educations = [
            [
                'institution' => 'Universitas Nahdlatul Ulama Indonesia',
                'degree'      => 'S1',
                'field'       => 'Teknik Informatika',
                'start_year'  => 2020,
                'end_year'    => 2024,
                'description' => 'Fokus pada rekayasa perangkat lunak, basis data, dan pengembangan web. Aktif di komunitas coding kampus.',
            ],
        ];

        WebsiteEducation::where('personal_website_id', $website->id)->delete();
        foreach ($educations as $edu) {
            WebsiteEducation::create(array_merge($edu, ['personal_website_id' => $website->id]));
        }

        // ── Portfolio ────────────────────────────────────────────────
        $portfolios = [
            [
                'title'       => 'Unu Course — Platform E-Learning',
                'description' => 'Platform e-learning lengkap dengan fitur manajemen kursus, pembayaran Midtrans, sertifikat otomatis, dan personal website generator.',
                'url'         => 'http://localhost:8080',
                'tech_stack'  => 'Laravel, Tailwind CSS, MySQL, Midtrans, Docker',
                'image'       => null,
            ],
            [
                'title'       => 'Sistem Manajemen Toko',
                'description' => 'Aplikasi kasir dan manajemen inventori untuk UMKM dengan fitur laporan penjualan real-time.',
                'url'         => null,
                'tech_stack'  => 'Laravel, Vue.js, MySQL, Chart.js',
                'image'       => null,
            ],
            [
                'title'       => 'REST API E-Commerce',
                'description' => 'Backend API untuk aplikasi e-commerce mobile dengan autentikasi JWT, manajemen produk, dan integrasi payment.',
                'url'         => null,
                'tech_stack'  => 'Laravel Sanctum, MySQL, Redis',
                'image'       => null,
            ],
        ];

        WebsitePortfolio::where('personal_website_id', $website->id)->delete();
        foreach ($portfolios as $portfolio) {
            WebsitePortfolio::create(array_merge($portfolio, ['personal_website_id' => $website->id]));
        }

        // ── Testimonials ─────────────────────────────────────────────
        $testimonials = [
            [
                'client_name' => 'Ahmad Fauzi',
                'client_role' => 'CEO, Startup EdTech',
                'content'     => 'Saeful sangat profesional dan komunikatif. Proyek selesai tepat waktu dengan kualitas yang melebihi ekspektasi. Sangat direkomendasikan!',
                'avatar'      => null,
            ],
            [
                'client_name' => 'Rina Marliana',
                'client_role' => 'Pemilik Toko Online',
                'content'     => 'Sistem yang dibuat Saeful sangat memudahkan operasional toko saya. Responsif dan selalu siap membantu jika ada kendala.',
                'avatar'      => null,
            ],
        ];

        WebsiteTestimonial::where('personal_website_id', $website->id)->delete();
        foreach ($testimonials as $testimonial) {
            WebsiteTestimonial::create(array_merge($testimonial, ['personal_website_id' => $website->id]));
        }

        $this->command->info('✓ Personal website data untuk saeful2026027@gmail.com berhasil di-seed!');
        $this->command->info('  Username diupdate ke: saeful');
        $this->command->info('  Portfolio URL: http://localhost:8080/portfolio/saeful');
    }
}
