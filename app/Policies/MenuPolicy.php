<?php

namespace App\Policies;

use App\Models\User;
use App\Models\SystemMenu;

class MenuPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('menu.view');
    }

    public function create(User $user): bool
    {
        return $user->can('menu.create');
    }

    public function update(User $user, SystemMenu $menu): bool
    {
        return $user->can('menu.edit');
    }

    public function delete(User $user, SystemMenu $menu): bool
    {
        return $user->can('menu.delete');
    }
}
