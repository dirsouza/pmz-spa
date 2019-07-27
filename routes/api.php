<?php

Route::prefix('v1/usuarios')->name('usuarios.')->group(function () {
    Route::resource('/', 'UsuarioController');
});

Route::prefix('v1/perfis')->name('perfis.')->group(function () {
    Route::resource('/', 'PerfilController');
});
