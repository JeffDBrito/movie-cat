<?php

use App\Http\Controllers\Filme\FilmeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\User\UserController;

// Auth routes
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum')->name('logout');

// User routes
Route::post('/user/store', [UserController::class, 'store'])->name('user.store');

// Filme routes
Route::post('/filmes/store', [FilmeController::class, 'store'])->name('filmes.store');
Route::get('/filmes/categoria/{categoria}', [FilmeController::class, 'filmesPorCategoria'])->name('filmes.categoria');
Route::post('/filmes/favoritar', [FilmeController::class, 'favoritar'])->name('filmes.favoritar');
Route::post('/filmes/desfavoritar', [FilmeController::class, 'desfavoritar'])->name('filmes.desfavoritar');
Route::get('/filmes/generos', [FilmeController::class, 'getGeneros'])->name('filmes.generos');
Route::get('/filmes/buscar/', [FilmeController::class, 'buscarPorTitulo'])->name('filmes.buscar');
Route::get('/filmes/buscar/genero', [FilmeController::class, 'buscarPorGenero'])->name('filmes.buscar.genero');


