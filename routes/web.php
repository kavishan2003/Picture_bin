<?php

use App\Livewire\Gallery;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImageController;

Route::get('/', function () {
    return view('welcome');
});


Route::get( '/gallery',Gallery::class);
Route::post( '/gallery',Gallery::class);

Route::get('/hash/{hash}',[ImageController::class,'getImage']);