<?php

namespace Tests\Unit;

use App\Services\LeadSpamService;
use Illuminate\Http\Request;
use Tests\TestCase;

class LeadSpamServiceTest extends TestCase
{
    public function test_disposable_email_scores_as_spam(): void
    {
        $service = app(LeadSpamService::class);
        $request = Request::create('/', 'POST', server: ['HTTP_USER_AGENT' => 'Mozilla/5.0']);

        $result = $service->score([
            'name' => 'Test User',
            'email' => 'spam@mailinator.com',
            'message' => 'We need HMIS for our hospital.',
            'phone' => '+254712345678',
        ], $request);

        $this->assertGreaterThanOrEqual(45, $result['score']);
        $this->assertTrue($service->isSpam($result['score']));
        $this->assertContains('Disposable email domain', $result['reasons']);
    }

    public function test_legitimate_inquiry_scores_below_spam_threshold(): void
    {
        $service = app(LeadSpamService::class);
        $request = Request::create('/', 'POST', server: ['HTTP_USER_AGENT' => 'Mozilla/5.0 Chrome']);

        $result = $service->score([
            'name' => 'Dr Jane Wanjiku',
            'email' => 'jane@countyhospital.go.ke',
            'message' => 'We would like to schedule an HMIS demo for our Level 4 facility in Kisumu.',
            'phone' => '+254712345678',
        ], $request);

        $this->assertLessThan(45, $result['score']);
        $this->assertFalse($service->isSpam($result['score']));
    }
}
