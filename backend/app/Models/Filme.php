<?php

namespace App\Models;

use App\Models\User;

use Illuminate\Database\Eloquent\Model;

class Filme extends Model
{

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
