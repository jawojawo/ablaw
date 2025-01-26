<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Contracts\Activity;

class AdministrativeFee extends Model
{
    use HasFactory;
    use LogsActivity;
    protected $fillable = [
        'law_case_id',
        'type',
        'amount',
        'fee_date',
    ];
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'type',
                'amount',
                'fee_date'
            ])->logOnlyDirty()->dontSubmitEmptyLogs();
    }

    public function tapActivity(Activity $activity, string $eventName)
    {
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
        // if ($eventName === 'updated' || $eventName === 'created' || $eventName === 'deleted') {
        //     $lawCase = $this->lawCase;
        //     switch ($eventName) {
        //         case 'created':
        //             $newFee =  $lawCase->total_fees + $this->amount;
        //             break;
        //         case 'deleted':
        //             $newFee =  $lawCase->total_fees - $this->amount;
        //             break;
        //         case 'updated':
        //             $originalAmount = $activity->properties['old']['amount'] ?? 0;
        //             $newAmount = $activity->properties['attributes']['amount'] ?? 0;
        //             if ($originalAmount != $newAmount) {
        //                 $difference = $newAmount - $originalAmount;
        //                 $newFee =  $lawCase->total_fees + $difference;
        //             }
        //             break;
        //     }
        //     if ($newFee) {
        //         $existingAttributes = $activity->properties->get('attributes', []);
        //         $newAttributes = array_merge($existingAttributes, [
        //             'custom_messages' => [
        //                 '<div class="fw-bold">' .  $lawCase->case_number . ' total Expenses </div><div class="text-end"><span class="text-muted">' . formattedMoney($lawCase->total_fees)
        //                     . '</span> → <span class="fw-bold">' . formattedMoney($newFee) . '</span></div>',
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
        static::creating(function ($adminFee) {
            if (Auth::check()) {
                $adminFee->user_id = Auth::id();
            }
            $lawCase = $adminFee->lawCase;
            $lawCase->total_fees += $adminFee->amount;
            $lawCase->save();
        });
        // static::created(function ($adminFee) {
        //     $lawCase = $adminFee->lawCase;
        //     $lawCase->total_fees += $adminFee->amount;
        //     $lawCase->save();
        // });
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
    public function notes()
    {
        return $this->morphMany(Note::class, 'notable');
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
