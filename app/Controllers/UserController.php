<?php

namespace app\Controllers;

use app\Managers\Action;
use app\Managers\Client;
use app\Managers\File;
use app\Managers\User;
use app\Managers\UserPhoto;
use app\Managers\UserToken;
use app\Tools\RandomStringGenerator;
use core\Controller;
use Exception;

class UserController extends Controller
{
    /**
     * @action login
     * @throws Exception
     */
    public function loginAction(): void
    {
        if (UserToken::isUserTokenValid($_SESSION['user_token'])) {
            header('location: /profile');
            return;
        }

        $user_data = (object)$_POST;
        $data = [];

        !empty($user_data->email)
            and $data['email'] = $user_data->email;

        if ($user_data->login) {
            if (empty($user_data->email) && empty($user_data->password)) {
                $data['error'] = 'Fields can not be empty!';
                $this->render('user/login.html.twig', $data);
                return;
            }

            $user = User::getUserByField('email', $user_data->email);

            if (empty($user) || ($user->password !== $user_data->password)) {
                $data['error'] = 'Incorrect login details!';
                $this->render('user/login.html.twig', $data);
                return;
            }

            if ($user->is_blocked) {//todo: fix
                $data['error'] = 'You are blocked!';
                $this->render('user/login.html.twig', $data);
                return;
            }

            Action::addAction($user->user_id, Action::TYPE_LOGIN, 'client');

            $_SESSION['user_token'] = UserToken::setUserToken($user->user_id);
            header('location: /profile');
        }

        $this->render('user/login.html.twig');
    }

    /**
     * @action logout
     * @throws Exception
     */
    public function logoutAction(): void
    {
        UserToken::deleteSession($_SESSION['user_token']);
        header('location: /profile');
    }

    /**
     * @action register
     * @throws Exception
     */
    public function registerAction(): void
    {
        if (UserToken::isUserTokenValid($_SESSION['user_token'])) {
            header('location: /profile');
            return;
        }

        $data = [];
        $user_data = (object)$_POST;

        !empty($user_data->email)
            and $data['email'] = $user_data->email;


        if ($user_data->register) {
            $error_msg = User::validateRegisterFields($user_data);

            if (!empty($error_msg)) {
                $data['error'] = $error_msg;
                $this->render('user/register.html.twig', $data);
                return;
            }

            do {
                $front_id = RandomStringGenerator::generate(User::FRONT_ID_LENGTH);
            } while (User::isValueExist('front_id', $front_id));

            $user_id = User::addNewUser($user_data, $front_id);
            Client::addNewClient((object)['user_id' => $user_id]);

            mkdir("userdata/{$front_id}");

            Action::addAction($user_id, Action::TYPE_REGISTER, 'client');
            $_SESSION['user_token'] = UserToken::setUserToken($user_id);
            header('location: /profile');
        }

        $this->render('user/register.html.twig');
    }

    /**
     * @action profile
     * @throws Exception
     */
    public function profileAction(): void
    {
        if (!UserToken::isUserTokenValid($_SESSION['user_token'])) {
            header('location: /login');
            return;
        }

        $user_id = UserToken::getUserIdByToken($_SESSION['user_token']);
        $user = Client::getClientInfo($user_id, false);

        if (empty($user)) {
            $this->render('user/profile.html.twig', ['error' => 'User not found!']);
            return;
        }

        $user = (array)$user;
        $user['photo'] = UserPhoto::getPhotoUrl($user_id);
        $user['files'] = File::getFilesByUserId($user_id, 10);

        $this->render('user/profile.html.twig', $user);
    }

    /**
     * @action profileEdit
     * @throws Exception
     */
    public function profileEditAction(): void
    {
        if (!UserToken::isUserTokenValid($_SESSION['user_token'])) {
            header('location: /login');
            return;
        }

        $_REQUEST['user_id'] = $user_id = UserToken::getUserIdByToken($_SESSION['user_token']);
        $user = Client::getClientInfo($user_id, false);

        if (empty($user)) {
            $this->render('user/profile.html.twig', ['error' => 'User not found!']);
            return;
        }

        $user = (array)$user;
        $user['photo'] = UserPhoto::getPhotoUrl($user_id);

        if ($_REQUEST['is_edit']) {
            Client::editClient((object)$_REQUEST);
            $user['message'] = 'User was updated successful!';
        }

        if ($_REQUEST['is_load_photo']) {
            $user['photo'] = UserPhoto::uploadPhoto((object)array_merge($_REQUEST, $user));
        }

        $this->render('user/profile_edit.html.twig', $user);
    }
}