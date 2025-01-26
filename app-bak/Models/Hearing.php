<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Hearing extends Model
{
    use HasFactory;
    protected $fillable = [
        'law_case_id',
        'title',
        'hearing_date',
        'court_branch_id',
        'user_id',
    ];
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($adminDeposit) {
            if (Auth::check()) {
                $adminDeposit->user_id = Auth::id();
            }
        });
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
        return $query
            ->orderByRaw("CASE WHEN hearing_date >= NOW() THEN 0 ELSE 1 END, 
                              CASE WHEN hearing_date >= NOW() THEN hearing_date ELSE NULL END ASC, 
                              hearing_date DESC");
    }
}
