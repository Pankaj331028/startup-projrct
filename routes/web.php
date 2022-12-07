<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TagController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\ZipcodeController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\PricingPlanController;
use App\Http\Controllers\PricingPlanDetailController;
use App\Http\Controllers\PricingPlanHistoryController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function() {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::delete('languages/destroy', [LanguageController::class, 'massDestroy'])->name('languages.massDestroy');
    Route::resource('languages', LanguageController::class);

    Route::delete('countries/destroy', [CountryController::class, 'massDestroy'])->name('countries.massDestroy');
    Route::resource('countries', CountryController::class);

    Route::delete('cities/destroy', [CityController::class, 'massDestroy'])->name('cities.massDestroy');
    Route::resource('cities', CityController::class);

    Route::delete('tags/destroy', [TagController::class, 'massDestroy'])->name('tags.massDestroy');
    Route::resource('tags', TagController::class);

    Route::delete('zipcodes/destroy', [ZipcodeController::class, 'massDestroy'])->name('zipcodes.massDestroy');
    Route::resource('zipcodes', ZipcodeController::class);

    Route::delete('pricing-plans/destroy', [PricingPlanController::class, 'massDestroy'])->name('pricing-plans.massDestroy');
    Route::resource('pricing-plans', PricingPlanController::class);

    Route::delete('pricing-plan-details/destroy', [PricingPlanDetailController::class, 'massDestroy'])->name('pricing-plan-details.massDestroy');
    Route::resource('pricing-plans.pricing-plan-details', PricingPlanDetailController::class);

    Route::delete('pricing-plan-histories/destroy', [PricingPlanHistoryController::class, 'massDestroy'])->name('pricing-plan-histories.massDestroy');
    Route::resource('pricing-plan-histories', PricingPlanHistoryController::class)->only('destroy');

    Route::resource('users', UserController::class);
});

require __DIR__.'/auth.php';
