<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Genero extends Model
{

    protected $fillable = [
        'id',
        'name',
    ];

    public function filmes()
    {
        return $this->belongsToMany(Filme::class, 'filme_genero', 'genero_id', 'filme_id');
    }

}
