<?php

namespace App\Models;

use Spatie\Activitylog\Models\Activity;
use Yogameleniawan\SearchSortEloquent\Traits\Searchable;
use Yogameleniawan\SearchSortEloquent\Traits\Sortable;

class ActivityLog extends Activity
{
    use Searchable, Sortable;

    protected $table = 'activity_log';
}