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
        if (empty($userId)){
            return [];
        }

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

    /**
     * 최근 로그인 시간 Update
     * @param $userData
     * @return bool
     */
    public static function updateLogInDate($userData)
    {
        // $user = 배열 && !Null 검사
        if (empty($userData) || !is_array($userData)) {
            return false;
        }

        // 추상화 Core Model 클래스 - getDB() 호출
        // DB 연결
        $db = static::getDB();

        $bindArray = [
            'dateTime'  => $userData['mem_log_dt'],
            'id'        => $userData['mem_user_id']
        ];
        $sql = "UPDATE user SET mem_log_dt = :dateTime WHERE mem_user_id = :id";
        $stmt = $db->prepare($sql);
        // binding 값 넘겨서 실행
        $stmt->execute($bindArray);
        return true;
        // 에러 처리 필요
    }

    /**
     * Log Table INSERT
     * @param $userData
     * @param $logValue
     * @return bool
     */
    public static function logTableInsert($userData, $logValue)
    {
        // $user = 배열 && !Null 검사
        if (empty($userData) || !is_array($userData)) {
            return false;
        }

        // 추상화 Core Model 클래스 - getDB() 호출
        // DB 연결
        $db = static::getDB();

        // user 테이블에서 가져온 값 로그 테이블에 저장하기 위해 bind
        $bindArray = [
            'mem_idx'       => $userData['mem_idx'],
            'id'            => $userData['mem_user_id'],
            'log_type'      => $logValue,
            'date_time'     => $userData['mem_log_dt'],
        ];

        $sql = "INSERT INTO login_logout_log SET
                     mem_idx        = :mem_idx,
                     id             = :id,
                     log_type       = :log_type,
                     date_time      = :date_time
        ";
        $stmt = $db->prepare($sql);
        $stmt->execute($bindArray);
        return true;
        // 에러 처리 필요
    }

}
