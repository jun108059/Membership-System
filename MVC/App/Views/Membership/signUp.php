<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="utf-8"/>
    <title>회원가입 페이지</title>
</head>
<body>
<form action="/Membership/signUpDB" method="post">
    <h1>회원가입</h1>
    <input type="hidden" name="email" id="email" value="<?php echo($email) ?>">
    <input type="hidden" id="collect_id" value="n">
    <input type="hidden" id="collect_password" value="n">
    <input type="hidden" id="collect_password_reg" value="n">
    <input type="hidden" id="collect_tell" value="n">
    <input type="hidden" id="collect_name" value="n">

    <h3>이메일 [<?php echo htmlspecialchars($email); ?>] 인증이 완료되었습니다.</h3>
    <fieldset>
        <legend>입력하세요</legend>
        <table>
            <tr>
                <td>아이디<br><br></td>
                <td><input type="text" id="userId" name="userId" size="35" class="checkId" style="ime-mode:disabled"
                           placeholder="아이디(4 ~ 30 글자)" maxlength="30" required><br><br></td>
                <td>
                    <div id="check_id_mention">&nbsp;실시간 ID 체크</div>
                    <br></td>

            </tr>
            <tr>
                <td>비밀번호<br><br></td>
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
                    <div id="alert-success" style="display: none; color: blue; font-weight: bold;"> 😄비밀번호 일치😄✔<br><br>
                    </div>
                    <div id="alert-danger" style="display: inline; color: red; font-weight: bold;"> ❗비밀번호가 다름❗<br><br>
                    </div>
                </td>

            </tr>
            <tr>
                <td>이름<br><br></td>
                <td><input type="text" size="35" name="name" placeholder="이름 2 ~ 20 글자"
                           class="memberName" id="name" maxlength="20" required><br><br></td>
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

        </table>

        <input type="submit" id="form_sub" value="가입하기" />&nbsp;&nbsp;<input type="reset" value="다시쓰기"/>

    </fieldset>
</form>

<script type="text/javascript" src="https://code.jquery.com/jquery-3.2.0.min.js"></script>
<script>
    $(document).ready(function () {
        var checkIdMention = $('#check_id_mention');
        var checkPhoneMention = $('#check_phone_mention');
        // 실시간 변화 감지
        $(".checkId").on("keyup", function (e) { //checkId 클래스에 입력을 감지
            // 한글 방지
            if (!(e.keyCode >= 37 && e.keyCode <= 40)) {
                var v = $(this).val();
                $(this).val(v.replace(/[^a-z0-9]/gi, ''));
            }

            var inputID = $("#userId").val();
            $.ajax({
                url: "/Membership/checkId",
                method: 'POST',
                data: {userId: inputID},
                dataType: "json",
                async: false
            }).done(function (data) {
                checkIdMention.html(data.mention);
                if (data.status === 'check') {
                    checkIdMention.css("color", "#FFB300"); // css 입히기
                    $('#collect_id').val('n');
                } else if (data.status === 'available') {
                    checkIdMention.css("color", "#00FF99"); // css 입히기
                    $('#collect_id').val('y');
                } else {
                    checkIdMention.css("color", "#F00"); // css 입히기
                    $('#collect_id').val('n');
                }
                return false;
            });
        });

        // 비밀 번호 글자 수 유효성 검사
        $(".memberPw").on("keyup", function () {
            var passwordReg = /^(?=.*[a-zA-Z])((?=.*\d)|(?=.*\W)).{6,20}$/;
            if (!passwordReg.test($("#pw1").val())) {
                $("#check-pw-fail").css('display', 'inline');
                $("#check-pw-success").css('display', 'none');
                $("#collect_password_reg").val('n');
            } else {
                $("#check-pw-fail").css('display', 'none');
                $("#check-pw-success").css('display', 'inline');
                $('#collect_password_reg').val('y');
            }
        });

        // 비밀 번호 일치 검사
        $(".memberPw2").on("keyup", function () {
            var pwd1 = $("#pw1").val();
            var pwd2 = $("#pw2").val();

            // Null 확인
            if (pwd1 && !pwd2) {

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

        // 이름 유효성 검사
        $(".memberName").on("keyup", function () {
            var nameReg = /^[^0-9][^`~!@#$%^&*|\\\'\";:\/?]{2,20}$/;
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

        $("#form_sub").click(function () {
            if ($('#collect_id').val() !== 'y') {
                alert('아이디를 확인해주세요.');
                return false;
            } else if ($('#collect_password').val() !== 'y') {
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
