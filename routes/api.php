<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\QueueController;
use Illuminate\Support\Facades\Route;

Route::prefix('contacts')->group(function () {
    $contact = '{contact}';

    Route::get('/', [ContactController::class, 'index']);
    Route::post('/', [ContactController::class, 'store']);
    Route::get($contact, [ContactController::class, 'show']);
    Route::put($contact, [ContactController::class, 'update']);
    Route::delete($contact, [ContactController::class, 'destroy']);
    Route::post('export', [ContactController::class, 'exportCsv']);
});

Route::get('/queue/emails', [QueueController::class, 'listEmailJobs']);
