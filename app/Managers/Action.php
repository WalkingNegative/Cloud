<?php


namespace app\Managers;


use core\db\DB;

class Action
{
    const TYPE_LOGIN = 'login';
    const TYPE_REGISTER = 'register';
    const TYPE_FILE_LOAD = 'load_file';
    const TYPE_FILE_DELETE = 'delete_file';

    /**
     * @param int $user_id
     * @param string $type
     * @param string $description
     */
    public static function addAction(int $user_id, string $type, string $description = ''): void
    {
        $sql = "
            INSERT INTO action
            SET
                user_id = '{$user_id}',
                type = '{$type}',
                description = '{$description}'
        ";

        DB::getPDO()->query($sql);
    }

    public static function getActionList(): array
    {
        $sql = "
            SELECT DISTINCT
                first_name,
                last_name, 
                email,
                user.front_id AS user_id,
                type,
                description,
                action_time
            FROM
                action
                LEFT JOIN user USING (user_id)
                LEFT JOIN client c USING (user_id)
            ORDER BY
                action_time DESC
            LIMIT
                100
        ";

        $rows = DB::getPDO()->query($sql)->fetchAll(\PDO::FETCH_OBJ);

        foreach ($rows as &$row) {
            $row->description = self::getDescriptionByType($row->description, $row->type);
        }

        return $rows;
    }

    public static function getDescriptionByType(string $description, string $type): string
    {
        switch ($type) {
            case self::TYPE_FILE_DELETE:
                $info = json_decode($description);
                return "file id: {$info->file_id}; status: {$info->status}";

            case self::TYPE_FILE_LOAD:
                $info = json_decode($description);
                return "file name: {$info->name}; size: {$info->size}; is private: {$info->is_private}";

            default:
                return $description;
        }
    }
}