<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use App\Models\Content;
use App\Models\Cta;
use App\Models\Lead;
use App\Models\Redirect;
use App\Models\Tag;
use App\Models\Testimonial;
use App\Observers\ContentObserver;
use App\Observers\CtaObserver;
use App\Observers\LeadObserver;
use App\Observers\RedirectObserver;
use App\Observers\TagObserver;
use App\Observers\TestimonialObserver;
use App\Support\AdminUiCache;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Keep generated URLs aligned with APP_URL (required for subdirectory installs
        // e.g. http://localhost/fslc/public — prevents links/redirects to http://localhost/).
        if ($rootUrl = config('app.url')) {
            URL::forceRootUrl(rtrim($rootUrl, '/'));
        }

        if (app()->environment('production')) {
            URL::forceScheme('https');
        }

        Content::observe(ContentObserver::class);
        Cta::observe(CtaObserver::class);
        Testimonial::observe(TestimonialObserver::class);
        Redirect::observe(RedirectObserver::class);
        Tag::observe(TagObserver::class);
        Lead::observe(LeadObserver::class);

        if (class_exists(\Spatie\Activitylog\Models\Activity::class)) {
            \Spatie\Activitylog\Models\Activity::created(function (): void {
                AdminUiCache::forgetRecentActivity();
            });
        }

        // Unified rate limiter for contact + leads (prevents 15/min by hitting both endpoints).
        $leadLimit = (int) config('app.lead_submissions_per_minute', 10);
        RateLimiter::for('lead-submissions', function (Request $request) use ($leadLimit) {
            return Limit::perMinute($leadLimit)->by($request->user()?->id ?: $request->ip());
        });

        // Share admin sidebar metrics (cached).
        View::composer(['layouts.admin'], function ($view): void {
            $view->with('newLeads', AdminUiCache::newLeadsCount());
            $view->with('followUpLeads', AdminUiCache::followUpLeadsCount());
            $view->with('highIntentLeads', AdminUiCache::highIntentLeadsCount());

            $recentActivities = Cache::remember(AdminUiCache::RECENT_ACTIVITY_KEY, now()->addMinutes(5), function () {
                if (! class_exists(\Spatie\Activitylog\Models\Activity::class)) {
                    return collect();
                }

                return \Spatie\Activitylog\Models\Activity::with('causer')
                    ->latest()
                    ->take(5)
                    ->get();
            });
            $view->with('recentActivities', $recentActivities);
        });
    }
}
