<?php

namespace App\Policies;

use App\Models\Client;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ClientPolicy
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
        return $user->hasPermissionFor('Clients', 'view');
    }

    public function create(User $user)
    {
        return $user->hasPermissionFor('Clients', 'create');
    }

    public function update(User $user)
    {
        return $user->hasPermissionFor('Clients', 'update');
    }

    public function delete(User $user)
    {
        return $user->hasPermissionFor('Clients', 'delete');
    }
}
