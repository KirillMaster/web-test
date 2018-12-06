<?php

use Illuminate\Http\Request;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::group(["prefix" => "groups"], function () {
    Route::get("/year/{year}", "GroupApiController@getByYear");
});

Route::group(["prefix" => "students"], function () {
    Route::group(["prefix" => "transfer"], function () {
        Route::post("/all/next", "StudentApiController@transferAllToNextCourse");
        Route::post("/{studentId}/next", "StudentApiController@transferToNextCourse");
    });
});
