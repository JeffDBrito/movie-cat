<?php

namespace App\Http\Controllers\Filme;

use App\Http\Controllers\Controller;
use App\Models\Filme;
use App\Models\User;
use App\Services\TMDB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FilmeController extends Controller
{

    private $tmdb;

    public function __construct()
    {
        $this->tmdb = new TMDB();
    }

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

    /**
     * Retorna os filmes de uma categoria.
     * @param Request $request
     * @param string $categoria
     * @return JsonResponse
     */
    public function filmesPorCategoria(Request $request, string $categoria): JsonResponse
    {
        $page = $request->input('page', 1);
        $filmes = $this->tmdb->getFilmesPorCategoria($categoria, $page);

        foreach ($filmes['results'] as $filme) {
            $filme['title'] = json_encode($filme['title'], JSON_UNESCAPED_UNICODE);
        }

        return response()->json($filmes, 200);
    }

    /**
     * Busca filmes por título.
     * @param Request $request
     * @param string $title
     * @return JsonResponse
     */
    public function buscarPorTitulo(string $title): JsonResponse
    {

        $filmes = $this->tmdb->buscarPorTitulo($title);

        foreach ($filmes['results'] as $filme) {
            $filme['title'] = json_encode($filme['title'], JSON_UNESCAPED_UNICODE);
        }

        return response()->json($filmes, 200);
    }

}
