<?php

use App\Livewire\Gallery;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::get( '/gallery',Gallery::class);