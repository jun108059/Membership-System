<?php

namespace App\Service;

use DateTime;

class SessionManager
{
    function __construct() //생성자
    {
        session_start(); //세션을 시작
    }

    function setSessionValue($key,$value) //생성자
    {
        $_SESSION[$key] = $value;
    }

    /**
    function getSession($key,$value)
    {
    return (!empty($_SESSION[$key])) ? : '';
    }
     */

    /**
     * 로그인 유효성 검사
     **/
    function isLoginExpired()
    {
        $result = false;
//        echo"<br>".(time() - strtotime($_SESSION['userLog']))." 값<br>";
        if ((time() - strtotime($_SESSION['userLog'])) > 1800) //30분동안 활동이 없으면 자동 로그아웃
        {
            echo '<script> alert("🔴시간 초과로 로그아웃 되었습니다\n로그인 후 이용해주세요!🔴"); </script>';
            $result = true;
            session_destroy();
        }
        return $result;
    }

    /** 세션을 종료 */
    function destroy_session()
    {
        session_destroy();
    }

    /** 접근 유효성 검사 */
    function isValidAccess()
    {
        $vaa = isset($_SESSION['userID']);
        echo $vaa;
        $result = false;
        if (isset($_SESSION['userID'])) {
            $result = true;
        }
        return $result;
    }

    /** 로그인 유지 시간 갱신 */
    function update_active_time()
    {
        $_SESSION["userLog"] = (new DateTime())->format('Y-m-d H:i:s');
    }
}
