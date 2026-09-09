<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\Testimonial;
use App\Http\Requests\TestimonialRequest;

class TestimonialController extends Controller
{
    public function index(Request $request): View
    {
        $query = Testimonial::query()
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->featured !== null, fn($q) => $q->where('is_featured', $request->featured))
            ->when($request->search, fn($q) => $q->where('client_name', 'like', "%{$request->search}%"))
            ->latest();

        $testimonials = $query->paginate(15)->appends($request->query());
        $statuses = Testimonial::STATUSES;

        return view('admin.testimonials.index', compact('testimonials', 'statuses'));
    }

    public function create(): View
    {
        $testimonial = new Testimonial();
        $statuses = Testimonial::STATUSES;

        return view('admin.testimonials.create', compact('testimonial', 'statuses'));
    }

    public function store(TestimonialRequest $request): RedirectResponse
    {
        Testimonial::create($request->validated());

        return redirect()->route('admin.testimonials.index')
            ->with('success', 'Testimonial created successfully.');
    }

    public function show(Testimonial $testimonial): View
    {
        return view('admin.testimonials.show', compact('testimonial'));
    }

    public function edit(Testimonial $testimonial): View
    {
        $statuses = Testimonial::STATUSES;

        return view('admin.testimonials.edit', compact('testimonial', 'statuses'));
    }

    public function update(TestimonialRequest $request, Testimonial $testimonial): RedirectResponse
    {
        $testimonial->update($request->validated());

        return redirect()->route('admin.testimonials.index')
            ->with('success', 'Testimonial updated successfully.');
    }

    public function destroy(Testimonial $testimonial): RedirectResponse
    {
        $testimonial->delete();

        return redirect()->route('admin.testimonials.index')
            ->with('success', 'Testimonial deleted successfully.');
    }

    public function approve(Testimonial $testimonial): RedirectResponse
    {
        $testimonial->update(['status' => 'approved']);

        return back()->with('success', 'Testimonial approved successfully.');
    }

    public function reject(Testimonial $testimonial): RedirectResponse
    {
        $testimonial->update(['status' => 'rejected']);

        return back()->with('success', 'Testimonial rejected.');
    }

    public function toggleFeatured(Testimonial $testimonial): RedirectResponse
    {
        $testimonial->update(['is_featured' => !$testimonial->is_featured]);

        $message = $testimonial->is_featured ? 'Testimonial marked as featured.' : 'Testimonial unmarked as featured.';
        
        return back()->with('success', $message);
    }

    public function bulkApprove(Request $request): RedirectResponse
    {
        $request->validate([
            'testimonial_ids' => ['required', 'array', 'min:1'],
            'testimonial_ids.*' => ['integer', 'exists:testimonials,id'],
        ]);

        $count = Testimonial::query()
            ->whereIn('id', $request->testimonial_ids)
            ->where('status', 'pending')
            ->update(['status' => 'approved']);

        return back()->with('success', "Approved {$count} pending testimonial(s).");
    }
}
