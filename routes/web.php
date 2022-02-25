<?php

use App\Http\Controllers\Controller;
use App\Http\Controllers\UserDashboardController;
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

//This is the route for view
Route::get('/dashboard', [UserDashboardController::class,'getUserInformation']);

//This is the route for Ajax request to fetch Data a/c
Route::get('/filter-user', [UserDashboardController::class,'getUserFilterData']);