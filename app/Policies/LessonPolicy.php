<?php

namespace App\Policies;

use App\Models\Lesson;
use App\Models\User;

class LessonPolicy
{
    public function create(User $user): bool
    {
        return $user->isTeacher() || $user->isAdmin();
    }

    public function update(User $user, Lesson $lesson): bool
    {
        return $user->id === $lesson->course->teacher_id || $user->isAdmin();
    }

    public function delete(User $user, Lesson $lesson): bool
    {
        return $user->id === $lesson->course->teacher_id || $user->isAdmin();
    }
}
