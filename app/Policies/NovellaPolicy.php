<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Novella;
use App\Models\User;

class NovellaPolicy
{
    public function viewAny(?User $user): bool
    {
        return $user && $user->isContributor();
    }

    public function view(?User $user, Novella $novella): bool
    {
        return $user && $user->isContributor();
    }

    public function create(User $user): bool
    {
        return $user->isContributor();
    }

    public function update(User $user, Novella $novella): bool
    {
        return $user->isContributor();
    }

    public function delete(User $user, Novella $novella): bool
    {
        return $user->isAdmin();
    }
}
