<?php

namespace App\Policies;

use App\Models\Lecon;
use App\Models\User;

class LeconPolicy
{
    public function view(User $user, Lecon $lecon): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->role === 'enseignant' || $user->role === 'administrateur';
    }

    public function update(User $user, Lecon $lecon): bool
    {
        $enseignant = $user->enseignant;
        return $enseignant && $enseignant->id === $lecon->cours->enseignant_id;
    }

    public function delete(User $user, Lecon $lecon): bool
    {
        $enseignant = $user->enseignant;
        return $enseignant && $enseignant->id === $lecon->cours->enseignant_id;
    }
}
