<?php


namespace app\Managers;


use core\db\DB;

class Client
{
    const FIELD_LIST = ['user_id', 'first_name', 'last_name', 'birth', 'country', 'city'];

    public static function isValueExist(string $field, $value): bool
    {
        $sql = "
            SELECT
                *
            FROM
                client
            WHERE
                {$field} = '{$value}'
            LIMIT 1
        ";

        $rows = DB::getPDO()->query($sql)->fetchAll(\PDO::FETCH_OBJ);

        return !!count($rows);
    }

    public static function addNewClient(\stdClass $params): ?int
    {
        if (empty($params->user_id)) {
            return null;
        }

        $params = (array)$params;
        $sql = "
            INSERT INTO client
            SET 
        ";

        $fields = [];
        foreach ($params as $field => $value) {
            array_search($field, self::FIELD_LIST) !== false
            and $fields[] = "{$field} = '{$value}'";
        }

        $sql .= implode(', ', $fields);
        DB::getPDO()->query($sql);

        return DB::getPdo()->lastInsertId();
    }

    public static function editClient(\stdClass $params): ?int
    {
        if (empty($params->user_id)) {
            return null;
        }

        $params = (array)$params;
        $sql = "
            UPDATE client
            SET 
        ";

        $fields = [];
        foreach ($params as $field => $value) {
            array_search($field, self::FIELD_LIST) !== false
            and $fields[] = "{$field} = '{$value}'";
        }

        $sql .= implode(', ', $fields) . "
            WHERE user_id = '{$params['user_id']}'
        ";

        DB::getPDO()->query($sql);

        return DB::getPdo()->lastInsertId();
    }

    /**
     * @param $id
     * @param bool $is_front_id
     * @return mixed
     */
    public static function getClientInfo($id, bool $is_front_id = true)
    {
        $field = $is_front_id ? 'front_id' : 'user_id';

        $sql = "
            SELECT
                email,
                client.user_id,
                front_id,
                registration_time,
                first_name,
                last_name,
                birth,
                country,
                city
            FROM
                client
                JOIN user USING (user_id)
            WHERE
                user.{$field} = '{$id}'
            LIMIT 1
        ";

        $rows = DB::getPDO()->query($sql)->fetchAll(\PDO::FETCH_OBJ);

        return reset($rows);
    }

    /**
     * @return array
     */
    public static function getAllClientsInfo(): array
    {
        $sql = "
            SELECT DISTINCT
                email,
                client.user_id,
                front_id,
                registration_time,
                first_name,
                last_name,
                birth,
                country,
                city,
                block_time
            FROM
                client
                JOIN user USING (user_id)
                LEFT JOIN blocked_users USING (user_id)
        ";

        return DB::getPDO()->query($sql)->fetchAll(\PDO::FETCH_OBJ);
    }
}