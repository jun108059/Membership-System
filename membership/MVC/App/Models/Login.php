<?php

namespace App\Models;

use PDO;
use PDOException;

/**
 * Login model
 */
class Login extends \Core\Model
{

    /**
     * Get all the posts as an associative array
     *
     * @return array
     */
    public static function getIdPassword()
    {
        try {
            // 추상화 Core Model 클래스 - getDB() 호출
            // DB 연결
            $db = static::getDB();

            $stmt = $db->query('SELECT mem_user_id, mem_password FROM user');
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}
