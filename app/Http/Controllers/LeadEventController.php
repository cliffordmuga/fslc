<?php

namespace App\Http\Controllers;

use App\Models\LeadEvent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LeadEventController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'event' => ['required', 'string', 'max:64'],
            'step' => ['nullable', 'string', 'max:64'],
            'source' => ['nullable', 'string', 'max:120'],
            'page' => ['nullable', 'string', 'max:255'],
            'utm_source' => ['nullable', 'string', 'max:120'],
            'utm_medium' => ['nullable', 'string', 'max:120'],
            'utm_campaign' => ['nullable', 'string', 'max:120'],
        ]);

        LeadEvent::create([
            'event' => $validated['event'],
            'step' => $validated['step'] ?? null,
            'source' => $validated['source'] ?? null,
            'page' => $validated['page'] ?? $request->path(),
            'utm_source' => $validated['utm_source'] ?? null,
            'utm_medium' => $validated['utm_medium'] ?? null,
            'utm_campaign' => $validated['utm_campaign'] ?? null,
            'ip_address' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 500),
        ]);

        return response()->json(['ok' => true]);
    }
}

