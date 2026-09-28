<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;

class PostPolicy
{
    /**
     * Determine whether the user can create models.
     */
    public function store(User $user): bool
    {
        return $user->role === 'writer' || $user->role === 'admin';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Post $post): bool
    {
        return $user->id === $post->author_id || $user->role === 'editor' || $user->role === 'admin';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Post $post): bool
    {
        return $user->id === $post->author_id || $user->role === 'editor' || $user->role === 'admin';
    }

    /**
     * Determine whether the user can replace the model.
     */
    public function replace(User $user, Post $post): bool
    {
        return $user->id === $post->author_id || $user->role === 'editor' || $user->role === 'admin';
    }
}
