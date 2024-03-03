<?php

namespace App\Policies;

use App\Models\Enums\RoleEnums;
use App\Models\Expense;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ExpensePolicy
{
    public function confirm(User $user): bool
    {
        return $user->role() == RoleEnums::ADMIN;
    }

    public function reject(User $user): bool
    {
        return $user->role() == RoleEnums::ADMIN;
    }
}
