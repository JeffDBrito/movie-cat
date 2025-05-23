<?php

namespace App\Models;

use App\Models\User;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Filme extends Model
{

    use HasFactory;

    protected $fillable = [
        "tmdb_id",
        "title",
        "poster_path",
        "tmdb_details"
    ];

    protected $casts = [
        'tmdb_details' => 'array',
    ];

    public function favoritadoPor()
    {
        return $this->belongsToMany(User::class, 'favoritos', 'filme_id', 'user_id');
    }

    public function generos()
    {
        return $this->belongsToMany(Genero::class, 'filme_genero', 'filme_id', 'genero_id');
    }

    public function favoritar(User $user)
    {
        return $this->favoritadoPor()->attach($user);
    }

    public function desfavoritar(User $user)
    {
        return $this->favoritadoPor()->detach($user);
    }

    public function isFavoritadoPor(User $user)
    {
        return $this->favoritadoPor()->where('user_id', $user->id)->exists();
    }

}
