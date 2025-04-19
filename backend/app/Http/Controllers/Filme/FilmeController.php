<?php

namespace App\Http\Controllers\Filme;

use App\Http\Controllers\Controller;
use App\Models\Filme;
use App\Models\User;
use App\Services\TMDB;
use Illuminate\Http\Request;

class FilmeController extends Controller
{

    public function storeMovieById($tmdbId)
    {

        $service_tmdb = new TMDB();
        $filme = $service_tmdb->getFilme($tmdbId);

        if ($filme) {
            return Filme::updateOrCreate(
                ['tmdb_id' => $tmdbId],
                [
                    'title' => $filme['title'],
                    'poster_path' => $filme['poster_path'],
                    'tmdb_details' => $filme['tmdb_details'],
                ]
            );
        }

        return null;
    }

}
