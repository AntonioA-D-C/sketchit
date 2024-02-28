<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CommentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

 

Route::post("/register",  [AuthController::class, 'register']);

Route::post("/login",  [AuthController::class, 'login']);
Route::middleware('auth:api')->group(function () {


Route::get("/posts",  [PostController::class, 'index']);
Route::prefix('/post')->group(function(){
    Route::post("",  [PostController::class, 'create']);
    Route::get("/{post}",  [PostController::class, 'show']);
    Route::delete("/{post}",  [PostController::class, 'destroy']);
    Route::patch("/{post}",  [PostController::class, 'edit']);
  
});
Route::get("/comments",  [CommentController::class, 'index']);
Route::prefix('/comment')->group(function(){
    Route::post("",  [CommentController::class, 'create']);
 
    Route::get("/{comment}",  [CommentController::class, 'show']);
    Route::delete("/{comment}",  [CommentController::class, 'destroy']);
    Route::patch("/{comment}",  [CommentController::class, 'edit']);
  
});

});