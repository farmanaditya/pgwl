<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PointController;
use App\Http\Controllers\PolylineController;
use App\Http\Controllers\PolygonController;

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

// GeoJSON points
Route::get('/points', [PointController::class, 'index'])->name('api.points');

// GeoJSON point
Route::get('/point/{id}', [PointController::class, 'show'])->name('api.point');

// GeoJSON polylines
Route::get('/polylines', [PolylineController::class, 'index'])->name('api.polylines');

// GeoJSON polygons
Route::get('/polygons', [PolygonController::class, 'index'])->name('api.polygons');
