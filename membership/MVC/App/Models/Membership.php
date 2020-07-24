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
            echo $e->getMessage();
        }
    }

    public static function getId()
    {
        try {
            // 추상화 Core Model 클래스 - getDB() 호출
            // DB 연결
            $db = static::getDB();

            $stmt = $db->query('SELECT mem_user_id FROM user');
            return $stmt->fetchAll(PDO::FETCH_ASSOC); // fetch One 으로 변경
            // 배열 형태가 아니고 하나의 값으로 가져오기
            // Where 절 추가

        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        return $stmt;
    }
}
