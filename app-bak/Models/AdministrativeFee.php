<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AdministrativeFee extends Model
{
    use HasFactory;
    protected $fillable = [
        'law_case_id',
        'administrative_fee_category_id',
        'amount',
        'fee_date',
        'user_id'
    ];
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($adminFee) {
            if (Auth::check()) {
                $adminFee->user_id = Auth::id();
            }
        });
        static::created(function ($adminFee) {
            $lawCase = $adminFee->lawCase;
            $lawCase->total_fees += $adminFee->amount;
            $lawCase->save();
        });
        static::updating(function ($adminFee) {
            $originalAmount = $adminFee->getOriginal('amount');
            if ($originalAmount != $adminFee->amount) {
                $difference = $adminFee->amount - $originalAmount;
                $adminFee->lawCase->increment('total_fees', $difference);
            }
        });

        static::deleting(function ($adminFee) {
            $lawCase = $adminFee->lawCase;
            $lawCase->total_fees -= $adminFee->amount;
            $lawCase->save();
        });
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function lawCase()
    {
        return $this->belongsTo(LawCase::class);
    }
    public function adminFeeCategory()
    {
        return $this->belongsTo(AdministrativeFeeCategory::class, 'administrative_fee_category_id');
    }
}
