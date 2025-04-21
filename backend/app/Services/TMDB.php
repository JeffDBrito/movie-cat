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
    public function buscarPorTitulo($titulo, $page = 1)
    {
        $url = "{$this->baseUrl}/search/movie?api_key={$this->apiKey}&query={$titulo}&language=pt-BR&page={$page}";

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

    /**
     * Busca generos disponíveis.
     * @return array|null
     */
    public function getGeneros()
    {
        $url = "{$this->baseUrl}/genre/movie/list?api_key={$this->apiKey}&language=pt-BR";
        try{
            $client = new Client();
            $response = $client->get($url);
            if ($response->getStatusCode() === 200) {
                $data = json_decode($response->getBody(), true);
                return $data;
            }
        }catch (\Exception $e) {
            Log::error("Erro ao buscar gêneros no TMDB: " . $e->getMessage());
            return null;
        }
    }


    /**
     * Busca filmes por gênero.
     * @param array $generos
     * @param bool $adults
     * @param int $page
     * @param string $sort
     * @param string $order
     */
    public function buscarPorGenero($generos, $page = 1, $adult = true, $ano = null, $ordem = 'popularity.desc')
    {
        $generos = is_array($generos) ? implode(',', $generos) : $generos;
        $url = "{$this->baseUrl}/discover/movie?api_key={$this->apiKey}&include_adult={$adult}&language=pt-BR&page={$page}&sort_by={$ordem}&with_genres={$generos}&primary_release_year={$ano}";
        try{
            $client = new Client();
            $response = $client->get($url);
            if ($response->getStatusCode() === 200) {
                $data = json_decode($response->getBody(), true);
                return $data;
            }
        }catch (\Exception $e) {
            Log::error("Erro ao buscar filmes por gênero no TMDB: " . $e->getMessage());
            return null;
        }
    }



}
