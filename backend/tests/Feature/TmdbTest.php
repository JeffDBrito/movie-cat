<?php

use App\Services\TMDB;

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('verifica se a integração com o Tmdb Service está funcionando', function () {
    $tmdbService = new TMDB();
    $response = $tmdbService->getFilme(68718);

    expect($response['tmdb_details'])->toBeArray();
    expect($response['tmdb_details'])->toHaveKey('original_title');
    expect($response['tmdb_details']['original_title'])->toBe('Django Unchained');
});


