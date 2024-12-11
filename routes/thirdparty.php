<?php

use App\Http\Controllers\Api;
use Illuminate\Support\Facades\Route;

Route::post('api/chunk-upload', [Api\ThirdPartyApiController::class, 'chunkUploadFile']);

Route::post('api/upload/save', [Api\ThirdPartyApiController::class, 'saveProduct']);

Route::post('api/agent-software', [Api\ThirdPartyApiController::class, 'getFileContent'])->withoutMiddleware('validateThirdParty');
