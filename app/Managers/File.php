<?php


namespace app\Managers;


use core\db\DB;

class File
{
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
        ";

        !empty($limit)
            and $sql .= "LIMIT {$limit}";

        return DB::getPDO()->query($sql)->fetchAll(\PDO::FETCH_OBJ);
    }
}