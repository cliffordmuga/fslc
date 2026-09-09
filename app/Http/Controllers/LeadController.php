<?php

namespace App\Http\Controllers;

use App\Http\Requests\LeadRequest;
use App\Models\Lead;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LeadController extends Controller
{
    public function index(Request $request): View
    {
        $spamFilter = $request->query('spam_filter', 'exclude');

        $query = $this->filteredQuery($request, $spamFilter)
            ->latest();

        $leads = $query->paginate(15)->appends($request->query());
        $statuses = Lead::STATUSES;
        $inquiryTypes = Lead::INQUIRY_TYPES;

        return view('admin.leads.index', compact('leads', 'statuses', 'inquiryTypes', 'spamFilter'));
    }

    public function show(Lead $lead): View
    {
        $lead->load('sourceContent');

        $activities = class_exists(\Spatie\Activitylog\Models\Activity::class)
            ? \Spatie\Activitylog\Models\Activity::forSubject($lead)->latest()->take(20)->get()
            : collect();

        return view('admin.leads.show', compact('lead', 'activities'));
    }

    public function edit(Lead $lead): View
    {
        $statuses = Lead::STATUSES;

        return view('admin.leads.edit', compact('lead', 'statuses'));
    }

    public function update(LeadRequest $request, Lead $lead): RedirectResponse
    {
        $lead->update($request->validated());

        return redirect()->route('admin.leads.index')
            ->with('success', 'Lead updated successfully.');
    }

    public function destroy(Lead $lead): RedirectResponse
    {
        $this->deleteLeadAttachment($lead);
        $lead->delete();

        return redirect()->route('admin.leads.index')
            ->with('success', 'Lead deleted successfully.');
    }

    public function downloadAttachment(Lead $lead): BinaryFileResponse
    {
        $diskName = $this->attachmentDisk($lead);
        abort_unless($diskName, 404);

        $filesystem = Storage::disk($diskName);
        if (! $filesystem instanceof FilesystemAdapter) {
            abort(500, 'Attachment storage disk is misconfigured.');
        }

        $filename = $lead->attachment_original_name ?: basename($lead->attachment_path);

        return response()->download(
            $filesystem->path($lead->attachment_path),
            $filename
        );
    }

    public function markAsSpam(Lead $lead): RedirectResponse
    {
        $lead->update(['is_spam' => true]);

        return back()->with('success', 'Lead marked as spam.');
    }

    public function markAsNotSpam(Lead $lead): RedirectResponse
    {
        $lead->update(['is_spam' => false]);
        $lead->notifyAdmin(queue: false);

        return back()->with('success', 'Lead unmarked as spam.');
    }

    public function bulk(Request $request): RedirectResponse
    {
        $request->validate([
            'action' => ['required', 'in:mark_spam,mark_not_spam,status'],
            'lead_ids' => ['required', 'array', 'min:1'],
            'lead_ids.*' => ['integer', 'exists:leads,id'],
            'status' => ['required_if:action,status', 'string', 'in:' . implode(',', array_keys(Lead::STATUSES))],
        ]);

        $ids = $request->input('lead_ids', []);

        match ($request->action) {
            'mark_spam' => Lead::whereIn('id', $ids)->update(['is_spam' => true]),
            'mark_not_spam' => Lead::whereIn('id', $ids)->get()->each(function (Lead $lead): void {
                $lead->update(['is_spam' => false]);
                $lead->notifyAdmin();
            }),
            'status' => Lead::whereIn('id', $ids)->update(['status' => $request->status]),
        };

        return back()->with('success', 'Bulk action applied to ' . count($ids) . ' lead(s).');
    }

    public function export(Request $request): StreamedResponse
    {
        $spamFilter = $request->query('spam_filter', 'exclude');
        $query = $this->filteredQuery($request, $spamFilter)->latest();
        $filename = 'leads_' . now()->format('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($query): void {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                'ID', 'Name', 'Email', 'Phone', 'Message', 'Inquiry Type',
                'Source Content', 'Status', 'Spam', 'Spam Score', 'UTM Source',
                'UTM Medium', 'UTM Campaign', 'Conversion Value', 'Created At',
            ]);

            $query->with('sourceContent:id,title')->lazy(200)->each(function (Lead $lead) use ($handle): void {
                fputcsv($handle, [
                    $lead->id,
                    $lead->name,
                    $lead->email,
                    $lead->phone,
                    $lead->message,
                    $lead->inquiry_type,
                    $lead->sourceContent?->title ?? 'N/A',
                    $lead->status,
                    $lead->is_spam ? 'yes' : 'no',
                    $lead->spam_score,
                    $lead->utm_source,
                    $lead->utm_medium,
                    $lead->utm_campaign,
                    $lead->conversion_value,
                    $lead->created_at,
                ]);
            });

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }

    private function filteredQuery(Request $request, string $spamFilter)
    {
        return Lead::with('sourceContent')
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->when($request->inquiry_type, fn ($q) => $q->where('inquiry_type', $request->inquiry_type))
            ->when($request->priority === 'high', fn ($q) => $q->highIntent())
            ->when($request->search, function ($q) use ($request) {
                $q->where(function ($query) use ($request) {
                    $query->where('name', 'like', "%{$request->search}%")
                        ->orWhere('email', 'like', "%{$request->search}%");
                });
            })
            ->when($spamFilter === 'exclude', fn ($q) => $q->where('is_spam', false));
    }

    private function attachmentDisk(Lead $lead): ?string
    {
        if (! $lead->attachment_path) {
            return null;
        }

        if (Storage::disk('local')->exists($lead->attachment_path)) {
            return 'local';
        }

        if (Storage::disk('public')->exists($lead->attachment_path)) {
            return 'public';
        }

        return null;
    }

    private function deleteLeadAttachment(Lead $lead): void
    {
        $disk = $this->attachmentDisk($lead);
        if ($disk) {
            Storage::disk($disk)->delete($lead->attachment_path);
        }
    }
}
