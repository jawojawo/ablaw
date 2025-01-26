<?php

namespace App\Policies;

use App\Models\AdmininstrativeFee;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CaseExpensePolicy
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
        return $user->hasPermissionFor('Case Expenses', 'view');
    }

    public function create(User $user)
    {
        return $user->hasPermissionFor('Case Expenses', 'create');
    }

    public function update(User $user)
    {
        return $user->hasPermissionFor('Case Expenses', 'update');
    }

    public function delete(User $user)
    {
        return $user->hasPermissionFor('Case Expenses', 'delete');
    }
}
