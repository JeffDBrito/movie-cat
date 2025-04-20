<?php

namespace App\Http\Controllers\Filme;

use App\Http\Controllers\Controller;
use App\Models\Filme;
use App\Models\User;
use App\Services\TMDB;
use Auth;
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

        $user = Auth::user();
        $user_filmes = [];
        if ($user) {
            $user_filmes = $user->favoritos()->pluck('tmdb_id')->toArray();
        }

        $page = $request->input('page', 1);
        $filmes = $this->tmdb->getFilmesPorCategoria($categoria, $page);

        $response = [];

        if (isset($filmes['results'])) {
            $response['results'] = [];
        } else {
            return response()->json(['error' => 'Nenhum filme encontrado.'], 404);
        }

        // Formata a resposta e adiciona o campo is_favorito
        foreach ($filmes['results'] as $filme) {
            $response['page'] = $filmes['page'];
            $response['total_pages'] = $filmes['total_pages'];
            $response['total_results'] = $filmes['total_results'];
            $response['results'][] = [
                'id' => $filme['id'],
                'title' => json_encode($filme['title'], JSON_UNESCAPED_UNICODE),
                'poster_path' => $filme['poster_path'],
                'is_favorito' => in_array($filme['id'], $user_filmes),
                'release_date' => $filme['release_date'],
                'overview' => $filme['overview'],
                'vote_average' => $filme['vote_average'],
                'vote_count' => $filme['vote_count'],
                'backdrop_path' => $filme['backdrop_path'],
                'original_language' => $filme['original_language'],
                'original_title' => $filme['original_title'],
                'genre_ids' => $filme['genre_ids'],
                'adult' => $filme['adult'],
                'video' => $filme['video'],
                'popularity' => $filme['popularity'],
            ];
        }

        return response()->json($response, 200);
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

    /**
     * Adiciona um filme aos favoritos do usuário.
     * @param Request $request
     * @return JsonResponse
     */
    public function favoritar(Request $request): JsonResponse
    {

        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Usuário não autenticado.'], 401);
        }

        $request->validate([
            'tmdb_id' => 'required|integer',
        ]);

        $tmdb_id = $request->input('tmdb_id');

        // Verifica se o filme já está registrado no banco de dados
        $filme = Filme::where('tmdb_id', $tmdb_id)->first();

        // Se o filme não estiver registrado, busca os detalhes no TMDB e registra
        if(!$filme){

            $filme = $this->tmdb->getFilme($tmdb_id);
            $filme_json = $this->store($filme);

            if ($filme_json->getStatusCode() !== 201) {
                return response()->json(['error' => 'Falha ao registrar filme.'], 500);
            }

            $filme = Filme::where('tmdb_id', $tmdb_id)->first();
            if (!$filme) {
                return response()->json(['error' => 'Falha ao registrar filme.'], 500);
            }

        }

        $user->favoritos()->attach($filme->id);

        return response()->json(['message' => 'Filme adicionado aos favoritos.'], 200);
    }

    /**
     * Remove um filme dos favoritos do usuário.
     * @param Request $request
     * @return JsonResponse
     */
    public function desfavoritar(Request $request): JsonResponse
    {

        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Usuário não autenticado.'], 401);
        }

        $request->validate([
            'tmdb_id' => 'required|integer',
        ]);

        $tmdb_id = $request->input('tmdb_id');

        // Verifica se o filme está
        $filme = Filme::where('tmdb_id', $tmdb_id)->first();

        if (!$filme) {
            return response()->json(['error' => 'Filme não encontrado.'], 404);
        }

        $user->favoritos()->detach($filme->id);

        return response()->json(['message' => 'Filme removido dos favoritos.'], 200);
    }

}
