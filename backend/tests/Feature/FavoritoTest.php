<?php

use App\Models\User;
use App\Models\Filme;

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('verifica se o usuário pode favoritar um filme', function () {
    $user = User::factory()->create();
    $filme = Filme::factory()->create();

    $user->favoritar($filme);

    expect($user->favoritos->pluck('id'))->toContain($filme->id);
});

it('verifica se o usuário pode desfavoritar um filme', function () {
    $user = User::factory()->create();
    $filme = Filme::factory()->create();

    $user->favoritar($filme);
    $user->desfavoritar($filme);

    expect($user->favoritos->pluck('id'))->not->toContain($filme->id);
})->depends('it verifica se o usuário pode favoritar um filme');

