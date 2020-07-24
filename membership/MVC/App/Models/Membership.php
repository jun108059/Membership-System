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

    public static function insertInfo($user)
    {
        try {
            // 추상화 Core Model 클래스 - getDB() 호출
            // DB 연결
            $db = static::getDB();

            $sql = "INSERT INTO user SET
                     mem_user_id = '" . $user['mem_user_id'] . "',
                     mem_email = '" . $user['mem_email'] . "',
                     mem_password = '" . $user['mem_password'] . "',
                     mem_status = '" . $user['mem_status'] . "',
                     mem_cert = '" . $user['mem_cert'] . "',
                     mem_name = '" . $user['mem_name'] . "',
                     mem_phone = '" . $user['mem_phone'] . "',
                     mem_gender = '" . $user['mem_gender'] . "',
                     mem_level = '" . $user['mem_level'] . "',
                     mem_reg_dt = '" . $user['mem_reg_dt'] . "',
                     mem_log_dt = '" . $user['mem_log_dt'] . "',
                     mem_pw_dt = '" . $user['mem_pw_dt'] . "',
                     email_hash = '" . $user['certify'] . "'
                   ";

            echo $sql;

            $stmt = $db->exec($sql);
            echo $stmt;
        } catch (PDOException $e) {
            echo $e->getMessage(); // error 로그를 파일로 관리하면 좋음
            return false;
        }
    }

    public static function checkEmail($userEmail)
    {
        try {
            // 추상화 Core Model 클래스 - getDB() 호출
            // DB 연결
            $db = static::getDB();

            $stmt = $db->prepare("SELECT email_hash from user WHERE mem_email=:userEmail");
            $stmt->bindValue(':userEmail', $userEmail, PDO::PARAM_STR);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public static function isUserExisted($userID) {
        try {
            // 추상화 Core Model 클래스 - getDB() 호출
            // DB 연결
            $db = static::getDB();

            $stmt = $db->prepare("SELECT mem_user_id from user WHERE mem_user_id=:userID");
            $stmt->bindValue(':userID',$userID,PDO::PARAM_STR);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public static function isEmailExisted($email) {
        try {
            // 추상화 Core Model 클래스 - getDB() 호출
            // DB 연결
            $db = static::getDB();

            $stmt = $db->prepare("SELECT mem_email from user WHERE mem_email=:email");
            $stmt->bindValue(':email',$email,PDO::PARAM_STR);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public static function isPhoneExisted($phone)
    {
        try {
            // 추상화 Core Model 클래스 - getDB() 호출
            // DB 연결
            $db = static::getDB();

            $stmt = $db->prepare("SELECT mem_phone from user WHERE mem_phone=:phone");
            $stmt->bindValue(':phone', $phone, PDO::PARAM_STR);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

}
