<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PioController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\NavController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');;

Route::get('/dashboardAdmin', [AdminController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboardAdmin');;


Route::match(['put', 'patch'], 'users/{user}', [DashboardController::class, 'update'])->name('users.update');
Route::delete('users/{id}', [DashboardController::class, 'destroy'])->name('users.destroy');

//comments
Route::post('/pios/{pios}/comments', [CommentController::class, 'store'])->name('comments.store');
Route::delete('comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

//tags
Route::get('/searchbytag', [PioController::class, 'searchByTag'])->name('pios.searchByTag');

//likes
Route::post('/pios/{pio_id}', [LikeController::class, 'store'])->name('likes.store');

Route::middleware('auth')->group(function () {
    Route::get('/profile/partials', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile', [ProfileController::class, 'profile'])->name('profile.profile');
});

Route::resource('pios',PioController::class)
    ->middleware(['auth','verified']);

require __DIR__.'/auth.php';
