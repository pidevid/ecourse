<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VideoController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Landing\CartController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\MyCourseController;
use App\Http\Controllers\Admin\ShowcaseController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ExamController;
use App\Http\Controllers\Landing\CheckoutController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\NotificationDatabaseController;
use App\Http\Controllers\Admin\CertificateController;
use App\Http\Controllers\Landing\CertificateController as LandingCertificateController;
use App\Http\Controllers\Member\VideoController as MemberVideoController;
use App\Http\Controllers\Member\ExamController as MemberExamController;
use App\Http\Controllers\Member\CourseController as MemberCourseController;
use App\Http\Controllers\Member\ReviewController as MemberReviewController;
use App\Http\Controllers\Landing\CourseController as LandingCourseController;
use App\Http\Controllers\Landing\ExamController as LandingExamController;
use App\Http\Controllers\Landing\ReviewController as LandingReviewController;
use App\Http\Controllers\Member\ProfileController as MemberProfileController;
use App\Http\Controllers\Member\MyCourseController as MemberMyCourseController;
use App\Http\Controllers\Member\ShowcaseController as MemberShowcaseController;
use App\Http\Controllers\Landing\CategoryController as LandingCategoryController;
use App\Http\Controllers\Landing\ShowcaseController as LandingShowcaseController;
use App\Http\Controllers\Member\DashboardController as MemberDashboardController;
use App\Http\Controllers\Member\TransactionController as MemberTransactionController;
// web.php

use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\Member\CKEditorController;
use App\Http\Controllers\Landing\PortfolioController;
use App\Http\Controllers\Member\WebsiteController;
use App\Http\Controllers\Member\WebsitePaymentController;


Route::get('/auth/{provider}', [SocialiteController::class, 'redirectToProvider']);
Route::get('/auth/{provider}/callback', [SocialiteController::class, 'handleProviderCallback']);
Route::fallback(function () {
    return view('errors-404');
});


// Public portfolio route
Route::get('/portfolio/{username}', [PortfolioController::class, 'show'])->name('portfolio.show');

// Midtrans website payment callback (outside auth, CSRF exempt)
Route::post('/website/payment/callback', [WebsitePaymentController::class, 'callback'])
    ->name('website.payment.callback')
    ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

Route::get('/', [HomeController::class, '__invoke'])
    ->name('home')
    ->middleware('verified');


Route::view('/tim', 'landing.tim')->name('tim')->middleware('verified');

Route::view('/faq', 'landing.faq')->name('faq')->middleware('verified');

Route::view('/tentang', 'landing.tentang')->name('tentang')->middleware('verified');
// course route
Route::middleware('verified')->group(function () {
    Route::controller(LandingCourseController::class)
        ->as('course.')
        ->group(function () {
            Route::get('/course', 'index')->name('index');
            Route::get('/course/{course:slug}/detail', 'show')->name('show');
            Route::get('/course/{course:slug}/pertemuan/{video:episode}', 'video')->name('video');
        });
});
Route::middleware('verified')->group(function () {
    Route::get('/certificates/{user}/{course}/{serialNumber}', [LandingCertificateController::class, 'show'])->name('Landing.certificates.show');

    Route::get('/certificates/{user}/{course}/{serialNumber}/Sertificates Unusia Course', [LandingCertificateController::class, 'generatePDF'])->name('Landing.certificates.pdf');
});

Route::middleware('verified')->group(function () {
    Route::controller(LandingExamController::class)
        ->as('exams.') // Prefix nama rute yang benar
        ->group(function () {
            Route::get('/course/{course:slug}/soal', 'exam')->name('exam');
            Route::get('/course/{course:slug}/exam/detail', 'examDetail')->name('examDetail');
            Route::post('/course/{course:slug}/submit-exam', 'submitExam')->name('submitExam');
        });
});

Route::middleware(['verified'])->group(function () {
    Route::get('/category/{category:slug}', LandingCategoryController::class)->name('category');
    Route::get('/review', LandingReviewController::class)->name('review');
    Route::get('/showcase', LandingShowcaseController::class)->name('showcase');
});

Route::controller(CartController::class)
    ->middleware('auth')
    ->as('cart.')
    ->group(function () {
        Route::get('/cart', 'index')->name('index');
        Route::post('/cart/{course}', 'store')->name('store');
        Route::delete('/cart/{cart}', 'delete')->name('destroy');
    });
