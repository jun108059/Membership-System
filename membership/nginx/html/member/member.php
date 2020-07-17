<?php
include "../db.php";
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>회원가입 폼</title>
</head>
<body>
<form method="post" action="member_ok.php">
    <h1>회원가입 폼</h1>
    <fieldset>
        <legend>입력하세요</legend>
        <table>
            <tr>
                <td>아이디<br><br></td>
                <td><input type="text" size="35" name="id" placeholder="아이디(30자 이하)"
                           maxlength="30"><br><br></td>
            </tr>
            <tr>
                <td>비밀번호<br><br></td>
                <td><input type="password" size="35" name="password" placeholder="비밀번호(20자 이하)"
                           maxlength="20"><br><br></td>
            </tr>
            <tr>
                <td>이메일<br><br></td>
                <td><input type="email" name="email" maxlength="30" placeholder="인증할 email">
                    @<select name="emadress">
                        <option value="naver.com">naver.com</option>
                        <option value="google.com">google.com</option>
                    </select>
                <input type="button"
                       id="button1"
                       value="인증하기"
                       onclick="button1_click();"><br><br>
                </td>
            </tr>
            <tr>
                <td>이름<br><br></td>
                <td><input type="text" size="35" name="name" placeholder="이름"><br><br></td>
            </tr>
            <tr>
                <td>전화번호<br><br></td>
                <td><input type="text" size="35" name="phone" placeholder="010-1234-1234"
                    pattern = "\d{3}\-\d{4}\-\d{4}"
                    title="010-1234-1234 형식으로 입력해주세요"
                    maxlength="13"><br><br></td>
            </tr>
            <tr>
                <td>성별<br><br></td>
                <td>남<input type="radio" name="gender" value="M"> 여<input type="radio" name="gender" value="F"><br><br></td>
            </tr>

        </table>

        <input type="submit" value="가입하기"/>&nbsp;&nbsp;<input type="reset" value="다시쓰기"/>

    </fieldset>
</form>
</body>
</html>

<script>
    function button1_click() {
        alert("버튼1을 눌렀다!");
    }
</script>