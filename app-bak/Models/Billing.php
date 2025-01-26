<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Billing extends Model
{
    use HasFactory;
    protected $fillable = [
        'law_case_id',
        'title',
        'amount',
        'total_paid',
        'billing_date',
        'due_date',
        'status',
        'user_id',
    ];
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
            } elseif ($billing->total_paid > 0) {
                $billing->status = 'partially_paid';
            } else {
                $billing->status = 'unpaid';
            }
            $billing->saveQuietly();
        });
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
            return 'table-secondary';
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
    public function getDueDateBadgeAttribut()
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
}
