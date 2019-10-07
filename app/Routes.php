<?php

namespace app;

use core\Route;

class Routes
{
    public static function registerRotes()
    {
        Route::add('^/$', ['controller' => 'Default', 'action'=>'index']);
        Route::add('^/login', ['controller' => 'User', 'action'=>'login']);
        Route::add('^/register', ['controller' => 'User', 'action'=>'register']);
        Route::add('^/profile/?$', ['controller' => 'User', 'action'=>'profile']);
        Route::add('^/profile/edit', ['controller' => 'User', 'action'=>'profileEdit']);
        Route::add('^/logout', ['controller' => 'User', 'action'=>'logout']);

        Route::add('^/myfiles', ['controller' => 'File', 'action'=>'myfiles']);
        Route::add('^/myfiles/edit', ['controller' => 'File', 'action'=>'myfiles/edit']);
        Route::add('^/myfiles/delete', ['controller' => 'File', 'action'=>'myfiles/delete']);
        Route::add('^/files', ['controller' => 'File', 'action'=>'files']);
        Route::add('^/files/edit', ['controller' => 'File', 'action'=>'files/edit']);
        Route::add('^/files/delete', ['controller' => 'File', 'action'=>'files/delete']);

    }
}
