<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\NoteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function (){
    Route::post('/logout', [AuthController::class, 'logout']);

    // get all notes
    Route::get('/notes', [NoteController::class, 'index']);

    // get specific note by id 
    Route::get('/notes/{id}', [NoteController::class, 'show']);

    // store new notes
    Route::post('/notes', [NoteController::class, 'store']);

    // edit note
    Route::patch('/notes/{id}', [NoteController::class, 'update']);
    
    // delete note
    Route::delete('/notes/{id}', [NoteController::class, 'destroy']);
});
