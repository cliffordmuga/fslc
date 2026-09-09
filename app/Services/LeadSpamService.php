<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LeadSpamService
{
    /**
     * Score a lead submission.
     * Returns: ['score' => int, 'reasons' => string[]]
     */
    public function score(array $leadData, Request $request): array
    {
        $score = 0;
        $reasons = [];

        $name = (string)($leadData['name'] ?? '');
        $email = strtolower((string)($leadData['email'] ?? ''));
        $message = (string)($leadData['message'] ?? '');
        $phone = (string)($leadData['phone'] ?? '');

        $ua = strtolower((string)$request->userAgent());
        $ip = (string)$request->ip();

        // 1) Disposable / suspicious email domains
        $domain = Str::contains($email, '@') ? Str::after($email, '@') : '';
        $disposable = [
            'mailinator.com', 'guerrillamail.com', '10minutemail.com', 'tempmail.com',
            'yopmail.com', 'trashmail.com', 'getnada.com', 'fakeinbox.com',
        ];
        if ($domain && in_array($domain, $disposable, true)) {
            $score += 50;
            $reasons[] = 'Disposable email domain';
        }

        // 2) Invalid-ish email patterns (not caught by validator sometimes)
        if (Str::startsWith($email, ['test@', 'admin@', 'info@']) && $domain && $domain !== 'gmail.com') {
            $score += 10;
            $reasons[] = 'Generic mailbox pattern';
        }

        // 3) Too many links in message
        $linkCount = preg_match_all('/https?:\/\/|www\./i', $message) ?: 0;
        if ($linkCount >= 2) {
            $score += 25;
            $reasons[] = 'Multiple links in message';
        } elseif ($linkCount === 1) {
            $score += 10;
            $reasons[] = 'Contains a link';
        }

        // 4) Common spam keywords
        $spamWords = [
            'seo', 'backlinks', 'rank your website', 'guest post', 'casino', 'betting',
            'crypto', 'forex', 'loan', 'viagra', 'adult', 'escort', 'investment opportunity',
        ];
        $hay = strtolower($message);
        foreach ($spamWords as $w) {
            if (Str::contains($hay, $w)) {
                $score += 15;
                $reasons[] = "Spam keyword: {$w}";
                break;
            }
        }

        // 5) Very short or nonsense content
        $plain = trim(preg_replace('/\s+/', ' ', strip_tags($message)));
        if (mb_strlen($plain) < 12) {
            $score += 15;
            $reasons[] = 'Very short message';
        }

        // 6) Suspicious user agent
        if ($ua === '' || Str::contains($ua, ['curl/', 'wget', 'python-requests', 'httpclient', 'go-http-client'])) {
            $score += 20;
            $reasons[] = 'Suspicious user agent';
        }

        // 7) Phone contains too many non-digits
        $digits = preg_replace('/\D+/', '', $phone);
        if ($phone !== '' && (mb_strlen($digits) < 7 || mb_strlen($digits) > 16)) {
            $score += 10;
            $reasons[] = 'Suspicious phone format';
        }

        // 8) Repeated characters (spammy)
        if (preg_match('/(.)\1{6,}/u', $plain)) {
            $score += 15;
            $reasons[] = 'Repeated characters';
        }

        // Light IP-based heuristic hook (no external lookup; safe default)
        if ($ip === '0.0.0.0' || $ip === '127.0.0.1') {
            // usually local/dev; ignore
        }

        // Clamp to 0..100
        $score = max(0, min(100, $score));

        return ['score' => $score, 'reasons' => array_values(array_unique($reasons))];
    }

    public function isSpam(int $score): bool
    {
        return $score >= (int) env('LEAD_SPAM_THRESHOLD', 45);
    }

    /**
     * Score newsletter subscription (email + UA only).
     * Returns: ['score' => int, 'reasons' => string[]]
     */
    public function scoreNewsletter(string $email, Request $request): array
    {
        $score = 0;
        $reasons = [];
        $email = strtolower(trim($email));
        $domain = Str::contains($email, '@') ? Str::after($email, '@') : '';
        $ua = strtolower((string) $request->userAgent());

        $disposable = [
            'mailinator.com', 'guerrillamail.com', '10minutemail.com', 'tempmail.com',
            'yopmail.com', 'trashmail.com', 'getnada.com', 'fakeinbox.com',
        ];
        if ($domain && in_array($domain, $disposable, true)) {
            $score += 60;
            $reasons[] = 'Disposable email domain';
        }

        if (Str::startsWith($email, ['test@', 'admin@', 'info@', 'noreply@']) && $domain && $domain !== 'gmail.com') {
            $score += 20;
            $reasons[] = 'Generic mailbox pattern';
        }

        if ($ua === '' || Str::contains($ua, ['curl/', 'wget', 'python-requests', 'httpclient', 'go-http-client'])) {
            $score += 30;
            $reasons[] = 'Suspicious user agent';
        }

        $score = max(0, min(100, $score));
        return ['score' => $score, 'reasons' => array_values(array_unique($reasons))];
    }
}
