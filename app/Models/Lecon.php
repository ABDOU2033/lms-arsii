<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lecon extends Model
{
    use HasFactory;

    protected $fillable = ['cours_id', 'titre', 'contenu', 'type', 'ordre'];

    public function cours()
    {
        return $this->belongsTo(Cours::class);
    }

    public function leconsVues()
    {
        return $this->hasMany(LeconVue::class);
    }
}