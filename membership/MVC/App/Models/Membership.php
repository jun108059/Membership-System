<?php
namespace App\Models;

use DateTime;
use PDO;
use PDOException;

/**
 * Login model
 */
class Membership extends \Core\Model
{

    public static function insertInfo($user)
    {
        echo "인서트 출력되나요";
        try {
            // 추상화 Core Model 클래스 - getDB() 호출
            // DB 연결
            $db = static::getDB();
            /**
             * @add insert 문장 추가 하기!
             */
            $now = (new DateTime())->format('Y-m-d H:i:s');
            $mem_user_id = $user['id'];
            $mem_email = $user['email'] . '@' . $user['emadress'];
            $mem_password = password_hash($user['password'], PASSWORD_DEFAULT);
            $mem_status = 'Y'; // enum 타입 - 정상 가입
            $mem_cert = 'N'; // enum 타입 - 본인 인증 여부 디폴트 = N
            $mem_name = $user['name'];
            $mem_phone = $user['phone'];
            $mem_gender = $user['gender']; // enum 타입
            $mem_level = 4; // 일반 사용자 level 4
            $mem_reg_dt = $now; // 회원가입 일시
            $mem_log_dt = $now; // 마지막 로그인 일시
            $mem_pw_dt = $now; // 마지막 비밀번호 변경 일시
            $hash = md5( rand(0,1000) ); // 32자리 암호화 hash 값 생성 (인증용)
            // Example output: f4552671f8909587cf485ea990207f3b

            // 이메일 인증 위한 hash
            $certify = password_hash($user['email']. '@' . $user['emadress'], PASSWORD_DEFAULT);

            $sql = "INSERT INTO user(
                mem_user_id, mem_email, mem_password, mem_status, mem_cert, mem_name,
                mem_phone, mem_gender, mem_level, mem_reg_dt, mem_log_dt, mem_pw_dt, email_hash
            ) VALUES (
                '" . $mem_user_id . "', 
                '" . $mem_email . "', 
                '" . $mem_password . "',
                '" . $mem_status . "',
                '" . $mem_cert . "', 
                '" . $mem_name . "',
                '" . $mem_phone . "',
                '" . $mem_gender . "',
                '" . $mem_level . "',
                '" . $mem_reg_dt . "',
                '" . $mem_log_dt . "',
                '" . $mem_pw_dt . "',
                '" . $hash . "'
            )";
            echo $sql;
            exit;

            $stmt = $db->exec($sql);
            echo $stmt;
        } catch (PDOException $e) {
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
