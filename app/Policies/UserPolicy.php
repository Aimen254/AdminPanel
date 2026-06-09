<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $authUser): bool
    {
        return $authUser->hasAnyRole(['admin', 'manager']);
    }

    public function view(User $authUser, User $user): bool
    {
        return $authUser->hasAnyRole(['admin', 'manager']) || $authUser->id === $user->id;
    }

    public function create(User $authUser): bool
    {
        return $authUser->hasRole('admin');
    }

    public function update(User $authUser, User $user): bool
    {
        if ($authUser->hasRole('admin')) {
            return true;
        }
        return $authUser->id === $user->id;
    }

    public function delete(User $authUser, User $user): bool
    {
        return $authUser->hasRole('admin') && $authUser->id !== $user->id;
    }

    public function assignRoles(User $authUser): bool
    {
        return $authUser->hasRole('admin');
    }
}
