<?php

Route::post('api/chunk-upload', 'Api\ThirdPartyApiController@chunkUploadFile');

Route::post('api/upload/save', 'Api\ThirdPartyApiController@saveProduct');
