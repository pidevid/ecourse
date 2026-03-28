<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Video;
use Illuminate\Database\Seeder;

class VideoSeeder extends Seeder
{
    public function run(): void
    {
        // Dummy YouTube video codes (placeholder)
        $videoCodes = [
            'dQw4w9WgXcQ',
            'ScMzIvxBSi4',
            'jNQXAC9IVRw',
            'kJQP7kiw5Fk',
            '9bZkp7q19f0',
            'hT_nvWreIhg',
            'OPf0YbXqDm0',
            'RgKAFK5djSk',
            'JGwWNGJdvx8',
            'CevxZvSJLk8',
        ];

        // Episode titles per course slug
        $episodeTitles = [
            'belajar-laravel-dari-nol' => [
                'Pengenalan Laravel & Instalasi',
                'Routing & Controller Dasar',
                'Blade Template Engine',
                'Eloquent ORM & Migration',
                'Relasi Database (hasMany, belongsTo)',
                'Authentication dengan Laravel Breeze',
                'Middleware & Authorization',
                'File Upload & Storage',
                'REST API dengan Laravel',
                'Deployment ke Shared Hosting',
            ],
            'react-js-untuk-pemula' => [
                'Pengenalan React & JSX',
                'Component & Props',
                'State dengan useState Hook',
                'useEffect & Lifecycle',
                'Event Handling di React',
                'React Router DOM',
                'Fetch API & Axios',
                'Context API',
                'React Query untuk Data Fetching',
                'Build & Deploy React App',
            ],
            'flutter-complete-course' => [
                'Pengenalan Flutter & Dart',
                'Widget Dasar: Text, Container, Row, Column',
                'Stateful vs Stateless Widget',
                'Navigator & Routing',
                'Form & Validasi Input',
                'HTTP Request dengan Dio',
                'State Management dengan Provider',
                'Local Database dengan SQLite',
                'Push Notification dengan Firebase',
                'Publish App ke Play Store',
            ],
            'python-untuk-data-science' => [
                'Pengenalan Python & Environment Setup',
                'Tipe Data & Struktur Data Python',
                'NumPy untuk Komputasi Numerik',
                'Pandas: DataFrame & Series',
                'Membaca Data CSV & Excel',
                'Data Cleaning & Preprocessing',
                'Visualisasi dengan Matplotlib',
                'Visualisasi dengan Seaborn',
                'Exploratory Data Analysis (EDA)',
                'Studi Kasus: Analisis Data E-Commerce',
            ],
            'machine-learning-dengan-python' => [
                'Pengenalan Machine Learning',
                'Supervised vs Unsupervised Learning',
                'Linear Regression',
                'Logistic Regression & Klasifikasi',
                'Decision Tree & Random Forest',
                'Support Vector Machine (SVM)',
                'K-Nearest Neighbor (KNN)',
                'K-Means Clustering',
                'Evaluasi Model: Accuracy, Precision, Recall',
                'Studi Kasus: Prediksi Harga Rumah',
            ],
            'ui-ux-design-dengan-figma' => [
                'Pengenalan UI/UX & Design Thinking',
                'Mengenal Interface Figma',
                'Frame, Layer & Komponen Dasar',
                'Typography & Color System',
                'Design Grid & Layout',
                'Auto Layout di Figma',
                'Component & Variant',
                'Prototyping & Interaksi',
                'Design System dari Nol',
                'Handoff ke Developer',
            ],
            'docker-kubernetes-fundamentals' => [
                'Pengenalan Container & Docker',
                'Instalasi Docker & Perintah Dasar',
                'Dockerfile: Build Custom Image',
                'Docker Volumes & Networking',
                'Docker Compose Multi-Container',
                'Pengenalan Kubernetes (K8s)',
                'Pod, Deployment & Service',
                'ConfigMap & Secret',
                'Ingress Controller',
                'CI/CD dengan Docker & GitHub Actions',
            ],
            'ethical-hacking-penetration-testing' => [
                'Pengenalan Ethical Hacking & Hukum',
                'Reconnaissance & Information Gathering',
                'Scanning dengan Nmap',
                'Vulnerability Assessment',
                'Eksploitasi dengan Metasploit',
                'Web Application Attack: SQL Injection',
                'Cross-Site Scripting (XSS)',
                'Privilege Escalation',
                'Post Exploitation',
                'Laporan Penetration Testing',
            ],
            'aws-cloud-practitioner' => [
                'Pengenalan Cloud Computing & AWS',
                'AWS Global Infrastructure',
                'IAM: User, Role & Policy',
                'Amazon EC2 & Auto Scaling',
                'Amazon S3 & Storage Services',
                'Amazon RDS & Database Services',
                'VPC & Networking di AWS',
                'AWS Lambda & Serverless',
                'Monitoring dengan CloudWatch',
                'Latihan Soal Sertifikasi AWS CCP',
            ],
            'unity-game-development-2d' => [
                'Pengenalan Unity & Interface',
                'GameObject, Component & Transform',
                'Sprite & Animasi 2D',
                'Physics 2D: Rigidbody & Collider',
                'Scripting dengan C# Dasar',
                'Input System & Kontrol Karakter',
                'Tilemap & Level Design',
                'UI: Health Bar, Score & Menu',
                'Audio Manager & Sound Effect',
                'Build & Export ke Android/PC',
            ],
            'digital-marketing-masterclass' => [
                'Pengenalan Digital Marketing',
                'SEO On-Page & Off-Page',
                'Google Search Console & Analytics',
                'Google Ads: Search Campaign',
                'Facebook & Instagram Ads',
                'Content Marketing Strategy',
                'Email Marketing dengan Mailchimp',
                'Social Media Management',
                'Conversion Rate Optimization',
                'Studi Kasus: Campaign dari Nol',
            ],
            'nodejs-express-rest-api' => [
                'Pengenalan Node.js & NPM',
                'Express.js: Routing & Middleware',
                'Koneksi MongoDB dengan Mongoose',
                'CRUD REST API',
                'Authentication JWT',
                'Input Validation dengan Joi',
                'Upload File dengan Multer',
                'Error Handling & Logging',
                'Testing API dengan Jest',
                'Deploy ke Railway/Heroku',
            ],
        ];

        $courses = Course::all();

        foreach ($courses as $course) {
            $titles = $episodeTitles[$course->slug] ?? null;

            for ($ep = 1; $ep <= 10; $ep++) {
                $title = $titles[$ep - 1] ?? "Episode {$ep}: Materi {$course->name}";

                Video::firstOrCreate(
                    [
                        'course_id' => $course->id,
                        'episode'   => $ep,
                    ],
                    [
                        'name'       => $title,
                        'intro'      => $ep === 1 ? 1 : 0,
                        'video_code' => $videoCodes[($ep - 1) % count($videoCodes)],
                        'teori'      => "Pada episode ini kita akan membahas: {$title}. Ikuti materi dengan seksama dan praktikkan langsung agar lebih cepat memahami konsep yang diajarkan.",
                        'status'     => 'approved',
                    ]
                );
            }
        }
    }
}
