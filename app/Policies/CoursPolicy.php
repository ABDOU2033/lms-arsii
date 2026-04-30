<?php

namespace App\Policies;

use App\Models\Cours;
use App\Models\User;

class CoursPolicy
{
    public function view(User $user, Cours $cours): bool
    {
        return true; // Tous les utilisateurs peuvent voir les cours
    }

    public function create(User $user): bool
    {
        return $user->role === 'enseignant' || $user->role === 'administrateur';
    }

    public function update(User $user, Cours $cours): bool
    {
        $enseignant = $user->enseignant;
        return $enseignant && $enseignant->id === $cours->enseignant_id;
    }

    public function delete(User $user, Cours $cours): bool
    {
        $enseignant = $user->enseignant;
        return $enseignant && $enseignant->id === $cours->enseignant_id;
    }
}
