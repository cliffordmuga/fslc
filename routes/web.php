<?php

use App\Http\Controllers\ActivityLogController as AdminActivityLogController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\RedirectController as AdminRedirectController;
use App\Http\Controllers\AnalyticsController as AdminAnalyticsController;
use App\Http\Controllers\Api\CtaController;
use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\Auth\TwoFactorController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ContentController as AdminContentController;
use App\Http\Controllers\CspReportController;
use App\Http\Controllers\CtaController as AdminCtaController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\HealthController;
use App\Http\Controllers\LeadController as AdminLeadController;
use App\Http\Controllers\LeadEventController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SettingController as AdminSettingController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\TestimonialController as AdminTestimonialController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application.
| These routes are loaded by the RouteServiceProvider within a group
| which contains the "web" middleware group.
|
*/

// ===================================
// PUBLIC ROUTES (No Auth Required)
// ===================================

// Homepage — must be first to avoid shadowing
Route::get('/', [FrontendController::class, 'index'])->name('home');

// Static Pages
Route::get('/about', [FrontendController::class, 'about'])->name('about');
Route::get('/privacy', [FrontendController::class, 'privacy'])->name('privacy');
Route::get('/terms', [FrontendController::class, 'terms'])->name('terms');

// Contact
Route::get('/contact', [ContactController::class, 'show'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])
    ->name('contact.store')
    ->middleware('throttle:lead-submissions');

// Search
Route::get('/search', [SearchController::class, 'index'])->name('search');

// Listing Pages (plural)
Route::get('/portfolio', [FrontendController::class, 'portfolio'])->name('portfolio.index');
Route::get('/services', [FrontendController::class, 'services'])->name('services.index');

// Insights hub (primary URL; /blog redirects for legacy links)
Route::get('/insights', [FrontendController::class, 'blog'])->name('insights.index');
Route::redirect('/blog', '/insights', 301)->name('blog.index');

// Public Lead (alias endpoint for site-wide forms)
// NOTE: ContactController::store already handles honeypot, uploads, email queue, conversion tracking
Route::post('/leads', [ContactController::class, 'store'])
    ->name('leads.store')
    ->middleware('throttle:lead-submissions');

Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])
    ->name('newsletter.subscribe')
    ->middleware('throttle:5,1');

Route::post('/lead-events', [LeadEventController::class, 'store'])
    ->name('lead-events.store')
    ->middleware('throttle:30,1');

Route::get('/health', HealthController::class)->name('health');

// Clear-cache route is in bootstrap/app.php (no web middleware — works without sessions table)

Route::post('/csp-report', CspReportController::class)
    ->name('csp.report')
    ->middleware('throttle:30,1'); // max 30 reports per minute per IP

// ===================================
// SOCIAL AUTHENTICATION
// ===================================
Route::prefix('auth')->name('socialite.')->group(function () {
    Route::get('{provider}/redirect', [SocialiteController::class, 'redirectToProvider'])->name('redirect');
    Route::get('{provider}/callback', [SocialiteController::class, 'handleProviderCallback'])->name('callback');
});

// ===================================
// CONTENT DETAIL PAGES (Specific First)
// ===================================

// Portfolio Detail
Route::get('/portfolio/{slug}', [FrontendController::class, 'show'])
    ->name('portfolio.show')
    ->where('slug', '[a-z0-9\-]+');

// Service Detail (singular 'service' for type consistency)
Route::get('/services/{slug}', [FrontendController::class, 'show'])
    ->name('services.show')
    ->where('slug', '[a-z0-9\-]+');

// Insights / Blog Detail
Route::get('/insights/{slug}', [FrontendController::class, 'show'])
    ->name('insights.show')
    ->where('slug', '[a-z0-9\-]+');

Route::get('/blog/{slug}', function (string $slug) {
    return redirect()->to('/insights/'.$slug, 301);
})->name('blog.show')->where('slug', '[a-z0-9\-]+');

// Page Detail
Route::get('/page/{slug}', [FrontendController::class, 'show'])
    ->name('page.show')
    ->where('slug', '[a-z0-9\-]+');

// Special Static Pages (mission, vision, intro, about detail)
/* Route::get('/mission', fn() => app(FrontendController::class)->show('mission', 'mission'))->name('mission');
Route::get('/vision', fn() => app(FrontendController::class)->show('vision', 'vision'))->name('vision');
Route::get('/intro', fn() => app(FrontendController::class)->show('intro', 'intro'))->name('intro'); */

// Special singletons (render via dedicated methods or just map to page.show style)
// Easiest: treat them as page slugs and use page.show route pattern:
Route::get('/mission', [FrontendController::class, 'mission'])->name('mission');
Route::get('/vision', [FrontendController::class, 'vision'])->name('vision');
Route::get('/intro', [FrontendController::class, 'intro'])->name('intro');

Route::get('/tags/{slug}', [FrontendController::class, 'tag'])->name('tags.show')
    ->where('slug', '[a-z0-9\-]+');

Route::get('/preview/content/{content}', [FrontendController::class, 'previewContent'])
    ->middleware('signed')
    ->name('content.preview');

