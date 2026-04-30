<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeconVue extends Model
{
    use HasFactory;

    protected $table = 'lecons_vues';

    protected $fillable = ['etudiant_id', 'cours_id', 'lecon_id'];

    public function etudiant()
    {
        return $this->belongsTo(Etudiant::class);
    }

    public function cours()
    {
        return $this->belongsTo(Cours::class);
    }

    public function lecon()
    {
        return $this->belongsTo(Lecon::class);
    }
}