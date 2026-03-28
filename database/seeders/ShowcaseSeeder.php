<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Showcase;
use App\Models\User;
use Illuminate\Database\Seeder;

class ShowcaseSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil member yang dibuat di ReviewSeeder
        $memberEmails = [
            'budi@member.com',
            'siti@member.com',
            'andi@member.com',
            'dewi@member.com',
            'rizky@member.com',
        ];
        $members = User::whereIn('email', $memberEmails)->get();

        if ($members->isEmpty()) {
            $this->command->warn('Member users not found. Run ReviewSeeder first.');
            return;
        }

        // Data showcase per course slug
        $showcaseData = [
            'belajar-laravel-dari-nol' => [
                ['title' => 'E-Commerce App dengan Laravel 10', 'link' => 'https://github.com/demo/laravel-ecommerce', 'desc' => 'Aplikasi e-commerce lengkap dengan fitur cart, checkout, payment gateway Midtrans, dan admin dashboard menggunakan AdminLTE.'],
                ['title' => 'Blog Platform Multi-User', 'link' => 'https://github.com/demo/laravel-blog', 'desc' => 'Platform blog dengan fitur multi-author, kategori, tag, komentar, dan SEO-friendly URL menggunakan Laravel 10.'],
                ['title' => 'Sistem Manajemen Inventori', 'link' => 'https://github.com/demo/laravel-inventory', 'desc' => 'Aplikasi manajemen stok barang dengan laporan, export Excel, dan notifikasi stok minimum menggunakan Laravel dan Spatie.'],
            ],
            'react-js-untuk-pemula' => [
                ['title' => 'Todo App dengan React Hooks', 'link' => 'https://github.com/demo/react-todo', 'desc' => 'Aplikasi todo list modern menggunakan React Hooks, Context API, dan local storage untuk persistensi data.'],
                ['title' => 'Dashboard Cuaca Real-time', 'link' => 'https://github.com/demo/react-weather', 'desc' => 'Aplikasi cuaca yang mengambil data dari OpenWeather API dengan fitur search kota dan tampilan forecast 7 hari.'],
                ['title' => 'Movie Database App', 'link' => 'https://github.com/demo/react-movies', 'desc' => 'Aplikasi pencarian film menggunakan TMDB API, React Router, dan Axios dengan fitur watchlist dan detail film.'],
            ],
            'flutter-complete-course' => [
                ['title' => 'Aplikasi Kasir Mobile', 'link' => 'https://github.com/demo/flutter-kasir', 'desc' => 'Aplikasi point of sale mobile dengan fitur produk, transaksi, laporan harian, dan cetak struk menggunakan Flutter dan SQLite.'],
                ['title' => 'Chat App dengan Firebase', 'link' => 'https://github.com/demo/flutter-chat', 'desc' => 'Aplikasi chat realtime dengan fitur room chat, kirim gambar, dan push notification menggunakan Flutter dan Firebase.'],
                ['title' => 'Fitness Tracker App', 'link' => 'https://github.com/demo/flutter-fitness', 'desc' => 'Aplikasi tracking olahraga harian dengan fitur log latihan, kalori, BMI calculator, dan statistik mingguan.'],
            ],
            'python-untuk-data-science' => [
                ['title' => 'Analisis Penjualan E-Commerce', 'link' => 'https://github.com/demo/ds-ecommerce', 'desc' => 'Analisis dataset penjualan e-commerce dengan visualisasi tren, segmentasi pelanggan, dan produk terlaris menggunakan Pandas dan Matplotlib.'],
                ['title' => 'Eksplorasi Data COVID-19', 'link' => 'https://github.com/demo/ds-covid', 'desc' => 'EDA dataset COVID-19 Indonesia dengan visualisasi interaktif menggunakan Plotly, termasuk heatmap provinsi dan tren harian.'],
                ['title' => 'Analisis Sentimen Twitter', 'link' => 'https://github.com/demo/ds-sentiment', 'desc' => 'Analisis sentimen tweet menggunakan TextBlob dan WordCloud untuk memvisualisasikan kata-kata yang paling sering muncul.'],
            ],
            'machine-learning-dengan-python' => [
                ['title' => 'Prediksi Harga Rumah Jakarta', 'link' => 'https://github.com/demo/ml-house-price', 'desc' => 'Model prediksi harga rumah di Jakarta menggunakan Random Forest dan XGBoost dengan deployment via Flask API.'],
                ['title' => 'Klasifikasi Gambar Buah', 'link' => 'https://github.com/demo/ml-fruit', 'desc' => 'Model klasifikasi 10 jenis buah menggunakan CNN dengan akurasi 94% dan antarmuka web sederhana untuk upload gambar.'],
                ['title' => 'Sistem Rekomendasi Film', 'link' => 'https://github.com/demo/ml-recommender', 'desc' => 'Sistem rekomendasi film berbasis collaborative filtering menggunakan dataset MovieLens dengan Scikit-learn.'],
            ],
            'ui-ux-design-dengan-figma' => [
                ['title' => 'Redesign App Transportasi Online', 'link' => 'https://www.figma.com/demo/transport-redesign', 'desc' => 'Redesign UI/UX aplikasi transportasi online dengan pendekatan design thinking, user research, dan prototype interaktif di Figma.'],
                ['title' => 'Design System untuk Fintech App', 'link' => 'https://www.figma.com/demo/fintech-design-system', 'desc' => 'Design system lengkap untuk aplikasi fintech mencakup komponen, typography, color palette, dan panduan penggunaan.'],
                ['title' => 'Landing Page SaaS Product', 'link' => 'https://www.figma.com/demo/saas-landing', 'desc' => 'Desain landing page produk SaaS dengan hero section, fitur, pricing, testimoni, dan CTA yang dioptimasi untuk konversi.'],
            ],
            'docker-kubernetes-fundamentals' => [
                ['title' => 'Microservices dengan Docker Compose', 'link' => 'https://github.com/demo/docker-microservices', 'desc' => 'Implementasi arsitektur microservices dengan 5 service (auth, product, order, payment, notification) menggunakan Docker Compose.'],
                ['title' => 'CI/CD Pipeline dengan GitHub Actions', 'link' => 'https://github.com/demo/k8s-cicd', 'desc' => 'Pipeline CI/CD otomatis dari push kode hingga deploy ke Kubernetes cluster menggunakan GitHub Actions dan Docker Hub.'],
                ['title' => 'Monitoring Stack dengan Prometheus & Grafana', 'link' => 'https://github.com/demo/k8s-monitoring', 'desc' => 'Setup monitoring lengkap untuk Kubernetes cluster menggunakan Prometheus, Grafana, dan Alertmanager dalam satu stack Docker Compose.'],
            ],
            'ethical-hacking-penetration-testing' => [
                ['title' => 'Vulnerable Web App Lab', 'link' => 'https://github.com/demo/vuln-lab', 'desc' => 'Laboratorium aplikasi web yang sengaja dibuat rentan untuk latihan penetration testing, mencakup SQLi, XSS, CSRF, dan IDOR.'],
                ['title' => 'Pentest Report Template', 'link' => 'https://github.com/demo/pentest-report', 'desc' => 'Template laporan penetration testing profesional dengan contoh temuan, CVSS scoring, dan rekomendasi remediasi dalam Bahasa Indonesia.'],
                ['title' => 'CTF Write-up Collection', 'link' => 'https://github.com/demo/ctf-writeups', 'desc' => 'Kumpulan write-up CTF (Capture The Flag) dari berbagai kompetisi dengan penjelasan step-by-step solusi setiap tantangan.'],
            ],
            'aws-cloud-practitioner' => [
                ['title' => 'Serverless API dengan AWS Lambda', 'link' => 'https://github.com/demo/aws-serverless', 'desc' => 'REST API serverless menggunakan AWS Lambda, API Gateway, DynamoDB, dan S3 dengan deployment via AWS SAM.'],
                ['title' => 'Static Website di S3 + CloudFront', 'link' => 'https://github.com/demo/aws-static-site', 'desc' => 'Deploy static website React ke S3 dengan distribusi CloudFront, custom domain Route 53, dan SSL certificate ACM.'],
                ['title' => 'Auto-scaling Web App di EC2', 'link' => 'https://github.com/demo/aws-autoscaling', 'desc' => 'Arsitektur web app scalable menggunakan EC2 Auto Scaling Group, Application Load Balancer, RDS Multi-AZ, dan CloudWatch alarms.'],
            ],
            'unity-game-development-2d' => [
                ['title' => 'Platformer Game 2D - Adventure Quest', 'link' => 'https://github.com/demo/unity-platformer', 'desc' => 'Game platformer 2D dengan 5 level, sistem nyawa, power-up, boss fight, dan animasi karakter lengkap menggunakan Unity 2D.'],
                ['title' => 'Tower Defense Game', 'link' => 'https://github.com/demo/unity-tower-defense', 'desc' => 'Game tower defense dengan 3 jenis menara, 5 gelombang musuh, sistem upgrade, dan leaderboard lokal menggunakan Unity.'],
                ['title' => 'Puzzle Game - Match 3', 'link' => 'https://github.com/demo/unity-match3', 'desc' => 'Game puzzle match-3 mirip Candy Crush dengan 20 level, sistem skor, animasi smooth, dan efek partikel menggunakan Unity 2D.'],
            ],
            'digital-marketing-masterclass' => [
                ['title' => 'Strategi SEO untuk UMKM', 'link' => 'https://docs.google.com/demo/seo-umkm', 'desc' => 'Dokumen strategi SEO lengkap untuk bisnis UMKM lokal mencakup keyword research, on-page optimization, dan link building.'],
                ['title' => 'Kampanye Google Ads - Toko Online', 'link' => 'https://docs.google.com/demo/google-ads', 'desc' => 'Case study kampanye Google Ads untuk toko online fashion dengan ROAS 4x lipat melalui optimasi keyword dan landing page.'],
                ['title' => 'Social Media Content Plan', 'link' => 'https://docs.google.com/demo/content-plan', 'desc' => 'Rencana konten media sosial 3 bulan untuk brand F&B dengan strategi Instagram, TikTok, dan engagement framework.'],
            ],
            'nodejs-express-rest-api' => [
                ['title' => 'REST API E-Commerce dengan Node.js', 'link' => 'https://github.com/demo/node-ecommerce-api', 'desc' => 'Backend API lengkap untuk aplikasi e-commerce dengan fitur auth JWT, produk, cart, order, dan payment integration.'],
                ['title' => 'Realtime Chat API dengan Socket.io', 'link' => 'https://github.com/demo/node-chat-api', 'desc' => 'Backend realtime chat menggunakan Node.js, Express, Socket.io, dan MongoDB dengan fitur private room dan pesan terbaca.'],
                ['title' => 'API Gateway dengan Rate Limiting', 'link' => 'https://github.com/demo/node-api-gateway', 'desc' => 'Implementasi API gateway dengan fitur rate limiting, caching Redis, logging Winston, dan dokumentasi Swagger otomatis.'],
            ],
        ];

        $courses  = Course::all()->keyBy('slug');
        $memberCount = $members->count();

        foreach ($showcaseData as $slug => $items) {
            $course = $courses[$slug] ?? null;
            if (!$course) continue;

            foreach ($items as $i => $item) {
                $user = $members[$i % $memberCount];

                $exists = Showcase::where('course_id', $course->id)
                    ->where('user_id', $user->id)
                    ->where('title', $item['title'])
                    ->exists();

                if ($exists) continue;

                Showcase::create([
                    'course_id'   => $course->id,
                    'user_id'     => $user->id,
                    'title'       => $item['title'],
                    'link'        => $item['link'],
                    'cover'       => 'default.jpg',
                    'description' => $item['desc'],
                ]);
            }
        }
    }
}
