<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AdminDeposit extends Model
{
    use HasFactory;
    protected $fillable = [
        'law_case_id',
        'payment_type_id',
        'amount',
        'deposit_date',
        'success',
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
        static::created(function ($adminDeposit) {
            $lawCase = $adminDeposit->lawCase;
            $lawCase->total_deposits += $adminDeposit->amount;
            $lawCase->save();
        });
        static::updating(function ($adminDeposit) {
            $originalAmount = $adminDeposit->getOriginal('amount');
            if ($originalAmount != $adminDeposit->amount) {
                $difference = $adminDeposit->amount - $originalAmount;
                $adminDeposit->lawCase->increment('total_deposits', $difference);
            }
        });
        static::deleting(function ($adminDeposit) {
            $lawCase = $adminDeposit->lawCase;
            $lawCase->total_deposits -= $adminDeposit->amount;
            $lawCase->save();
        });
        // static::created(function ($adminDeposit) {
        //     $lawCase = $adminDeposit->lawCase;
        //     if ($adminDeposit->success) {
        //         $lawCase->total_deposits += $adminDeposit->amount;
        //         $lawCase->save();
        //     }
        // });

        // static::updating(function ($adminDeposit) {
        //     $originalAmount = $adminDeposit->getOriginal('amount');
        //     $originalSuccess = $adminDeposit->getOriginal('success');

        //     if ($originalSuccess && !$adminDeposit->success) {
        //         $adminDeposit->lawCase->decrement('total_deposits', $originalAmount);
        //     } elseif (!$originalSuccess && $adminDeposit->success) {
        //         $adminDeposit->lawCase->increment('total_deposits', $adminDeposit->amount);
        //     } elseif ($originalSuccess && $adminDeposit->success && $originalAmount != $adminDeposit->amount) {
        //         $difference = $adminDeposit->amount - $originalAmount;
        //         $adminDeposit->lawCase->increment('total_deposits', $difference);
        //     }
        // });

        // static::deleting(function ($adminDeposit) {
        //     if ($adminDeposit->success) {
        //         $lawCase = $adminDeposit->lawCase;
        //         $lawCase->total_deposits -= $adminDeposit->amount;
        //         $lawCase->save();
        //     }
        // });
    }
    public function lawCase()
    {
        return $this->belongsTo(LawCase::class);
    }
    public function paymentType()
    {
        return $this->belongsTo(PaymentType::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
