<?php

namespace App\Http\Controllers\Web\BackEnd;

use App\Enums\PermissionEnum;
use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ActivityLogController extends Controller
{
    public function index(Request $request): View | RedirectResponse
    {
        Gate::authorize(PermissionEnum::READ_LOGS->value);

        $allowedFilterFields = ['log_name', 'description', 'causer_id', 'subject_type', 'subject_id'];
        $allowedSortFields = ['created_at', 'updated_at', 'id'];
        $limits = [10, 25, 50, 100];

        $logs = ActivityLog::search(
                keyword: $request->keyword,
                columns: $allowedFilterFields,
            )->sort(
                sort_by: $request->sort_by ?? 'created_at',
                sort_order: $request->sort_order ?? 'DESC'
            )
            ->paginate($request->query('limit') ?? 10);

        return view('pages.log.index', [
            'title' => 'Activity Log',
            'logs' => $logs,
            'allowedFilterFields' => $allowedFilterFields,
            'allowedSortFields' => $allowedSortFields,
            'limits' => $limits
        ]);
    }
}
