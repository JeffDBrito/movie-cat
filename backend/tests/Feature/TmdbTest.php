<?php

use App\Services\TMDB;

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('verifica se a integração com o Tmdb Service está funcionando', function () {
    $tmdbService = new TMDB();
    $response = $tmdbService->getFilme(68718);

    expect($response)->toBeArray();
    expect($response)->toHaveKey('original_title');
    expect($response['original_title'])->toBe('Django Unchained');
});

it('verifica se é possível buscar filmes por categoria', function () {
    $tmdbService = new TMDB();
    $response = $tmdbService->getFilmesPorCategoria('popular');

    expect($response['results'])->toBeArray();
    expect($response['results'])->not()->toBeEmpty();
    expect($response['results'][0])->toHaveKey('original_title');
});

it('verifica se é possível buscar filmes por título', function () {
    $tmdbService = new TMDB();
    $response = $tmdbService->buscarPorTitulo('Django Unchained');

    expect($response['results'])->toBeArray();
    expect($response['results'])->not()->toBeEmpty();
    expect($response['results'][0])->toHaveKey('original_title');
    expect($response['results'][0]['original_title'])->toBe('Django Unchained');
});

it('verifica se é possível buscar a lista de generos', function () {
    $tmdbService = new TMDB();
    $response = $tmdbService->getGeneros();

    expect($response['genres'])->toBeArray();
    expect($response['genres'])->not()->toBeEmpty();
    expect($response['genres'][0])->toHaveKey('name');
});

it('verifica se é possível buscar filmes por genero', function () {
    $tmdbService = new TMDB();
    $response = $tmdbService->buscarPorGenero([28,12]);

    expect($response['results'])->toBeArray();
    expect($response['results'])->not()->toBeEmpty();
    expect($response['results'][0])->toHaveKey('original_title');
});
