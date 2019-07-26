<?php

Route::prefix('v1/usuarios')->name('usuarios.')->group(function () {
    Route::resource('/', 'UsuarioController');
});
