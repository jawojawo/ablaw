<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Contracts\Activity;

class Billing extends Model
{
    use HasFactory;
    use LogsActivity;
    protected $fillable = [
        'law_case_id',
        'title',
        'amount',
        'total_paid',
        'billing_date',
        'fully_paid_date',
        'due_date',
        'status',

    ];
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'title',
                'amount',
                'billing_date',
                'due_date',
                'status'
            ])->logOnlyDirty()->dontSubmitEmptyLogs();
    }
    public function tapActivity(Activity $activity, string $eventName)
    {
        if ($eventName === 'deleted') {
            $activity->properties = $activity->properties->merge([
                'old' => array_merge(
                    [
                        'law_case_id' => $this->law_case_id,
                    ],
                    $activity->properties['old'] ?? [],
                ),
            ]);
        } else {
            $activity->properties = $activity->properties->merge([
                'attributes' => array_merge(
                    [
                        'law_case_id' => $this->law_case_id,
                    ],
                    $activity->properties['attributes'] ?? [],
                ),
            ]);
        }
    }
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($billing) {
            if (Auth::check()) {
                $billing->user_id = Auth::id();
            }
        });
        static::updating(function ($billing) {
            //$originalAmount = $billing->getOriginal('amount');
            $newAmount = $billing->amount;
            if ($billing->total_paid >= $newAmount) {
                $billing->status = 'paid';
                //$fullyPaidDate = $billing->deposits()->orderBy('deposit_date', 'desc')->get()->first();
                //$billing->fully_paid_date = $fullyPaidDate->deposit_date;
                // $billing->fully_paid_date =$billing->deposit->find;
            } elseif ($billing->total_paid > 0) {
                $billing->status = 'partially_paid';
                //  $billing->fully_paid_date = null;
            } else {
                $billing->status = 'unpaid';
                // $billing->fully_paid_date = null;
            }
            $billing->saveQuietly();
        });
    }
    public function notes()
    {
        return $this->morphMany(Note::class, 'notable');
    }
    public function scopeUnpaidOverDueBillings($query)
    {
        return $query->whereIn('status', ['unpaid', 'partially_paid'])
            ->orderBy('due_date');
    }

    public function scopeOrderedBillings($query)
    {
        return $query->orderByRaw("
        CASE 
            WHEN billings.status IN ('unpaid', 'partially_paid')  THEN 1
            WHEN billings.status = 'paid' THEN 2
            ELSE 3
        END,
        due_date ASC
    ");
    }
    public function client()
    {
        return $this->hasOneThrough(Client::class, LawCase::class, 'id', 'id', 'law_case_id', 'client_id');
    }
    public function getDefecitAttribute()
    {
        return max(0, $this->amount - $this->total_paid);
    }
    public function getDueDateTableClassAttribute()
    {
        // $dueDate = Carbon::parse($this->due_date);
        // if ($this->status == 'paid')
        //     return 'table-secondary';
        // if ($dueDate->isToday()) {
        //     return 'table-warning';
        // } elseif ($dueDate->isPast()) {
        //     return 'table-danger';
        // } elseif ($dueDate->isFuture()) {
        //     return 'table-primary';
        // }
        // return '';
        $dueDate = Carbon::parse($this->due_date);
        if ($this->status == 'paid')
            return 'table-success';
        if ($dueDate->isToday()) {
            return 'table-primary';
        } elseif ($dueDate->isPast()) {
            return 'table-danger';
        }
        return '';
    }

    public function getStatusClassAttribute()
    {
        $status = $this->status;
        if ($status == 'paid') {
            return 'bg-success';
        } elseif ($status == 'partially_paid') {
            return 'bg-warning';
        } elseif ($status == 'unpaid') {
            return 'bg-danger';
        }
        return '';
    }
    public function getStatusTableClassAttribute()
    {
        $status = $this->status;
        if ($status == 'paid') {
            return 'table-success';
        } elseif ($status == 'partially_paid') {
            return 'table-warning';
        } elseif ($status == 'unpaid') {
            return 'table-danger';
        }
        return '';
    }
    public function getDueDateBadgeAttribute()
    {
        $dueDate = Carbon::parse($this->due_date);
        if ($this->status == 'paid')
            return '';
        if ($dueDate->isToday()) {
            return `<span class="badge table-warning"> Due Date Today </span>`;
        } elseif ($dueDate->isPast()) {
            return 'table-danger';
        } elseif ($dueDate->isFuture()) {
            return 'table-primary';
        }
        return '';
    }

    public function lawCase()
    {
        return $this->belongsTo(LawCase::class);
    }
    public function deposits()
    {
        return $this->hasMany(Deposit::class);
    }
    public function getFormattedBillingDateAttribute()
    {
        return Carbon::parse($this->billing_date)->format('M j, Y');
    }
    public function getFormattedDueDateAttribute()
    {
        return Carbon::parse($this->due_date)->format('M j, Y');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getStatusBadgeAttribute()
    {
        if ($this->status == 'paid')
            return "<div class='border border-secondary bg-success m-auto' style='width:20px;height:20px'></div>";
        if ($this->status != 'paid') {
            $dueDate = Carbon::parse($this->due_date);
            if ($dueDate->isToday())
                return "<div class='border border-secondary bg-primary m-auto' style='width:20px;height:20px'></div>";
            if ($dueDate->isPast())
                return "<div class='border border-secondary bg-danger m-auto' style='width:20px;height:20px'></div>";
            if ($dueDate->isFuture())
                return "<div class='border border-secondary m-auto' style='width:20px;height:20px'></div>";
        }
    }
}
