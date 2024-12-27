<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\APIController;

Route::prefix('settings')->group(function () {
    Route::post('update/{setting}', [APIController::class, 'updateSetting']);
    Route::post('confirm/{change}', [APIController::class, 'confirmChange']);
});
