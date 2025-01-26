<?php

namespace App\Policies;

use App\Models\CustomEvent;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CustomEventPolicy
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
        return $user->hasPermissionFor('Custom Events', 'view');
    }

    public function create(User $user)
    {
        return $user->hasPermissionFor('Custom Events', 'create');
    }

    public function update(User $user)
    {
        return $user->hasPermissionFor('Custom Events', 'update');
    }

    public function delete(User $user)
    {
        return $user->hasPermissionFor('Custom Events', 'delete');
    }
}
