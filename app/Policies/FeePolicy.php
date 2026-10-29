<?php

namespace App\Policies;

use App\Models\User;
use App\Models\FeePayment;

class FeePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('fee.view');
    }

    public function collect(User $user): bool
    {
        return $user->can('fee.collect');
    }

    public function refund(User $user): bool
    {
        return $user->can('fee.refund');
    }
}
