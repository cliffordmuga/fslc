<?php

return [
    /*
     * Enforce the Content-Security-Policy header.
     * Keep false while tuning; set true in production once reports are clean.
     * Env: SECURITY_CSP_ENFORCE=true
     */
    'csp_enforce' => env('SECURITY_CSP_ENFORCE', false),

    /*
     * Send CSP violation reports to /csp-report.
     * Env: SECURITY_CSP_REPORT_ENABLED=false  (to silence reports)
     */
    'csp_report_enabled' => env('SECURITY_CSP_REPORT_ENABLED', true),

    /*
     * Optional extra directives appended to the built-in policy.
     * Example: "frame-src 'self' https://www.youtube.com"
     * Env: SECURITY_CSP_EXTRA_DIRECTIVES="frame-src ..."
     */
    'csp_extra_directives' => env('SECURITY_CSP_EXTRA_DIRECTIVES', ''),

    /*
     * CSP violation report handling (CspReportController). Kept here rather than
     * read via env() at request time so overrides survive `config:cache`.
     */
    'csp_report_sample_percent' => (int) env('CSP_REPORT_SAMPLE_PERCENT', 10),
    'csp_report_dedupe_seconds' => (int) env('CSP_REPORT_DEDUPE_SECONDS', 120),
];
