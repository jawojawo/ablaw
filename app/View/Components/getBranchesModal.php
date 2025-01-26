<?php

namespace App\View\Components;

use App\Models\CourtBranch;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class getBranchesModal extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        // $regions = CourtBranch::select('region')->distinct()->get();
        // $courtTypes = CourtBranch::select('type')->distinct()->get();
        return view('components.get-branches-modal');
    }
}
