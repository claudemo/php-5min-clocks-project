<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OpenAIController;

Route::post('/openai', [OpenAIController::class, 'handle']);