<?php

namespace app\Controllers;


use app\Managers\Action;
use app\Managers\BlockedUser;
use app\Managers\Client;
use app\Managers\File;
use app\Managers\User;
use app\Managers\UserToken;
use app\Managers\Operator;
use core\Controller;
use Exception;

class AdminController extends Controller
{
    /**
     * @action login
     * @throws Exception
     */
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

    /**
     * @action user
     */
    public function usersAction(): void
    {
        if (!Operator::isAdminLogined($_SESSION['user_token'])) {
            header('location: /admin/login');
            return;
        }

        $users = Client::getAllClientsInfo();

        $this->render('admin/users.html.twig', ['users' => $users]);
    }

    /**
     * @action ban
     */
    public function banAction(): void
    {
        if (!Operator::isAdminLogined($_SESSION['user_token'])) {
            return;
        }

        $user = Client::getClientInfo($_REQUEST['front_id'], true);

        if (empty($user)) {
            return;
        }

        if ($_REQUEST['is_ban']) {
            BlockedUser::blockUser($user->user_id);
        } else {
            BlockedUser::unblockUser($user->user_id);
        }
    }

    /**
     * @action files
     * @throws Exception
     */
    public function filesAction(): void
    {
        if (!Operator::isAdminLogined($_SESSION['user_token'])) {
            header('location: /admin/login');
            return;
        }

        $data = [];
        $data['files'] = File::getPublicFiles('');

        $this->render('admin/files.html.twig', $data);
    }

    /**
     * @action fileDelete
     * @throws Exception
     */
    public function fileDeleteAction(): void
    {
        if (!Operator::isAdminLogined($_SESSION['user_token'])) {
            header('location: /admin/login');
            return;
        }

        $user_id = UserToken::getUserIdByToken($_SESSION['user_token']);
        $file_id = $_REQUEST['front_id'];
        $answer = [
            'file_id' => $file_id,
        ];


        $answer['status'] = 1;
        $file = File::getFileInfoByFrontId($file_id);
        $file = (object)$file;

        File::deleteFileByFrontId($file_id);
        unlink($file->path);

        $answer = json_encode($answer);
        Action::addAction($user_id, Action::TYPE_FILE_DELETE, $answer);

        echo $answer;
    }

    /**
     * @action fileDownload
     * @throws Exception
     */
    public function fileDownloadAction(): void
    {
        if (!Operator::isAdminLogined($_SESSION['user_token'])) {
            header('location: /admin/login');
            return;
        }

        $file_id = $_REQUEST['front_id'];
        File::downloadFile($file_id);
    }

    /**
     * @action actions
     * @throws Exception
     */
    public function actionsAction(): void
    {
        if (!Operator::isAdminLogined($_SESSION['user_token'])) {
            header('location: /admin/login');
            return;
        }

        $data = [];
        $data['actions'] = Action::getActionList();

        $this->render('admin/actions.html.twig', $data);
    }
}