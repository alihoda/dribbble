<?php

namespace App\Policies;

use App\Models\SocialNetwork;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SocialNetworkPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SocialNetwork  $socialNetwork
     * @return mixed
     */
    public function view(User $user, SocialNetwork $socialNetwork)
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SocialNetwork  $socialNetwork
     * @return mixed
     */
    public function update(User $user, SocialNetwork $socialNetwork)
    {
        return $user->id === $socialNetwork->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SocialNetwork  $socialNetwork
     * @return mixed
     */
    public function delete(User $user, SocialNetwork $socialNetwork)
    {
        return $user->id === $socialNetwork->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SocialNetwork  $socialNetwork
     * @return mixed
     */
    public function restore(User $user, SocialNetwork $socialNetwork)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SocialNetwork  $socialNetwork
     * @return mixed
     */
    public function forceDelete(User $user, SocialNetwork $socialNetwork)
    {
        return false;
    }
}
