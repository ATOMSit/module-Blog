<?php

namespace Modules\Blog\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Blog\Entities\Post;

class PostPolicy
{
    use HandlesAuthorization;

    /**
     * Validate users with a higher role.
     *
     * @param User $user
     * @param $ability
     * @return bool
     */
    public function before(User $user, $ability)
    {
        if ($user->hasRole('ATOMSit')) {
            return true;
        }
        if ($user->hasRole('Proprietaire')) {
            return true;
        } else {
            return null;
        }
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param \App\User $user
     * @param \App\User $model
     * @return mixed
     */
    public function view(User $user, Post $model)
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param \App\User $user
     * @return mixed
     */
    public function create(User $user)
    {
        if ($user->hasPermissionTo("blog_post_create")) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param \App\User $user
     * @param \App\User $model
     * @return mixed
     */
    public function update(User $user, Post $model)
    {
        if ($model->author->is($user)) {
            return true;
        } elseif ($user->hasPermissionTo('blog_post_update')) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param \App\User $user
     * @param \App\User $model
     * @return mixed
     */
    public function delete(User $user, Post $model)
    {
        if ($user->is($model)) {
            return true;
        } elseif ($user->hasPermissionTo('blog_post_delete')) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param \App\User $user
     * @param \App\User $model
     * @return mixed
     */
    public function restore(User $user, Post $model)
    {
        if ($user->is($model)) {
            return true;
        } elseif ($user->hasPermissionTo('blog_post_restore')) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param \App\User $user
     * @param \App\User $model
     * @return mixed
     */
    public function forceDelete(User $user, Post $model)
    {
        if ($user->is($model)) {
            return true;
        } elseif ($user->hasPermissionTo('blog_post_forceDelete')) {
            return true;
        } else {
            return false;
        }
    }
}