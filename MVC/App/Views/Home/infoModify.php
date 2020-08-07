<?php
if (!isset($_SESSION['userID'])) {
    echo '<script> alert("🧨잘못된 접근입니다.(home/index.php)"); history.back(); </script>';
//    echo "<meta http-equiv='refresh' content='0; url=/'>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="utf-8"/>
    <title>개인정보수정</title>
</head>
<body>
<form action="/Membership/newInfoToDB" method="post">
    <h1>🔧 개인정보수정 🔧</h1>
    <input type="hidden" name="user_id" id="user_id" value="<?php echo($user_id) ?>">
    <input type="hidden" name="user_pw" id="user_pw" value="<?php echo($user_pw) ?>">
    <input type="hidden" name="user_name" id="user_name" value="<?php echo($user_name) ?>">
    <input type="hidden" name="phone" id="phone" value="<?php echo($phone) ?>">
    <input type="hidden" name="gender" id="gender" value="<?php echo($gender) ?>">
    <input type="hidden" name="register" id="register" value="<?php echo($register) ?>">
    <input type="hidden" name="email" id="email" value="<?php echo($email) ?>">
    <input type="hidden" id="collect_password" value="n">
    <input type="hidden" id="collect_password_reg" value="n">
    <input type="hidden" id="collect_tell" value="n">
    <input type="hidden" id="collect_name" value="n">

    <h3>[<?php echo htmlspecialchars($user_id); ?>]님의 개인정보 수정 페이지입니다.</h3>
    <fieldset>
        <legend>개인정보수정</legend>
        <table>
            <tr>
                <td style="color: mediumblue; font-weight: bold;">아이디<br><br></td>
                <td><?php echo($user_id) ?><br><br></td>
            </tr>
            <tr>
                <td style="color: mediumblue; font-weight: bold;">이메일<br><br></td>
                <td><?php echo($email) ?><br><br></td>
            </tr>
            <tr>
                <td style="color: mediumblue; font-weight: bold;">현재 비밀번호<br><br></td>
                <td><input type="password" size="35" name="password" placeholder="사용중인 비밀번호"
                           class="checkPw" id="curr_pw" maxlength="20" required><br><br></td>
                <td>
                    <div id="check_pw_mention">&nbsp;현재 비밀번호를 입력하세요.</div>
                    <br></td>

            <tr>
                <td>변경할 비밀번호<br><br></td>
                <td><input type="password" size="35" name="password" placeholder="비밀번호(8 ~ 20 글자)"
                           class="memberPw" id="pw1" maxlength="20" required><br><br></td>
                <td>
                    <div id="check-pw-success" style="display: none; color: limegreen; font-weight: bold;">&nbsp;🟢사용가능한 비밀번호🟢<br><br></div>
                    <div id="check-pw-fail" style="display: inline; color: orange; font-weight: bold;">&nbsp;❌8~20자리로 영문, 숫자 포함❌<br><br></div>
                </td>

            </tr>
            <tr>
                <td>비밀번호 확인<br><br></td>
                <td><input type="password" size="35" name="password2" placeholder="비밀번호 확인"
                           class="memberPw2" id="pw2" maxlength="20" required><br><br></td>
                <td>
                    <div id="alert-success" style="display: none; color: blue; font-weight: bold;">&nbsp;✔😄비밀번호 일치😄✔<br><br></div>
                    <div id="alert-danger" style="display: inline; color: red; font-weight: bold;">&nbsp;❗비밀번호가 다름❗<br><br></div>
                </td>
            </tr>
            <tr>
                <td>이름<br><br></td>
                <td><input type="text" size="35" name="name" placeholder="<?php echo($user_name) ?>"
                           class="memberName" maxlength="20" required><br><br></td>
                <td>
                    <div id="name-available" style="display: none; color: blue; font-weight: bold;"> ✔사용 가능한 이름입니다.<br><br></div>
                    <div id="name-disable" style="display: inline; color: red; font-weight: bold;"> ❌이름은 한글 또는 영문 (2~20)<br><br></div>
                </td>
            </tr>
            <tr>
                <td>전화번호<br><br></td>
                <td><input type="text" size="35" name="phone" placeholder="010-1234-1234"
                           id="phone" class="tellCheck" title="010-1234-1234 형식" maxlength="13" required><br><br></td>
                <td>
                    <div id="check_phone_mention">&nbsp;실시간 전화번호 체크</div>
                    <br></td>
            </tr>
            <tr>
                <td>성별<br><br></td>
                <td>남<input type="radio" name="gender" value="M"> 여<input type="radio" name="gender" value="F" required><br><br>
                </td>
            </tr>
            <tr>
                <td style="color: mediumblue; font-weight: bold;">가입일<br><br></td>
                <td><?php echo($register) ?><br><br></td>
            </tr>

        </table>

        <input type="submit" id="form_sub" value="변경하기"/>&nbsp;&nbsp;<input type="reset" value="다시쓰기"/>

    </fieldset>
</form>

<div>
    <br>
    <input type="button" id="back" value="뒤로가기"
           style="color: #fff; background:gray; border-radius:0.5em; padding:3px 10px;" />
</div>

<div>
    <br>
    <input type="button" id="withdraw" value="회원탈퇴하기"
           style="color: #fff; background :red; border-radius:0.5em; padding:3px 10px;"
    />
</div>

<script type="text/javascript" src="https://code.jquery.com/jquery-3.2.0.min.js"></script>
<script>

    function goBack() {
        window.history.back();
    }

    $(document).ready(function () {
        var checkPwMention = $('#check_pw_mention');
        var checkPhoneMention = $('#check_phone_mention');
        // 실시간 변화 감지
        $(".checkPw").on("keyup", function(e){ // check 라는 클래스에 입력을 감지
            var curr_pw = $("#curr_pw").val(); // 입력한 password
            var $user_id = $("#user_id").val();
            $.ajax({
                url: "/Membership/checkPw",
                method: 'POST',
                data: {userId: $user_id, currPw: curr_pw},
                dataType: "json",
                async: false
            }).done(function (data) {
                checkPwMention.html(data.mention);
                if (data.status === 'check') {
                    checkPwMention.css("color", "#FFB300"); // css 입히기
                    $('#collect_password').val('n');
                } else if (data.status === 'available') {
                    checkPwMention.css("color", "#00FF99"); // css 입히기
                    $('#collect_password').val('y');
                } else {
                    checkPwMention.css("color", "#F00"); // css 입히기
                    $('#collect_password').val('n');
                }
                return false;
            });
        });

        // 비밀 번호 글자 수 유효성 검사
        $(".memberPw").on("keyup", function () {
            let passwordReg = /^(?=.*[a-zA-Z])((?=.*\d)|(?=.*\W)).{6,20}$/;
            if (!passwordReg.test($("#pw1").val())) {
                $("#check-pw-fail").css('display', 'inline');
                $("#check-pw-success").css('display', 'none');
                $("#collect_password_reg").val('n');
            } else {
                $("#check-pw-fail").css('display', 'none');
                $("#check-pw-success").css('display', 'inline');
                $("#collect_password_reg").val('y');
            }
        });

        // 비밀 번호 일치 검사
        $(".memberPw2").on("keyup", function () {
            let pwd1 = $("#pw1").val();
            let pwd2 = $("#pw2").val();

            // Null 확인
            if (pwd1 && !pwd2) {
                null;
            } else if (pwd1 || pwd2) {
                if (pwd1 === pwd2) {
                    $("#alert-success").css('display', 'inline');
                    $("#alert-danger").css('display', 'none');
                    $('#collect_password').val('y');
                } else {
                    $("#alert-success").css('display', 'none');
                    $("#alert-danger").css('display', 'inline');
                    $('#collect_password').val('n');
                }
            }
        });

        $(".tellCheck").on("keyup", function (e) { //checkId 클래스에 입력을 감지
            // 한글 방지
            if (!(e.keyCode >= 37 && e.keyCode <= 40)) {
                var v = $(this).val();
                $(this).val(v.replace(/[^a-z0-9-]/gi, ''));
            }

            var inputPhone = $("#phone").val();
            $.ajax({
                url: "/Membership/checkPhone",
                method: 'POST',
                data: {phone: inputPhone},
                dataType: "json",
                async: false
            }).done(function (data) {
                checkPhoneMention.html(data.mention);
                if (data.status === 'check') {
                    checkPhoneMention.css("color", "#FFB300"); // css 입히기
                    $('#collect_tell').val('n');
                } else if (data.status === 'available') {
                    checkPhoneMention.css("color", "#00FF99"); // css 입히기
                    $('#collect_tell').val('y');
                } else {
                    checkPhoneMention.css("color", "#F00"); // css 입히기
                    $('#collect_tell').val('n');
                }
                return false;
            });
        });

        // 이름 유효성 검사
        $(".memberName").on("keyup", function () {
            var nameReg = /^[^0-9][^`~!@#$%^&*|\\\'\";:\/?]{1,20}$/;
            if (!nameReg.test($("#name").val())) {
                $("#name-disable").css('display', 'inline');
                $("#name-available").css('display', 'none');
                $("#collect_name").val('n');
            } else {
                $("#name-disable").css('display', 'none');
                $("#name-available").css('display', 'inline');
                $('#collect_name').val('y');
            }
        });

        $('#withdraw').click(function () {
            var result = confirm('정말로 탈퇴하시겠습니까?');
            if (result) {
                //yes
                location.replace('/Membership/withDrawPage');
            } else {
                //no - nothing
            }
        });

        $('#back').click(function () {
            var result = confirm('이전 페이지로 이동할까요?');
            if (result) {
                //yes
                history.back();
            } else {
                //no - nothing
            }
        });

        $("#form_sub").click(function () {
            if ($('#collect_password').val() !== 'y') {
                alert('비밀번호가 일치하지 않습니다.');
                return false;
            } else if ($('#collect_password_reg').val() !== 'y') {
                alert('비밀번호는 8~20자리로 영문, 숫자를 포함해주세요.');
                return false;
            } else if ($('#collect_name').val() !== 'y') {
                alert('이름 형식에 맞게 입력해주세요.');
                return false;
            }else if ($('#collect_tell').val() !== 'y') {
                alert('전화번호를 확인해주세요.');
                return false;
            }
            alert("🎉회원가입이 완료되었습니다!");
            $('#form').submit();
        });

    });

</script>
</body>
</html>
