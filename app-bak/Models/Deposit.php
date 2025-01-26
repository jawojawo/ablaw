<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Deposit extends Model
{
    use HasFactory;
    protected $fillable = [
        'billing_id',
        'payment_type_id',
        'amount',
        'deposit_date',
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

        static::created(function ($deposit) {
            $billing = $deposit->billing;
            $billing->total_paid += $deposit->amount;
            $billing->save();
            static::updateBillingStatus($billing);
        });
        static::updating(function ($deposit) {
            $billing = $deposit->billing;
            $originalAmount = $deposit->getOriginal('amount');
            $newAmount = $deposit->amount;
            $billing->total_paid += ($newAmount - $originalAmount);
            $billing->save();

            static::updateBillingStatus($billing);
        });

        static::deleting(function ($deposit) {
            $billing = $deposit->billing;
            $billing->total_paid -= $deposit->amount;
            $billing->save();
            static::updateBillingStatus($billing);
        });
    }
    public function billing()
    {
        return $this->belongsTo(Billing::class);
    }

    public function payment_type()
    {
        return $this->belongsTo(PaymentType::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function updateBillingStatus($billing)
    {
        if ($billing->total_paid >= $billing->amount) {
            $billing->status = 'paid';
        } elseif ($billing->total_paid > 0) {
            $billing->status = 'partially paid';
        } else {
            $billing->status = 'unpaid';
        }
        $billing->save();
    }
}
