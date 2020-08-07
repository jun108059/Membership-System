<?php
include('head.php');
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta http-equiv="Content-Type" content="text/html;
    charset=UTF-8" />
    <title>멤버쉽 시스템</title>
    <script type="text/javascript" src="https://code.jquery.com/jquery.min.js"></script>
    <link rel="stylesheet" href="/bootstrap/css/bootstrap.min.css">
    <script src="/bootstrap/js/bootstrap.min.js"></script>
</head>
<body>
<div class="container">
    <div class="page-header">
        <h1 class="h2">&nbsp; 👑 관리자 Page </h1><hr>
    </div>
    <div class="row">

        <table class="table table-bordered table-hover table-striped" style="table-layout: fixed">
            <thead>
            <tr>
                <th> 사용자 정보 검색</th>
                <th> 휴면 계정 알림</th>
                <th> 휴면 계정 전환</th>
                <th> 휴면 계정 파기</th>
                <th> 미니 CRM</th>
            </tr>
            </thead>

            <tbody>
            <tr>
                <td><a class="btn btn-primary" href="/Admin/allUserInfo/1"><span class="glyphicon glyphicon-menu-hamburger"></span> 사용자보기</a></td>
                <td><a class="btn btn-info" href="/Dormant/dormantNoticeMail" onclick="return confirm('휴면 전환 알림 메일을 보낼까요?')">
                        <span class="glyphicon glyphicon-envelope"></span> 메일 전송</a></td>
                <td><a class="btn btn-success" href="/Dormant/turnIntoDormant" onclick="return confirm('30일 미접속자 휴면 계정으로 전환합니다.')">
                        <span class="glyphicon glyphicon-send"></span> 계정 전환</a></td>
                <td><a class="btn btn-danger" href="/Dormant/deleteDormant" onclick="return confirm('60일 미접속 휴면 계정 정보를 파기합니다.')">
                        <span class="glyphicon glyphicon-trash"></span> 정보 파기</a></td>
                <td><a class="btn btn-warning" href="" onclick="return confirm('⏰곧 구현하겠습니당😄')">
                        <span class="glyphicon glyphicon-signal"></span> 제작 중</a></td>
            </tr>
            </tbody>
        </table>
    </div>

</body>
</html>