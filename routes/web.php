<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\EngineeringConstructionController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InspectionController;
use App\Http\Controllers\OmuCreekController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\RealEstateDevelopmentController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', AboutController::class)->name('about');
Route::get('/real-estate-development', RealEstateDevelopmentController::class)->name('real-estate-development');
Route::get('/real-estate-development/omu-creek', OmuCreekController::class)->name('omu-creek');
Route::get('/engineering-construction', EngineeringConstructionController::class)->name('engineering-construction');
Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
Route::get('/faq', FaqController::class)->name('faq');
Route::get('/book-inspection', [InspectionController::class, 'create'])->name('inspections.create');
Route::post('/book-inspection', [InspectionController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('inspections.store');
Route::get('/contact', ContactController::class)->name('contact');
