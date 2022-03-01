<?php

namespace App\Policies;

use App\QueryGroup;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class QueryGrouptPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function index(User $user)
    {
        return !$user->hasRole('client');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\QueryGroup  $queryGroup
     * @return mixed
     */
    public function view(User $user, QueryGroup $queryGroup)
    {
        return !$user->hasRole('client');
    }
    public function addtarget(User $user)
    {
        return !$user->hasRole('client');
    }

    public function archive(User $user)
    {
        return !$user->hasRole('client');
    }
    public function destroy(User $user)
    {
        return !$user->hasRole('client');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return !$user->hasRole('client');
    }

    public function store(User $user)
    {
        return !$user->hasRole('client');
    }
public function edit(User $user)
    {
        return !$user->hasRole('client');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\QueryGroup  $queryGroup
     * @return mixed
     */
    public function update(User $user)
    {
        return !$user->hasRole('client');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\QueryGroup  $queryGroup
     * @return mixed
     */
    public function delete(User $user)
    {
        return !$user->hasRole('client');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\QueryGroup  $queryGroup
     * @return mixed
     */
    public function restore(User $user)
    {
        //z`
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\QueryGroup  $queryGroup
     * @return mixed
     */
    public function forceDelete(User $user)
    {
        //
    }
}
