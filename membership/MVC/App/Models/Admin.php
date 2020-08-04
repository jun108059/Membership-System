<?php

namespace App\Models;

use PDO;

/**
 * Admin model
 */
class Admin extends \Core\Model
{

    public static function getUserData()
    {
        // DB 연결 - Abstract Core Model 클래스 - getDB() 호출
        $db = static::getDB();
        // 쿼리를 담은 PDOStatement 객체 생성 - return PDOStatement 객체
        // 페이지 나눠서 쿼리 요청 수정
        $stmt = $db->prepare("SELECT * FROM user ORDER BY mem_idx");

        // PDOStatement 객체가 가진 쿼리를 실행
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}
