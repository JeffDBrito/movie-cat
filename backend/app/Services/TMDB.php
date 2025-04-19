<?php

namespace App\Services;

use GuzzleHttp\Client;


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

    public function getFilme($tmdbId)
    {
        $url = "{$this->baseUrl}/movie/{$tmdbId}?api_key={$this->apiKey}&language=pt-BR";

        $client = new Client();

        $response = $client->get($url);

        if ($response->getStatusCode() === 200) {


            $data = json_decode($response->getBody(), true);
            return [
                'tmdb_id' => $data['id'],
                'title' => $data['title'],
                'poster_path' => $data['poster_path'],
                'tmdb_details' => $data,
            ];
        }
        return null;

    }
}
