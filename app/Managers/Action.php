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
}