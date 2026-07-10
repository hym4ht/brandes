<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\IotController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| Tempat mendaftarkan rute API untuk aplikasi Brandes.
| Digunakan terutama untuk komunikasi alat IoT (ESP32).
*/

Route::prefix('iot')->group(function () {
    Route::post('/register', [IotController::class, 'register']);
    Route::post('/verify', [IotController::class, 'verify']);
    Route::post('/gps', [IotController::class, 'updateGps']);
    Route::post('/heartbeat', [IotController::class, 'heartbeat']);
    Route::post('/alert', [IotController::class, 'alert']); // Endpoint untuk alarm pembobolan
    Route::post('/reset', [IotController::class, 'resetMemory']); // Endpoint sinkronisasi hapus memori
    Route::get('/status', [IotController::class, 'getStatus']);
    Route::get('/latest-registration', [IotController::class, 'getLatestRegistration']);
});