// Catch-all for other types (last!)
/* Route::get('/{type}/{slug}', [FrontendController::class, 'show'])
    ->name('details.show')
    ->whereIn('type', ['testimonial', 'about', 'mission', 'vision', 'intro', 'blog', 'portfolio', 'service', 'page'])
    ->where('slug', '[a-z0-9\-]+'); */

// ===================================
// SITEMAP & SEO ROUTES
// ===================================

// XML Sitemap (auto-regenerate if missing/outdated)
Route::get('/sitemap.xml', [SitemapController::class, 'xml'])->name('sitemap.xml');

// Optional HTML sitemap page (for users)
Route::get('/sitemap', [SitemapController::class, 'html'])->name('sitemap');

// ===================================
// AUTHENTICATION & 2FA ROUTES
// ===================================

// 2FA Routes (require login; throttled enable/verify)
Route::middleware(['auth'])->prefix('2fa')->name('2fa.')->group(function () {
    Route::get('/setup', [TwoFactorController::class, 'setup'])->name('setup');
    Route::post('/enable', [TwoFactorController::class, 'enable'])->middleware('throttle:10,1')->name('enable');
    Route::delete('/disable', [TwoFactorController::class, 'disable'])->name('disable');
    Route::get('/verify', [TwoFactorController::class, 'verify'])->name('verify');
    Route::post('/verify', [TwoFactorController::class, 'verifyCode'])->middleware('throttle:10,1')->name('store');
});

// Authenticated Routes (require email verification)
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard with role-based redirect
    Route::get('/dashboard', function () {

        /** @var User $user */
        $user = Auth::user();
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        return view('user.dashboard');
    })->name('dashboard');

    // Profile Management (2FA required — same bar as admin)
    Route::middleware(['auth', 'verified', '2fa'])->prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
    });
});

// Protected Routes (require 2FA verification)
Route::middleware(['auth', 'verified', '2fa'])->group(function () {
    // Add any routes that need full 2FA here
});

// ===================================
// ADMIN ROUTES (Admin Role + 2FA Required)
// ===================================
Route::middleware(['auth', 'verified', '2fa', 'admin.2fa', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Lead extras (register before resource so "export" / "bulk" are not captured as {lead})
        Route::get('leads/export', [AdminLeadController::class, 'export'])->name('leads.export');
        Route::get('leads/{lead}/attachment', [AdminLeadController::class, 'downloadAttachment'])->name('leads.attachment');
        Route::post('leads/bulk', [AdminLeadController::class, 'bulk'])->name('leads.bulk');

        Route::patch('testimonials/bulk-approve', [AdminTestimonialController::class, 'bulkApprove'])->name('testimonials.bulk-approve');

        // Resources
        Route::resource('content', AdminContentController::class);
        Route::get('content/{content}/preview', [AdminContentController::class, 'preview'])->name('content.preview-link');
        Route::resource('leads', AdminLeadController::class)->except(['create', 'store']);
        Route::resource('testimonials', AdminTestimonialController::class);
        Route::resource('settings', AdminSettingController::class);
        Route::resource('ctas', AdminCtaController::class);

        // Lead Extras
        Route::prefix('leads')->name('leads.')->group(function () {
            Route::patch('/{lead}/spam', [AdminLeadController::class, 'markAsSpam'])->name('spam');
            Route::patch('/{lead}/not-spam', [AdminLeadController::class, 'markAsNotSpam'])->name('not-spam');
        });

        // Testimonial Extras
        Route::prefix('testimonials')->name('testimonials.')->group(function () {
            Route::patch('/{testimonial}/approve', [AdminTestimonialController::class, 'approve'])->name('approve');
            Route::patch('/{testimonial}/reject', [AdminTestimonialController::class, 'reject'])->name('reject');
            Route::patch('/{testimonial}/toggle-featured', [AdminTestimonialController::class, 'toggleFeatured'])->name('toggle-featured');
        });

        // Analytics
        Route::prefix('analytics')->name('analytics.')->group(function () {
            Route::get('/', [AdminAnalyticsController::class, 'index'])->name('index');
            Route::get('/content', [AdminAnalyticsController::class, 'content'])->name('content');
        });

        // Activity Log
        Route::get('/activity', [AdminActivityLogController::class, 'index'])->name('activity.index');

        // Sitemap Management
        Route::post('/sitemap/generate', [SitemapController::class, 'generate'])->name('sitemap.generate');

        Route::resource('redirects', AdminRedirectController::class)->except(['show']);
        Route::patch('redirects/{redirect}/toggle', [AdminRedirectController::class, 'toggle'])->name('redirects.toggle');

        Route::get('redirects/import', [AdminRedirectController::class, 'importForm'])->name('redirects.importForm');
        Route::post('redirects/import', [AdminRedirectController::class, 'import'])->name('redirects.import');

        Route::get('redirects/test', [AdminRedirectController::class, 'testForm'])->name('redirects.testForm');
        Route::post('redirects/test', [AdminRedirectController::class, 'test'])->name('redirects.test');
    });

// ===================================
// API / AJAX ROUTES
// ===================================
Route::prefix('api')->name('api.')->middleware('web')->group(function () {
    Route::post('/cta/click', [CtaController::class, 'click'])
        ->name('cta.click')
        ->middleware('throttle:100,1'); // 100 clicks per minute
});

// ===================================
// AUTH ROUTES (Laravel default)
// ===================================
require __DIR__.'/auth.php';
