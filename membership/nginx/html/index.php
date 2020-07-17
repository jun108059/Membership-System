<?php include "db.php"; ?>
<!DOCTYPE html>
<head>
    <meta charset="utf-8"/>
    <title>회원가입 및 로그인 사이트</title>
    <link rel="stylesheet" type="text/css" href="/css/common.css"/>
</head>
<body>
<br>
<h1> <div style="text-align: center;">멤버쉽 기본 기능</div></h1>
<br>
<div id="login_box">
    <br>
    <h1><div style="text-align: center;">로그인</div></h1>
    <form method="post" action="/member/login_ok.php">
        <table align="center" border="0" cellspacing="0" width="300">
            <tr>
                <td width="130" colspan="1">
                    <input type="text" name="mem_user_id" class="inph">
                </td>
                <td rowspan="2" align="center" width="100">
                    <button type="submit" id="btn">로그인</button>
                </td>
            </tr>
            <tr>
                <td width="130" colspan="1">
                    <input type="password" name="mem_password" class="inph">
                </td>
            </tr>
            <tr>
                <td colspan="3" align="center" class="mem">
                    <br>
                    <a href="/member/member.php">회원가입</a>
                </td>
            </tr>
        </table>
    </form>
</div>
</body>
</html>