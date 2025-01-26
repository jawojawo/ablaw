<?php

namespace App\Policies;

use App\Models\AdminDeposit;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CaseDepositPolicy
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

        return $user->hasPermissionFor('Case Deposits', 'view');
    }

    public function create(User $user)
    {
        return $user->hasPermissionFor('Case Deposits', 'create');
    }

    public function update(User $user)
    {
        return $user->hasPermissionFor('Case Deposits', 'update');
    }

    public function delete(User $user)
    {
        return $user->hasPermissionFor('Case Deposits', 'delete');
    }
}
