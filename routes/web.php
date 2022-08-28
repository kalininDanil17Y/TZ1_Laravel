<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::view('/', 'page.index')->name('index');

Route::name('auth.')->middleware('guest')->group(function (){
    //Обработчик авторизации
    Route::view('/login', 'login')->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    //Обработчик регистрации
    Route::view('/register', 'register')->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});



Route::name('api.')->group(function (){
    Route::GET('/users', [ApiController::class, 'getUsers'])->name('getUsers');
    Route::GET('/users/logged', [ApiController::class, 'getUsersLogged'])->name('getUsersLogged');
    Route::GET('/users/notrophy', [ApiController::class, 'getUsersNoTrophy'])->name('getUsersNoTrophy');

    Route::POST('/trophy', [ApiController::class, 'getSumTrophy'])->name('getSumTrophy');
    Route::POST('/trophy/add', [ApiController::class, 'addTrophy'])->name('addTrophy');
    Route::POST('/trophy/del', [ApiController::class, 'delTrophy'])->name('delTrophy');
});




Route::get('/logout', function (){
    Auth::logout();
    return redirect(route('index'));
})->name('logout');
