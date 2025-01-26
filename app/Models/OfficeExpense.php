<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class OfficeExpense extends Model
{
    use HasFactory;
    use LogsActivity;
    protected $fillable = [
        'type',
        'description',
        'amount',
        'expense_date',
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
}
