<?php

namespace App\Models;

use PDO;
use PDOException;

/**
 * Post model
 */
class Post extends \Core\Model
{

    /**
     * Get all the posts as an associative array
     *
     * @return array
     */
    public static function getAll()
    {
//        $host = 'localhost';
//        $dbname = 'membership';
//        $username = 'root';
//        $password = '1234';

        try {
            // $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8",
            //    $username, $password);
            // 추상화 Core Model 클래스 - getDB() 호출
            $db = static::getDB();

            $stmt = $db->query('SELECT id, title, content FROM posts
                                ORDER BY created_at');
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $results;

        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}
