<?php

namespace App\Policies;

use App\Models\Hearing;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CaseHearingPolicy
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
        return $user->hasPermissionFor('Case Hearings', 'view');
    }

    public function create(User $user)
    {
        return $user->hasPermissionFor('Case Hearings', 'create');
    }

    public function update(User $user)
    {
        return $user->hasPermissionFor('Case Hearings', 'update');
    }

    public function delete(User $user)
    {
        return $user->hasPermissionFor('Case Hearings', 'delete');
    }
}
