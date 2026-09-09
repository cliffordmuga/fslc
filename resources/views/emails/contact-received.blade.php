{{-- resources/views/emails/contact-received.blade.php --}}
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>New Contact Inquiry</title>
</head>

<body style="margin:0;padding:0;background:#f6f7fb;font-family:Arial,Helvetica,sans-serif;color:#111827;">
    <div style="max-width:680px;margin:0 auto;padding:24px;">
        <div style="background:#ffffff;border:1px solid #e5e7eb;border-radius:16px;overflow:hidden;">

            {{-- Header --}}
            <div style="padding:20px 22px;background:linear-gradient(135deg,#0ea5e9,#2563eb);color:#fff;">
                <div style="font-size:14px;opacity:.95;">New inquiry received</div>

                <div style="font-size:22px;font-weight:800;line-height:1.2;margin-top:6px;">
                    {{ ucfirst($lead->inquiry_type ?? 'general') }} inquiry
                </div>

                <div style="font-size:14px;opacity:.95;margin-top:8px;">
                    Submitted: {{ optional($lead->created_at)->format('D, M j, Y g:i A') }}
                </div>
            </div>

            {{-- Body --}}
            <div style="padding:22px;">

                {{-- Normalize WhatsApp phone (Kenya-friendly) --}}
                @php
                    $rawPhone = $lead->phone ?? '';
                    $digits = preg_replace('/\D+/', '', $rawPhone);

                    // If user enters 0700..., convert to 254700...
                    if (!empty($digits) && str_starts_with($digits, '0')) {
                        $digits = '254' . substr($digits, 1);
                    }

                    // If user enters +254..., already fine after stripping non-digits
                    $whatsAppDigits = $digits ?: null;

                    // Light labels
                    $projectTypeLabel = $lead->project_type ? ucwords(str_replace('_', ' ', $lead->project_type)) : '—';
                    $nameLabel = $lead->name ?? '—';
                    $phoneLabel = $lead->phone ?? '—';
                @endphp

                {{-- Primary actions --}}
                <div style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:16px;">
                    <a href="mailto:{{ $lead->email }}"
                        style="display:inline-block;padding:10px 14px;border-radius:12px;background:#111827;color:#fff;text-decoration:none;font-weight:700;font-size:14px;">
                        Reply via Email
                    </a>

                    @if (!empty($lead->phone))
                        <a href="tel:{{ $lead->phone }}"
                            style="display:inline-block;padding:10px 14px;border-radius:12px;background:#f3f4f6;color:#111827;text-decoration:none;font-weight:700;font-size:14px;border:1px solid #e5e7eb;">
                            Call
                        </a>
                    @endif

                    @if (!empty($whatsAppDigits))
                        <a href="https://wa.me/{{ $whatsAppDigits }}"
                            style="display:inline-block;padding:10px 14px;border-radius:12px;background:#22c55e;color:#fff;text-decoration:none;font-weight:700;font-size:14px;">
                            WhatsApp
                        </a>
                    @endif
                </div>

                {{-- Lead summary --}}
                <table role="presentation" cellspacing="0" cellpadding="0" style="width:100%;border-collapse:collapse;">
                    <tr>
                        <td style="width:34%;padding:10px 0;color:#6b7280;font-size:13px;">Name</td>
                        <td style="padding:10px 0;font-size:14px;font-weight:700;">
                            {{ $nameLabel }}
                        </td>
                    </tr>

                    <tr style="border-top:1px solid #f3f4f6;">
                        <td style="width:34%;padding:10px 0;color:#6b7280;font-size:13px;">Email</td>
                        <td style="padding:10px 0;font-size:14px;">
                            <a href="mailto:{{ $lead->email }}"
                                style="color:#2563eb;text-decoration:none;font-weight:700;">
                                {{ $lead->email }}
                            </a>
                        </td>
                    </tr>

                    <tr style="border-top:1px solid #f3f4f6;">
                        <td style="width:34%;padding:10px 0;color:#6b7280;font-size:13px;">Phone</td>
                        <td style="padding:10px 0;font-size:14px;font-weight:700;">
                            {{ $phoneLabel }}
                        </td>
                    </tr>

                    <tr style="border-top:1px solid #f3f4f6;">
                        <td style="width:34%;padding:10px 0;color:#6b7280;font-size:13px;">Project Type</td>
                        <td style="padding:10px 0;font-size:14px;font-weight:700;">
                            {{ $projectTypeLabel }}
                        </td>
                    </tr>
                </table>

                {{-- Message --}}
                <div
                    style="margin-top:18px;padding:16px;border:1px solid #e5e7eb;border-radius:14px;background:#fafafa;">
                    <div style="font-size:13px;color:#6b7280;font-weight:700;margin-bottom:8px;">Message</div>
                    <div style="font-size:14px;line-height:1.6;white-space:pre-wrap;color:#111827;">
                        {{ $lead->message ?? '—' }}
                    </div>
                </div>

                {{-- Suggested follow-ups (reply-optimized) --}}
                <div
                    style="margin-top:16px;padding:14px;border:1px solid #e5e7eb;border-radius:14px;background:#ffffff;">
                    <div style="font-size:13px;color:#6b7280;font-weight:700;margin-bottom:8px;">Suggested follow-ups
                    </div>
                    <ul style="margin:0;padding-left:18px;font-size:14px;line-height:1.6;color:#111827;">
                        <li>What’s your target launch date?</li>
                        <li>Do you have examples you like (2–3 links)?</li>
                        <li>What budget range are you working with?</li>
                    </ul>
                </div>

                {{-- Attachment (private storage — admin download only) --}}
                @if (!empty($lead->attachment_path))
                    <div style="margin-top:14px;font-size:13px;color:#6b7280;line-height:1.6;">
                        <strong style="color:#111827;">Attachment:</strong>
                        {{ $lead->attachment_original_name ?: 'Uploaded file' }}
                        <br>
                        <a href="{{ route('admin.leads.show', $lead) }}"
                           style="color:#0284c7;text-decoration:none;font-weight:600;">
                            View lead in admin to download
                        </a>
                    </div>
                @endif

                {{-- Attribution / Tracking --}}
                <div style="margin-top:18px;font-size:12px;color:#6b7280;line-height:1.6;">
                    <div>
                        <strong style="color:#111827;">Source Content ID:</strong>
                        {{ $lead->source_content_id ?? '—' }}
                    </div>
                    <div><strong style="color:#111827;">UTM Source:</strong> {{ $lead->utm_source ?? '—' }}</div>
                    <div><strong style="color:#111827;">UTM Medium:</strong> {{ $lead->utm_medium ?? '—' }}</div>
                    <div><strong style="color:#111827;">UTM Campaign:</strong> {{ $lead->utm_campaign ?? '—' }}</div>
                    <div><strong style="color:#111827;">Referrer:</strong> {{ $lead->referrer ?? '—' }}</div>
                </div>
            </div>

            {{-- Footer --}}
            <div
                style="padding:14px 22px;border-top:1px solid #e5e7eb;background:#ffffff;color:#6b7280;font-size:12px;">
                Tip: hit “Reply” — set your mailable to use <strong>Reply-To: {{ $lead->email }}</strong> so replies
                go straight to the lead.
            </div>
        </div>
    </div>
</body>

</html>
