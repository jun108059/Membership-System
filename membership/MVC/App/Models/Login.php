<?php

namespace App\Models;

use PDO;
use PDOException;

/**
 * Login model
 */
class Login extends \Core\Model
{

    public static function getUserData($userId)
    {
        // DB 연결 - Abstract Core Model 클래스 - getDB() 호출
        $db = static::getDB();
        // 쿼리를 담은 PDOStatement 객체 생성 - return PDOStatement 객체
        $stmt = $db->prepare("SELECT * FROM user WHERE mem_user_id = :user_id");

        // PDOStatement 객체가 가진 쿼리의 parameter 에 변수 값을 바인드
        $stmt->bindValue(":user_id", $userId, PDO::PARAM_STR);

        // PDOStatement 객체가 가진 쿼리를 실행
        $stmt->execute();

        return $stmt->fetch();
    }

    public static function LoginCheck()
    {
        // 세션이 유지 되고 있는지 확인 하기!
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
