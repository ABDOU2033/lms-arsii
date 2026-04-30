<?php

namespace App\Policies;

use App\Models\Quiz;
use App\Models\User;

class QuizPolicy
{
    public function view(User $user, Quiz $quiz): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->role === 'enseignant' || $user->role === 'administrateur';
    }

    public function update(User $user, Quiz $quiz): bool
    {
        $enseignant = $user->enseignant;
        return $enseignant && $enseignant->id === $quiz->cours->enseignant_id;
    }

    public function delete(User $user, Quiz $quiz): bool
    {
        $enseignant = $user->enseignant;
        return $enseignant && $enseignant->id === $quiz->cours->enseignant_id;
    }
}
