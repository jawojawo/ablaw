<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Client extends Model
{
    use HasFactory;
    protected $fillable = [
        'first_name',
        'last_name',
        'suffix',
        'info',
    ];
    public function lawCases()
    {
        return $this->hasMany(LawCase::class);
    }
    public function getFullnameAttribute()
    {
        return $this->first_name . " " . $this->last_name . " " . $this->suffix;
    }
    public function billings()
    {
        return $this->hasManyThrough(Billing::class, LawCase::class);
    }
    public function hearings()
    {
        return $this->hasManyThrough(Hearing::class, LawCase::class);
    }
    public function adminDeposits()
    {
        return $this->hasManyThrough(AdminDeposit::class, LawCase::class);
    }
    public function adminFees()
    {
        return $this->hasManyThrough(AdministrativeFee::class, LawCase::class);
    }

    public function getPaidBillsCountAttribute()
    {
        return $this->billings->where('status', 'paid')->count();
    }
    public function getDueTodayBillsCountAttribute()
    {

        return $this->billings()->whereNotIn('billings.status', ['paid'])->whereDate('billings.due_date', Carbon::today())->count();
    }
    public function getUpcommingBillsCountAttribute()
    {

        return $this->billings()->whereNotIn('billings.status', ['paid'])->whereDate('billings.due_date', '>', Carbon::today())->count();
    }
    public function getOverDueBillsCountAttribute()
    {

        return $this->billings()->whereNotIn('billings.status', ['paid'])->whereDate('billings.due_date', '<', Carbon::today())->count();
    }
    public function getTotalOutstandingBillBalanceAttribute()
    {

        $amount = $this->billings->whereNotIn('status', ['paid'])->sum('amount');
        $paid = $this->billings->whereNotIn('status', ['paid'])->sum('total_paid');
        return $amount - $paid;
    }

    public function getNextHearingAttribute()
    {
        return $this->hearings()
            ->where('hearing_date', '>', now())
            ->orderBy('hearing_date', 'asc')
            ->first();
    }
    // public function getUpcommingHearingCountAttribute()
    // {
    //     return $this->hearings()
    //         ->where('hearing_date', '>', Carbon::now())
    //         ->count();
    // }
    // public function getPastHearingCountAttribute()
    // {
    //     return $this->hearings()
    //         ->where('hearing_date', '<', Carbon::now())
    //         ->count();
    // }
    public function getCaseStatusCountBadgesAttribute()
    {
        // Define the status counts
        $statusCounts = $this->lawCases()
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');
        //  return $statusCounts;
        // Define badge classes for each status
        $badgeClasses = [
            'open' => 'badge bg-primary',
            'in_progress' => 'badge bg-info',
            'settled' => 'badge bg-warning',
            'won' => 'badge bg-success',
            'lost' => 'badge bg-danger',
            'closed' => 'badge bg-secondary',
        ];

        // Generate the badges
        $badges = [];
        foreach ($statusCounts as $status => $count) {
            $class = $badgeClasses[$status] ?? 'badge bg-light'; // Default badge class
            $readableStatus = Str::headline($status);
            $badges[] = "<span class='badge rounded-pill {$class} m-1'><span class='text-bg-light badge'>{$count}</span> {$readableStatus}</span>";
        }

        // Return the badges as a string
        return implode(' ', $badges);
    }
    public function getCaseTypeCountBadgesAttribute()
    {
        $typeCounts = $this->lawCases()
            ->select('case_type', DB::raw('count(*) as total'))
            ->groupBy('case_type')
            ->pluck('total', 'case_type');

        $badgeClasses = [
            'litigation' => 'badge t-success',
            'non_litigation' => 'badge t-danger',
        ];

        $badges = [];
        foreach ($typeCounts as $type => $count) {
            $class = $badgeClasses[$type] ?? 'badge bg-secondary';
            $readableType = Str::headline(str_replace('_', ' ', $type)); // Convert to headline format
            $badges[] = "<span class='rounded-pill {$class} m-1'><span class='text-bg-light badge'>{$count}</span> {$readableType}</span>";
        }

        return implode(' ', $badges);
    }
    public function getRoleCountBadgesAttribute()
    {
        // Get role counts grouped by `party_role` for the client's law cases
        $roleCounts = $this->lawCases()
            ->select('party_role', DB::raw('count(*) as total'))
            ->groupBy('party_role')
            ->pluck('total', 'party_role');

        // Define badge classes for each role
        $badgeClasses = [
            'petitioner' => 'badge t-warning',
            'respondent' => 'badge t-info',
        ];

        // Generate badges dynamically
        $badges = [];
        foreach ($roleCounts as $role => $count) {
            $class = $badgeClasses[$role] ?? 'badge bg-secondary'; // Default class if role not in defined list
            $readableRole = Str::headline(str_replace('_', ' ', $role)); // Convert role to readable format
            $badges[] = "<span class='{$class} m-1'>{$count} {$readableRole}</span>";
        }

        return implode(' ', $badges); // Combine badges into a single string
    }
}
