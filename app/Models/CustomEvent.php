<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class CustomEvent extends Model
{
    use HasFactory;
    use LogsActivity;
    protected $fillable = [
        'title',
        'description',
        'type',
        'start_time',
        'end_time',
        'location',
    ];
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()->logOnlyDirty()->dontSubmitEmptyLogs();
    }
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($lawCase) {
            if (Auth::check()) {
                $lawCase->user_id = Auth::id();
            }
        });
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function notes()
    {
        return $this->morphMany(Note::class, 'notable');
    }
    public function scopeSortByEventStatus($query)
    {
        return $query->orderByRaw("
            CASE
                WHEN start_time <= NOW() AND end_time >= NOW() THEN 1
                WHEN start_time > NOW() THEN 2
                ELSE 3
            END, start_time ASC
        ");
    }
    public function getStatusAttribute()
    {
        $now = Carbon::now();

        if ($this->start_time <= $now && $this->end_time >= $now) {
            return 'ongoing';
        } elseif ($this->start_time > $now) {
            return 'upcoming';
        } else {
            return 'past';
        }
    }
    public function getStatusBadgeAttribute()
    {
        switch ($this->status) {
            case 'ongoing':
                return "<div class='border border-secondary bg-primary m-auto' style='width:20px;height:20px'></div>";
                break;

            case 'upcoming':
                return "<div class='border border-secondary m-auto' style='width:20px;height:20px'></div>";
                break;
            case 'past':
                return "<div class='border border-secondary bg-secondary m-auto' style='width:20px;height:20px'></div>";
                break;

            default:
                # code...
                break;
        }
    }
}
