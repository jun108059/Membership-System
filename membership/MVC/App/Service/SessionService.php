<?php

class SessionService
{
    var $last_active_time; //마지막 활동 시간
    var $mem_user_id; //사용자 ID

    function __construct() //생성자
    {
        session_start(); //세션을 시작
        $this->mem_user_id = $_SESSION["mem_user_id"]; //멤버 변수 접근은 this 사용
        $this->last_active_time = $_SESSION["sign_in_timestamp"];
    }

    /**
     * 로그인 유효성 검사
     **/
    function isLoginExpired()
    {
        $result = false;
        if ((time() - $this->last_active_time) > 1800) //30분동안 활동이 없으면 자동 로그아웃
        {
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
        $result = false;
        if (isset($this->mem_user_id)) {
            $result = true;
        }
        return $result;
    }

    /** 로그인 유지 시간 갱신 */
    function update_active_time()
    {
        $_SESSION["sign_in_timestamp"] = time();
        $this->last_active_time = $_SESSION["sign_in_timestamp"];
    }
}
