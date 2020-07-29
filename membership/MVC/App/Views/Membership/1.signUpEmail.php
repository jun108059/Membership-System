<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8"/>
    <title>회원가입 페이지</title>
</head>

<body>
<form method="post">
    <h1>Email 본인 인증</h1>

    <div id="emailCheck">
    <fieldset>
        <legend>입력하세요</legend>
        <table>
            <tr>
                <td>이메일</td>
                <td><input type="text" name="email" maxlength="30" placeholder="인증 받을 email" required>
                    @<select name="emadress">
                        <option value="naver.com">naver.com</option>
                        <option value="google.com">google.com</option>
                    </select><span id="user-availability-status"></span>
                    <input type="submit" value="본인 인증 메일 전송 하기" formaction="/Membership/sendMail"/>
                </td>
            </tr>
        </table>

    </fieldset>
    </div>
</form>
</body>
</html>


<!--<style>-->
<!--    body{width:50%;}-->
<!--    #emailCheck {border-top:#F0F0F0 2px solid;background:#FAF8F8;padding:10px;}-->
<!--    .inputBox{padding:7px; border:#F0F0F0 1px solid; border-radius:4px;}-->
<!--    .status-available{color:#2FC332;}-->
<!--    .status-not-available{color:#D60202;}-->
<!--</style>-->
<!--<script type="text/javascript" src="https://code.jquery.com/jquery-3.2.0.min.js" ></script>-->
<!--<script>-->
<!--    function checkAvailability() {-->
<!--        // alert("abc");-->
<!--        // return false;-->
<!---->
<!--        $("#loaderIcon").show();-->
<!--        jQuery.ajax({-->
<!--            url: "/Membership/checkEmail",-->
<!--            data: 'email=' + $("#email").val(),-->
<!--            type: "POST",-->
<!--            success: function (data) {-->
<!--                $("#user-availability-status").html(data);-->
<!--                $("#loaderIcon").hide();-->
<!--            },-->
<!--            error: function () {-->
<!--            }-->
<!--        });-->
<!--    }-->
<!--</script>-->