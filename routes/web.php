<?php

use App\Http\Controllers\CaptchaServiceController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('home', ['title' => 'Home']);
})->name('home');

Route::get('register', [UserController::class, 'register'])->name('register');
Route::post('register', [UserController::class, 'register_action'])->name('register.action');
Route::get('login', [UserController::class, 'login'])->name('login');
Route::post('login', [UserController::class, 'login_action'])->name('login.action');
Route::get('password', [UserController::class, 'password'])->name('password');
Route::post('password', [UserController::class, 'password_action'])->name('password.action');
Route::get('logout', [UserController::class, 'logout'])->name('logout');
Route::get('/reload-captcha', [CaptchaServiceController::class, 'reloadCaptcha']);
Route::get('/forgot-password', [UserController::class, 'forgot_password'])->middleware('guest')->name('password.request');
Route::post('/forgot-password', [UserController::class, 'password_email'])->middleware('guest')->name('password.email');
Route::get('/reset-password/{token}', [UserController::class, 'reset_password'])->middleware('guest')->name('password.reset');
Route::post('/reset-password', [UserController::class, 'reset_password_action'])->middleware('guest')->name('password.update');