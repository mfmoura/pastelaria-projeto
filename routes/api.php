<?php

use App\Http\Controllers\ClienteController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\ForcarRespostaJson;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::middleware(['auth:sanctum', ForcarRespostaJson::class])->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware([ForcarRespostaJson::class, 'auth:sanctum'])->group(function () {
    Route::apiResource('produtos', ProdutoController::class);
    Route::apiResource('clientes', ClienteController::class);
    Route::apiResource('pedidos', PedidoController::class);
});