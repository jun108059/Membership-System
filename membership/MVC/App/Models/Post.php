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
        try {
            // 추상화 Core Model 클래스 - getDB() 호출
            // DB 연결
            $db = static::getDB();

            $stmt = $db->query('SELECT id, title, content FROM posts
                                ORDER BY created_at');
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}
