<?php


namespace app\Managers;


use core\db\DB;
use stdClass;

class File
{
    const FRONT_ID_LENGTH = 8;

    /**
     * @param string $field
     * @param $value
     * @return bool
     */
    public static function isValueExist(string $field, $value): bool
    {
        $sql = "
            SELECT
                *
            FROM
                file
            WHERE
                {$field} = '{$value}'
            LIMIT 1
        ";

        $rows = DB::getPDO()->query($sql)->fetchAll(\PDO::FETCH_OBJ);

        return !!count($rows);
    }

    /**
     * @param int $user_id
     * @param int $limit
     * @return array
     */
    public static function getFilesByUserId(int $user_id, int $limit = 0): array
    {
        $sql = "
            SELECT
                file.front_id,
                file.name,
                file.size,
                file.path,
                file.is_private,
                file.load_time
            FROM
                file
                JOIN user USING (user_id)
            WHERE
                user_id = '{$user_id}'
            ORDER BY
                file.load_time DESC
        ";

        !empty($limit)
            and $sql .= "LIMIT {$limit}";

        return DB::getPDO()->query($sql)->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function getPublicFiles(string $file_name, int $user_id = null): array
    {
        if (empty($user_id)) {
            $where = "
                is_private = 0
            ";
        } else {
            $where = "
                user_id = {$user_id}
            ";
        }

        $sql = "
            SELECT
                file.front_id,
                name,
                size,
                path,
                is_private,
                first_name,
                last_name, 
                email,
                user.front_id AS user_id,
                load_time
            FROM
                file
                LEFT JOIN user USING (user_id)
                LEFT JOIN client USING (user_id)
            WHERE
                {$where}
                AND `name` LIKE '%{$file_name}%'
            ORDER BY
                file.load_time DESC
        ";

        !empty($limit)
        and $sql .= "LIMIT {$limit}";

        return DB::getPDO()->query($sql)->fetchAll(\PDO::FETCH_OBJ);
    }

    /**
     * @param stdClass $user
     * @param string $file_front_id
     * @param stdClass $file
     * @param bool $is_private
     */
    public static function uploadFile(stdClass $user, string $file_front_id, stdClass $file, bool $is_private = false): void
    {
        $file_path = "userdata/{$user->front_id}/{$file->name}";
        move_uploaded_file($file->tmp_name, $file_path);

        $params = [
            'front_id' => $file_front_id,
            'user_id' => $user->user_id,
            'name' => $file->name,
            'size' => $file->size,
            'path' => $file_path,
            'is_private' => $is_private,
        ];

        self::addNewFile((object)$params);
    }

    /**
     * @param stdClass $params
     */
    public static function addNewFile(stdClass $params): void
    {
        $params->is_private = (int)$params->is_private;
        $params->size = self::getFileSize($params->size);

        $sql = "
            INSERT INTO
                file
            SET 
                front_id = '{$params->front_id}',
                user_id = '{$params->user_id}',
                name = '{$params->name}',
                size = '{$params->size}',
                path = '{$params->path}',
                is_private = '{$params->is_private}'
        ";

        DB::getPDO()->query($sql);
    }

    /**
     * @param string $front_id
     */
    public static function deleteFileByFrontId(string $front_id): void
    {
        $sql = "
            DELETE FROM
                file
            WHERE
                front_id = '{$front_id}'
        ";

        DB::getPDO()->query($sql);
    }

    /**
     * @param string $front_id
     * @param int $user_id
     * @return bool
     */
    public static function isFileOwner(string $front_id, int $user_id): bool
    {
        $sql = "
            SELECT
                *
            FROM
                file
            WHERE
                front_id = '{$front_id}'
                AND (
                    user_id = '{$user_id}'
                    OR is_private = 0
                )
            LIMIT 1
        ";

        $rows = DB::getPDO()->query($sql)->fetchAll(\PDO::FETCH_OBJ);

        return !!count($rows);
    }

    /**
     * @param string $front_id
     * @return mixed|null
     */
    public static function getFileInfoByFrontId(string $front_id)
    {
        $sql = "
            SELECT
                file.front_id,
                file.name,
                file.size,
                file.path,
                file.is_private,
                file.load_time
            FROM
                file
            WHERE
                front_id = '{$front_id}'
            LIMIT 1
        ";

        $result = DB::getPDO()->query($sql)->fetchAll(\PDO::FETCH_OBJ);

        return !empty($result) ? reset($result) : null;
    }

    /**
     * @param string $front_id
     */
    public static function downloadFile(string $front_id)
    {
        $file = self::getFileInfoByFrontId($front_id);

        if (empty($file) || empty($file->path)) {
            return;
        }

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . basename($file->path));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file->path));
        readfile($file->path);
    }

    /**
     * @param int $size
     * @return string
     */
    public static function getFileSize(int $size): string
    {
        if ($size < 1024) {
            return round($size, 2) . ' B';
        } elseif ($size < 1048576) {
            return round($size / 1024, 2) . ' KB';
        } elseif ($size / 1073741824) {
            return round($size / 1048576, 2) . ' MB';
        } else {
            return round($size / 1073741824, 2) . ' GB';
        }
    }
}