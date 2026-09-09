{{-- 503 Maintenance page — self-contained, no DB/Vite dependency --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Down for Maintenance – {{ config('app.name', env('APP_NAME', 'Our Site')) }}</title>
    <meta name="robots" content="noindex, nofollow">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --primary: #0ea5e9;
            --primary-dark: #0284c7;
            --neutral-900: #171717;
            --neutral-600: #525252;
            --neutral-400: #a3a3a3;
            --neutral-200: #e5e5e5;
            --neutral-100: #f5f5f5;
            --white: #ffffff;
        }
        html { font-size: 16px; }
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background: var(--neutral-900);
            color: var(--white);
            font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            -webkit-font-smoothing: antialiased;
        }

        /* Left accent bar */
        body::before {
            content: '';
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: var(--primary);
            z-index: 100;
        }

        /* Header */
        header {
            border-bottom: 1px solid rgba(255,255,255,0.08);
            padding: 1rem 1.5rem;
        }
        .logo {
            display: inline-flex;
            align-items: center;
            gap: 0.625rem;
            text-decoration: none;
            color: var(--white);
            font-weight: 800;
            font-size: 0.875rem;
            letter-spacing: 0.025em;
        }
        .logo-box {
            width: 2rem;
            height: 2rem;
            background: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.875rem;
            font-weight: 900;
            color: var(--white);
            flex-shrink: 0;
        }

        /* Main */
        main {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 4rem 1.5rem;
        }
        .container {
            max-width: 36rem;
            width: 100%;
            text-align: center;
        }

        /* Status badge */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.65rem;
            font-weight: 700;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            color: var(--primary);
            border: 1px solid rgba(14, 165, 233, 0.3);
            padding: 0.375rem 0.75rem;
            margin-bottom: 2rem;
        }
        .status-dot {
            width: 6px;
            height: 6px;
            background: var(--primary);
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.4; }
        }

        /* Heading */
        h1 {
            font-size: clamp(1.75rem, 5vw, 2.5rem);
            font-weight: 900;
            line-height: 1.1;
            letter-spacing: -0.025em;
            margin-bottom: 1rem;
            color: var(--white);
        }

        /* Accent bar */
        .accent-bar {
            width: 3rem;
            height: 3px;
            background: var(--primary);
            margin: 0 auto 1.5rem;
        }

        /* Description */
        p.desc {
            font-size: 0.9375rem;
            line-height: 1.7;
            color: rgba(255,255,255,0.55);
            margin-bottom: 2.5rem;
        }

        /* Info grid */
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1px;
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.08);
            margin-bottom: 2.5rem;
        }
        .info-cell {
            padding: 1.25rem 1rem;
            background: rgba(255,255,255,0.03);
        }
        .info-label {
            font-size: 0.65rem;
            font-weight: 700;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            color: rgba(255,255,255,0.35);
            margin-bottom: 0.375rem;
        }
        .info-value {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--white);
        }

        /* CTA link */
        .cta-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--primary);
            text-decoration: none;
            border: 1px solid rgba(14, 165, 233, 0.4);
            padding: 0.75rem 1.5rem;
            transition: background 0.15s, color 0.15s;
        }
        .cta-link:hover {
            background: var(--primary);
            color: var(--white);
        }

        /* Footer */
        footer {
            border-top: 1px solid rgba(255,255,255,0.06);
            padding: 1.25rem 1.5rem;
            text-align: center;
            font-size: 0.75rem;
            color: rgba(255,255,255,0.25);
        }
        footer a {
            color: rgba(255,255,255,0.45);
            text-decoration: none;
        }
        footer a:hover { color: var(--primary); }

        @media (max-width: 480px) {
            .info-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

    <header>
        <a href="{{ url('/') }}" class="logo">
            <span class="logo-box">{{ substr(config('app.name', 'S'), 0, 1) }}</span>
            {{ config('app.name', env('APP_NAME', 'Our Site')) }}
        </a>
    </header>

    <main>
        <div class="container">
            <div class="status-badge">
                <span class="status-dot"></span>
                Scheduled Maintenance
            </div>

            <h1>We'll Be Right Back</h1>
            <div class="accent-bar"></div>

            <p class="desc">
                {{ $exception?->getMessage() ?: 'We\'re performing scheduled maintenance to improve your experience. The site will be back online shortly.' }}
            </p>

            <div class="info-grid">
                <div class="info-cell">
                    <p class="info-label">Status</p>
                    <p class="info-value">Under Maintenance</p>
                </div>
                <div class="info-cell">
                    <p class="info-label">Expected Duration</p>
                    <p class="info-value">A few hours</p>
                </div>
                <div class="info-cell">
                    <p class="info-label">Last Updated</p>
                    <p class="info-value">{{ now()->format('d M Y, H:i') }} EAT</p>
                </div>
                <div class="info-cell">
                    <p class="info-label">Support</p>
                    <p class="info-value">
                        <a href="mailto:{{ config('mail.from.address', 'hello@example.com') }}"
                           style="color: var(--primary); text-decoration: none;">
                            {{ config('mail.from.address', 'hello@example.com') }}
                        </a>
                    </p>
                </div>
            </div>

            <a href="mailto:{{ config('mail.from.address', 'hello@example.com') }}" class="cta-link">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                Email Us While We're Down
            </a>
        </div>
    </main>

    <footer>
        &copy; {{ date('Y') }} {{ config('app.name', env('APP_NAME', 'Our Site')) }}
        &nbsp;&middot;&nbsp;
        <a href="mailto:{{ config('mail.from.address', 'hello@example.com') }}">Contact</a>
    </footer>

</body>
</html>
