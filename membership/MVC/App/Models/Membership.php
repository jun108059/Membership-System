<?php

namespace App\Models;

use DateTime;
use PDO;
use PDOException;

/**
 * Membership model
 */
class Membership extends \Core\Model
{
    /**
     * 회원 가입을 통해 받은 유저 정보 저장
     * @param $user // 가입 유저 정보
     * @return bool $user != 배열 || Null = False
     */
    public static function insertInfo($user)
    {
        // $user = 배열 && !Null 검사
        if (empty($user) || !is_array($user)) {
            return false;
        }
        // DB 연결 > 추상화 Core Model 클래스 - getDB() 호출
        $db = static::getDB();

        $bindArray = [
            'mem_user_id'   => $user['mem_user_id'],
            'mem_email'     => $user['mem_email'],
            'mem_password'  => $user['mem_password'],
            'mem_status'    => $user['mem_status'],
            'mem_cert'      => $user['mem_cert'],
            'mem_name'      => $user['mem_name'],
            'mem_phone'     => $user['mem_phone'],
            'mem_gender'    => $user['mem_gender'],
            'mem_level'     => $user['mem_level'],
            'mem_reg_dt'    => $user['mem_reg_dt'],
            'mem_log_dt'    => $user['mem_log_dt'],
            'mem_pw_dt'     => $user['mem_pw_dt']
        ];
        // SQL Injection 방지 (placeHolder)
        $stmt = $db->prepare("INSERT INTO user SET
                     mem_user_id    = :mem_user_id,
                     mem_email      = :mem_email,
                     mem_password   = :mem_password,
                     mem_status     = :mem_status,
                     mem_cert       = :mem_cert,
                     mem_name       = :mem_name,
                     mem_phone      = :mem_phone,
                     mem_gender     = :mem_gender,
                     mem_level      = :mem_level,
                     mem_reg_dt     = :mem_reg_dt,
                     mem_log_dt     = :mem_log_dt,
                     mem_pw_dt      = :mem_pw_dt
        ");
        // binding 값 넘겨서 실행
        $stmt->execute($bindArray);
        return true;
    }

    /**
     * 중복 Id 있는지 검사
     * @param $userID
     * @return bool
     */
    public static function isUserExisted($userID)
    {
        // 추상화 Core Model 클래스 - getDB() 호출
        // DB 연결
        $db = static::getDB();

        $stmt = $db->prepare("SELECT mem_user_id from user WHERE mem_user_id=:userID");
        $stmt->bindValue(':userID', $userID, PDO::PARAM_STR);
        $stmt->execute();

        // true or false 만 반환
        return $stmt->rowCount() > 0;
        // 경우의 수가 두가지 뿐일때 왼쪽과 같이 간단하게 작성
        // return ($stmt->rowCount() > 0)? true : false;
        /*
        if ($stmt->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
        */
    }

    /**
     * 중복 Email 있는지 검사
     * @param $email
     * @return bool
     */
    public static function isEmailExisted($email)
    {
        // 추상화 Core Model 클래스 - getDB() 호출
        // DB 연결
        $db = static::getDB();

        $stmt = $db->prepare("SELECT mem_email from user WHERE mem_email=:email");
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }

    /**
     * 중복 Phone 있는지 검사
     * @param $phone
     * @return bool
     */
    public static function isPhoneExisted($phone)
    {
        // 추상화 Core Model 클래스 - getDB() 호출
        // DB 연결
        $db = static::getDB();

        $stmt = $db->prepare("SELECT mem_phone from user WHERE mem_phone=:phone");
        $stmt->bindValue(':phone', $phone, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    /**
     * ID 찾기
     * @param $name
     * @param $email
     * @return
     */
    public static function findId($email)
    {
        // 추상화 Core Model 클래스 - getDB() 호출
        // DB 연결
        $db = static::getDB();

        $stmt = $db->prepare("SELECT mem_user_id from user WHERE mem_email=:email");
        $stmt->bindValue(':email', $email,PDO::PARAM_STR);
        // PDO Statement 객체가 가진 쿼리 실행
        $stmt->execute();
        // 결과 값 가져 오기
        $row = $stmt ->fetch();
        echo "<pre>";
        print_r($row);
        echo "</pre>";
//        echo($row['mem_user_id']);
//        exit();
        return $row['mem_user_id'];
    }


    /**
     * 개인 정보 수정 - 현재 Password 일치 여부 검사
     * @param $userId
     * @return bool
     */
    public static function checkPassword($userId, $userPw)
    {
        // 추상화 Core Model 클래스 - getDB() 호출
        // DB 연결
        $db = static::getDB();

        $stmt = $db->prepare("SELECT mem_password from user WHERE mem_user_id=:userID");
        $stmt->bindValue(':userID', $userId, PDO::PARAM_STR);
        $stmt->execute();

        // true or false 만 반환
        return $stmt->rowCount() === 1;
        // 경우의 수가 두가지 뿐일때 왼쪽과 같이 간단하게 작성
        // return ($stmt->rowCount() > 0)? true : false;
        /*
        if ($stmt->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
        */
    }


}
