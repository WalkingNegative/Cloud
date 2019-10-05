<?php


namespace app\Managers;


use app\Tools\RandomStringGenerator;
use core\Config;
use core\db\DB;
use Exception;

class UserToken
{
    const TOKEN_LENGTH = 64;

    /**
     * @param string $field
     * @param $value
     *
     * @return bool
     */
    public static function isValueExist(string $field, $value): bool
    {
        $sql = "
            SELECT
                *
            FROM
                user_token
            WHERE
                {$field} = '{$value}'
            LIMIT 1
        ";

        $rows = DB::getPDO()->query($sql)->fetchAll(\PDO::FETCH_OBJ);

        return !!count($rows);
    }

    /**
     * @param $token
     * @return bool
     * @throws Exception
     */
    public static function isUserTokenValid($token): bool
    {
        if (empty($token)) {
            return false;
        }

        $now = new \DateTime();
        $now = $now->format('Y-n-d H:i:s');

        $sql = "
            SELECT
                *
            FROM
                 user_token
            WHERE
                token = '{$token}'
                AND valid_to >= '{$now}'
            LIMIT 1
        ";

        $rows = DB::getPDO()->query($sql)->fetchAll(\PDO::FETCH_OBJ);

        return !!count($rows);
    }

    /**
     * @param int $user_id
     * @return string
     * @throws Exception
     */
    public static function setUserToken(int $user_id): string
    {
        do {
            $token = RandomStringGenerator::generate(self::TOKEN_LENGTH);
        } while (self::isValueExist('token', $token));

        $valid_to = new \DateTime('+' . Config::singleton()->get('user.session_duration') . ' minutes');
        $valid_to = $valid_to->format('Y-n-d H:i:s');

        $sql = "
            INSERT INTO user_token
            SET
                user_id = '{$user_id}',
                token = '{$token}',
                valid_to = '{$valid_to}'
            ON DUPLICATE KEY UPDATE
                token = '{$token}',
                valid_to = '{$valid_to}'
        ";

        DB::getPDO()->query($sql);

        return $token;
    }

    /**
     * @param string $token
     * @return int|null
     * @throws Exception
     */
    public static function getUserIdByToken(string $token): ?int
    {
        $now = new \DateTime();
        $now = $now->format('Y-n-d H:i:s');

        $sql = "
            SELECT
                *
            FROM
                 user_token
            WHERE
                token = '{$token}'
                AND valid_to <= '{$now}'
            LIMIT 1
        ";

        $rows = DB::getPDO()->query($sql)->fetchAll(\PDO::FETCH_OBJ);
        $user_token = reset($rows);

        return !empty($user_token) ? $user_token->user_id : null;
    }
}