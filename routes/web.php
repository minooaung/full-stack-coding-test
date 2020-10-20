<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', [\App\Http\Controllers\Auth\LoginController::class, 'showLoginForm']);

Auth::routes();

Route::get('/home', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/users', [\App\Http\Controllers\UsersController::class, 'index'])->name('users');
Route::get('/allusers', [\App\Http\Controllers\UsersController::class, 'getAllUsers'])->name('getAllUsers');

Route::get('/getuser/{id}', [\App\Http\Controllers\UsersController::class, 'getUser'])->name('getUser');
Route::post('/adduser', [\App\Http\Controllers\UsersController::class, 'addUser'])->name('addUser');
Route::put('/updateuser/{id}', [\App\Http\Controllers\UsersController::class, 'updateUser'])->name('updateUser');
Route::delete('/deleteuser/{id}', [\App\Http\Controllers\UsersController::class, 'deleteUser'])->name('deleteUser');







