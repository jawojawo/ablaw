<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Contracts\Activity;

class Deposit extends Model
{
    use HasFactory;
    use LogsActivity;
    protected $fillable = [
        'billing_id',
        'payment_type',
        'amount',
        'deposit_date',
        'received_from',
        'user_id',
    ];
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([

                'payment_type',
                'amount',
                'deposit_date',
                'received_from',
                'user_id',
            ])->logOnlyDirty()->dontSubmitEmptyLogs();
    }
    public function tapActivity(Activity $activity, string $eventName)
    {
        // $lawCase = $this->lawCase;

        $activity->properties = $activity->properties->merge([
            'attributes' => array_merge(
                [
                    'law_case_id' => $this->billing->law_case_id,
                    'billing_id' => $this->billing_id,
                    'defecit' => $this->billing->defecit,
                ],
                $activity->properties['attributes'] ?? [],
            ),
        ]);
        // $activity->properties = $activity->properties->merge([
        //     'attributes' => array_merge(
        //         [
        //             'custom_messages' => [
        //                 '<div class="d-flex justify-content-between gap-4">
        //                 <div><span class="fw-bold">' . $lawCase->case_number . '</span></div>
        //                 <div class="fst-italic">' . $this->billing->title . '</div>
        //                 </div>',
        //             ]
        //         ],
        //         $activity->properties['attributes'] ?? [],
        //     ),
        // ]);
    }
    // public function tapActivity(Activity $activity, string $eventName)
    // {
    //     if ($eventName === 'updated' || $eventName === 'created' || $eventName === 'deleted') {
    //         $billing = $this->billing;
    //         $lawCase = $billing->lawCase;
    //         switch ($eventName) {
    //             case 'created':
    //                 $newPaid =  $billing->total_paid + $this->amount;
    //                 break;
    //             case 'deleted':
    //                 $newPaid =  $billing->total_paid  - $this->amount;
    //                 break;
    //             case 'updated':
    //                 $originalAmount = $activity->properties['old']['amount'] ?? 0;
    //                 $newAmount = $activity->properties['attributes']['amount'] ?? 0;
    //                 if ($originalAmount != $newAmount) {
    //                     $difference = $newAmount - $originalAmount;
    //                     $newPaid =  $billing->total_paid + $difference;
    //                 }
    //                 break;
    //         }
    //         if ($newPaid) {
    //             $existingAttributes = $activity->properties->get('attributes', []);
    //             $newAttributes = array_merge($existingAttributes, [
    //                 'custom_messages' => [
    //                     '<div class="fw-bold">Total Paid</div><div class="text-end"><span class="text-muted">' . formattedMoney($billing->total_paid)
    //                         . '</span> → <span class="fw-bold">' . formattedMoney($newPaid) . '</span></div>',
    //                     ($billing->amount - $newPaid) ? '<div class="fw-bold">Defecit</div><div class="text-end fw-bold">' . formattedMoney($billing->amount) . '</div>' : '',
    //                     '<div class="fw-bold">Case</div><div class="text-end fw-bold">' . $lawCase->case_number . '</div>',
    //                 ],
    //             ]);
    //             $activity->properties = $activity->properties->merge([
    //                 'attributes' => $newAttributes,
    //             ]);
    //         }
    //     }
    // }
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($deposit) {
            if (Auth::check()) {
                $deposit->user_id = Auth::id();
            }
            $billing = $deposit->billing;
            $billing->total_paid += $deposit->amount;
            $billing->save();
            static::updateBillingStatus($billing);
        });

        // static::created(function ($deposit) {});
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
    public function lawCase()
    {
        return $this->hasOneThrough(
            LawCase::class,
            Billing::class,
            'id',
            'id',
            'billing_id',
            'law_case_id'
        );
    }
    public function client()
    {
        return $this->hasOneThrough(
            Client::class,     // Target model
            LawCase::class,  // Intermediate model
            'id',              // Foreign key on cases table (case_id in billings table)
            'id',              // Foreign key on clients table (client_id in cases table)
            'billing_id',      // Local key on deposits table
            'client_id'        // Local key on cases table
        )->join('billings', 'billings.id', '=', 'deposits.billing_id');
    }
    public function notes()
    {
        return $this->morphMany(Note::class, 'notable');
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
