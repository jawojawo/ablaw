<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class LawCase extends Model
{
    use HasFactory;
    use LogsActivity;
    protected $fillable = [
        'case_number',
        'case_title',
        'client_id',
        'party_role',
        'associate_id',
        'opposing_party',
        'case_type',
        'total_deposits',
        'total_fees',
        'status',
        'user_id'
    ];
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'case_number',
                'case_title',
                'client_id',
                'party_role',
                'associate_id',
                'opposing_party',
                'case_type',
                'status'
            ])->logOnlyDirty()->dontSubmitEmptyLogs();
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
    public function contacts()
    {
        return $this->morphMany(Contact::class, 'contactable');
    }
    public function notes()
    {
        return $this->morphMany(Note::class, 'notable');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    public function associate()
    {
        return $this->belongsTo(Associate::class);
    }

    public function adminDeposits()
    {
        return $this->hasMany(AdminDeposit::class)->orderBy('deposit_date', 'desc');
    }
    public function adminFees()
    {
        return $this->hasMany(AdministrativeFee::class);
    }
    public function hearings()
    {
        return $this->hasMany(Hearing::class);
    }
    // public function orderedHearings()
    // {
    //     return $this->hasMany(Hearing::class)
    //         ->orderByRaw("CASE WHEN hearing_date >= NOW() THEN 0 ELSE 1 END, 
    //                           CASE WHEN hearing_date >= NOW() THEN hearing_date ELSE NULL END ASC, 
    //                           hearing_date DESC");
    // }
    public function billings()
    {
        return $this->hasMany(Billing::class);
    }

    public function getUnpaidBillAttribute()
    {
        return $this->billings()->whereIn('status', ['unpaid', 'partially_paid'])->count();
        // return $this->billings()->unpaidOverDueBillings()->first();
    }
    public function getNextHearingAttribute()
    {
        return $this->hearings()
            ->where('status', 'upcoming')
            ->where('hearing_date', '>', now())
            ->orderBy('hearing_date', 'asc')
            ->first();
    }
    public function getFormattedNextHearingAttribute()
    {
        $hearing = $this->hearings()
            ->where('status', 'upcoming')
            ->where('hearing_date', '>', now())
            ->orderBy('hearing_date', 'asc')
            ->first();
        if ($hearing) {
            $hearingDate = Carbon::parse($hearing->hearing_date)->format('M j, Y');
            $hearingTime = Carbon::parse($hearing->hearing_date)->format('g:i A');
            return "<div class='text-nowrap'>$hearingDate</div>
             <div class='text-muted'>$hearingTime</div>";
        } else {
            return "<div class='text-muted'>None</div>";
        }
    }
    public function getRemainingDepositAttribute()
    {
        return number_format($this->total_deposits - $this->total_fees, 2);
    }
    public function getCaseTypeBadgeAttribute()
    {

        if ($this->case_type == 'litigation') {
            return "<span class='badge  t-success m-1'>L</span>";
        } elseif ($this->case_type == 'non_litigation') {
            return "<span class='badge  t-danger m-1'>N</span>";
        }
    }
    public function getCasePartyRoleBadgeAttribute()
    {
        if ($this->party_role == 'petitioner') {
            return "<span class='badge  t-warning m-1'>P</span>";
        } elseif ($this->party_role == 'respondent') {
            return "<span class='badge  t-info m-1'>R</span>";
        }
    }
    public function getStatusBadgeAttribute()
    {
        switch ($this->status) {
            case 'open':
                return "<span class='badge  text-bg-primary '>Open</span>";
                break;
            case 'in_progress':
                return "<span class='badge  text-bg-info '>In Progress</span>";
                break;
            case 'settled':
                return "<span class='badge  text-bg-warning '>Settled</span>";
                break;
            case 'won':
                return "<span class='badge  text-bg-success '>Won</span>";
                break;
            case 'lost':
                return "<span class='badge  text-bg-danger '>Lost</span>";
                break;
            case 'archived':
                return "<span class='badge  text-bg-secondary '>Archived</span>";
                break;
            case 'appeal':
                return "<span class='badge  text-bg-secondary '>Appeal</span>";
                break;
            case 'closed':
                return "<span class='badge  text-bg-secondary '>Closed</span>";
                break;

            default:
                return '';
                break;
        }
    }
    public function getRemainingDepositTableClassAttribute()
    {
        if ($this->remaining_deposit > 0) {
            return 'table-success';
        } elseif ($this->remaining_deposit < 0) {
            return 'table-danger';
        } elseif ($this->remaining_deposit == 0) {
            return 'table-secondary';
        }

        return '';
    }
    public function getRemainingDepositClassAttribute()
    {
        if ($this->remaining_deposit > 0) {
            return 'text-bg-success';
        } elseif ($this->remaining_deposit < 0) {
            return 'text-bg-danger';
        } elseif ($this->remaining_deposit == 0) {
            return 'text-bg-secondary';
        }

        return '';
    }

    public function getClientFullnameAttribute()
    {
        return $this->client->first_name . " " . $this->client->last_name . " " . $this->client->suffix;
    }
    public function getAssociateFullnameAttribute()
    {
        return $this->associate->first_name . " " . $this->associate->last_name . " " . $this->associate->suffix;
    }
    public function getUnpaidBillsAttribute()
    {
        return $this->billings->where('status', '!=', 'paid')->count();
    }

    // public function getUpcommingHearingCountAttribute()
    // {
    //     return $this->hearings()
    //         ->where('hearing_date', '>', Carbon::now())
    //         ->count();
    // }
    public function upComingHearings()
    {
        return $this->hearings()->where('status', 'upcoming')->where('hearing_date', '>', now())->orderBy('hearing_date', 'asc');
    }
    public function completedHearings()
    {
        return $this->hearings()->where('status', 'completed')->orderBy('hearing_date', 'asc');
    }
    public function canceledHearings()
    {
        return $this->hearings()->where('status', 'canceled')->orderBy('hearing_date', 'asc');
    }
    // public function getPastHearingCountAttribute()
    // {
    //     return $this->hearings()
    //         ->where('hearing_date', '<', Carbon::now())
    //         ->count();
    // }

    public  function ongoingHearings()
    {
        return $this->hearings()->where('status', 'upcoming')->where('hearing_date', '<=', now())->orderBy('hearing_date', 'asc');
    }
    // public function getPaidBillsCountAttribute()
    // {
    //     return $this->billings->where('status', 'paid')->count();
    // }
    public function getPartiallyPaidBillsCountAttribute()
    {
        return $this->billings->where('status', 'partially_paid')->count();
    }
    public function getUnpaidBillsCountAttribute()
    {
        return $this->billings->where('status', 'unpaid')->count();
    }
    public function paidBills()
    {
        return $this->billings()->where('status', 'paid');
    }
    // public function getDueTodayBillsCountAttribute()
    // {

    //     return $this->billings()->whereNotIn('status', ['paid'])->whereDate('due_date', Carbon::today())->count();
    // }
    public function dueTodayBills()
    {
        return $this->billings()->whereNotIn('status', ['paid'])->whereDate('due_date', Carbon::today());
    }
    // public function getUpcommingBillsCountAttribute()
    // {

    //     return $this->billings()->whereNotIn('status', ['paid'])->whereDate('due_date', '>', Carbon::today())->count();
    // }
    public function upcomingBills()
    {
        return $this->billings()->whereNotIn('status', ['paid'])->whereDate('due_date', '>', Carbon::today());
    }
    // public function getOverDueBillsCountAttribute()
    // {

    //     return $this->billings()->whereNotIn('status', ['paid'])->whereDate('due_date', '<', Carbon::today())->count();
    // }
    public function overDueBills()
    {
        return $this->billings()->whereNotIn('status', ['paid'])->whereDate('due_date', '<', Carbon::today());
    }
    public function getTotalOutstandingBillBalanceAttribute()
    {

        $amount = $this->billings->whereNotIn('status', ['paid'])->sum('amount');
        $paid = $this->billings->whereNotIn('status', ['paid'])->sum('total_paid');
        return $amount - $paid;
    }
}
