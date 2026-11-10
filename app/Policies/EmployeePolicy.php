<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Employee;

class EmployeePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('employees.view');
    }

    public function view(User $user, Employee $employee): bool
    {
        if ($user->can('employees.view')) {
            if ($user->hasRole('Manager')) {
                return $employee->manager_id ===
                    $user->employee?->id
                    || $employee->id === $user->employee?->id;
            }

            return true;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->can('employees.create');
    }

    public function update(User $user, Employee $employee): bool
    {
        return $user->can('employees.edit');
    }

    public function delete(User $user, Employee $employee): bool
    {
        return $user->can('employees.delete');
    }
}