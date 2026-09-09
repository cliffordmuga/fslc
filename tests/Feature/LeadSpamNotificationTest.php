<?php

namespace Tests\Feature;

use App\Mail\ContactReceived;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class LeadSpamNotificationTest extends TestCase
{
    use RefreshDatabase;

    private function actingAsAdmin(): self
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'email_verified_at' => now(),
            'google2fa_secret' => 'TESTSECRETKEY000',
            'two_factor_recovery_codes' => ['a', 'b'],
        ]);
        session(['2fa_verified' => true]);

        return $this->actingAs($admin);
    }

    private function spamLead(): Lead
    {
        return Lead::create([
            'name' => 'Flagged',
            'email' => 'flagged@example.com',
            'message' => 'A genuine inquiry that tripped the filter.',
            'inquiry_type' => 'general',
            'status' => 'new',
            'is_spam' => true,
            'spam_score' => 60,
        ]);
    }

    public function test_unmarking_spam_notifies_the_admin_once(): void
    {
        Mail::fake();
        $lead = $this->spamLead();

        $this->actingAsAdmin()
            ->patch(route('admin.leads.not-spam', $lead))
            ->assertRedirect();

        Mail::assertQueued(ContactReceived::class, 1);
        $this->assertNotNull($lead->fresh()->admin_notified_at);
    }

    public function test_unmarking_spam_again_does_not_resend(): void
    {
        Mail::fake();
        $lead = $this->spamLead();

        $this->actingAsAdmin()->patch(route('admin.leads.not-spam', $lead));
        $lead->update(['is_spam' => true]);
        $this->actingAsAdmin()->patch(route('admin.leads.not-spam', $lead));

        Mail::assertQueued(ContactReceived::class, 1);
    }

    public function test_non_spam_submission_marks_lead_notified(): void
    {
        Mail::fake();

        $this->post('/leads', [
            'name' => 'Dr Jane Wanjiku',
            'email' => 'jane@countyhospital.go.ke',
            'message' => 'We would like to schedule an HMIS demo for our Level 4 facility.',
            'phone' => '+254712345678',
        ])->assertRedirect();

        $lead = Lead::firstWhere('email', 'jane@countyhospital.go.ke');

        $this->assertFalse($lead->is_spam);
        $this->assertNotNull($lead->admin_notified_at);
        Mail::assertQueued(ContactReceived::class, 1);
    }

    public function test_bulk_unmark_spam_notifies_each_lead(): void
    {
        Mail::fake();
        $a = $this->spamLead();
        $b = $this->spamLead();

        $this->actingAsAdmin()
            ->post(route('admin.leads.bulk'), [
                'action' => 'mark_not_spam',
                'lead_ids' => [$a->id, $b->id],
            ])
            ->assertRedirect();

        Mail::assertQueued(ContactReceived::class, 2);
    }
}
