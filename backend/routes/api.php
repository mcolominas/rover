<?php

use App\Http\Controllers\PlanetController;
use App\Http\Controllers\RoverController;
use Illuminate\Support\Facades\Route;

Route::post('planet', [PlanetController::class, 'store']);
Route::post('rovers/launch', [RoverController::class, 'launch']);
Route::post('rovers/{rover}/commands', [RoverController::class, 'executeCommands']);
