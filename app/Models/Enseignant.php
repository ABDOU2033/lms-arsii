<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enseignant extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'specialite', 'bio'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cours()
    {
        return $this->hasMany(Cours::class, 'enseignant_id');
    }

    public function creerCours(array $data)
    {
        return $this->cours()->create($data);
    }

    public function consulterResultats()
    {
        // Return quizzes results for his courses
        return Quiz::whereHas('cours', function ($query) {
            $query->where('enseignant_id', $this->id);
        })->with('reponses')->get();
    }
}