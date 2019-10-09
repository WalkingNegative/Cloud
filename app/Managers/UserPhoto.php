<?php


namespace app\Managers;

use app\lib\img\AcImage;
use core\db\DB;

class UserPhoto
{
    /**
     * @param string $field
     * @param $value
     * @return bool
     */
    public static function isValueExist(string $field, $value): bool {
        $sql = "
            SELECT
                *
            FROM
                user_photo
            WHERE
                {$field} = '{$value}'
            LIMIT 1
        ";

        $rows = DB::getPDO()->query($sql)->fetchAll(\PDO::FETCH_OBJ);

        return !!count($rows);
    }


    public static function uploadPhoto(\stdClass $params) {
        $image_path = "userdata/{$params->front_id}/{$_FILES['upload_photo']['name']}";
        move_uploaded_file($_FILES["upload_photo"]["tmp_name"], $image_path);
        $params->photo_url = $image_path;

        self::deletePhoto($params->user_id);
        self::addPhoto($params);

        $image = AcImage::createImage($image_path);
        $image->resizeByHeight(350);
        $image->cropCenter(225, 350);
        unlink($image_path);
        $image->save($image_path);

        return "http://{$_SERVER['SERVER_NAME']}/{$image_path}";
    }

    /**
     * @param \stdClass $params
     * @return string
     */
    public static function addPhoto(\stdClass $params) {
        $sql = "
            INSERT INTO user_photo
            SET
                user_id = '{$params->user_id}',
                photo_url = '{$params->photo_url}'
        ";

        DB::getPDO()->query($sql);

        return DB::getPdo()->lastInsertId();
    }

    /**
     * @param $user_id
     */
    public static function deletePhoto($user_id) {
        $photo_url = self::getLocalPhotoUrl($user_id);

        $sql = "
            DELETE FROM user_photo
            WHERE user_id = '{$user_id}'
        ";

        DB::getPDO()->query($sql);

        unlink($photo_url);
    }

    /**
     * @param $user_id
     * @return |null
     */
    public static function getLocalPhotoUrl($user_id) {
        $sql = "
            SELECT
                photo_url
            FROM
                user_photo
            WHERE
                user_id = '{$user_id}'
            LIMIT 1
        ";

        $rows = DB::getPDO()->query($sql)->fetchAll();

        return !!count($rows) ? reset($rows)->photo_url : null;
    }

    /**
     * @param $user_id
     * @return string
     */
    public static function getFullPhotoUrl($user_id) {
        $sql = "
            SELECT
                photo_url
            FROM
                user_photo
            WHERE
                user_id = '{$user_id}'
            LIMIT 1
        ";

        $rows = DB::getPDO()->query($sql)->fetchAll(\PDO::FETCH_OBJ);

        $url = !!count($rows) ? reset($rows)->photo_url : null;

        if (!empty($url)) {
            return "http://{$_SERVER['SERVER_NAME']}/{$url}";
        }

        return "http://{$_SERVER['SERVER_NAME']}/userdata/default.jpg";
    }
}