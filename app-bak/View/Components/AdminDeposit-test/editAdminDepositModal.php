<?php

namespace App\View\Components;

use App\Models\PaymentType;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class editAdminDepositModal extends Component
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
        $paymentTypes = PaymentType::all();
        return view('components.admin-deposit.edit-admin-deposit-modal', ['paymentTypes' => $paymentTypes]);
    }
}
