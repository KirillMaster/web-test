<?php

use Illuminate\Http\Request;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::group(["prefix" => 'groups'], function () {
    Route::get('/year/{year}', 'GroupApiController@getByYear');
});
