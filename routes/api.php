<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\NotificationHandler;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PublicController;
use App\Http\Controllers\Api\Member\UserController;
use App\Http\Controllers\Api\Member\CheckoutApiController;
use App\Http\Controllers\Api\Member\WebsiteApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Midtrans notification webhook (no auth)
Route::post('notification/handler', NotificationHandler::class)->name('notification.handler');

// ╔══════════════════════════════════════════════════════════════╗
// ║  PUBLIC — siapa pun bisa akses (guest & login)              ║
// ╚══════════════════════════════════════════════════════════════╝

// Kategori
Route::get('categories', [PublicController::class, 'categories']);

// Kursus catalog
Route::get('courses',        [PublicController::class, 'courses']);
Route::get('courses/{slug}', [PublicController::class, 'courseDetail']);

// Showcase publik
Route::get('showcases', [PublicController::class, 'showcases']);

// Portfolio user publik
Route::get('portfolio/{username}', [PublicController::class, 'portfolio']);

// ╔══════════════════════════════════════════════════════════════╗
// ║  AUTH                                                        ║
// ╚══════════════════════════════════════════════════════════════╝

Route::prefix('auth')->group(function () {
    Route::post('login',  [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
});

// ╔══════════════════════════════════════════════════════════════╗
// ║  PRIVATE — harus login (Bearer token)                       ║
// ╚══════════════════════════════════════════════════════════════╝

Route::middleware('auth:sanctum')->prefix('me')->group(function () {

    // Profil + stats dashboard
    Route::get('/', [UserController::class, 'me']);

    // Kelas yang sudah dibeli (enrolled)
    Route::get('enrolled',      [UserController::class, 'enrolled']);
    Route::get('enrolled/{id}', [UserController::class, 'enrolledDetail']);

    // Soal ujian (harus sudah beli kursusnya)
    Route::get('exams/{courseId}', [UserController::class, 'exams']);

    // Kursus yang dibuat sendiri (author/admin)
    Route::get('courses', [UserController::class, 'courses']);

    // Transaksi
    Route::get('transactions',      [UserController::class, 'transactions']);
    Route::get('transactions/{id}', [UserController::class, 'transactionDetail']);

    // Cart & Checkout
    Route::get('cart',               [CheckoutApiController::class, 'cart']);
    Route::post('cart/{courseId}',   [CheckoutApiController::class, 'cartAdd']);
    Route::delete('cart/{cartId}',   [CheckoutApiController::class, 'cartRemove']);
    Route::post('checkout',          [CheckoutApiController::class, 'checkout']);

    // Sertifikat
    Route::get('certificates', [UserController::class, 'certificates']);

    // Hasil ujian
    Route::get('exam-scores', [UserController::class, 'examScores']);

    // Review
    Route::get('reviews', [UserController::class, 'reviews']);

    // Showcase (CRUD)
    Route::get('showcases',         [UserController::class, 'showcases']);
    Route::post('showcases',        [UserController::class, 'showcaseStore']);
    Route::post('showcases/{id}',   [UserController::class, 'showcaseUpdate']);
    Route::delete('showcases/{id}', [UserController::class, 'showcaseDestroy']);

    // Personal website — semua section sekaligus
    Route::get('website', [UserController::class, 'website']);

    // Personal website — per section
    Route::prefix('website')->group(function () {
        Route::get('profile',      [WebsiteApiController::class, 'profile']);
        Route::get('skills',       [WebsiteApiController::class, 'skills']);
        Route::get('services',     [WebsiteApiController::class, 'services']);
        Route::get('experiences',  [WebsiteApiController::class, 'experiences']);
        Route::get('educations',   [WebsiteApiController::class, 'educations']);
        Route::get('portfolios',   [WebsiteApiController::class, 'portfolios']);
        Route::get('testimonials', [WebsiteApiController::class, 'testimonials']);
        Route::get('social-links', [WebsiteApiController::class, 'socialLinks']);
    });
});
