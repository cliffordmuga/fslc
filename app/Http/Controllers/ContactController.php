<?php

namespace App\Http\Controllers;

use App\Http\Requests\LeadRequest;
use App\Models\Lead;
use App\Models\PageAnalytic;
use App\Models\Setting;
use App\Services\ContentService;
use App\Services\LeadSpamService;
use App\Services\SecureUploadService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View as ViewFacade;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function __construct(protected ContentService $contentService) {}

    public function show(Request $request): View
    {
        $contactPage = $this->contentService->getContactPageContent();
        $services = $this->contentService->getPublishedServicesList();

        $inquiryType = normalize_inquiry_type($request->query('inquiry_type', 'general'));
        $preselectedServiceId = null;

        if ($serviceSlug = $request->query('service')) {
            $preselectedServiceId = $services->firstWhere('slug', $serviceSlug)?->id;
        } else {
            $inquiryServiceMap = config('forefront.inquiry_service_map', []);
            if (isset($inquiryServiceMap[$inquiryType])) {
                $preselectedServiceId = $services->firstWhere('slug', $inquiryServiceMap[$inquiryType])?->id;
            }
        }

        $contactCta = $this->contentService->getContactPageCta();

        $companyInfo = [
            'name' => Setting::get('company_name', config('app.name')),
            'address' => Setting::get('address', 'Nairobi, Kenya'),
            'email' => Setting::get('email', 'hello@forefrontsolutions.co.ke'),
            'phone' => Setting::get('phone', ''),
            'maps_embed_url' => Setting::get('maps_embed_url'),
        ];

        $stats = [
            'response_time' => Setting::get('response_time', '24'),
            'satisfaction' => Setting::get('satisfaction', '98'),
        ];

        $leadUx = lead_ux_for_request($inquiryType !== 'general' ? $inquiryType : null);
        $formUx = contact_form_ux($inquiryType);
        $pillarLinks = config('forefront.contact_pillar_links', []);
        $formSuccess = $request->query('utm_source') === 'form_success';

        ViewFacade::share('inquiryType', $inquiryType);

        $seoData = $this->contentService->getSeoData('contact', $contactPage) ?? [
            'og_title' => 'Contact '.setting('company_name'),
            'og_description' => 'Request an HMIS demo, software quote, or campaign strategy session.',
            'og_image' => cdn_asset('images/default-og-image.png'),
        ];
        $pageSchemas = $this->contentService->getPageSchemas('contact');

        return view('frontend.contact', compact(
            'contactPage',
            'contactCta',
            'companyInfo',
            'stats',
            'seoData',
            'pageSchemas',
            'services',
            'inquiryType',
            'preselectedServiceId',
            'leadUx',
            'formUx',
            'pillarLinks',
            'formSuccess',
        ));
    }

    public function store(LeadRequest $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validated();
        $wantsJson = $request->wantsJson();

        if ($request->filled('website_url') || $request->filled('company_website')) {
            return $wantsJson
                ? response()->json(['ok' => true], 200)
                : back()->with('status', "Thank you! We'll respond within 24 hours.");
        }

        $renderedAt = (int) $request->input('_form_rendered_at', 0);
        if ($renderedAt > 0 && (time() - $renderedAt) < 4) {
            return $wantsJson
                ? response()->json(['ok' => true], 200)
                : back()->with('status', "Thank you! We'll respond within 24 hours.");
        }

        $validated['utm_source'] = $validated['utm_source'] ?? $request->query('utm_source', 'direct');
        $validated['utm_medium'] = $validated['utm_medium'] ?? $request->query('utm_medium');
        $validated['utm_campaign'] = $validated['utm_campaign'] ?? $request->query('utm_campaign');
        $validated['referrer'] = $request->headers->get('referer');
        $validated['ip_address'] = $request->ip();
        $validated['user_agent'] = substr((string) $request->userAgent(), 0, 500);

        if ($request->hasFile('file')) {
            $stored = app(SecureUploadService::class)->storeLeadAttachment($request->file('file'));
            $validated['attachment_path'] = $stored['path'];
            $validated['attachment_original_name'] = $stored['original_name'];
        }

        $spam = app(LeadSpamService::class)->score($validated, $request);
        $validated['spam_score'] = $spam['score'];
        $validated['spam_reasons'] = $spam['reasons'];
        $validated['is_spam'] = app(LeadSpamService::class)->isSpam((int) $spam['score']);

        $lead = Lead::create($validated);

        if (! $lead->is_spam) {
            $lead->notifyAdmin(queue: false);

            if ($lead->source_content_id) {
                $this->trackConversion($lead);
            }
        } else {
            Log::warning('Spam lead captured', [
                'lead_id' => $lead->id,
                'spam_score' => $lead->spam_score,
                'reasons' => $lead->spam_reasons,
                'ip' => $lead->ip_address,
            ]);
        }

        $redirectUrl = route('contact', [
            'utm_source' => 'form_success',
            'utm_medium' => 'lead',
            'inquiry_type' => $lead->inquiry_type,
        ]);

        $successMessage = contact_form_ux($lead->inquiry_type)['success_message']
            ?? "Thank you! We'll respond within 24 hours.";

        return $wantsJson
            ? response()->json(['ok' => true, 'redirect' => $redirectUrl], 200)
            : redirect()->to($redirectUrl)->with('success', $successMessage);
    }

    private function trackConversion(Lead $lead): void
    {
        PageAnalytic::forDay($lead->source_content_id, now()->toDateString())
            ->increment('leads_generated');
    }
}
