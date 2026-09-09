<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Redirect;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\View\View;

class RedirectController extends Controller
{
    public function index(Request $request): View
    {
        $q = trim((string) $request->get('q', ''));
        $active = $request->get('active', '');

        $redirects = Redirect::query()
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($qq) use ($q) {
                    $qq->where('old_path', 'like', "%{$q}%")
                       ->orWhere('new_path', 'like', "%{$q}%");
                });
            })
            ->when($active !== '', function ($query) use ($active) {
                $query->where('is_active', $active === '1');
            })
            ->orderByDesc('updated_at')
            ->paginate(20)
            ->withQueryString();

        return view('admin.redirects.index', compact('redirects', 'q', 'active'));
    }

    public function create(): View
    {
        return view('admin.redirects.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);

        if ($this->normalizePathOnly($data['new_path']) === $data['old_path']) {
            return back()->withErrors(['new_path' => 'New path cannot be the same as old path.'])->withInput();
        }

        if ($this->wouldCreateLoop($data['old_path'], $data['new_path'], null)) {
            return back()->withErrors(['new_path' => 'This redirect would create a loop (cycle).'])->withInput();
        }

        Redirect::create($data);

        return redirect()->route('admin.redirects.index')->with('success', 'Redirect created.');
    }

    public function edit(Redirect $redirect): View
    {
        return view('admin.redirects.edit', compact('redirect'));
    }

    public function update(Request $request, Redirect $redirect): RedirectResponse
    {
        $data = $this->validated($request, $redirect->id);

        if ($this->normalizePathOnly($data['new_path']) === $data['old_path']) {
            return back()->withErrors(['new_path' => 'New path cannot be the same as old path.'])->withInput();
        }

        if ($this->wouldCreateLoop($data['old_path'], $data['new_path'], $redirect->id)) {
            return back()->withErrors(['new_path' => 'This redirect would create a loop (cycle).'])->withInput();
        }

        $redirect->update($data);

        return redirect()->route('admin.redirects.index')->with('success', 'Redirect updated.');
    }

    public function destroy(Redirect $redirect): RedirectResponse
    {
        $redirect->delete();
        return redirect()->route('admin.redirects.index')->with('success', 'Redirect deleted.');
    }

    public function toggle(Redirect $redirect): RedirectResponse
    {
        $redirect->update(['is_active' => !$redirect->is_active]);
        return redirect()->route('admin.redirects.index')->with('success', 'Redirect status updated.');
    }

    // -------------------------
    // Import (bulk create/update)
    // -------------------------
    public function importForm(): View
    {
        return view('admin.redirects.import');
    }

    public function import(Request $request): RedirectResponse
    {
        $request->validate([
            'csv' => ['required', 'string', 'min:3', 'max:100000'],
        ]);

        // CSV format (one per line):
        // old_path,new_path,status_code,is_active
        // /old,/new,301,1
        $lines = preg_split("/\r\n|\n|\r/", trim($request->input('csv')));
        $created = 0;
        $updated = 0;
        $skipped = 0;
        $errors = [];

        foreach ($lines as $idx => $line) {
            $lineNo = $idx + 1;
            $line = trim($line);

            if ($line === '' || Str::startsWith($line, ['#', '//'])) {
                continue;
            }

            $parts = str_getcsv($line);
            $parts = array_map('trim', $parts);

            if (count($parts) < 2) {
                $skipped++;
                $errors[] = "Line {$lineNo}: expected at least 2 columns (old_path,new_path).";
                continue;
            }

            $old = $this->normalizePath($parts[0]);
            $new = $this->normalizeTarget($parts[1]);

            $code = isset($parts[2]) && $parts[2] !== '' ? (int)$parts[2] : 301;
            if (!in_array($code, [301, 302, 307, 308], true)) $code = 301;

            $active = true;
            if (isset($parts[3]) && $parts[3] !== '') {
                $active = in_array((string)$parts[3], ['1', 'true', 'yes', 'on'], true);
            }

            if ($this->normalizePathOnly($new) === $old) {
                $skipped++;
                $errors[] = "Line {$lineNo}: skipped self-redirect ({$old} -> {$new}).";
                continue;
            }

            if ($this->wouldCreateLoop($old, $new, null)) {
                $skipped++;
                $errors[] = "Line {$lineNo}: skipped loop-causing redirect ({$old} -> {$new}).";
                continue;
            }

            $existing = Redirect::where('old_path', $old)->first();
            if ($existing) {
                $existing->update([
                    'new_path' => $new,
                    'status_code' => $code,
                    'is_active' => $active,
                ]);
                $updated++;
            } else {
                Redirect::create([
                    'old_path' => $old,
                    'new_path' => $new,
                    'status_code' => $code,
                    'is_active' => $active,
                    'hits' => 0,
                ]);
                $created++;
            }
        }

        $msg = "Import complete: {$created} created, {$updated} updated, {$skipped} skipped.";
        return redirect()
            ->route('admin.redirects.index')
            ->with('success', $msg)
            ->with('import_errors', $errors);
    }

    // -------------------------
    // Test tool (resolve chain)
    // -------------------------
    public function testForm(): View
    {
        return view('admin.redirects.test');
    }

    public function test(Request $request): View
    {
        $request->validate([
            'path' => ['required', 'string', 'max:500'],
        ]);

        $path = $this->normalizePath($request->input('path'));
        $result = $this->resolveChain($path);

        return view('admin.redirects.test', [
            'path' => $path,
            'result' => $result,
        ]);
    }

    // -------------------------
    // Helpers
    // -------------------------
    private function validated(Request $request, ?int $ignoreId = null): array
    {
        $data = $request->validate([
            'old_path' => ['required', 'string', 'max:500'],
            'new_path' => ['required', 'string', 'max:500'],
            'status_code' => ['required', 'integer', 'in:301,302,307,308'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['old_path'] = $this->normalizePath($data['old_path']);
        $data['new_path'] = $this->normalizeTarget($data['new_path']);
        $data['is_active'] = (bool)($data['is_active'] ?? true);

        $exists = Redirect::query()
            ->where('old_path', $data['old_path'])
            ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
            ->exists();

        if ($exists) {
            abort(422, 'Old path already exists.');
        }

        return $data;
    }

    private function normalizePath(string $path): string
    {
        $p = '/' . ltrim($path, '/');
        $p = rtrim($p, '/');
        return $p === '' ? '/' : $p;
    }

    private function normalizeTarget(string $target): string
    {
        if (preg_match('#^https?://#i', $target)) {
            return $target;
        }
        return $this->normalizePath($target);
    }

    private function normalizePathOnly(string $target): string
    {
        if (preg_match('#^https?://#i', $target)) {
            $parts = parse_url($target);
            $p = $parts['path'] ?? '/';
            $p = '/' . ltrim($p, '/');
            $p = rtrim($p, '/');
            return $p === '' ? '/' : $p;
        }

        $p = '/' . ltrim($target, '/');
        $p = rtrim($p, '/');
        return $p === '' ? '/' : $p;
    }

    /**
     * Detect if adding old -> new creates a loop (cycle).
     * We follow redirects starting from new_path (as a path) and see if we ever reach old_path.
     */
    private function wouldCreateLoop(string $oldPath, string $newTarget, ?int $ignoreId): bool
    {
        // Absolute external URLs cannot form an internal loop
        if (preg_match('#^https?://#i', $newTarget)) {
            return false;
        }

        $visited = [];
        $current = $this->normalizePathOnly($newTarget);

        // Limit chain traversal to avoid infinite loops
        for ($i = 0; $i < 20; $i++) {
            if (isset($visited[$current])) {
                return true; // loop elsewhere
            }
            $visited[$current] = true;

            if ($current === $oldPath) {
                return true;
            }

            $next = Redirect::query()
                ->where('is_active', true)
                ->where('old_path', $current)
                ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
                ->value('new_path');

            if (!$next) {
                return false;
            }

            if (preg_match('#^https?://#i', $next)) {
                return false;
            }

            $current = $this->normalizePathOnly($next);
        }

        // If it’s absurdly deep, treat as unsafe
        return true;
    }

    /**
     * Resolve redirect chain for testing: returns info about hops and final target.
     */
    private function resolveChain(string $path): array
    {
        $hops = [];
        $visited = [];
        $current = $path;

        for ($i = 0; $i < 20; $i++) {
            if (isset($visited[$current])) {
                return [
                    'matched' => true,
                    'loop' => true,
                    'hops' => $hops,
                    'final' => $current,
                ];
            }
            $visited[$current] = true;

            $r = Redirect::query()
                ->where('is_active', true)
                ->where('old_path', $current)
                ->first();

            if (!$r) {
                return [
                    'matched' => count($hops) > 0,
                    'loop' => false,
                    'hops' => $hops,
                    'final' => $current,
                ];
            }

            $hops[] = [
                'old' => $r->old_path,
                'new' => $r->new_path,
                'code' => $r->status_code,
                'id' => $r->id,
            ];

            // If absolute URL target, stop
            if (preg_match('#^https?://#i', $r->new_path)) {
                return [
                    'matched' => true,
                    'loop' => false,
                    'hops' => $hops,
                    'final' => $r->new_path,
                ];
            }

            $current = $this->normalizePathOnly($r->new_path);
        }

        return [
            'matched' => true,
            'loop' => true,
            'hops' => $hops,
            'final' => $current,
        ];
    }
}
