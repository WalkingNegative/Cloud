<?php

namespace app\Controllers;


use app\Managers\Action;
use app\Managers\User;
use app\Managers\UserToken;
use app\Managers\Operator;
use core\Controller;

class AdminController extends Controller
{
    public function loginAction(): void
    {
        if (Operator::isAdminLogined($_SESSION['user_token'])) {
            header('location: /admin/users');
            return;
        }

        $user_data = (object)$_POST;
        $data = [];

        !empty($user_data->email)
            and $data['email'] = $user_data->email;

        if ($user_data->login) {
            if (empty($user_data->email) && empty($user_data->password)) {
                $data['error'] = 'Fields can not be empty!';
                $this->render('admin/login.html.twig', $data);
                return;
            }

            $user = User::getUserByField('email', $user_data->email);

            if (empty($user) || ($user->password !== $user_data->password) || empty(Operator::getOperatorByField('user_id', $user->user_id))) {
                $data['error'] = 'Incorrect login details!';
                $this->render('admin/login.html.twig', $data);
                return;
            }

            Action::addAction($user->user_id, Action::TYPE_LOGIN, 'operator');

            $_SESSION['user_token'] = UserToken::setUserToken($user->user_id);
            header('location: /admin/users');
        }

        $this->render('admin/login.html.twig');
    }
}