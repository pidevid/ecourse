<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\NotificationHandler;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\SocialAuthController;
use App\Http\Controllers\Api\PublicController;
use App\Http\Controllers\Api\Member\UserController;
use App\Http\Controllers\Api\Member\CheckoutApiController;
use App\Http\Controllers\Api\Member\WebsiteApiController;

// Midtrans notification webhook (no auth)
Route::post('notification/handler', NotificationHandler::class)->name('notification.handler');

// ╔══════════════════════════════════════════════════════════════╗
// ║  PUBLIC                                                      ║
// ╚══════════════════════════════════════════════════════════════╝
Route::get('categories',           [PublicController::class, 'categories']);
Route::get('courses',              [PublicController::class, 'courses']);
Route::get('courses/{slug}',       [PublicController::class, 'courseDetail']);
Route::get('showcases',            [PublicController::class, 'showcases']);
Route::get('portfolio/{username}', [PublicController::class, 'portfolio']);

// ╔══════════════════════════════════════════════════════════════╗
// ║  AUTH                                                        ║
// ╚══════════════════════════════════════════════════════════════╝
Route::prefix('auth')->group(function () {

    Route::post('register', [AuthController::class, 'register']);
    Route::post('login',    [AuthController::class, 'login']);

    // Email verification (link dari email — pakai signed URL)
    Route::get('verify-email/{id}/{hash}', [AuthController::class, 'verifyEmail'])
        ->middleware('signed')
        ->name('verification.verify');

    // Forgot & Reset password
    Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('reset-password',  [AuthController::class, 'resetPassword']);

    // Social OAuth — browser redirect ke Google/GitHub
    Route::get('{provider}/redirect', [SocialAuthController::class, 'redirect']);
    Route::get('{provider}/callback', [SocialAuthController::class, 'callback']);

    // Social OAuth — Next.js kirim access_token langsung (tanpa redirect)
    Route::post('{provider}/token', [SocialAuthController::class, 'tokenLogin']);

    // Perlu login
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout',       [AuthController::class, 'logout']);
        Route::get('me',            [AuthController::class, 'me']);
        Route::post('email/resend', [AuthController::class, 'resendVerification']);
    });
});

// ╔══════════════════════════════════════════════════════════════╗
// ║  PRIVATE — Bearer token required                            ║
// ╚══════════════════════════════════════════════════════════════╝
Route::middleware('auth:sanctum')->prefix('me')->group(function () {

    Route::get('/', [UserController::class, 'me']);

    Route::get('enrolled',         [UserController::class, 'enrolled']);
    Route::get('enrolled/{id}',    [UserController::class, 'enrolledDetail']);
    Route::get('exams/{courseId}', [UserController::class, 'exams']);

    Route::get('courses', [UserController::class, 'courses']);

    Route::get('transactions',      [UserController::class, 'transactions']);
    Route::get('transactions/{id}', [UserController::class, 'transactionDetail']);

    Route::get('cart',               [CheckoutApiController::class, 'cart']);
    Route::post('cart/{courseId}',   [CheckoutApiController::class, 'cartAdd']);
    Route::delete('cart/{cartId}',   [CheckoutApiController::class, 'cartRemove']);
    Route::post('checkout',          [CheckoutApiController::class, 'checkout']);

    Route::get('certificates', [UserController::class, 'certificates']);
    Route::get('exam-scores',  [UserController::class, 'examScores']);
    Route::get('reviews',      [UserController::class, 'reviews']);

    Route::get('showcases',         [UserController::class, 'showcases']);
    Route::post('showcases',        [UserController::class, 'showcaseStore']);
    Route::post('showcases/{id}',   [UserController::class, 'showcaseUpdate']);
    Route::delete('showcases/{id}', [UserController::class, 'showcaseDestroy']);

    Route::get('website', [UserController::class, 'website']);
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
