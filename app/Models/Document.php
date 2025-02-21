<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = ['title', 'file_path', 'project_id']; // Ajoute ces colonnes dans $fillable

    public function project()
    {
        return $this->belongsTo(Project::class); // Un document appartient à un projet
    }
}