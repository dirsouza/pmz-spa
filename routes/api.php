<?php

Route::prefix('v1/usuarios')->name('usuarios.')->group(function () {
    Route::get('/', 'UsuarioController@index')->name('index');
    Route::post('create', 'UsuarioController@create')->name('create');
    Route::put('update/{id}', 'UsuarioController@update')->name('update');
    Route::delete('delete/{id}', 'UsuarioController@delete')->name('delete');
});

Route::prefix('v1/perfis')->name('perfis.')->group(function () {
    Route::get('/', 'PerfilController@index')->name('index');
    Route::post('create', 'PerfilController@create')->name('create');
    Route::put('update/{id}', 'PerfilController@update')->name('update');
    Route::delete('delete/{id}', 'PerfilController@delete')->name('delete');
});

Route::prefix('v1/aparelhos')->name('aparelhos.')->group(function () {
    Route::get('/', 'AparelhoController@index')->name('index');
    Route::post('create', 'AparelhoController@create')->name('create');
    Route::put('update/{id}', 'AparelhoController@update')->name('update');
    Route::delete('delete/{id}', 'AparelhoController@delete')->name('delete');
});
