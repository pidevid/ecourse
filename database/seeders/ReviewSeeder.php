<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        // Buat 5 member reviewer
        $members = [
            ['name' => 'Budi Santoso',   'username' => 'budi_santoso',   'email' => 'budi@member.com'],
            ['name' => 'Siti Rahayu',    'username' => 'siti_rahayu',    'email' => 'siti@member.com'],
            ['name' => 'Andi Pratama',   'username' => 'andi_pratama',   'email' => 'andi@member.com'],
            ['name' => 'Dewi Lestari',   'username' => 'dewi_lestari',   'email' => 'dewi@member.com'],
            ['name' => 'Rizky Firmansyah','username' => 'rizky_firmansyah','email' => 'rizky@member.com'],
        ];

        $memberRole = Role::where('name', 'member')->first();
        $memberUsers = [];

        foreach ($members as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name'     => $data['name'],
                    'username' => $data['username'],
                    'password' => bcrypt('member123'),
                ]
            );
            if ($memberRole && !$user->hasRole('member')) {
                $user->assignRole($memberRole);
            }
            $memberUsers[] = $user;
        }

        // Pool review text per rating
        $reviewPool = [
            5 => [
                'Kursus ini sangat luar biasa! Penjelasannya mudah dipahami dan langsung bisa dipraktikkan. Sangat recommended!',
                'Materi yang disajikan sangat lengkap dan terstruktur. Instruktur menjelaskan dengan sangat jelas. Worth every penny!',
                'Ini adalah kursus terbaik yang pernah saya ikuti. Dari nol sampai bisa dalam waktu singkat. Terima kasih!',
                'Sangat puas dengan kursus ini. Contoh kasus nyata membuat pembelajaran lebih mudah dipahami. 5 bintang!',
                'Konten berkualitas tinggi, penjelasan detail, dan support yang baik. Tidak menyesal membeli kursus ini.',
            ],
            4 => [
                'Kursus yang bagus dan informatif. Materi cukup lengkap meski ada beberapa topik yang bisa diperdalam lagi.',
                'Secara keseluruhan sangat memuaskan. Instruktur berpengalaman dan materi up-to-date. Sedikit kekurangan di bagian latihan.',
                'Pembelajaran yang menyenangkan dan terstruktur. Beberapa bagian perlu lebih banyak contoh praktis.',
                'Kursus yang solid dan berkualitas. Penjelasan jelas, cuma durasi per video agak panjang. Tetap recommended!',
                'Bagus sekali! Materi relevan dengan kebutuhan industri. Berharap ada lebih banyak project portfolio.',
            ],
            3 => [
                'Kursus cukup baik untuk pemula. Materi dasar sudah tercakup, tapi perlu diperdalam untuk level lanjut.',
                'Lumayan bagus tapi ada beberapa bagian yang kurang jelas. Butuh penjelasan lebih untuk konsep tertentu.',
                'Materi cukup, tapi penyampaian bisa lebih interaktif. Overall masih layak untuk belajar dasar-dasar.',
                'Standar untuk harga yang ditawarkan. Cocok untuk pemula tapi kurang untuk yang sudah berpengalaman.',
                'Cukup membantu sebagai pengantar. Harap ada update materi karena beberapa bagian sudah sedikit outdated.',
            ],
        ];

        $courses = Course::all();

        foreach ($courses as $course) {
            // Buat 5 review per course (1 per member)
            $ratings = [5, 5, 4, 4, 3];
            shuffle($ratings);

            foreach ($memberUsers as $index => $user) {
                // Cegah duplikat
                $exists = Review::where('course_id', $course->id)
                    ->where('user_id', $user->id)
                    ->exists();

                if ($exists) continue;

                $rating = $ratings[$index];
                $texts  = $reviewPool[$rating];

                Review::create([
                    'course_id' => $course->id,
                    'user_id'   => $user->id,
                    'rating'    => $rating,
                    'review'    => $texts[array_rand($texts)],
                ]);
            }
        }
    }
}
