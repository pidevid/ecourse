<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Course;
use App\Models\Review;
use App\Models\Showcase;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    // ────────────────────────────────────────────────────────────
    // GET /api/categories
    // ────────────────────────────────────────────────────────────
    public function categories()
    {
        $categories = Category::withCount('courses')->latest()->get()->map(fn($c) => [
            'id'           => $c->id,
            'name'         => $c->name,
            'slug'         => $c->slug,
            'image'        => $c->image,
            'total_courses'=> $c->courses_count,
        ]);

        return response()->json(['data' => $categories]);
    }

    // ────────────────────────────────────────────────────────────
    // GET /api/courses?search=&category=
    // ────────────────────────────────────────────────────────────
    public function courses(Request $request)
    {
        $query = Course::with('category', 'user')
            ->withCount('videos', 'reviews')
            ->withCount(['details as enrolled' => fn($q) =>
                $q->whereHas('transaction', fn($tq) => $tq->where('status', 'settlement'))
            ])
            ->approved()
            ->latest();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category')) {
            $query->whereHas('category', fn($q) => $q->where('slug', $request->category));
        }

        $courses = $query->get()->map(fn($c) => $this->formatCourse($c));

        return response()->json(['data' => $courses]);
    }

    // ────────────────────────────────────────────────────────────
    // GET /api/courses/{slug}
    // ────────────────────────────────────────────────────────────
    public function courseDetail(Request $request, $slug)
    {
        $course = Course::with(['category', 'user', 'reviews.user'])
            ->where('slug', $slug)
            ->approved()
            ->firstOrFail();

        // Hitung enrolled
        $enrolled = Transaction::whereHas('details', fn($q) => $q->where('course_id', $course->id))
            ->where('status', 'settlement')
            ->count();

        // Episode: free = intro=0, premium = intro=1
        // Guest & non-buyer hanya lihat metadata episode (tanpa video_code premium)
        $videos = $course->videos()->approved()->orderBy('episode')->get()->map(fn($v) => [
            'id'         => $v->id,
            'episode'    => $v->episode,
            'name'       => $v->name,
            'is_free'    => $v->intro == 0,
            'video_code' => $v->intro == 0 ? $v->video_code : null, // premium dikunci
            'duration'   => null,
        ]);

        $avgRating = $course->reviews->avg('rating');
        $reviews   = $course->reviews->map(fn($r) => [
            'id'         => $r->id,
            'rating'     => $r->rating,
            'review'     => $r->review,
            'user_name'  => $r->user?->name,
            'user_avatar'=> $r->user?->avatar,
            'created_at' => $r->created_at,
        ]);

        return response()->json([
            'id'          => $course->id,
            'name'        => $course->name,
            'slug'        => $course->slug,
            'image'       => $course->image,
            'description' => $course->description,
            'price'       => $course->price,
            'discount'    => $course->discount,
            'category'    => $course->category?->name,
            'author'      => [
                'name'   => $course->user?->name,
                'avatar' => $course->user?->avatar,
            ],
            'enrolled'    => $enrolled,
            'avg_rating'  => round($avgRating, 1),
            'total_reviews' => $course->reviews->count(),
            'total_episodes'=> $videos->count(),
            'episodes'    => $videos,
            'reviews'     => $reviews,
        ]);
    }

    // ────────────────────────────────────────────────────────────
    // GET /api/showcases?search=
    // ────────────────────────────────────────────────────────────
    public function showcases(Request $request)
    {
        $query = Showcase::with(['user', 'course'])->latest();

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $showcases = $query->get()->map(fn($s) => [
            'id'          => $s->id,
            'title'       => $s->title,
            'description' => $s->description,
            'link'        => $s->link,
            'cover'       => $s->cover,
            'course_name' => $s->course?->name,
            'user_name'   => $s->user?->name,
            'user_avatar' => $s->user?->avatar,
            'created_at'  => $s->created_at,
        ]);

        return response()->json(['data' => $showcases]);
    }

    // ────────────────────────────────────────────────────────────
    // GET /api/portfolio/{username}
    // ────────────────────────────────────────────────────────────
    public function portfolio($username)
    {
        $user = User::where('username', $username)->firstOrFail();
        $website = $user->personalWebsite;

        if (! $website || ! $website->is_published) {
            return response()->json(['message' => 'Portfolio tidak ditemukan.'], 404);
        }

        $website->load([
            'profile', 'socialLinks', 'skills', 'services',
            'experiences', 'educations', 'portfolios', 'testimonials',
        ]);

        return response()->json([
            'username'    => $user->username,
            'theme'       => $website->theme,
            'accent_color'=> $website->accent_color,
            'font_family' => $website->font_family,
            'meta_title'  => $website->meta_title,
            'meta_description' => $website->meta_description,
            'profile'     => $website->profile,
            'social_links'=> $website->socialLinks,
            'skills'      => $website->skills,
            'services'    => $website->services,
            'experiences' => $website->experiences,
            'educations'  => $website->educations,
            'portfolios'  => $website->portfolios,
            'testimonials'=> $website->testimonials,
        ]);
    }

    private function formatCourse(Course $c): array
    {
        return [
            'id'             => $c->id,
            'name'           => $c->name,
            'slug'           => $c->slug,
            'image'          => $c->image,
            'price'          => $c->price,
            'discount'       => $c->discount,
            'category'       => $c->category?->name,
            'author'         => $c->user?->name,
            'total_videos'   => $c->videos_count,
            'total_reviews'  => $c->reviews_count,
            'enrolled'       => $c->enrolled,
            'created_at'     => $c->created_at,
        ];
    }
}
