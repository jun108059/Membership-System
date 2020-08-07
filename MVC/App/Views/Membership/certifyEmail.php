<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8"/>
    <title>회원가입 페이지</title>
</head>

<body>
<form id="myForm" action="/Membership/signUp" method="post" >
    <h1>Email 본인 인증</h1>

    <input type="hidden" id="collect_email" value="n">
    <input type="hidden" name="cert_finish" value="y">
    <input type="hidden" id="can_cert_time" value="n">
    <input type="hidden" id="correct_cert_num" value="">
    <fieldset>
        <legend>입력하세요</legend>
        <table>
            <tr>
                <td>이메일<br><br></td>
                <td>
                    <input type="text" name="email" id="email" maxlength="30" class="check" placeholder="인증 받을 email"/>
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
            <input type="submit" value="인증하기" id="cert_num_btn" />
        </p>
        <div id="timeView">인증 시간 : </div>
        <p id="result"></p>
    </div>
</form>

<script type="text/javascript" src="https://code.jquery.com/jquery.min.js"></script>
<script>

    function timer() {
        var time = 180; // 기준 시간
        var min = '';  // 분
        var sec = ''; // 초

        //setInterval(함수, 시간) : 주기적 실행
        var x = setInterval(function () {
            //parseInt() : 정수 반환
            min = parseInt(time / 60); // 몫
            sec = time % 60; // 나머지

            $('#timeView').html("남은 인증 시간 : " + min + "분 " + sec + "초");
            time--;

            // 타임 아웃
            if (time < 0) {
                clearInterval(x); // setInterval 종료
                $('#timeView').html("인증 시간이 초과되었습니다");
                $('#can_cert_time').val('n'); // 이메일 인증 유효시간 아닐때
            }
        }, 1000);
    }

    $(function () {

        $(".check").on("keyup", function (e) { //check 라는 클래스에 입력을 감지
            // 한글 방지
            if (!(e.keyCode >= 37 && e.keyCode <= 40)) {
                var v = $(this).val();
                $(this).val(v.replace(/[^a-z0-9]/gi, ''));
            }
        });
        // ID focus out 됐을 때 유효성 체크
        $(".check").on("blur", function () {
            let emailReg = /^[a-z]+[a-z0-9]{3,29}$/g;
            if (!emailReg.test($(this).val())) {
                alert("🟡이메일은 4~30자 영문자 또는 숫자이어야 합니다.🟡");
                $("#collect_email").val('n');
            } else {
                $("#collect_email").val('y');
            }
        });

        $('#send_email_btn').click(function () {
            var email = $('#email').val(),
                emAddress = $('#emadress').val();
            if (email === '' || $("#collect_email").val() === 'n') {
                alert('올바른 이메일 주소를 입력해주세요');
                return false;
            }
            else {
                $.ajax({
                    url: "/Membership/sendMail",
                    method: 'POST',
                    data: {email: email, emAddress: emAddress},
                    dataType: "json",
                    async: false
                }).done(function (data) {
                    // alert(data.result);
                    if (data.result === 'success') {
                        alert('이메일이 전송되었습니다.\n3분 내로 본인인증을 완료해주세요.');
                        timer();
                        $('#can_cert_time').val('y'); // 이메일 인증 유효 시간 내
                        $('#correct_cert_num').val(data.cert_num);
                        $('#certify_num_area').show();
                        $("#email").attr("readonly", true);
                        $("#emadress").attr("disabled", true);
                    } else if (data.alert !== ''){
                        alert(data.alert);
                    }else{
                        alert("오류가 발생했습니다.");
                    }
                    return false;
                });
            }

            $('#cert_num_btn').click(function () {
                if ($('#can_cert_time').val() !== 'y') {
                    alert('인증번호 유효시간이 초과하였습니다.\n재시도 해주세요.');
                    return false;
                }

                if ($('#cert_num').val() !== $('#correct_cert_num').val()) {
                    alert('인증번호를 정확히 입력해주세요.');
                    return false;
                }
                alert("🎉본인인증이 완료되었습니다!");
                $('#emadress').attr("disabled", false);
                $('#form').submit();
            });
        });
    });

</script>


</body>
</html>