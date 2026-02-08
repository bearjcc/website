<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\LorePage;
use App\Models\User;

class LorePagePolicy
{
    public function viewAny(?User $user): bool
    {
        return $user && $user->isContributor();
    }

    public function view(?User $user, LorePage $lorePage): bool
    {
        return $user && $user->isContributor();
    }

    public function create(User $user): bool
    {
        return $user->isContributor();
    }

    public function update(User $user, LorePage $lorePage): bool
    {
        return $user->isContributor();
    }

    public function delete(User $user, LorePage $lorePage): bool
    {
        return $user->isAdmin();
    }
}
