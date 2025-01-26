<?php

use App\Models\Billing;
use App\Models\Client;
use App\Models\CourtBranch;
use App\Models\LawCase;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use App\Models\User;
use Illuminate\Support\Str;

if (!function_exists('getHearingRowClass')) {
    function getHearingRowClass($status)
    {
        return match ($status) {
            'ongoing' => 'table-primary',
            'completed' => 'table-secondary',
            'canceled' => 'table-danger',
            default => '',
        };
    }
}
if (!function_exists('formattedDate')) {
    function formattedDate($date)
    {
        return $date ? Carbon::parse($date)->format('M j, Y') : null;
    }
}
if (!function_exists('formattedTime')) {
    function formattedTime($date)
    {

        return $date ? Carbon::parse($date)->format('g:i A') : null;
    }
}
if (!function_exists('formattedDateTime')) {
    function formattedDateTime($dateTime)
    {
        return $dateTime ? Carbon::parse($dateTime)->format('M j, Y g:i A') : null;
    }
}
if (!function_exists('formattedDateDiff')) {
    function formattedDateDiff($start, $end)
    {
        $options = [
            'join' => true,
            'parts' => 3,
            'syntax' => CarbonInterface::DIFF_ABSOLUTE,
        ];
        $s = Carbon::parse($start);
        $e = Carbon::parse($end);
        return $s->diffForHumans($e, $options);
    }
}
if (!function_exists('abbreviate')) {
    function abbreviate($string)
    {
        return collect(explode(' ', $string))
            ->map(fn($word) => strtoupper($word[0] ?? ''))
            ->join('');
    }
}
if (!function_exists('abbreviateFirstLast')) {
    function abbreviateFirstLast($string)
    {
        $words = explode(' ', $string);

        if (count($words) === 1) {
            // If there's only one word, abbreviate it
            return strtoupper(substr($words[0], 0, 1));
        }

        // Abbreviate the first and last words
        $firstAbbr = strtoupper(substr($words[0], 0, 1));
        $lastAbbr = strtoupper(substr(end($words), 0, 1));

        return $firstAbbr . $lastAbbr;
    }
}
if (!function_exists('permissionIcon]')) {
    function permissionIcon($permission, $permissionName = null)
    {
        return match ($permission) {
            'view' => "<span data-bs-toggle='tooltip'  data-bs-placement='right' title='" . ($permissionName ? "Can view $permissionName" : "") . "' class='badge rounded-circle bg-primary'  style='width:30px;height:30px;padding-top: 8px;'><i class='bi bi-eye'></i></span>",
            'create' => "<span data-bs-toggle='tooltip'  data-bs-placement='right' title='" . ($permissionName ? "Can create $permissionName" : "") . "' class='badge rounded-circle bg-success' style='width:30px;height:30px;padding-top: 8px;'><i class='bi bi-plus-lg'></i></span>",
            'update' => "<span data-bs-toggle='tooltip'  data-bs-placement='right' title='" . ($permissionName ? "Can update $permissionName" : "") . "' class='badge rounded-circle bg-warning' style='width:30px;height:30px;padding-top: 8px;'><i class='bi bi-pencil'></i></span>",
            'delete' => "<span data-bs-toggle='tooltip'  data-bs-placement='right' title='" . ($permissionName ? "Can delete $permissionName" : "") . "' class='badge rounded-circle bg-danger' style='width:30px;height:30px;padding-top: 8px;'><i class='bi bi-trash'></i></span>",
            default => '',
        };
    }
}
if (!function_exists('getPermissionClass')) {
    function getPermissionClass($permission)
    {
        return config('enums.permissionModels')[$permission]['class'] ?? null;
    }
}

if (!function_exists('formattedHearingDate')) {
    function formattedHearingDate($hearing)
    {
        return $hearing;
        // if ($hearing) {
        //     $hearingDate = Carbon::parse($hearing->hearing_date)->format('M j, Y');
        //     $hearingTime = Carbon::parse($hearing->hearing_date)->format('g:i A');
        //     return "<div class='text-nowrap'>$hearingDate</div>
        //      <div class='text-muted'>$hearingTime</div>";
        // } else {
        //     return "<div class='text-muted'>None</div>";
        // }
    }
}
if (!function_exists('formattedMoney')) {
    function formattedMoney($input)
    {
        return "₱" . number_format($input, 2);
    }
}
if (!function_exists('numberSpellOut')) {
    function numberSpellOut($input)
    {

        $num = explode(".", $input);
        //return print_r($num);
        $digit = new NumberFormatter("en", NumberFormatter::SPELLOUT);
        $a = $digit->format($num[0]) . ' pesos';
        if (isset($num[1])) {
            $b =  ($num[1] > 0 ? ' pesos and ' . $digit->format($num[1]) . ' cents' : '');
        } else {
            $b = '';
        }
        return $a . $b;
    }
}
if (!function_exists('getUserName')) {
    function getUserName($id)
    {
        $user = User::find($id);
        return $user->name ?? '';
    }
}
if (!function_exists('headline')) {
    function headline($string)
    {
        return Str::headline($string);
    }
}
if (!function_exists('getClientName')) {
    function getClientName($id)
    {
        $client = Client::find($id);
        return $client->name;
    }
}
if (!function_exists('getCaseNumber')) {
    function getCaseNumber($id)
    {
        $case = LawCase::find($id);
        return  $case->case_number ?? '';
    }
}
if (!function_exists('getCourtBranch')) {
    function getCourtBranch($id)
    {
        $courtBranch = CourtBranch::find($id);
        return "$courtBranch->region / $courtBranch->city / $courtBranch->type / $courtBranch->branch";
    }
}
if (!function_exists('getBilling')) {
    function getBilling($id)
    {
        $billing = Billing::find($id);
        return $billing->title;
    }
}
if (!function_exists('getCaseFromBilling')) {
    function getCaseFromBilling($id)
    {
        $billing = Billing::find($id);
        return $billing->lawCase->case_number;
    }
}
