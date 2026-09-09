<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContentRequest;
use App\Models\Content;
use App\Models\Image;
use App\Services\ContentService;
use App\Services\ImageService;
use App\Services\SeoService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\View\View;

class ContentController extends Controller
{
    public function __construct(
        private ContentService $contentService,
        private ImageService $imageService,
        private SeoService $seoService
    ) {}

    public function index(Request $request): View
    {
        $query = Content::with(['creator', 'images' => fn ($q) => $q->where('collection', 'featured')])
            ->when($request->type, fn ($q) => $q->where('type', $request->type))
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->when($request->search, fn ($q) => $q->where('title', 'like', "%{$request->search}%"))
            ->latest();

        $contents = $query->paginate(15)->appends($request->query());
        $types = Content::typeLabels();
        $statuses = Content::STATUSES;

        return view('admin.content.index', compact('contents', 'types', 'statuses'));
    }

    public function create(): View
    {
        $content = new Content;
        $types = Content::typeLabels();
        $statuses = Content::STATUSES;

        return view('admin.content.create', compact('content', 'types', 'statuses'));
    }

    public function store(ContentRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $content = Content::create([
            ...$validated,
            'created_by' => Auth::id(),
        ]);

        // Sync tags if provided
        if ($request->filled('tags')) {
            $content->tags()->sync($request->tags);
        }

        // Handle image uploads
        $this->handleImages($request, $content);

        // Handle SEO metadata (always ensure it exists; auto-generate when empty)
        $this->handleSeoMetadata($request, $content);

        return redirect()->route('admin.content.index')
            ->with('success', 'Content created successfully.');
    }

    public function show(Content $content): View
    {
        $content->load(['creator', 'images', 'seoMetadata', 'ctas', 'analytics']);
        $groupedImages = $this->imageService->groupImagesByUuid($content->images);

        return view('admin.content.show', compact('content', 'groupedImages'));
    }

    public function edit(Content $content): View
    {
        $content->load(['images', 'seoMetadata']);
        $types = Content::typeLabels();
        $statuses = Content::STATUSES;
        $groupedImages = $this->imageService->groupImagesByUuid($content->images);

        return view('admin.content.edit', compact('content', 'types', 'statuses', 'groupedImages'));
    }

    public function preview(Content $content): RedirectResponse
    {
        return redirect()->to(
            URL::temporarySignedRoute(
                'content.preview',
                now()->addHours(2),
                ['content' => $content->id]
            )
        );
    }

    public function update(ContentRequest $request, Content $content): RedirectResponse
    {
        $content->update($request->validated());

        // Sync tags
        if ($request->filled('tags')) {
            $content->tags()->sync($request->tags);
        } else {
            $content->tags()->detach();
        }

        // Handle image uploads
        $this->handleImages($request, $content);

        // Handle SEO metadata
        $this->handleSeoMetadata($request, $content);

        // Update image alt text
        foreach ($request->input('image_alt', []) as $uuid => $altText) {
            Image::where('imageable_type', Content::class)
                ->where('imageable_id', $content->id)
                ->where('image_url', 'like', "%-{$uuid}-%")
                ->update(['alt_text' => $altText ?: null]);
        }

        // Handle image deletions (from checkboxes) – UUIDs identify image groups
        $imageGroupUuidsToDelete = $request->input('images_to_delete', []);
        if (! empty($imageGroupUuidsToDelete)) {
            $this->imageService->deleteImageGroupByUuids($imageGroupUuidsToDelete);
        }

        return redirect()->route('admin.content.index')
            ->with('success', 'Content updated successfully.');
    }

    public function destroy(Content $content): RedirectResponse
    {
        $content->deleteImages($content->id);
        $content->delete();

        return redirect()->route('admin.content.index')
            ->with('success', 'Content deleted successfully.');
    }

    private function handleImages(ContentRequest $request, Content $content): void
    {
        $slug = $content->slug;
        $folder = match ($content->type) {
            'portfolio' => 'portfolio',
            'services' => 'services',
            default => 'pages',
        };

        // Featured image
        if ($request->hasFile('featured_image')) {
            $altBase = $content->title ?: $slug;
            $this->imageService->uploadImageVariants(
                file: $request->file('featured_image'),
                folder: $folder,
                slug: "{$slug}-featured",
                altText: $this->imageService->generateAltText($altBase, 'Featured'),
                modelClass: Content::class,
                modelId: $content->id,
                collection: 'featured'
            );
        }

        // Gallery images
        if ($request->hasFile('gallery_images')) {
            $altBase = $content->title ?: $slug;
            foreach ($request->file('gallery_images') as $index => $file) {
                $this->imageService->uploadImageVariants(
                    file: $file,
                    folder: $folder,
                    slug: "{$slug}-gallery-{$index}",
                    altText: $this->imageService->generateAltText($altBase, 'Gallery '.($index + 1)),
                    modelClass: Content::class,
                    modelId: $content->id,
                    collection: 'gallery',
                    customVariants: null,
                    sortOrder: $index,
                );
            }
        }
    }

    private function handleSeoMetadata(ContentRequest $request, Content $content): void
    {
        // Always ensure SEO metadata exists. Use request values when provided;
        // otherwise SeoService auto-generates from title, excerpt, content.
        $this->seoService->updateMetadata(
            model: $content,
            title: $request->filled('meta_title') ? $request->meta_title : null,
            description: $request->filled('meta_description') ? $request->meta_description : null,
            keywords: $request->input('meta_keywords'),
            canonicalUrl: $request->filled('canonical_url') ? $request->canonical_url : null,
            ogImage: $request->input('og_image'),
            ogTitle: $request->filled('og_title') ? $request->og_title : null,
            ogDescription: $request->filled('og_description') ? $request->og_description : null,
            noindex: $request->boolean('noindex', false),
            nofollow: $request->boolean('nofollow', false)
        );
    }
}
