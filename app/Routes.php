<?php

namespace app;

use core\Route;

class Routes
{
    public static function registerRotes()
    {
        Route::add('^/$', ['controller' => 'Default', 'action'=>'index']);

        Route::add('^/login/?$', ['controller' => 'User', 'action'=>'login']);
        Route::add('^/register/?$', ['controller' => 'User', 'action'=>'register']);
        Route::add('^/profile/?$', ['controller' => 'User', 'action'=>'profile']);
        Route::add('^/profile/edit/?$', ['controller' => 'User', 'action'=>'profileEdit']);
        Route::add('^/logout/?$', ['controller' => 'User', 'action'=>'logout']);

        Route::add('^/file_load/?$', ['controller' => 'File', 'action'=>'load']);
        Route::add('^/file_delete/?$', ['controller' => 'File', 'action'=>'delete']);
        Route::add('^/file_download', ['controller' => 'File', 'action'=>'download']);
        Route::add('^/files/?$', ['controller' => 'File', 'action'=>'files']);

    }
}
