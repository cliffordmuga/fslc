<?php

namespace App\Http\Controllers;

use App\Http\Requests\NewsletterRequest;
use App\Models\Lead;
use App\Services\LeadSpamService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

class NewsletterController extends Controller
{
    public function subscribe(NewsletterRequest $request, LeadSpamService $spamService): JsonResponse|RedirectResponse
    {
        $validated = $request->validated();
        $wantsJson = $request->wantsJson();

        // Honeypots: silently succeed.
        if ($request->filled('website_url') || $request->filled('company_website')) {
            return $wantsJson
                ? response()->json(['success' => true])
                : back()->with('success', 'Thanks for subscribing!');
        }

        // Time-based check: submitted in under 3 seconds = likely bot
        $renderedAt = (int) $request->input('_form_rendered_at', 0);
        if ($renderedAt > 0 && (time() - $renderedAt) < 3) {
            return $wantsJson
                ? response()->json(['success' => true])
                : back()->with('success', 'Thanks for subscribing!');
        }

        $spam = $spamService->scoreNewsletter($validated['email'], $request);
        if ($spamService->isSpam($spam['score'])) {
            Log::info('Newsletter spam blocked', [
                'email_domain' => Str::contains($validated['email'], '@') ? Str::after($validated['email'], '@') : null,
                'score' => $spam['score'],
                'reasons' => $spam['reasons'],
            ]);
            return $wantsJson
                ? response()->json(['success' => true])
                : back()->with('success', 'Thanks for subscribing!');
        }

        try {
            // Treat newsletter as a special lead and dedupe by email.
            $lead = Lead::firstOrCreate([
                'email' => $validated['email'],
                'inquiry_type' => 'newsletter',
            ], [
                'name' => 'Newsletter Subscriber',
                'email' => $validated['email'],
                'message' => 'Newsletter subscription',
                'inquiry_type' => 'newsletter',
                'utm_source' => $request->input('utm_source', $request->query('utm_source', 'newsletter')),
                'utm_medium' => $request->input('utm_medium', $request->query('utm_medium', 'form')),
                'utm_campaign' => $request->input('utm_campaign', $request->query('utm_campaign', 'lead_gen_' . date('Y-m-d'))),
                'status' => 'new',
                'is_spam' => false,
            ]);

            Log::info('Newsletter subscription stored as lead', ['lead_id' => $lead->id]);

            return $wantsJson
                ? response()->json(['success' => true, 'message' => 'Thanks for subscribing!'])
                : back()->with('success', 'Thanks for subscribing!');
        } catch (\Exception $e) {
            Log::error('Newsletter subscription failed', ['error' => $e->getMessage(), 'data' => $validated]);

            return $wantsJson
                ? response()->json(['success' => false, 'message' => 'Subscription failed. Please try again.'], 500)
                : back()->with('error', 'Subscription failed. Please try again.');
        }
    }
}