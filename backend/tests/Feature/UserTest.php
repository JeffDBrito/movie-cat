<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('cria um usuário com os atributos válidos', function () {
    $user = new User([
        'name' => 'Dante',
        'email' => 'dante@example.com',
        'password' => bcrypt('secret'),
    ]);

    expect($user->name)->toBe('Dante');
    expect($user->email)->toBe('dante@example.com');
    expect($user->password)->not->toBe('secret');
});

it('Trata a criação de um usuário com dados inválidos', function () {

    $response = $this->postJson('/api/user/store', [
        'name' => '',
        'email' => 'invalid-email',
        'password' => '123',
        'password_confirmation' => '123',
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['name', 'email', 'password', 'password_confirmation']);
});

