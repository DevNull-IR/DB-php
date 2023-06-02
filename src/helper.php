<?php

use DevNull\DB\DB;

if (!function_exists('connect')) {
    /**
     * @param string $dbname
     * @param string $username_db
     * @param string $password_db
     * @param string $host
     *
     * @return DB
     */
    function connect(string $dbname, string $username_db, string $password_db, string $host = 'localhost'): DB
    {
        return new DB($dbname, $username_db, $password_db, $host);
    }
}
if (function_exists('connect') && !function_exists('select')) {
    /**
     * @param DB               $connection
     * @param string           $select
     * @param string           $db
     * @param string|array|int $where
     * @param string|null      $other
     *
     * @return bool|array
     */
    function select(DB $connection, string $select, string $db, string|array|int $where = 'None', string $other = null): bool|array
    {
        return $connection->select($select, $db, $where, $other);
    }
}

if (function_exists('connect') && !function_exists('insert')) {
    /**
     * @param DB     $connection
     * @param string $table
     * @param array  $array
     *
     * @return bool|int
     */
    function insert(DB $connection, string $table, array $array): bool|int
    {
        return $connection->insert($table, $array);
    }
}

if (function_exists('connect') && !function_exists('deleted')) {

    /**
     * @param DB               $connection
     * @param string           $table
     * @param string|array|int $where
     * @param string|null      $other
     *
     * @return bool|int
     */
    function deleted(DB $connection, string $table, string|array|int $where = 'None', string $other = null): bool|int
    {
        return $connection->deleted($table, $where, $other);
    }
}

if (function_exists('connect') && !function_exists('update')) {
    /**
     * @param DB               $connection
     * @param string           $db
     * @param                  $update
     * @param string|array|int $where
     * @param string|null      $other
     *
     * @return bool|int
     */
    function update(DB $connection, string $db, $update, string|array|int $where = 'None', string $other = null): bool|int
    {
        return $connection->update($db, $update, $where, $other);
    }
}

if (function_exists('connect') && !function_exists('like')) {
    /**
     * @param DB     $connection
     * @param string $select
     * @param string $table
     * @param        $like
     * @param null   $where
     *
     * @return array|bool|PDO
     */
    function like(DB $connection, string $select, string $table, $like, $where = null): array|bool|PDO
    {
        return $connection->like($select, $table, $like, $where);
    }
}

if (function_exists('connect') && !function_exists('table')) {
    /**
     * @param DB     $connection
     * @param string $table
     * @param        $column
     *
     * @return bool
     */
    function table(DB $connection, string $table, $column): bool
    {
        return $connection->table($table, $column);
    }
}

if (function_exists('connect') && !function_exists('unique')) {
    /**
     * @param DB     $connection
     * @param string $table
     * @param        $column
     *
     * @return bool
     */
    function unique(DB $connection, string $table, $column): bool
    {
        return $connection->unique($table, $column);
    }
}

if (function_exists('connect') && !function_exists('primary')) {
    /**
     * @param DB     $connection
     * @param string $table
     * @param        $column
     *
     * @return bool
     */
    function primary(DB $connection, string $table, $column): bool
    {
        return $connection->primary($table, $column);
    }
}

if (function_exists('connect') && !function_exists('drop')) {
    /**
     * @param DB    $connection
     * @param       $table
     * @param array $columns
     *
     * @return bool
     */
    function drop(DB $connection, $table, array $columns = []): bool
    {
        return $connection->drop($table, $columns);
    }
}

if (function_exists('connect') && !function_exists('autoIncrement')) {
    /**
     * @param DB     $connection
     * @param string $table
     * @param string $column
     *
     * @return bool
     */
    function autoIncrement(DB $connection, string $table, string $column): bool
    {
        return $connection->autoIncrement($table, $column);
    }
}