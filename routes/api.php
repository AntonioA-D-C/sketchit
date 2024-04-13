<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\CommentLikeController;
use App\Http\Controllers\CommentReplyController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PostCommentController;
use App\Http\Controllers\PostLikeController;
use App\Http\Controllers\UserController;

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
Route::get("/posts",  [PostController::class, 'index']);
Route::prefix('/post')->group(function () {
    Route::get("/{post}/comments",  [PostCommentController::class, 'post_comments']);
});

Route::post("/login",  [AuthController::class, 'login']);
Route::middleware('auth:api')->group(function () {


   
    Route::prefix('/post')->group(function () {
        Route::post("",  [PostController::class, 'create']);
        Route::get("/{post}",  [PostController::class, 'show']);
        Route::delete("/{post}",  [PostController::class, 'destroy']);
        Route::patch("/{post}",  [PostController::class, 'edit']);
        Route::post("/{post}/like",  [PostLikeController::class, 'like']);
        Route::post("/{post}/unlike",  [PostLikeController::class, 'unlike']);
        Route::get("/{post}/likes",  [PostLikeController::class, 'post_likes']);
        Route::post("/{post}/comment",  [PostCommentController::class, 'leave_comment']);
    });
    Route::get("/comments",  [CommentController::class, 'index']);
    Route::prefix('/comment')->group(function () {

        Route::get("/{comment}",  [CommentController::class, 'show']);


        Route::delete("/{comment}",  [CommentController::class, 'destroy']);
        Route::patch("/{comment}",  [CommentController::class, 'edit']);

        Route::post("/{comment}/like",  [CommentLikeController::class, 'like']);
        Route::post("/{comment}/unlike",  [CommentLikeController::class, 'unlike']);
        Route::post("/{comment}/reply",  [CommentReplyController::class, 'reply']);
        Route::get("/{comment}/replies",  [CommentReplyController::class, 'all_replies']);
    });
   Route::get('users', [UserController::class, 'index']);
    Route::prefix('/user')->group(function () {
        Route::get("/{user}",  [UserController::class, 'show']);
        Route::get("/{user}/posts",  [UserController::class, 'posts']);
        Route::get("/{user}/comments",  [UserController::class, 'comments']);
        Route::get("/{user}/follows",  [UserController::class, 'follows']);
        Route::get("/{user}/followers",  [UserController::class, 'followers']);
        Route::post("/{user}/follow",  [UserController::class, 'follow']);
        Route::delete("/{user}/unfollow",  [UserController::class, 'unfollow']);
    });

});


