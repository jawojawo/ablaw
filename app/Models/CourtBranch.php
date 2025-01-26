<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class CourtBranch extends Model
{
    use HasFactory;
    use LogsActivity;
    protected $fillable = [
        'region',
        'city',
        'type',
        'branch',
        'address',
        'judge',
    ];
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()->logOnlyDirty()->dontSubmitEmptyLogs();
    }
    public function lawCase()
    {
        return $this->hasMany(LawCase::class);
    }
    public function hearings()
    {
        return $this->hasMany(Hearing::class);
    }
    public function nextHearing()
    {
        return $this->hearings()->where('status', 'upcoming')->orderBy('hearing_date', 'asc')->limit(1);
    }

    public function getFormattedCourtAttribute()
    {
        $abbType = abbreviate($this->type);
        return "$this->city / $abbType " . ($this->branch ? "/ {$this->branch}" : '');
    }
    public function contacts()
    {
        return $this->morphMany(Contact::class, 'contactable');
    }
    public function notes()
    {
        return $this->morphMany(Note::class, 'notable');
    }
}
