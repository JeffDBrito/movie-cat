<?php

use App\Models\User;
use App\Models\Filme;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);


it('retorna os usuários que favoritaram o filme', function () {
    $filme = Filme::factory()->create();
    $user = User::factory()->create();

    $user->favoritar($filme);

    expect($filme->favoritadoPor->pluck("id"))->toContain($user->id);
    expect($filme->isFavoritadoPor($user))->toBeTrue();
});

it('testa se o filme pode ser registrado no banco', function (){

    $filme = Filme::factory()->create([
        'tmdb_id' => 123456,
        'title' => 'Teste de Filme',
        'poster_path' => '/test/path.jpg',
        'tmdb_details' => json_encode(['overview' => 'Descrição do filme']),
    ]);

    expect($filme->tmdb_id)->toBe(123456);
    expect($filme->title)->toBe('Teste de Filme');
    expect($filme->poster_path)->toBe('/test/path.jpg');
    expect($filme->tmdb_details)->toBe(json_encode(['overview' => 'Descrição do filme']));
});
