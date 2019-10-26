<?php

namespace app\Controllers;

use app\Managers\Action;
use app\Managers\Client;
use app\Managers\File;
use app\Managers\UserToken;
use app\Tools\RandomStringGenerator;
use core\Controller;
use Exception;

class FileController extends Controller
{
    /**
     * @action load
     * @throws Exception
     */
    public function loadAction(): void
    {
        if (!UserToken::isUserTokenValid($_SESSION['user_token'])) {
            return;
        }

        if (empty($_FILES)) {
            return;
        }

        do {
            $front_id = RandomStringGenerator::generate(File::FRONT_ID_LENGTH);
        } while (File::isValueExist('front_id', $front_id));

        $user_id = UserToken::getUserIdByToken($_SESSION['user_token']);
        $user = Client::getClientInfo($user_id, false);
        $file = (object)$_FILES['file'];
        $is_private = $_REQUEST['is_private'];

        File::uploadFile($user, $front_id, $file, $is_private);

        $answer = [
            'file_id' => $front_id,
            'name' => $file->name,
            'size' => round($file->size / 1048576, 2),
            'is_private' => $is_private,
        ];

        $file_info = json_encode($answer);
        Action::addAction($user_id, Action::TYPE_FILE_LOAD, $file_info);

        echo $file_info;
    }

    /**
     * @action delete
     * @throws Exception
     */
    public function deleteAction(): void
    {
        if (!UserToken::isUserTokenValid($_SESSION['user_token'])) {
            return;
        }

        $user_id = UserToken::getUserIdByToken($_SESSION['user_token']);
        $file_id = $_REQUEST['front_id'];
        $answer = [
            'user_id' => $user_id,
            'file_id' => $file_id,
        ];

        if (!File::isFileOwner($file_id, $user_id)) {
            $answer['status'] = 0;

            echo json_encode($answer);
            return;
        }

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
     * @action download
     * @throws Exception
     */
    public function downloadAction(): void
    {
        if (!UserToken::isUserTokenValid($_SESSION['user_token'])) {
            return;
        }

        $user_id = UserToken::getUserIdByToken($_SESSION['user_token']);
        $file_id = $_REQUEST['front_id'];

        if (!File::isFileOwner($file_id, $user_id)) {
            return;
        }

        File::downloadFile($file_id);
    }

    /**
     * @action files
     * @throws Exception
     */
    public function filesAction(): void
    {
        if (!UserToken::isUserTokenValid($_SESSION['user_token'])) {
            header('location: /login');
        }

        $user_id = 0;
        $data = [];
        $data['is_private'] = $is_private = $_REQUEST['is_private'];
        $data['search'] = $search = $_REQUEST['search'] ?? '';

        if (!empty($is_private)) {
            $user_id = UserToken::getUserIdByToken($_SESSION['user_token']);
        }

        $data['files'] = File::getPublicFiles($search, $user_id);
        $this->render('file/files.html.twig', $data);
    }
}
