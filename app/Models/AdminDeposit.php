<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Contracts\Activity;

class AdminDeposit extends Model
{
    use HasFactory;
    use LogsActivity;
    protected $fillable = [
        'law_case_id',
        'payment_type',
        'amount',
        'deposit_date',
    ];
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'payment_type',
                'amount',
                'deposit_date'
            ])->logOnlyDirty()->dontSubmitEmptyLogs();
    }
    public function tapActivity(Activity $activity, string $eventName)
    {
        // $activity->properties = $activity->properties->merge([
        //     'attributes' => array_merge(
        //         ['law_case_id' => $this->law_case_id, 'remaining_deposit' => $this->lawCase->total_deposits - $this->lawCase->total_fees],
        //         $activity->properties['attributes'] ?? [],
        //     ),
        // ]);
        $lawCase = $this->lawCase;
        $remainingDeposit = $lawCase->total_deposits - $lawCase->total_fees;
        $class = $remainingDeposit > 0 ? 'text-success' : 'text-danger';
        $activity->properties = $activity->properties->merge([
            'attributes' => array_merge(
                [
                    'custom_messages' => [
                        '<div class="d-flex justify-content-between gap-4">
                        <div><span class="fw-bold">' . $lawCase->case_number . '</span> remaining deposit </div>
                        <div class="' . $class . '">' . formattedMoney($remainingDeposit) . '</div>
                        </div>',
                    ]
                ],
                $activity->properties['attributes'] ?? [],
            ),
        ]);
        // $lawCase = $this->lawCase;
        // $existingAttributes = $activity->properties->get('attributes', []);
        // $newAttributes = array_merge($existingAttributes, [
        //     'law_case_id' => $lawCase->id,
        // ]);
        // if ($eventName === 'updated' || $eventName === 'created' || $eventName === 'deleted') {
        //     $lawCase = $this->lawCase;
        //     switch ($eventName) {
        //         case 'created':
        //             $newDeposit =  $lawCase->total_deposits + $this->amount;
        //             break;
        //         case 'deleted':
        //             $newDeposit =  $lawCase->total_deposits - $this->amount;
        //             break;
        //         case 'updated':
        //             $originalAmount = $activity->properties['old']['amount'] ?? 0;
        //             $newAmount = $activity->properties['attributes']['amount'] ?? 0;
        //             if ($originalAmount != $newAmount) {
        //                 $difference = $newAmount - $originalAmount;
        //                 $newDeposit =  $lawCase->total_deposits + $difference;
        //             }
        //             break;
        //     }
        //     if ($newDeposit) {
        //         $existingAttributes = $activity->properties->get('attributes', []);
        //         $newAttributes = array_merge($existingAttributes, [
        //             'custom_messages' => [
        //                 '<div class="fw-bold">' .  $lawCase->case_number . ' total Deposits </div><div class="text-end"><span class="text-muted">' . formattedMoney($lawCase->total_deposits)
        //                     . '</span> → <span class="fw-bold">' . formattedMoney($newDeposit) . '</span></div>',
        //             ],
        //         ]);
        //         $activity->properties = $activity->properties->merge([
        //             'attributes' => $newAttributes,
        //         ]);
        //     }
        // }
    }
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($adminDeposit) {
            if (Auth::check()) {
                $adminDeposit->user_id = Auth::id();
            }
            $lawCase = $adminDeposit->lawCase;
            $lawCase->total_deposits += $adminDeposit->amount;
            $lawCase->save();
        });
        // static::created(function ($adminDeposit) {
        //     $lawCase = $adminDeposit->lawCase;
        //     $lawCase->total_deposits += $adminDeposit->amount;
        //     $lawCase->save();
        // });
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
    public function notes()
    {
        return $this->morphMany(Note::class, 'notable');
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
