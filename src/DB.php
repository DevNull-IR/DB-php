<?php
/**
 * Author: DevNull at PreCode
 *  Author Site: devnull-ali.ir
 *  Version: 5.0.
 */

namespace DevNull\DB;

use PDO;
use PDOException;

class DB
{
    protected PDO $pdo;

    /**
     * @param string $dbname
     * @param string $username_db
     * @param string $password_db
     * @param string $host
     */
    public function __construct(string $dbname, string $username_db, string $password_db, string $host = 'localhost')
    {
        $Option = [
            PDO::ATTR_PERSISTENT         => true,
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->pdo = new PDO('mysql:host='.$host.';dbname='.$dbname.';charset=utf8', $username_db, $password_db, $Option); //); // set and connect to db by pdo
            $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); // not hack :)
        } catch (PDOException $error) {
            file_put_contents('ErrorDB.log', $error->getMessage().PHP_EOL, 8);
            exit('Error To Connected To Mysql');
        }
    }

    /**
     * @param string           $select
     * @param string           $db
     * @param string|array|int $where
     * @param string|null      $other
     *
     * @return bool|array
     */
    public function select(string $select, string $db, string|array|int $where = 'None', string $other = null): bool|array
    {
        $a = null;
        $answer = [];
        if (isset($other) && !empty($other)) {
            $other = trim($other);
            $other = " $other";
        } else {
            $other = null;
        }
        if ($where === 'None') {
            $where_q = '1';
        } elseif (gettype($where) == 'array') {
            foreach ($where as $key => $value) {
                if (gettype($value) == 'string' or gettype($value) == 'integer') {
                    $a .= "$key = ? and ";
                    $answer[] = $value;
                } elseif (gettype($value) == 'array') {
                    $a .= "{$value[0]} "."{$value[1]} ".'? and ';
                    $answer[] = $value[2];
                }
            }
            $where_q = preg_replace("/ and(?=( \w+)?$)/", null, trim($a));
        } else {
            $where_q = '1';
        }
        $query = 'select '.$select.' from '.$db.' where '.$where_q.$other;

        try {
            $result = $this->pdo->prepare($query);
            if (gettype($where) == 'array') {
                for ($i = 1; $i < count($where) + 1; $i++) {
                    $result->bindValue($i, $answer[$i - 1]);
                }
            }
            $result->execute();
            $execute = [
                'count'    => $result->rowCount(),
                'fetchAll' => $result->fetchall(PDO::FETCH_ASSOC),
            ];
            $execute['fetch'] = $execute['fetchAll'][0] ?? null;
            if ($execute['fetch'] == null) {
                unset($execute['fetch']);
            }

            return $execute;
        } catch (PDOException $error) {
            file_put_contents('ErrorDB.log', $error->getMessage().PHP_EOL, 8);

            return false;
        }
    }

    /**
     * @param string $table
     * @param array  $array
     *
     * @return bool|int
     */
    public function insert(string $table, array $array): bool|int
    {
        $a = null;
        $b = null;
        $answer = [];
        foreach ($array as $key => $value) {
            $a .= ' ? ,';
            $b .= " $key ,";
            $answer[] = $value;
        }
        $a = preg_replace('/,(?=( \w+)?$)/', null, $a);
        $b = preg_replace('/,(?=( \w+)?$)/', null, $b);
        $query = "INSERT INTO \`{$table}\`( ' {$b} ' ) VALUES (' {$a} ')";

        try {
            $result = $this->pdo->prepare($query);
            for ($i = 1; $i < count($array) + 1; $i++) {
                $result->bindValue($i, $answer[$i - 1]);
            }
            $result->execute();

            return $result->rowCount();
        } catch (PDOException $error) {
            file_put_contents('ErrorDB.log', $error->getMessage().PHP_EOL, 8);

            return false;
        }
    }

    /**
     * @param string           $table
     * @param string|array|int $where
     * @param string|null      $other
     *
     * @return bool|int
     */
    public function deleted(string $table, string|array|int $where = 'None', string $other = null): bool|int
    {
        $a = null;
        $answer = [];
        if (isset($other) && !empty($other)) {
            $other = trim($other);
            $other = " $other";
        } else {
            $other = null;
        }
        if ($where === 'None') {
            $where_q = '1';
        } elseif (gettype($where) == 'array') {
            foreach ($where as $key => $value) {
                if (gettype($value) == 'string' or gettype($value) == 'integer') {
                    $a .= "$key = ? and ";
                    $answer[] = $value;
                } elseif (gettype($value) == 'array') {
                    $a .= "{$value[0]} "."{$value[1]} ".'? and ';
                    $answer[] = $value[2];
                }
            }
            $where_q = preg_replace("/ and(?=( \w+)?$)/", null, trim($a));
        } else {
            $where_q = '1';
        }
        $query = "DELETE FROM {$table} WHERE {$where_q} {$other}";
        $query = trim($query);

        try {
            $result = $this->pdo->prepare($query);
            if (gettype($where) == 'array') {
                for ($i = 1; $i < count($where) + 1; $i++) {
                    $result->bindValue($i, $answer[$i - 1]);
                }
            }
            $result->execute();

            return $result->rowCount();
        } catch (PDOException $error) {
            file_put_contents('ErrorDB.log', $error->getMessage().PHP_EOL, 8);

            return false;
        }
    }

    /**
     * @param string           $db
     * @param                  $update
     * @param string|array|int $where
     * @param string|null      $other
     *
     * @return bool|int
     */
    public function update(string $db, $update, string|array|int $where = 'None', string $other = null): bool|int
    {
        $answer = [];
        $a = null;
        $answer_where = [];
        $b = null;
        foreach ($update as $key => $value) {
            $a .= "$key = ? , ";
            $answer[] = $value;
        }
        if (gettype($where) == 'array') {
            foreach ($where as $key => $value) {
                if (gettype($value) == 'string' or gettype($value) == 'integer') {
                    $b .= "$key = ? and ";
                    $answer_where[] = $value;
                } elseif (gettype($value) == 'array') {
                    $b .= "{$value[0]} "."{$value[1]} ".'? and ';
                    $answer_where[] = $value[2];
                }
            }
            $b = preg_replace("/ and(?=( \w+)?$)/i", null, trim($b));
        } else {
            $b = 1;
        }
        $a = preg_replace("/ ,(?=( \w+)?$)/", null, trim($a));
        if (isset($other) && !empty($other)) {
            $other = " $other";
        }
        $Sql = "UPDATE {$db} SET {$a} WHERE {$b}{$other}";
        $Sql = trim($Sql);

        try {
            $result = $this->pdo->prepare($Sql);
            if (gettype($update) == 'array') {
                for ($i = 1; $i < count($update) + 1; $i++) {
                    $result->bindValue($i, $answer[$i - 1]);
                }
            }
            if (gettype($where) == 'array') {
                for ($i = count($update) + 1; $i < count($where) + count($update) + 1; $i++) {
                    $result->bindValue($i, $answer_where[$i - count($update) - 1]);
                }
            }
            if ($result->execute()) {
                return $result->rowCount();
            } else {
                return false;
            }
        } catch (PDOException $error) {
            file_put_contents('ErrorDB.log', $error->getMessage().PHP_EOL, 8);

            return false;
        }

    }

    /**
     * @param string                $select
     * @param string                $table
     * @param                       $like
     * @param array|int|string|null $where
     *
     * @return array|bool|PDO
     */
    public function like(string $select, string $table, $like, array|int|string $where = null): array|bool|PDO
    {
        $a = null;
        $answer = [];
        if (gettype($like) == 'array') {
            foreach ($like as $key => $value) {
                $a .= "$key like ? and ";
                $answer[] = $value;
            }
        }
        if (gettype($where) == 'array') {
            foreach ($where as $key => $value) {
                if (gettype($value) == 'string' or gettype($value) == 'integer') {
                    $a .= "$key = ? and ";
                    $answer[] = $value;
                } elseif (gettype($value) == 'array') {
                    $a .= "$value[0] "."$value[1] ".'? and ';
                    $answer[] = $value[2];
                }
            }
        }
        $a = preg_replace("/ and(?=( \w+)?$)/i", null, trim($a));
        $a = "select {$select} from {$table} where {$a}";

        try {
            $result = $this->pdo->prepare($a);
            for ($i = 1; $i < count($answer) + 1; $i++) {
                $result->bindValue($i, $answer[$i - 1]);
            }
            $result->execute();
            $execute = [
                'count'    => $result->rowCount(),
                'fetchAll' => $result->fetchall(PDO::FETCH_ASSOC),
            ];
            $execute['fetch'] = $execute['fetchAll'][0] ?? null;
            if ($execute['fetch'] == null) {
                unset($execute['fetch']);
            }

            return $execute;
        } catch (PDOException $error) {
            file_put_contents('ErrorDB.log', $error->getMessage().PHP_EOL, 8);

            return false;
        }
    }

    /**
     * @param string $table
     * @param $column
     *
     * @return bool
     */
    public function table(string $table, $column): bool
    {
        $a = null;
        if (gettype($column) == 'array') {
            foreach ($column as $key => $value) {
                $a .= "$key $value,\n        ";
            }
        }
        $a = preg_replace("/,(?=( \w+)?$)/", null, trim($a));
        $query = "CREATE TABLE {$table} (\n        $a\n        )";

        try {
            $result = $this->pdo->prepare($query);
            if ($result->execute()) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $error) {
            file_put_contents('ErrorDB.log', $error->getMessage().PHP_EOL, 8);

            return false;
        }
    }

    /**
     * @param string $table
     * @param $column
     *
     * @return bool
     */
    public function unique(string $table, $column): bool
    {
        $a = null;
        if (gettype($column) == 'array') {
            foreach ($column as $value) {
                $a .= "$value,";
            }
        }
        $a = preg_replace("/,(?=( \w+)?$)/", null, trim($a));
        $q = "ALTER TABLE $table\n    ADD UNIQUE ($a);";

        try {
            $result = $this->pdo->prepare($q);
            if ($result->execute()) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $error) {
            file_put_contents('ErrorDB.log', $error->getMessage().PHP_EOL, 8);

            return false;
        }
    }

    /**
     * @param string $table
     * @param $column
     *
     * @return bool
     */
    public function primary(string $table, $column): bool
    {
        $a = null;
        if (gettype($column) == 'array') {
            foreach ($column as $value) {
                $a .= "$value,";
            }
        }
        $a = preg_replace("/,(?=( \w+)?$)/", null, trim($a));
        $q = "ALTER TABLE \`{$table}\` ADD PRIMARY KEY (\`{$a}\`);";

        try {
            $result = $this->pdo->prepare($q);
            if ($result->execute()) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $error) {
            file_put_contents('ErrorDB.log', $error->getMessage().PHP_EOL, 8);

            return false;
        }
    }

    /**
     * @param       $table
     * @param array $columns
     *
     * @return bool
     */
    public function drop($table, array $columns = []): bool
    {
        $a = null;
        $q = null;
        if (gettype($table) == 'array') {
            foreach ($table as $value) {
                $a .= " $value,";
            }
            $a = preg_replace("/,(?=( \w+)?$)/", null, trim($a));
            $q = "DROP TABLE {$a};";
        } else {
            foreach ($columns as $value) {
                $a .= "DROP COLUMN $value,";
            }
            $a = preg_replace("/,(?=( \w+)?$)/", null, trim($a));
            $q = "ALTER TABLE $table\n$a;";
        }

        try {
            $result = $this->pdo->prepare($q);
            if ($result->execute()) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $error) {
            file_put_contents('ErrorDB.log', $error->getMessage().PHP_EOL, 8);

            return false;
        }
    }

    /**
     * @param string $table
     * @param string $column
     *
     * @return bool
     */
    public function autoIncrement(string $table, string $column): bool
    {
        try {
            $this->primary($table, ["$column"]);
            $a = "ALTER TABLE \`{$table}\` CHANGE `{$column}` `{$column}` BIGINT NOT NULL AUTO_INCREMENT;";
            $result = $this->pdo->prepare($a);
            $result = $result->execute();
            if ($result) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $error) {
            file_put_contents('ErrorDB.log', $error->getMessage().PHP_EOL, 8);

            return false;
        }
    }
}
