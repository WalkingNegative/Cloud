<?php
include 'vendor/autoload.php';

ini_set('display_errors', 1);

use app\Routes;
use core\Route;

session_start();

!file_exists('userdata')
    and mkdir('userdata');

Routes::registerRotes();
Route::dispatch($_SERVER['REQUEST_URI']);
