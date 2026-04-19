<?php

use App\Http\Controllers\Api\AdminDashboardController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\KaziController;
use App\Http\Controllers\Api\MfanyakaziDashboardController;
use App\Http\Controllers\Api\MuajiliDashboardController;
use App\Http\Controllers\Api\SkillController;
use App\Http\Controllers\Api\WafanyakaziController;
use Illuminate\Support\Facades\Route;

Route::get('/wafanyakazi', WafanyakaziController::class)->name('api.wafanyakazi.index');
Route::get('/kazi', KaziController::class)->name('api.kazi.index');
Route::get('/categories', CategoryController::class)->name('api.categories.index');
Route::get('/skills', SkillController::class)->name('api.skills.index');

Route::middleware('auth')->group(function (): void {
    Route::get('/dashboard/muajili', MuajiliDashboardController::class)->name('api.dashboard.muajili');
    Route::get('/dashboard/mfanyakazi', MfanyakaziDashboardController::class)->name('api.dashboard.mfanyakazi');
    Route::get('/dashboard/admin', AdminDashboardController::class)->name('api.dashboard.admin');

    Route::prefix('muajili')->name('api.muajili.')->group(function (): void {
        Route::get('/kazi', \App\Http\Controllers\Api\Muajili\KaziZanguController::class)->name('kazi');
        Route::post('/kazi', \App\Http\Controllers\Api\Muajili\StoreKaziController::class)->name('kazi.store');
        Route::get('/maombi', \App\Http\Controllers\Api\Muajili\MaombiController::class)->name('maombi');
        Route::get('/wallet', \App\Http\Controllers\Api\Muajili\WalletController::class)->name('wallet');
    });
});

Route::post('/webhooks/snippe', [\App\Http\Controllers\Api\SnippeWebhookController::class, 'handle'])->name('api.webhooks.snippe');
Route::post('/webhooks/snippe-payout', [\App\Http\Controllers\Api\SnippePayoutWebhookController::class, 'handle'])->name('api.webhooks.snippe-payout');
Route::post('/webhooks/selcom-payout', [\App\Http\Controllers\Api\SelcomPayoutWebhookController::class, 'handle'])->name('api.webhooks.selcom-payout');
