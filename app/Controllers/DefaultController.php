<?php

namespace app\Controllers;

use app\Managers\UserToken;
use core\Controller;

class DefaultController extends Controller
{
    public function indexAction(): void
    {
        if (UserToken::isUserTokenValid($_SESSION['user_token'])) {
            header('location: /profile');
            return;
        }

        header('location: /login');
    }
}