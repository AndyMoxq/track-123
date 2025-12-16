<?php

use Illuminate\Support\Facades\Route;
use ThankSong\Track123\Controllers\WebhookController;

Route::prefix('api')->group(function(){
    Route::post('/track123/webhook', [WebhookController::class, 'handleWebhook']);
});