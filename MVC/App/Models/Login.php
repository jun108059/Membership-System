<?php

namespace App\Models;

use PDO;

/**
 * Login model
 */
class Login extends \Core\Model
{

    /**
     * (User 정보) 입력받은 id 일치하는 User 정보 가져오기
     * @param $userId
     * @return array|mixed
     */
    public static function getUserData($userId)
    {
        if (empty($userId)){
            return [];
        }
        // 입력받은 id 일치하는 User 정보 가져오기
        $db = static::getDB();
        $stmt = $db->prepare("SELECT * FROM user WHERE mem_user_id = :user_id");
        $stmt->bindValue(":user_id", $userId, PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch();
        if ($row['mem_status'] === 'H') {
            // 휴면계정 일 경우
            $stmt2 = $db->prepare("SELECT * FROM dormant WHERE mem_user_id = :user_id");
            $stmt2->bindValue(":user_id", $userId, PDO::PARAM_STR);
            $stmt2->execute();
            return $stmt2->fetch();
        }else {
            return $row;
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
        $db = static::getDB();

        $bindArray = [
            'dateTime'  => $userData['mem_log_dt'],
            'id'        => $userData['mem_user_id']
        ];
        // 최근 로그인 시간 Update 쿼리
        $sql = "UPDATE user SET mem_log_dt = :dateTime WHERE mem_user_id = :id";
        $stmt = $db->prepare($sql);
        $stmt->execute($bindArray);
        return true;
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
    }

}
