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
     * @param $email
     * @return string ID 반환
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
     * 이름 <-> Email 일치 확인
     * @param $email
     * @param $name
     * @return bool 일치 여부 반환
     */
    public static function checkNameEmailRight($name, $email)
    {
        // 추상화 Core Model 클래스 - getDB() 호출
        // DB 연결
        $db = static::getDB();

        $stmt = $db->prepare("SELECT mem_name from user WHERE mem_email=:email");
        $stmt->bindValue(':email', $email,PDO::PARAM_STR);
        // PDO Statement 객체가 가진 쿼리 실행
        $stmt->execute();
        // 결과 값 가져 오기
        $row = $stmt ->fetch();
        // DB name 과 입력된 name 일치 여부 반환 (T/F)
        return $row['mem_name']===$name;
    }

    /**
     * 재설정 비밀번호 DB Update
     * @param $userData
     * @return bool
     */
    public static function changePassword($userData)
    {
        // $user = 배열 && !Null 검사
        if (empty($userData) || !is_array($userData)) {
            return false;
        }

        // 추상화 Core Model 클래스 - getDB() 호출
        // DB 연결
        $db = static::getDB();

        $bindArray = [
            'password'  => $userData['mem_password'],
            'dateTime'  => $userData['mem_pw_dt'],
            'id'        => $userData['mem_user_id']
        ];

        $sql = "UPDATE user SET 
                mem_password = :password, 
                mem_pw_dt    = :dateTime 
                WHERE mem_user_id = :id";
        $stmt = $db->prepare($sql);
        // binding 값 넘겨서 실행
        $stmt->execute($bindArray);
        return true;
        // 에러 처리 필요
    }

    /**
     * 개인정보 수정 DB Update
     * @param $userData
     * @return bool
     */
    public static function changeInfo($userData)
    {
        // $user = 배열 && !Null 검사
        if (empty($userData) || !is_array($userData)) {
            return false;
        }

        // 추상화 Core Model 클래스 - getDB() 호출
        // DB 연결
        $db = static::getDB();

        $bindArray = [
            'password'      => $userData['mem_password'],
            'name'          => $userData['mem_name'],
            'phone'         => $userData['mem_phone'],
            'gender'        => $userData['mem_gender'],
            'pwDateTime'    => $userData['mem_pw_dt'],
            'logDateTime'   => $userData['mem_log_dt'],
            'id'            => $userData['mem_user_id']
        ];

        $sql = "UPDATE user SET 
                mem_password    = :password, 
                mem_name        = :name, 
                mem_phone       = :phone, 
                mem_gender      = :gender, 
                mem_pw_dt       = :pwDateTime,
                mem_log_dt      = :logDateTime
                WHERE mem_user_id = :id";
        $stmt = $db->prepare($sql);
        // binding 값 넘겨서 실행
        $stmt->execute($bindArray);
        return true;
        // 에러 처리 필요
    }

    /**
     * (삭제예정) 개인 정보 수정 - 현재 Password 일치 여부 검사
     * @param $userId
     * @param $userPw
     * @return bool
     */
    public static function checkPassword($userId, $userPw)
    {
        // (수정) 검사는 컨트롤러로 넘겨! 여기서 하는거 아니야

        // 추상화 Core Model 클래스 - getDB() 호출
        // DB 연결
        $db = static::getDB();

        $stmt = $db->prepare("SELECT mem_password from user WHERE mem_user_id=:userID");
        $stmt->bindValue(':userID', $userId, PDO::PARAM_STR);
        $stmt->execute();
        $password = $stmt->fetch();
        print($userPw);
        print('\n');
        print_r($password);
        print('\n');
        print(password_verify($password['mem_password'], $userPw));
        exit();
        return (password_verify($password['mem_password'], $userPw))? true : false;

//        if (password_verify($password['mem_password'], $userPw)) {
//            return true;
//        }else {
//            return false;
//        }
    }


}
