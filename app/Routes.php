<?php

namespace app;

use core\Route;

class Routes
{
    public static function registerRotes()
    {
        Route::add('^/$', ['controller' => 'Default', 'action'=>'index']);

        Route::add('^/login/?$', ['controller' => 'User', 'action'=>'login']);
        Route::add('^/logout/?$', ['controller' => 'User', 'action'=>'logout']);
        Route::add('^/register/?$', ['controller' => 'User', 'action'=>'register']);

        Route::add('^/profile/?$', ['controller' => 'User', 'action'=>'profile']);
        Route::add('^/profile/edit/?$', ['controller' => 'User', 'action'=>'profileEdit']);

        Route::add('^/file_load/?$', ['controller' => 'File', 'action'=>'load']);
        Route::add('^/file_delete/?$', ['controller' => 'File', 'action'=>'delete']);
        Route::add('^/file_download', ['controller' => 'File', 'action'=>'download']);
        Route::add('^/files/?$', ['controller' => 'File', 'action'=>'files']);

        Route::add('^/admin/login/?$', ['controller' => 'Admin', 'action'=>'login']);
        Route::add('^/admin/logout/?$', ['controller' => 'Admin', 'action'=>'login']);

        Route::add('^/admin/create_admin/?$', ['controller' => 'Admin', 'action'=>'create_admin']);
        Route::add('^/admin/users/?$', ['controller' => 'Admin', 'action'=>'users']);
        Route::add('^/admin/ban/?$', ['controller' => 'Admin', 'action'=>'ban']);

        Route::add('^/admin/files/?$', ['controller' => 'Admin', 'action'=>'files']);
        Route::add('^/admin/file_delete/?$', ['controller' => 'Admin', 'action'=>'file_delete']);
    }
}
