<?php

namespace App\Policies;

use App\Models\Associate;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AssociatePolicy
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
        return $user->hasPermissionFor('Associates', 'view');
    }

    public function create(User $user)
    {
        return $user->hasPermissionFor('Associates', 'create');
    }

    public function update(User $user)
    {
        return $user->hasPermissionFor('Associates', 'update');
    }

    public function delete(User $user)
    {
        return $user->hasPermissionFor('Associates', 'delete');
    }
}
