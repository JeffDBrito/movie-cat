<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class TMDB {
    private $apiKey;
    private $baseUrl;
    private $imageUrl;

    public function __construct()
    {
        $this->apiKey = config('tmdb.api_key');
        $this->baseUrl = config('tmdb.api_url');
        $this->imageUrl = config('tmdb.image_url');
    }

    /**
     * Busca detalhes de um filme pelo ID do TMDB.
     * @param mixed $tmdbId
     */
    public function getFilme($tmdbId)
    {

        $url = "{$this->baseUrl}/movie/{$tmdbId}?api_key={$this->apiKey}&language=pt-BR";

        try{
            $client = new Client();

            $response = $client->get($url);

            if ($response->getStatusCode() === 200) {


                $data = json_decode($response->getBody(), true);
                return $data;
            }
        }catch (\Exception $e) {
            Log::error("Erro ao buscar filme no TMDB: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Busca filmes por categoria.
     * @param mixed $categoria
     * @param mixed $page
     */
    public function getFilmesPorCategoria($categoria, $page = 1)
    {
        $url = "{$this->baseUrl}/movie/{$categoria}?api_key={$this->apiKey}&language=pt-BR&page={$page}";

        try{
            $client = new Client();

            $response = $client->get($url);

            if ($response->getStatusCode() === 200) {
                $data = json_decode($response->getBody(), true);
                return $data;
            }
        }catch (\Exception $e) {
            Log::error("Erro ao buscar filmes por categoria no TMDB: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Busca filmes por título.
     * @param mixed $titulo
     */
    public function buscarPorTitulo($titulo)
    {
        $url = "{$this->baseUrl}/search/movie?api_key={$this->apiKey}&query={$titulo}&language=pt-BR";

        try{
            $client = new Client();

            $response = $client->get($url);

            if ($response->getStatusCode() === 200) {
                $data = json_decode($response->getBody(), true);
                return $data;
            }
        }catch (\Exception $e) {
            Log::error("Erro ao buscar filme por título no TMDB: " . $e->getMessage());
            return null;
        }
    }

}
