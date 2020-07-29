<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8"/>
    <title>회원가입 페이지</title>
</head>

<body>
<form method="post">
    <h1>Email 본인 인증</h1>

    <input type="hidden" name="cert_finish" value="y">
    <input type="hidden" id="can_cert_time" value="n">
    <input type="hidden" id="correct_cert_num" value="">
    <fieldset>
        <legend>입력하세요</legend>
        <table>
            <tr>
                <td>이메일<br><br></td>
                <td>
                    <input type="text" name="email" id="email" maxlength="30" placeholder="인증 받을 email"/>
                    @
                    <select name="emadress" id="emadress">
                        <option value="naver.com">naver.com</option>
                        <option value="google.com">google.com</option>
                    </select>
                    <br><br>
                </td>
            </tr>
        </table>
    </fieldset>
    <a href="#" id="send_email_btn">본인 인증 메일 전송 하기</a>

    <div id="certify_num_area" style="display: none;">
        <h1>인증번호를 입력해주세요!</h1>
        <p>
            <strong>인증번호</strong>
            <input type="text" name="cert_num" id="cert_num" size="20" placeholder="6자리 숫자" maxlength="6"/>
            <a href="" id="cert_num_btn">인증하기</a>
        </p>
        <div id="time"></div>
        <p id="result"></p>
    </div>
</form>

<script type="text/javascript" src="https://code.jquery.com/jquery.min.js"></script>
<script>
    $(function () {
        var time = 600; // 기준 시간
        var min = '';  // 분
        var sec = ''; // 초

        //setInterval(함수, 시간) : 주기적 실행
        var x = setInterval(function () {
            //parseInt() : 정수 반환
            min = parseInt(time / 60); // 몫
            sec = time % 60; // 나머지

            $('#time').innerHTML = min + "분 " + sec + "초";
            time--;

            // 타임 아웃
            if (time < 0) {
                clearInterval(x); // setInterval 종료
                jQuery('#time').innerHTML = "인증 시간이 초과되었습니다";
                jQuery('#can_cert_time').val('n'); // 이메일 인증 유효시간 아닐때
            }
        }, 1000);

        $('#send_email_btn').click(function () {
            var email = $('#email').val(),
                emAddress = $('#emadress').val();
            var cert_num = randomNumber(100000, 999999);
            console.log(cert_num);
            if (email === '') {
                alert('이메일 주소를 입력해주세요');
            }

            $.ajax({
                url: "/Membership/sendMail/",
                method: 'POST',
                data: {email: email, emAddress: emAddress, cert_num: cert_num},
                dataType: "json",
                async: false
            }).done(function (data) {
                console.log(email);
                if (data.result === 'success') {
                    alert('이메일이 전송되었습니다.\n3분 내로 본인인증을 완료해주세요.');
                    console.log(data.cert_num);
                    $('#can_cert_time').val('y'); // 이메일 인증 유효시간 내
                    $('#correct_cert_num').val(data.cert_num);
                    $('#certify_num_area').show();
                    $("#email").attr("readonly", true);
                    $("#emadress").attr("disabled", true);

                } else {
                    alert('오류가 발생하였습니다.');
                }

                return false;
            });

            $('#cert_num_btn').click(function () {
                if ($('#can_cert_time').val() !== 'y') {
                    alert('인증번호 유효시간이 초과하였습니다.\n재시도 해주세요.');
                    return false;
                }

                if ($('#cert_num').val() !== $('#correct_cert_num').val()) {
                    alert('인증번호를 정확히 입력해주세요.');
                    return false;
                }

                $("#emadress").attr("disabled", false);
                $('#form').submit();
            });
        });
    });

    /**
     * min ~ max 사이의 랜덤 정수 반환
     * @param min
     * @param max
     * @returns 랜덤 정수
     */
    function randomNumber(min, max) {
        return Math.floor(Math.random() * (max - min + 1)) + min;
    }
</script>


</body>
</html>