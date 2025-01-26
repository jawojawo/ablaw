<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Note extends Model
{
    use HasFactory;
    protected $fillable = ['note', 'reminder_date'];
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
    public function notable()
    {
        return $this->morphTo();
    }
}
