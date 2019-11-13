<?php


namespace app\Managers;


use app\Tools\RandomStringGenerator;
use core\db\DB;

class Operator
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
                operator
            WHERE
                {$field} = '{$value}'
            LIMIT 1
        ";

        $rows = DB::getPDO()->query($sql)->fetchAll(\PDO::FETCH_OBJ);

        return !!count($rows);
    }

    public static function isAdminLogined(string $token = null): bool
    {
        if (empty($token)) {
            return false;
        }

        $user_id = UserToken::getUserIdByToken($token);

        if (empty($user_id)) {
            return false;
        }

        $user = self::getOperatorByField('user_id', $user_id);

        return ($user && $user->is_active);
    }

    /**
     * @param \stdClass $params
     * @param string $front_id
     * @return string
     */
    public static function addNewOperator(\stdClass $params, string $front_id) {
        $sql = "
            INSERT INTO user
            SET
                front_id = '{$front_id}',
                email = '{$params->email}',
                password = '{$params->password}'
        ";

        DB::getPDO()->query($sql);

        return DB::getPdo()->lastInsertId();
    }

    /**
     * @param string $field
     * @param $value
     * @return \stdClass|null
     */
    public static function getOperatorByField(string $field, $value) :?\stdClass{
        $sql = "
            SELECT
                *
            FROM
                operator
            WHERE
                {$field} = '{$value}'
            LIMIT 1
        ";

        $rows = DB::getPDO()->query($sql)->fetchAll(\PDO::FETCH_OBJ);

        return !empty($rows) ? reset($rows) : null;
    }
}