<?php


use App\Http\Controllers\Font\frontCommonController;



Route::controller(frontCommonController::class)->group(function () {

    Route::get('/', 'home')->name('home');
    //mine
});

