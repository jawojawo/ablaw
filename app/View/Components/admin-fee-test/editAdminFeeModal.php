<?php

namespace App\View\Components;

use App\Models\AdministrativeFeeCategory;
use App\Models\PaymentType;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class editAdminFeeModal extends Component
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
        $adminFeeCategories = AdministrativeFeeCategory::all();
        return view('components.admin-fee.edit-admin-fee-modal', ['adminFeeCategories' => $adminFeeCategories]);
    }
}
