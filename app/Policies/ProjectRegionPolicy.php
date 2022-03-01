<?php

namespace App\Policies;

use App\ProjectRegion;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProjectRegionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\ProjectRegion  $projectRegion
     * @return mixed
     */
    public function view(User $user, ProjectRegion $projectRegion)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *

     * @return mixed
     */
    public function create()
    {
        return true;
//        return true;403
//This action is unauthorized.
        dump($user->hasRole('client'));
        return !$user->hasRole('client');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\ProjectRegion  $projectRegion
     * @return mixed
     */
    public function update(User $user, ProjectRegion $projectRegion)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\ProjectRegion  $projectRegion
     * @return mixed
     */
    public function delete(User $user, ProjectRegion $projectRegion)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\ProjectRegion  $projectRegion
     * @return mixed
     */
    public function restore(User $user, ProjectRegion $projectRegion)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\ProjectRegion  $projectRegion
     * @return mixed
     */
    public function forceDelete(User $user, ProjectRegion $projectRegion)
    {
        //
    }
}
