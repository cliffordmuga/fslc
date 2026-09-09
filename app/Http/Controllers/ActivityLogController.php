<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
    public function index(Request $request): View
    {
        $subjectTypes = Activity::query()
            ->whereNotNull('subject_type')
            ->distinct()
            ->orderBy('subject_type')
            ->pluck('subject_type');

        $activities = Activity::with('causer')
            ->when($request->causer_id, fn ($q) => $q->where('causer_id', $request->causer_id))
            ->when($request->subject_type, fn ($q) => $q->where('subject_type', $request->subject_type))
            ->when($request->search, fn ($q) => $q->where('description', 'like', '%' . $request->search . '%'))
            ->latest()
            ->paginate(15)
            ->appends($request->query());

        $users = User::query()->orderBy('name')->get(['id', 'name']);

        return view('admin.activity.index', compact('activities', 'subjectTypes', 'users'));
    }
}
