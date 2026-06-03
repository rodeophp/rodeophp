<?php

declare(strict_types=1);

namespace Workbench\App\Policies;

use Workbench\App\Models\Horse;
use Workbench\App\Models\User;

class HorsePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, Horse $horse): bool
    {
        return $horse->name !== 'Locked';
    }

    public function delete(User $user, Horse $horse): bool
    {
        return false;
    }
}
