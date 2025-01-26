<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    public function before(User $user)
    {


        if ($user->id === 1) {
            return true;
        }
    }
    public function viewAny(User $user)
    {
        return $user->id === 1;
    }
    public function view(User $user, User $model)
    {

        return $user->id === $model->id;
    }
    public function create(User $user)
    {
        return $user->id === 1;
    }
    public function update(User $user, User $model)
    {

        return $user->id === $model->id;
    }
    public function delete(User $user)
    {

        return $user->id === 1;
    }
}
