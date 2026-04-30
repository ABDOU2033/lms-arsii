<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChoixReponse extends Model
{
    use HasFactory;

    protected $fillable = ['question_id', 'contenu', 'est_correcte'];

    protected $casts = [
        'est_correcte' => 'boolean',
    ];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}