<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\Cta;
use App\Models\Content;
use App\Http\Requests\CtaRequest;
use Illuminate\Http\JsonResponse;

class CtaController extends Controller
{
    public function index(Request $request): View
    {
        $query = Cta::with('content')
            ->when($request->content_id, fn($q) => $q->where('content_id', $request->content_id))
            ->when($request->type, fn($q) => $q->where('type', $request->type))
            ->latest();

        $ctas = $query->paginate(15)->appends($request->query());
        $contents = \App\Support\AdminUiCache::publishedContentOptions();

        return view('admin.ctas.index', compact('ctas', 'contents'));
    }

    public function create(): View
    {
        $cta = new Cta();
        $contents = \App\Support\AdminUiCache::publishedContentOptions();

        return view('admin.ctas.create', compact('cta', 'contents'));
    }

    public function store(CtaRequest $request): RedirectResponse
    {
        Cta::create($request->validated());

        return redirect()->route('admin.ctas.index')
            ->with('success', 'CTA created successfully.');
    }

    public function show(Cta $cta): View
    {
        $cta->load('content');

        return view('admin.ctas.show', compact('cta'));
    }

    public function edit(Cta $cta): View
    {
        $contents = \App\Support\AdminUiCache::publishedContentOptions();

        return view('admin.ctas.edit', compact('cta', 'contents'));
    }

    public function update(CtaRequest $request, Cta $cta): RedirectResponse
    {
        $cta->update($request->validated());

        return redirect()->route('admin.ctas.index')
            ->with('success', 'CTA updated successfully.');
    }

    public function destroy(Cta $cta): RedirectResponse
    {
        $cta->delete();

        return redirect()->route('admin.ctas.index')
            ->with('success', 'CTA deleted successfully.');
    }

    public function click(Request $request): JsonResponse
    {
        $request->validate([
            'cta_id' => 'required|exists:ctas,id'
        ]);

        $cta = Cta::findOrFail($request->cta_id);
        $cta->increment('clicks');

        // Track CTA click in page analytics
        if ($cta->content_id) {
            $today = now()->toDateString();
            \App\Models\PageAnalytic::where('content_id', $cta->content_id)
                ->where('date', $today)
                ->increment('cta_clicks');
        }

        return response()->json(['success' => true]);
    }
}
