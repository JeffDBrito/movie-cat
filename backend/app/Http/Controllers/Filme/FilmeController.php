<?php

namespace App\Http\Controllers\Filme;

use App\Http\Controllers\Controller;
use App\Models\Filme;
use App\Models\Genero;
use App\Models\User;
use App\Services\TMDB;
use Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
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

            if(sizeOf(Genero::all()) == 0){
                $this->getGeneros();
            }

            $generos = Genero::where('id', $tmdb_details['genres']);
            $filme->generos()->sync($generos->pluck('id'));

            return response()->json($filme, 201);

        }catch (\Exception $e) {
            Log::error('Erro ao registrar filme: ' . $e->getMessage());
            return response()->json(['error' => 'Falha ao registrar filme.'], 500);
        }

    }

// ====================== Métodos de busca

    /**
     * Busca a lista de generos.
     * @return JsonResponse
     */
    public function getGeneros(): JsonResponse
    {
        // Verifica se os gêneros estão em cache
        $generos = Genero::all();

        if($generos->isNotEmpty()){
            return response()->json($generos, 200);
        }else{
            $generos = $this->tmdb->getGeneros();

            foreach ($generos['genres'] as $genero) {
                Genero::updateOrCreate(
                    ['id' => $genero['id']],
                    ['name' => $genero['name']]
                );
            }
        }

        if (isset($generos['genres'])) {
            return response()->json($generos, 200);
        } else {
            return response()->json(['error' => 'Nenhum gênero encontrado.'], 404);
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

        $response = $this->formatarFilmes($filmes, $user_filmes);

        if (isset($filmes['results'])) {
            return response()->json($response, 200);
        } else {
            return response()->json(['error' => 'Nenhum filme encontrado.'], 404);
        }
    }

    /**
     * Busca filmes por gênero.
     * @param Request $request
     * @param array $generos
     * @return JsonResponse
     */
    public function buscarPorGenero(Request $request): JsonResponse
    {
        $user = Auth::user();
        $user_filmes = [];
        if ($user) {
            $user_filmes = $user->favoritos()->pluck('tmdb_id')->toArray();
        }

        Log::info('Request: ',$request->all());
        $generos = $request->input('genero');

        $page = $request->input('page', 1);
        $ano = $request->input('ano', null);
        $adults = $request->input('adults', false);
        $ordem = $request->input('ordem', 'popularity.desc');

        $filmes = $this->tmdb->buscarPorGenero($generos, $page, $adults, $ano, $ordem);
        $response = $this->formatarFilmes($filmes, $user_filmes);

        if (isset($filmes['results'])) {
            return response()->json($response, 200);
        } else {
            return response()->json(['error' => 'Nenhum filme encontrado.'], 404);
        }
    }

    /**
     * Busca filmes por título.
     * @param Request $request
     * @param string $title
     * @return JsonResponse
     */
    public function buscarPorTitulo(Request $request): JsonResponse
    {
        $titulo = $request->input('titulo');
        $page = $request->input('page', 1);

        if (!$titulo) {
            return response()->json(['error' => 'Título não fornecido.'], 400);
        }

        $user = Auth::user();
        $user_filmes = [];
        if ($user) {
            $user_filmes = $user->favoritos()->pluck('tmdb_id')->toArray();
        }

        $filmes = $this->tmdb->buscarPorTitulo($titulo, $page);
        $response = $this->formatarFilmes($filmes, $user_filmes);

        if (isset($filmes['results'])) {
            return response()->json($response, 200);
        } else {
            return response()->json(['error' => 'Nenhum filme encontrado.'], 404);
        }
    }

    public function favoritosPorTitulo(Request $request): JsonResponse
    {
        $titulo = $request->input('titulo');
        $page = $request->input('page', 1);

        if (!$titulo) {
            return response()->json(['error' => 'Título não fornecido.'], 400);
        }

        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Usuário não autenticado.'], 401);
        }

        $user_filmes = $user->favoritos()->where('title', 'like', '%' . $titulo . '%')->paginate();

        $response = [];
        foreach ($user_filmes as $filme) {

            $generos = [];
            foreach($filme->generos as $genero) {
                $generos[] = $genero->id;
            }

            $response['page'] = $user_filmes->currentPage();
            $response['total_pages'] = $user_filmes->lastPage();
            $response['total_results'] = $user_filmes->total();
            $response['results'][] = [
                'id' => $filme->tmdb_id,
                'title' => json_encode($filme->title, JSON_UNESCAPED_UNICODE),
                'is_favorito' => true,
                'poster_path' => $filme->poster_path,

                'release_date' => $filme->tmdb_details['release_date'],
                'overview' => $filme->tmdb_details['overview'],
                'vote_average' => $filme->tmdb_details['vote_average'],
                'vote_count' => $filme->tmdb_details['vote_count'],
                'backdrop_path' => $filme->tmdb_details['backdrop_path'],
                'original_language' => $filme->tmdb_details['original_language'],
                'original_title' => $filme->tmdb_details['original_title'],
                'genre_ids' => $generos,
                'adult' => $filme->tmdb_details['adult'],
                'video' => $filme->tmdb_details['video'],
                'popularity' => $filme->tmdb_details['popularity'],
            ];
        }

        return response()->json($response, 200);

    }

    /**
     * Busca filmes favoritos por gênero.
     * @param Request $request
     * @param array $generos
     * @return JsonResponse
     */
    public function favoritosPorGenero(Request $request): JsonResponse
    {

        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Usuário não autenticado.'], 401);
        }

        if(Genero::all()->isEmpty()){
            $this->getGeneros();
        }

        $generos = $request->input('genero');
        $page = $request->input('page', 1);

        $user_filmes_id = auth()->user()->favoritos()->get()->pluck('id');

        $filmes = Filme::whereIn('id',$user_filmes_id)
        ->whereHas('generos', function ($query) use ($generos) {
            if(!$generos){
                return $query;
            }
            $query->whereIn('id', $generos);
        })
        ->paginate(10, ['*'], 'page', $page);

        // Filtra os filmes favoritos do usuário
        $response = [];
        foreach ($filmes as $filme) {
            $response['page'] = $filmes->currentPage();
            $response['total_pages'] = $filmes->lastPage();
            $response['total_results'] = $filmes->total();
            $response['results'][] = [
                'id' => $filme->tmdb_id,
                'title' => json_encode($filme->title, JSON_UNESCAPED_UNICODE),
                'is_favorito' => true,
                'poster_path' => $filme->poster_path,

                'release_date' => $filme->tmdb_details['release_date'],
                'overview' => $filme->tmdb_details['overview'],
                'vote_average' => $filme->tmdb_details['vote_average'],
                'vote_count' => $filme->tmdb_details['vote_count'],
                'backdrop_path' => $filme->tmdb_details['backdrop_path'],
                'original_language' => $filme->tmdb_details['original_language'],
                'original_title' => $filme->tmdb_details['original_title'],
                'genre_ids' => $filme->tmdb_details['genres'],
                'adult' => $filme->tmdb_details['adult'],
                'video' => $filme->tmdb_details['video'],
                'popularity' => $filme->tmdb_details['popularity'],
            ];
        }
        return response()->json($response, 200);
    }

// ====================== Métodos de favoritos

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

    /**
     * Formata a resposta dos filmes.
     * @param array $filmes
     * @param array $user_filmes
     * @return array
     */
    public function formatarFilmes($filmes, $user_filmes)
    {
        $response = [];

        if (!isset($filmes['results'])) {
            return $response;
        }

        // Formata os filmes e adiciona o atributo is_favorito

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

        return $response;
    }
}
