<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactUsController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\OurServicesModelController;
use App\Http\Controllers\TestModelController;
use App\Http\Controllers\WebSiteElementsController;
use App\Http\Controllers\TeamInfoController;
use App\Http\Controllers\AboutUsController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('home-elements',[WebSiteElementsController::class,'homeElements']);
Route::get('get-blogs',[BlogController::class,'getBlogs']);
Route::get('blog-details/{id}',[BlogController::class,'blogDetails']);

Route::get('get-service',[OurServicesModelController::class,'getService']);
Route::get('service-details/{id}',[OurServicesModelController::class,'serviceDetail']);

// About Api 
Route::get('get-about',[AboutUsController::class,'getAbout']);
// Route::get('about-details/{id}',[AboutUsController::class,'aboutDetails']);

// team api
Route::get('get-team',[TeamInfoController::class,'getTeam']);
Route::get('team-details/{id}',[TeamInfoController::class,'teamDetail']);
