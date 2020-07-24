<?php

namespace App\Models;

use PDO;
use PDOException;

/**
 * Login model
 */
class Login extends \Core\Model
{
    public static function getPassword($mem_id)
    {
        try {
            // 추상화 Core Model 클래스 - getDB() 호출
            // DB 연결
            $db = static::getDB();
            // 쿼리를 담은 PDOStatement 객체 생성 - return PDOStatement 객체
            $stmt = $db->prepare("SELECT mem_password FROM user WHERE mem_user_id = :user_id");

            // PDOStatement 객체가 가진 쿼리의 parameter 에 변수 값을 바인드
            $stmt -> bindValue(":user_id", $mem_id);

            // PDOStatement 객체가 가진 쿼리를 실행
            $stmt -> execute();

            // PDOStatement 객체가 실행한 쿼리의 결과값 가져 오기
            //            echo "<pre>";
//            print_r($row);
//            echo "</pre>";
            return $stmt -> fetch();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

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
