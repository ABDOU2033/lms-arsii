<?php

namespace App\Policies;

use App\Models\Question;
use App\Models\User;

class QuestionPolicy
{
    public function view(User $user, Question $question): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->role === 'enseignant' || $user->role === 'administrateur';
    }

    public function update(User $user, Question $question): bool
    {
        $enseignant = $user->enseignant;
        return $enseignant && $enseignant->id === $question->quiz->cours->enseignant_id;
    }

    public function delete(User $user, Question $question): bool
    {
        $enseignant = $user->enseignant;
        return $enseignant && $enseignant->id === $question->quiz->cours->enseignant_id;
    }
}
