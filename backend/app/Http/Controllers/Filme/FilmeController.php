<?php

namespace App\Http\Controllers\Filme;

use App\Http\Controllers\Controller;
use App\Models\Filme;
use App\Models\User;
use App\Services\TMDB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FilmeController extends Controller
{

    /**
     * Registra um filme no banco de dados.
     * @param array $tmdb_details
     * @return mixed|JsonResponse
     */
    public function store($tmdb_details){

        try{

            $filme = Filme::updateOrCreate(
                ['tmdb_id' => $tmdb_details['id']],
                [
                    'title' => $tmdb_details['title'],
                    'poster_path' => $tmdb_details['poster_path'],
                    'tmdb_details' => $tmdb_details,
                ]
            );

            return response()->json($filme, 201);

        }catch (\Exception $e) {
            return response()->json(['error' => 'Falha ao registrar filme.'], 500);
        }

    }

}
