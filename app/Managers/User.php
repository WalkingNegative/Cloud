<?php


namespace app\Managers;


use app\Tools\RandomStringGenerator;
use core\db\DB;

class User
{
    const FRONT_ID_LENGTH = 8;

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
                user
            WHERE
                {$field} = '{$value}'
            LIMIT 1
        ";

        $rows = DB::getPDO()->query($sql)->fetchAll(\PDO::FETCH_OBJ);

        return !!count($rows);
    }

    /**
     * @param \stdClass $params
     * @param string $front_id
     * @return string
     */
    public static function addNewUser(\stdClass $params, string $front_id) {
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
     * @param \stdClass $params
     * @return string
     */
    public static function validateRegisterFields(\stdClass $params): string {
        if (empty($params->email)) {
            return 'Email can not be empty!';
        }

        if (self::isValueExist('email', $params->email)) {
            return "Email '{$params->email}' is already registered!";
        }

        if (empty($params->password)) {
            return 'Password can not be empty!';
        }

        if (empty($params->confirm_password)) {
            return 'Confirm password can not be empty!';
        }
        
        if ($params->password !== $params->confirm_password) {
            return 'Passwords should be equal!';
        }

        return '';
    }

    /**
     * @param string $field
     * @param $value
     * @return \stdClass|null
     */
    public static function getUserByField(string $field, $value) :?\stdClass{
        $sql = "
            SELECT
                *
            FROM
                user
            WHERE
                {$field} = '{$value}'
            LIMIT 1
        ";

        $rows = DB::getPDO()->query($sql)->fetchAll(\PDO::FETCH_OBJ);

        return !empty($rows) ? reset($rows) : null;
    }
}