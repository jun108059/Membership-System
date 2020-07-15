<?php
	session_start(); // 세션 시작
	header('Content-Type: text/html; charset=utf-8'); // utf-8인코딩

    // new mysqli(DB 호스트 주소, DB 아이디, DB 비밀번호, DB 이름)
	$db = new mysqli("localhost","yjpark","0000","POST_BOARD");
	$db->set_charset("utf8"); // DB 문자열 utf-8 인코딩

	function mq($sql)
	{
		global $db;
		return $db->query($sql);
	}
?>