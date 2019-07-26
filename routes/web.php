<?php

Route::group(['laroute' => false], function () {
    Route::get('{any}', function () {
        return view('app');
    })->where('any', '.*');
});
