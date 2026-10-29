<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Student;

class StudentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('student.view');
    }

    public function view(User $user, Student $student): bool
    {
        if ($user->hasRole('student')) {
            return $user->id === $student->user_id;
        }

        if ($user->hasRole('parent')) {
            return $user->phone === $student->parent_phone;
        }

        return $user->can('student.view');
    }

    public function create(User $user): bool
    {
        return $user->can('student.create');
    }

    public function update(User $user, Student $student): bool
    {
        return $user->can('student.edit');
    }

    public function delete(User $user, Student $student): bool
    {
        return $user->can('student.delete');
    }
}
