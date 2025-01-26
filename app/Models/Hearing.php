<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Contracts\Activity;

class Hearing extends Model
{
    use HasFactory;
    use LogsActivity;
    protected $fillable = [
        'law_case_id',
        'title',
        'hearing_date',
        'court_branch_id',
        'status',
        'user_id',
    ];
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'title',
                'hearing_date',
                'court_branch_id',
                'status',
            ])->logOnlyDirty()->dontSubmitEmptyLogs();
    }
    public function tapActivity(Activity $activity, string $eventName)
    {

        $activity->properties = $activity->properties->merge([
            'attributes' => array_merge(
                [
                    'law_case_id' => $this->law_case_id,
                ],
                $activity->properties['attributes'] ?? [],
            ),
        ]);
    }
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($hearing) {
            if (Auth::check()) {
                $hearing->user_id = Auth::id();
            }
        });
    }
    public function getStatusAttribute()
    {

        if ($this->hearing_date < now() && $this->attributes['status'] === 'upcoming') {

            return 'ongoing';
        }
        return $this->attributes['status'];
    }
    public function notes()
    {
        return $this->morphMany(Note::class, 'notable');
    }
    public function lawCase()
    {
        return $this->belongsTo(LawCase::class);
    }
    public function courtBranch()
    {
        return $this->belongsTo(CourtBranch::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function getFormattedHearingDateAttribute()
    {

        $hearingDate = Carbon::parse($this->hearing_date);
        // $hearingDateDate = Carbon::parse($this->hearing_date)->format('M j, Y');
        // $hearingDateTime = Carbon::parse($this->hearing_date)->format('g:i A');
        // return "
        // <div class='text-nowrap'>$hearingDateDate</div>
        // <div class='text-muted'>$hearingDateTime</div>
        // ";
        return [
            'full' => $hearingDate->format('M j, Y g:i A'),
            'date' => $hearingDate->format('M j, Y'),
            'time' => $hearingDate->format('g:i A'),
        ];
    }
    public function scopeOrderedHearings($query)
    {
        return $query->orderByRaw("
        CASE 
            WHEN hearings.status = 'upcoming' THEN 1
            WHEN hearings.status = 'completed' THEN 2
            WHEN hearings.status = 'canceled' THEN 3
            ELSE 4
        END
    ")->orderBy('hearing_date', 'asc');
        // return $query->orderBy('hearing_date', 'asc');
        // return $query
        //     ->orderByRaw("CASE WHEN hearing_date < NOW() THEN 0 ELSE 1 END, 
        //                       CASE WHEN hearing_date < NOW() THEN hearing_date ELSE NULL END ASC, 
        //                       hearing_date DESC");
    }
}
