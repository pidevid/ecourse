<?php

namespace App\Http\Controllers\Api\Member;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\Course;
use App\Models\Exam;
use App\Models\ExamScore;
use App\Models\Review;
use App\Models\Showcase;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    // ────────────────────────────────────────────────────────────
    // GET /api/me — profil + stat dashboard
    // ────────────────────────────────────────────────────────────
    public function me(Request $request)
    {
        $user = $request->user();

        $enrolledCount    = TransactionDetail::whereHas('transaction', fn($q) =>
                                $q->where('user_id', $user->id)->where('status', 'settlement')
                            )->count();
        $courseCount      = Course::where('user_id', $user->id)->count();
        $transactionCount = Transaction::where('user_id', $user->id)->count();
        $certificateCount = Certificate::where('user_id', $user->id)->count();
        $showcaseCount    = Showcase::where('user_id', $user->id)->count();

        return response()->json([
            'profile' => [
                'id'                 => $user->id,
                'name'               => $user->name,
                'email'              => $user->email,
                'username'           => $user->username,
                'avatar'             => $user->avatar,
                'github'             => $user->github,
                'instagram'          => $user->instagram,
                'about'              => $user->about,
                'roles'              => $user->getRoleNames(),
                'has_website_access' => (bool) $user->has_website_access,
                'has_website'        => $user->personalWebsite !== null,
                'portfolio_url'      => $user->username ? url('/portfolio/' . $user->username) : null,
            ],
            'stats' => [
                'enrolled_courses' => $enrolledCount,
                'my_courses'       => $courseCount,
                'transactions'     => $transactionCount,
                'certificates'     => $certificateCount,
                'showcases'        => $showcaseCount,
            ],
        ]);
    }

    // ────────────────────────────────────────────────────────────
    // GET /api/me/enrolled — kelas yang sudah dibeli
    // ────────────────────────────────────────────────────────────
    public function enrolled(Request $request)
    {
        $user = $request->user();

        $details = TransactionDetail::with(['course.category', 'course.videos'])
            ->whereHas('transaction', fn($q) =>
                $q->where('user_id', $user->id)->where('status', 'settlement')
            )
            ->get();

        // Deduplicate jika kursus dibeli lebih dari sekali
        $courses = $details->unique('course_id')->map(fn($d) => [
            'id'           => $d->course->id,
            'name'         => $d->course->name,
            'slug'         => $d->course->slug,
            'image'        => $d->course->image,
            'category'     => $d->course?->category?->name,
            'total_videos' => $d->course->videos->count(),
            'paid_price'   => $d->price,
        ])->values();

        return response()->json(['data' => $courses]);
    }

    // ────────────────────────────────────────────────────────────
    // GET /api/me/enrolled/{courseId} — detail kelas + semua episode
    // ────────────────────────────────────────────────────────────
    public function enrolledDetail(Request $request, $courseId)
    {
        $user = $request->user();

        // Pastikan user memang sudah beli kursus ini
        $owns = TransactionDetail::whereHas('transaction', fn($q) =>
                    $q->where('user_id', $user->id)->where('status', 'settlement')
                )->where('course_id', $courseId)->exists();

        if (! $owns) {
            return response()->json(['message' => 'Anda belum memiliki akses ke kursus ini.'], 403);
        }

        $course = Course::with([
            'category',
            'videos' => fn($q) => $q->orderBy('episode'),
        ])->findOrFail($courseId);

        return response()->json([
            'id'          => $course->id,
            'name'        => $course->name,
            'slug'        => $course->slug,
            'image'       => $course->image,
            'description' => $course->description,
            'category'    => $course->category?->name,
            'episodes'    => $course->videos->map(fn($v) => [
                'id'         => $v->id,
                'episode'    => $v->episode,
                'name'       => $v->name,
                'video_code' => $v->video_code,
                'intro'      => (bool) $v->intro,
                'teori'      => $v->teori,
            ]),
        ]);
    }

    // ────────────────────────────────────────────────────────────
    // GET /api/me/exams/{courseId} — soal ujian kursus yang dimiliki
    // ────────────────────────────────────────────────────────────
    public function exams(Request $request, $courseId)
    {
        $user = $request->user();

        $owns = TransactionDetail::whereHas('transaction', fn($q) =>
                    $q->where('user_id', $user->id)->where('status', 'settlement')
                )->where('course_id', $courseId)->exists();

        if (! $owns) {
            return response()->json(['message' => 'Anda belum memiliki akses ke kursus ini.'], 403);
        }

        $exams = Exam::where('course_id', $courseId)
            ->select('id', 'question', 'option1', 'option2', 'option3', 'option4')
            ->get();

        return response()->json(['data' => $exams]);
    }

    // ────────────────────────────────────────────────────────────
    // GET /api/me/courses — kursus yang dibuat (author/admin)
    // ────────────────────────────────────────────────────────────
    public function courses(Request $request)
    {
        $courses = Course::with('category')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get()
            ->map(fn($c) => [
                'id'           => $c->id,
                'name'         => $c->name,
                'slug'         => $c->slug,
                'image'        => $c->image,
                'price'        => $c->price,
                'discount'     => $c->discount,
                'status'       => $c->status,
                'category'     => $c->category?->name,
                'total_videos' => $c->videos()->count(),
                'created_at'   => $c->created_at,
            ]);

        return response()->json(['data' => $courses]);
    }

    // ────────────────────────────────────────────────────────────
    // GET /api/me/transactions — daftar transaksi
    // GET /api/me/transactions/{id} — detail transaksi
    // ────────────────────────────────────────────────────────────
    public function transactions(Request $request)
    {
        $transactions = Transaction::with('details.course')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get()
            ->map(fn($t) => $this->formatTransaction($t));

        return response()->json(['data' => $transactions]);
    }

    public function transactionDetail(Request $request, $id)
    {
        $transaction = Transaction::with('details.course')
            ->where('user_id', $request->user()->id)
            ->findOrFail($id);

        return response()->json($this->formatTransaction($transaction));
    }

    private function formatTransaction(Transaction $t): array
    {
        return [
            'id'          => $t->id,
            'invoice'     => $t->invoice,
            'name'        => $t->name,
            'grand_total' => $t->grand_total,
            'status'      => $t->status,
            'completed'   => (bool) $t->completed,
            'snap_token'  => $t->snap_token,
            'created_at'  => $t->created_at,
            'items'       => $t->details->map(fn($d) => [
                'course_id'   => $d->course_id,
                'course_name' => $d->course?->name,
                'course_slug' => $d->course?->slug,
                'price'       => $d->price,
            ]),
        ];
    }

    // ────────────────────────────────────────────────────────────
    // GET    /api/me/showcases
    // POST   /api/me/showcases
    // PUT    /api/me/showcases/{id}
    // DELETE /api/me/showcases/{id}
    // ────────────────────────────────────────────────────────────
    public function showcases(Request $request)
    {
        $showcases = Showcase::with('course')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get()
            ->map(fn($s) => $this->formatShowcase($s));

        return response()->json(['data' => $showcases]);
    }

    public function showcaseStore(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'link'        => 'required|url',
            'cover'       => 'required|image|max:2048',
            'course_id'   => 'nullable|exists:courses,id',
        ]);

        $cover = $request->file('cover');
        $cover->storeAs('public/showcases', $cover->hashName());

        $showcase = $request->user()->showcases()->create([
            'course_id'   => $request->course_id,
            'title'       => $request->title,
            'description' => $request->description,
            'link'        => $request->link,
            'cover'       => $cover->hashName(),
        ]);

        return response()->json($this->formatShowcase($showcase->load('course')), 201);
    }

    public function showcaseUpdate(Request $request, $id)
    {
        $showcase = Showcase::where('user_id', $request->user()->id)->findOrFail($id);

        $request->validate([
            'title'       => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'link'        => 'sometimes|url',
            'cover'       => 'sometimes|image|max:2048',
            'course_id'   => 'nullable|exists:courses,id',
        ]);

        $data = $request->only(['title', 'description', 'link', 'course_id']);

        if ($request->hasFile('cover')) {
            Storage::disk('local')->delete('public/showcases/' . basename($showcase->getRawOriginal('cover') ?? ''));
            $cover = $request->file('cover');
            $cover->storeAs('public/showcases', $cover->hashName());
            $data['cover'] = $cover->hashName();
        }

        $showcase->update($data);

        return response()->json($this->formatShowcase($showcase->load('course')));
    }

    public function showcaseDestroy(Request $request, $id)
    {
        $showcase = Showcase::where('user_id', $request->user()->id)->findOrFail($id);

        Storage::disk('local')->delete('public/showcases/' . basename($showcase->getRawOriginal('cover') ?? ''));
        $showcase->delete();

        return response()->json(['message' => 'Showcase deleted.']);
    }

    private function formatShowcase(Showcase $s): array
    {
        return [
            'id'          => $s->id,
            'title'       => $s->title,
            'description' => $s->description,
            'link'        => $s->link,
            'cover'       => $s->cover,
            'course_id'   => $s->course_id,
            'course_name' => $s->course?->name,
            'created_at'  => $s->created_at,
        ];
    }

    // ────────────────────────────────────────────────────────────
    // GET /api/me/certificates
    // GET /api/me/exam-scores
    // GET /api/me/reviews
    // GET /api/me/website
    // ────────────────────────────────────────────────────────────
    public function certificates(Request $request)
    {
        $certs = Certificate::with('course')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get()
            ->map(fn($c) => [
                'id'            => $c->id,
                'serial_number' => $c->serial_number,
                'score'         => $c->score,
                'course_name'   => $c->course?->name,
                'course_slug'   => $c->course?->slug,
                'file_url'      => $c->file_path ? url('sertifikat/' . $c->file_path) : null,
                'created_at'    => $c->created_at,
            ]);

        return response()->json(['data' => $certs]);
    }

    public function examScores(Request $request)
    {
        $scores = ExamScore::with('course')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get()
            ->map(fn($s) => [
                'id'          => $s->id,
                'score'       => $s->score,
                'passed'      => (bool) $s->passed,
                'course_name' => $s->course?->name,
                'course_slug' => $s->course?->slug,
                'created_at'  => $s->created_at,
            ]);

        return response()->json(['data' => $scores]);
    }

    public function reviews(Request $request)
    {
        $reviews = Review::with('course')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get()
            ->map(fn($r) => [
                'id'          => $r->id,
                'rating'      => $r->rating,
                'review'      => $r->review,
                'course_name' => $r->course?->name,
                'course_slug' => $r->course?->slug,
                'created_at'  => $r->created_at,
            ]);

        return response()->json(['data' => $reviews]);
    }

    public function website(Request $request)
    {
        $website = $request->user()->personalWebsite;

        if (! $website) {
            return response()->json(['message' => 'Belum ada personal website.'], 404);
        }

        $website->load([
            'profile', 'socialLinks', 'skills', 'services',
            'experiences', 'educations', 'portfolios', 'testimonials',
        ]);

        return response()->json($website);
    }
}
