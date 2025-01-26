<?php

namespace App\Policies;

use App\Models\Billing;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CaseBillingPolicy
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
        return $user->hasPermissionFor('Case Billings', 'view');
    }

    public function create(User $user)
    {
        return $user->hasPermissionFor('Case Billings', 'create');
    }

    public function update(User $user)
    {
        return $user->hasPermissionFor('Case Billings', 'update');
    }

    public function delete(User $user)
    {
        return $user->hasPermissionFor('Case Billings', 'delete');
    }
}
