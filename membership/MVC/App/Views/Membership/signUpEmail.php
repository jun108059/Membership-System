<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="utf-8"/>
    <title>회원가입 페이지</title>
</head>
<body>
<form method="post">
    <h1>Email 본인 인증</h1>

    <fieldset>
        <legend>입력하세요</legend>
        <table>
            <tr>
                <td>이메일<br><br></td>
                <td><input type="text" name="email" maxlength="30" placeholder="인증 받을 email" required>
                    @<select name="emadress">
                        <option value="naver.com">naver.com</option>
                        <option value="google.com">google.com</option>
                    </select>
                     <br><br>
                </td>
            </tr>
        </table>
        <input type="submit" value="본인 인증 메일 전송 하기" formaction="/Membership/sendMail"/>
    </fieldset>
</form>
</body>
</html>