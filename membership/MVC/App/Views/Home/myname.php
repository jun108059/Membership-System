<?php

//use App\Service\SessionManager;
////use Core\View;
//
//$session_manager = new SessionManager();
////        print_r($_SESSION);
////유효한 접근이 아니거나 로그인 유효시간이 지나면 로그인 page 로 이동
//if (!$session_manager->isValidAccess() || $session_manager->isLoginExpired()) {
//    $session_manager->destroy_session();
//} // 로그인 유효 시간 갱신
//else{
//    $session_manager->update_active_time();
//}

echo $_SESSION['mem_user_id'];
