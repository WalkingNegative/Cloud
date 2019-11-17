<?php


namespace app\Managers;


use core\db\DB;

class BlockedUser
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
                blocked_users
            WHERE
                {$field} = '{$value}'
            LIMIT 1
        ";

        $rows = DB::getPDO()->query($sql)->fetchAll(\PDO::FETCH_OBJ);

        return !!count($rows);
    }

    /**
     * @param int $id
     */
    public static function blockUser(int $id): void
    {
        $sql = "
            INSERT INTO blocked_users
            SET
                user_id = {$id},
                reason = 'Why not?'
        ";

        DB::getPDO()->query($sql);
    }

    /**
     * @param int $id
     */
    public static function unblockUser(int $id): void
    {
        $sql = "
            DELETE FROM blocked_users
            WHERE
                user_id = {$id}
        ";

        DB::getPDO()->query($sql);
    }
}