// checkout route
Route::get('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

// admin route
Route::group(['as' => 'admin.', 'prefix' => 'admin', 'middleware' => ['auth', 'role:admin', 'verified']], function () {
    // admin dashboard route
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    // admin marknotification route
    Route::controller(NotificationDatabaseController::class)->group(function () {
        Route::post('/mark-as-read/{id}', 'readNotification')->name('markNotification');
        Route::post('/mark-all-read', 'readAllNotification')->name('markAllRead');
    });
    Route::prefix('sertifikat')
        ->name('sertifikat.')
        ->group(function () {
            Route::get('/{course:slug}', [CertificateController::class, 'index'])->name('index');
            Route::get('/detail/{exam_score}', [CertificateController::class, 'show'])->name('show');
        });

    // admin category route
    Route::resource('/category', CategoryController::class);
    // admin course route
    Route::resource('/course', CourseController::class);

    Route::get('/my-course', MyCourseController::class)->name('mycourse');
    // admin showcase route
    Route::get('/showcase', ShowcaseController::class)->name('showcase.index');
    // admin review route
    Route::controller(ReviewController::class)->group(function () {
        Route::get('/review', 'index')->name('review.index');
        Route::post('/review/{course}', 'store')->name('review');
    });
    //admin user route
    Route::controller(UserController::class)
        ->as('user.')
        ->group(function () {
            Route::get('/user/profile', 'profile')->name('profile');
            Route::put('/user/profile/{user}', 'profileUpdate')->name('profile.update');
            Route::get('/user/profile/password/{user}', 'profile')->name('profile.password');
        });
    Route::resource('/user', UserController::class)->only('index', 'update', 'destroy');

    Route::controller(ExamController::class)
        ->as('exams.')
        ->group(function () {
            Route::get('/{course:slug}/exams', 'index')->name('index');
            Route::get('/{course:slug}/exams/show', 'show')->name('show');
            Route::get('/{course:slug}/exams/create', 'create')->name('create');
            Route::post('/{course:slug}/exams/store', 'store')->name('store');
            Route::get('/edit/{course:slug}/exams/{exam}', 'edit')->name('edit');
            Route::put('/update/{course:slug}/exams/{exam}', 'update')->name('update');
            Route::delete('/delete/{exam}', 'destroy')->name('destroy');
        });
    // admin course approve/reject
    Route::patch('/course/{course}/approve', [CourseController::class, 'approve'])->name('course.approve');
    Route::patch('/course/{course}/reject', [CourseController::class, 'reject'])->name('course.reject');

    // admin video route
    Route::controller(VideoController::class)
        ->as('video.')
        ->group(function () {
            Route::get('/{course:slug}/video', 'index')->name('index');
            Route::get('/{course:slug}/create', 'create')->name('create');
            Route::post('/{course:slug}/store', 'store')->name('store');
            Route::get('/edit/{course:slug}/{video}', 'edit')->name('edit');
            Route::put('/update/{course:slug}/{video}', 'update')->name('update');
            Route::delete('/delete/{video}', 'destroy')->name('destroy');
            Route::patch('/video/{video}/approve', 'approve')->name('approve');
            Route::patch('/video/{video}/reject', 'reject')->name('reject');
        });
    // admin transaction route
    Route::resource('/transaction', TransactionController::class)->only('index', 'show');
});

// member route
Route::group(['as' => 'member.', 'prefix' => 'account', 'middleware' => ['auth', 'role:member|author|admin', 'verified']], function () {
    // member dashboard route
    Route::get('/dashboard', MemberDashboardController::class)->name('dashboard');
    // member course route
    Route::get('/my-course', MemberMyCourseController::class)->name('mycourse');
    Route::resource('/course', MemberCourseController::class)->middleware('role:author');
    // member showcase route
    Route::resource('/showcase', MemberShowcaseController::class);

    Route::controller(MemberExamController::class)
        ->as('exams.')
        ->middleware('role:author')
        ->group(function () {
            Route::get('/{course:slug}/exams', 'index')->name('index');
            Route::get('/{course:slug}/exams/create', 'create')->name('create');
            Route::post('/{course:slug}/exams/store', 'store')->name('store');
            Route::get('/edit/{course:slug}/exams/{exam}', 'edit')->name('edit');
            Route::put('/update/{course:slug}/exams/{exam}', 'update')->name('update');
            Route::delete('/delete/{exam}', 'destroy')->name('destroy');
        });
    // member video route
    Route::controller(MemberVideoController::class)
        ->as('video.')
        ->middleware('role:author')
        ->group(function () {
            Route::get('/{course:slug}/video', 'index')->name('index');
            Route::get('/{course:slug}/create', 'create')->name('create');
            Route::post('/{course:slug}/store', 'store')->name('store');
            Route::get('/edit/{course:slug}/{video}', 'edit')->name('edit');
            Route::put('/update/{course:slug}/{video}', 'update')->name('update');
            Route::delete('/delete/{video}', 'destroy')->name('destroy');
        });
    // member review route
    Route::post('/review/{course}', [MemberReviewController::class, 'store'])->name('review');
    // CKEditor image upload
    Route::post('/ckeditor/upload', [CKEditorController::class, 'upload'])->name('ckeditor.upload');
    // member transaction route
    Route::resource('/transaction', MemberTransactionController::class)->only('index', 'show');
    // member profile route
    Route::controller(MemberProfileController::class)
        ->as('profile.')
        ->group(function () {
            Route::get('/profile', 'index')->name('index');
            Route::put('/profile/{user}', 'updateProfile')->name('update');
            Route::put('/profile/password/{user}', 'updatePassword')->name('password');
        });

    // ── Personal Website routes ───────────────────────────────────────────────
    Route::prefix('website')->as('website.')->group(function () {
        // Buy page — accessible before website.access middleware
        Route::get('/buy', [WebsitePaymentController::class, 'index'])->name('buy');
        Route::post('/buy/checkout', [WebsitePaymentController::class, 'checkout'])->name('checkout');

        // Protected by website.access middleware
        Route::middleware('website.access')->group(function () {
            Route::get('/', [WebsiteController::class, 'index'])->name('index');

            // Settings
            Route::get('/settings', [WebsiteController::class, 'settings'])->name('settings');
            Route::post('/settings', [WebsiteController::class, 'saveSettings'])->name('settings.save');

            // Profile
            Route::get('/profile', [WebsiteController::class, 'profile'])->name('profile');
            Route::post('/profile', [WebsiteController::class, 'saveProfile'])->name('profile.save');

            // Skills
            Route::get('/skills', [WebsiteController::class, 'skills'])->name('skills');
            Route::post('/skills', [WebsiteController::class, 'storeSkill'])->name('skills.store');
            Route::delete('/skills/{skill}', [WebsiteController::class, 'destroySkill'])->name('skills.destroy');

            // Services
            Route::get('/services', [WebsiteController::class, 'services'])->name('services');
            Route::post('/services', [WebsiteController::class, 'storeService'])->name('services.store');
            Route::delete('/services/{service}', [WebsiteController::class, 'destroyService'])->name('services.destroy');

            // Experience
            Route::get('/experience', [WebsiteController::class, 'experience'])->name('experience');
            Route::post('/experience', [WebsiteController::class, 'storeExperience'])->name('experience.store');
            Route::delete('/experience/{experience}', [WebsiteController::class, 'destroyExperience'])->name('experience.destroy');

            // Education
            Route::get('/education', [WebsiteController::class, 'education'])->name('education');
            Route::post('/education', [WebsiteController::class, 'storeEducation'])->name('education.store');
            Route::delete('/education/{education}', [WebsiteController::class, 'destroyEducation'])->name('education.destroy');

            // Portfolio
            Route::get('/portfolio', [WebsiteController::class, 'portfolio'])->name('portfolio');
            Route::post('/portfolio', [WebsiteController::class, 'storePortfolio'])->name('portfolio.store');
            Route::delete('/portfolio/{portfolio}', [WebsiteController::class, 'destroyPortfolio'])->name('portfolio.destroy');

            // Testimonials
            Route::get('/testimonials', [WebsiteController::class, 'testimonials'])->name('testimonials');
            Route::post('/testimonials', [WebsiteController::class, 'storeTestimonial'])->name('testimonials.store');
            Route::delete('/testimonials/{testimonial}', [WebsiteController::class, 'destroyTestimonial'])->name('testimonials.destroy');

            // Social Links
            Route::get('/social', [WebsiteController::class, 'socialLinks'])->name('social');
            Route::post('/social', [WebsiteController::class, 'saveSocialLinks'])->name('social.save');
        });
    });
});
