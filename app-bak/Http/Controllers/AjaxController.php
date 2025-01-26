<?php

namespace App\Http\Controllers;

use App\Models\Associate;
use App\Models\Client;
use App\Models\CourtBranch;
use Illuminate\Http\Request;

class AjaxController extends Controller
{
    public function getClients(Request $request)
    {
        $firstName = $request->input('first_name');
        $lastName = $request->input('last_name');

        $clients = Client::when($firstName, function ($query, $firstName) {
            return $query->where('first_name', 'like', "%{$firstName}%");
        })
            ->when($lastName, function ($query, $lastName) {
                return $query->where('last_name', 'like', "%{$lastName}%");
            })
            ->paginate(15);

        return response()->json($clients);
    }
    public function getAssociates(Request $request)
    {
        $firstName = $request->input('first_name');
        $lastName = $request->input('last_name');
        $associates = Associate::when($firstName, function ($query, $firstName) {
            return $query->where('first_name', 'like', "%{$firstName}%");
        })
            ->when($lastName, function ($query, $lastName) {
                return $query->where('last_name', 'like', "%{$lastName}%");
            })
            ->paginate(15);
        return response()->json($associates);
    }
    public function getCourtBranches(Request $request)
    {
        $courtBranches = CourtBranch::query()
            ->when($request->region, function ($query, $region) {
                $query->where('region', $region);
            })
            ->when($request->city, function ($query, $city) {
                $query->where('city', 'like', '%' . $city . '%');
            })
            ->when($request->type, function ($query, $type) {
                $query->where('type', $type);
            })
            ->when($request->branch, function ($query, $branch) {
                $query->where('branch', 'like', '%' . $branch . '%');
            })
            ->paginate(15);

        return response()->json([
            'courtBranches' => $courtBranches
        ]);
    }
    public function getRegions()
    {
        $region = CourtBranch::select('region')->distinct()->get();
        return response()->json($region);
    }
}
