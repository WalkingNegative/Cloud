<?php


namespace app\Managers;


use core\db\DB;

class BlockedUser
{
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