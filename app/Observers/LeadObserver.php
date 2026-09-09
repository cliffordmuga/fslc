<?php

namespace App\Observers;

use App\Models\Lead;
use App\Support\AdminUiCache;

class LeadObserver
{
    public function saved(Lead $lead): void
    {
        AdminUiCache::forgetNewLeads();
        AdminUiCache::forgetDashboardAndAnalytics();
    }

    public function deleted(Lead $lead): void
    {
        AdminUiCache::forgetNewLeads();
        AdminUiCache::forgetDashboardAndAnalytics();
    }
}
