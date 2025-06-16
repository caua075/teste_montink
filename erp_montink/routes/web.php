<?php

use App\Http\Controllers\ProdutoController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ProdutoController::class, 'index'])->name('produtos.index');
Route::post('/produtos', [ProdutoController::class, 'store'])->name('produtos.store');
Route::get('/produtos/{id}/editar', [ProdutoController::class, 'editar'])->name('produtos.editar');
Route::post('/comprar/{id}', [ProdutoController::class, 'comprar'])->name('produtos.comprar');
Route::get('/carrinho', [ProdutoController::class, 'carrinho'])->name('carrinho');
Route::post('/remover/{id}', [ProdutoController::class, 'remover'])->name('produtos.remover');

Route::post('/consultar-cep', [ProdutoController::class, 'consultarCep'])->name('consultar.cep');
Route::post('/finalizar-pedido', [ProdutoController::class, 'finalizarPedido'])->name('produtos.finalizarPedido');
