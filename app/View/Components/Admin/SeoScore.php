<?php

namespace App\View\Components\Admin;

use App\Models\Content;
use App\Services\SeoScoreService;
use Illuminate\View\Component;

class SeoScore extends Component
{
    public array $initialScore;
    public array $rules;

    public function __construct(?Content $content = null)
    {
        $service = app(SeoScoreService::class);
        $this->initialScore = $content
            ? $service->scoreContent($content)
            : ['score' => 0, 'grade' => '-', 'checks' => [], 'suggestions' => []];
        $this->rules = SeoScoreService::clientRules();
    }

    public function render()
    {
        return view('components.admin.seo-score');
    }
}
