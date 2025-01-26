<?php

namespace App\Policies;

use App\Models\OfficeExpense;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class OfficeExpensePolicy
{
    public function before(User $user)
    {
        return true;
        if ($user->id === 1) {
            return true;
        }
    }


    public function view(User $user)
    {
        return $user->hasPermissionFor('Office Expenses', 'view');
    }

    public function create(User $user)
    {
        return $user->hasPermissionFor('Office Expenses', 'create');
    }

    public function update(User $user)
    {
        return $user->hasPermissionFor('Office Expenses', 'update');
    }

    public function delete(User $user)
    {
        return $user->hasPermissionFor('Office Expenses', 'delete');
    }
}
