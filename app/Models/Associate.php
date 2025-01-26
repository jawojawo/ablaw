<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Associate extends Model
{
    use HasFactory;
    use LogsActivity;
    protected $fillable = [
        'name',
        'address',
    ];
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()->logOnlyDirty()->dontSubmitEmptyLogs();
    }
    public function contacts()
    {
        return $this->morphMany(Contact::class, 'contactable');
    }
    public function notes()
    {
        return $this->morphMany(Note::class, 'notable');
    }

    public function lawCases()
    {
        return $this->hasMany(LawCase::class);
    }
    public function getCaseStatusCountBadgesAttribute()
    {

        $statusCounts = $this->lawCases()
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $badgeClasses = [
            'open' => 'badge bg-primary',
            'in_progress' => 'badge bg-info',
            'settled' => 'badge bg-warning',
            'won' => 'badge bg-success',
            'lost' => 'badge bg-danger',
            'archived' => 'badge bg-secondary',
            'appeal' => 'badge bg-secondary',
            'closed' => 'badge bg-secondary',
        ];

        $badges = [];
        foreach ($statusCounts as $status => $count) {
            $class = $badgeClasses[$status] ?? 'badge bg-light'; // Default badge class
            $readableStatus = Str::headline($status);
            $badges[] = "<span class='badge rounded-pill {$class} m-1'><span class='text-bg-light badge'>{$count}</span> {$readableStatus}</span>";
        }

        // Return the badges as a string
        return implode(' ', $badges);
    }
    public function getCaseTypeCountBadgesAttribute()
    {
        $typeCounts = $this->lawCases()
            ->select('case_type', DB::raw('count(*) as total'))
            ->groupBy('case_type')
            ->pluck('total', 'case_type');

        $badgeClasses = [
            'litigation' => 'badge t-success',
            'non_litigation' => 'badge t-danger',
        ];

        $badges = [];
        foreach ($typeCounts as $type => $count) {
            $class = $badgeClasses[$type] ?? 'badge bg-secondary';
            $readableType = Str::headline(str_replace('_', ' ', $type)); // Convert to headline format
            $badges[] = "<span class='rounded-pill {$class} m-1'><span class='text-bg-light badge'>{$count}</span> {$readableType}</span>";
        }

        return implode(' ', $badges);
    }
}
