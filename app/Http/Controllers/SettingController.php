<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\Setting;
use App\Http\Requests\SettingRequest;

class SettingController extends Controller
{
    public function index(Request $request): View
    {
        $query = Setting::query()
            ->when($request->category, fn($q) => $q->where('category', $request->category))
            ->when($request->search, fn($q) => $q->where('key', 'like', "%{$request->search}%"));

        $settings = $query->orderBy('category')->orderBy('key')->paginate(20)->appends($request->query());
        
        $categories = Setting::distinct()->pluck('category')->sort();

        return view('admin.settings.index', compact('settings', 'categories'));
    }

    public function create(): View
    {
        $setting = new Setting();
        $categories = Setting::distinct()->pluck('category')->sort();
        $types = ['text', 'textarea', 'boolean', 'integer', 'float', 'json'];

        return view('admin.settings.create', compact('setting', 'categories', 'types'));
    }

    public function store(SettingRequest $request): RedirectResponse
    {
        Setting::create($request->validated());

        return redirect()->route('admin.settings.index')
            ->with('success', 'Setting created successfully.');
    }

    public function show(Setting $setting): View
    {
        return view('admin.settings.show', compact('setting'));
    }

    public function edit(Setting $setting): View
    {
        $categories = Setting::distinct()->pluck('category')->sort();
        $types = ['text', 'textarea', 'boolean', 'integer', 'float', 'json'];

        return view('admin.settings.edit', compact('setting', 'categories', 'types'));
    }

    public function update(SettingRequest $request, Setting $setting): RedirectResponse
    {
        $setting->update($request->validated());

        // Clear cache for this setting
        \Illuminate\Support\Facades\Cache::forget("setting_{$setting->key}");

        \App\Support\ContentCache::bust();


        return redirect()->route('admin.settings.index')
            ->with('success', 'Setting updated successfully.');
    }

    public function destroy(Setting $setting): RedirectResponse
    {
        // Clear cache before deleting
        \Illuminate\Support\Facades\Cache::forget("setting_{$setting->key}");
        
        $setting->delete();

        return redirect()->route('admin.settings.index')
            ->with('success', 'Setting deleted successfully.');
    }
}
