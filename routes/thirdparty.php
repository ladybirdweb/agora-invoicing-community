<?php

Route::post('chunk-upload', 'Api\ThirdPartyApiController@chunkUploadFile');

Route::post('upload/save', 'Api\ThirdPartyApiController@saveProduct')->name('upload/save');
