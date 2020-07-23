<?php

namespace Core;

use PDO;
use PDOException;

/**
 * Base model
 *
 * PHP version 7.4
 */
abstract class Model
{

    /**
     * Get the PDO database connection
     *
     * @return mixed
     */
    protected static function getDB()
    {
        static $db = null;

        if ($db === null) {
            $host = 'localhost';
            $dbname = 'membership';
            $username = 'root';
            $password = '1234';

            try {
                // DB 연결 코드
                $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8",
                    $username, $password);

            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }

        return $db;
    }
}
