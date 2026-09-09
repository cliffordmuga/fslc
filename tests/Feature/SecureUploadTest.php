<?php

namespace Tests\Feature;

use App\Models\Lead;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SecureUploadTest extends TestCase
{
    use RefreshDatabase;

    /** @param array<string, mixed> $attributes */
    private function adminUser(array $attributes = []): User
    {
        return User::factory()->createOne(array_merge([
            'role' => 'admin',
            'email_verified_at' => now(),
            'google2fa_secret' => 'TESTSECRETKEY000',
            'two_factor_recovery_codes' => ['recovery-one'],
        ], $attributes));
    }

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('local');
    }

    public function test_contact_attachment_is_stored_on_private_disk(): void
    {
        $file = UploadedFile::fake()->createWithContent(
            'brief.pdf',
            "%PDF-1.4\n% Fake PDF for testing\n"
        );

        $this->post(route('contact.store'), [
            'name' => 'Upload Tester',
            'email' => 'upload@example.com',
            'message' => 'Please review my attachment.',
            'inquiry_type' => 'general',
            'file' => $file,
            '_form_rendered_at' => time() - 10,
        ])->assertRedirect();

        $lead = Lead::where('email', 'upload@example.com')->first();
        $this->assertNotNull($lead);
        $this->assertNotNull($lead->attachment_path);
        $this->assertSame('brief.pdf', $lead->attachment_original_name);
        $this->assertTrue(Storage::disk('local')->exists($lead->attachment_path));
        $this->assertFalse(Storage::disk('public')->exists($lead->attachment_path));
    }

    public function test_rejects_invalid_contact_attachment_mime(): void
    {
        $file = UploadedFile::fake()->create('evil.exe', 10, 'application/x-msdownload');

        $response = $this->postJson(route('contact.store'), [
            'name' => 'Bad Upload',
            'email' => 'bad@example.com',
            'message' => 'Invalid file test',
            'inquiry_type' => 'general',
            'file' => $file,
            '_form_rendered_at' => time() - 10,
        ]);

        $response->assertStatus(422);
        $this->assertDatabaseMissing('leads', ['email' => 'bad@example.com']);
    }

    public function test_admin_can_download_private_lead_attachment(): void
    {
        Storage::disk('local')->put('leads/sample.pdf', '%PDF-1.4 test');

        $admin = $this->adminUser();
        session(['2fa_verified' => true]);

        $lead = Lead::create([
            'name' => 'Lead With File',
            'email' => 'file@example.com',
            'message' => 'See attachment',
            'inquiry_type' => 'general',
            'status' => 'new',
            'attachment_path' => 'leads/sample.pdf',
            'attachment_original_name' => 'sample.pdf',
            'is_spam' => false,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.leads.attachment', $lead))
            ->assertOk()
            ->assertHeader('content-disposition');
    }

    public function test_guest_cannot_download_lead_attachment(): void
    {
        Storage::disk('local')->put('leads/hidden.pdf', 'secret');

        $lead = Lead::create([
            'name' => 'Hidden',
            'email' => 'hidden@example.com',
            'message' => 'Private file',
            'inquiry_type' => 'general',
            'status' => 'new',
            'attachment_path' => 'leads/hidden.pdf',
            'is_spam' => false,
        ]);

        $this->get(route('admin.leads.attachment', $lead))->assertRedirect();
    }
}
