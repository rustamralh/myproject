<?php

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
    return inertia('Auth/Login');
});
Route::get('/login', function () {
    return inertia('Auth/Login');
});
Route::get('/dashboard', function () {
    return inertia('Dashboard');
});
Route::get('/navbar', function () {
    return inertia('NavBar');
});
Route::get('/photos', function () {
    return inertia('Photos');
});
// Route::get('/full-page', function () {
//     return inertia('FullPageLayout');
// });
