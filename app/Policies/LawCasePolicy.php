<?php

namespace App\Policies;

use App\Models\LawCase;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class LawCasePolicy
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
        return $user->hasPermissionFor('Cases', 'view');
    }

    public function create(User $user)
    {
        return $user->hasPermissionFor('Cases', 'create');
    }

    public function update(User $user)
    {
        return $user->hasPermissionFor('Cases', 'update');
    }

    public function delete(User $user)
    {
        return $user->hasPermissionFor('Cases', 'delete');
    }
}
