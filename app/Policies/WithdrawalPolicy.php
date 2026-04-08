<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Withdrawal;

class WithdrawalPolicy
{
    public function view(User $user, Withdrawal $withdrawal): bool
    {
        return $user->id === $withdrawal->user_id;
    }

    public function update(User $user, Withdrawal $withdrawal): bool
    {
        return $user->id === $withdrawal->user_id && $withdrawal->status === 'pending';
    }

    public function delete(User $user, Withdrawal $withdrawal): bool
    {
        return $user->id === $withdrawal->user_id && $withdrawal->status === 'pending';
    }
}
