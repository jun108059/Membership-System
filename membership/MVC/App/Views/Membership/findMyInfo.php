<html lang="ko">
<head>
    <meta charset="utf-8" />
    <title>아이디/비밀번호 찾기</title>
    <style>
        * {margin: 0 auto;}
        a {color:#333; text-decoration: none;}
        .find {text-align:center; width:500px; margin-top:30px; }
    </style>
</head>
<body>
<div class="find">
    <form action="/Membership/findId" method="post" >
        <h1>회원계정 찾기🔎</h1>

        <input type="hidden" name="cert_finish" value="y">
        <input type="hidden" id="can_cert_time" value="n">
        <input type="hidden" id="correct_cert_num" value="">

        <br>
        <p><a href="/">홈으로</a></p>
        <br>
        <fieldset>
            <legend>아이디 찾기</legend>
            <table>
                <tr>
                    <td>이름</td>
                    <td><input type="text" id="name" size="35" name="name" placeholder="이름"></td>
                </tr>
                <tr>
                    <td>이메일</td>
                    <td><input type="text" id="email" name="email">
                        @
                        <select id="emadress" name="emadress">
                            <option value="naver.com">naver.com</option>
                            <option value="gmail.com">gmail.com</option>
                        </select>
                    </td>
                </tr>
            </table>
            <input type="submit" id="find_ID_Submit" value="아이디 찾기" />
        </fieldset>
    </form>
</div>
<div class="find">
    <form action="passwordChange" method="post" >
        <fieldset>
            <legend>비밀번호 찾기</legend>
            <table>
                <tr>
                    <td>아이디</td>
                    <td><input type="text" id="user_id" name="user_id" size="35" placeholder="아이디"></td>
                </tr>
            </table>
        </fieldset>
        <a href="#" id="send_email_btn">본인 인증 후 비밀번호 재설정</a>

        <div id="certify_num_area" style="display: none;">
            <h1>인증번호를 입력해주세요!</h1>
            <p>
                <strong>인증번호</strong>
                <input type="text" name="cert_num" id="cert_num" size="20" placeholder="6자리 숫자" maxlength="6"/>
                <input type="submit" id="cert_num_btn" value="인증하기"  />
                <!--            <a href="/Membership/signUp" id="cert_num_btn">인증하기</a>-->
            </p>
            <div id="timeView">인증 시간 : </div>
            <p id="result"></p>
        </div>
    </form>
</div>




<script type="text/javascript" src="https://code.jquery.com/jquery.min.js"></script>
<script>
    $(function () {
        $('#find_ID_Submit').click(function () {
            var email = $('#email').val(),
                emAddress = $('#emadress').val(),
                name = $('#name').val();
            if (name === '') {
                alert('이름을 입력해주세요');
                return false;
            }
            else if (email === '') {
                alert('이메일 주소를 입력해주세요');
                return false;
            }
            else {
                $('form').submit();
            }
        });

        $('#send_email_btn').click(function () {
            var userId = $('#user_id').val();
            if (userId === '') {
                alert('아이디를 입력해주세요!');
                return false;
            }
            $.ajax({
                url: "/Membership/emailForFindPw",
                method: 'POST',
                data: {user_id: userId},
                dataType: "json",
                async: false
            }).done(function (data) {
                if (data.result === 'success') {
                    alert('이메일이 전송되었습니다.\n3분 내로 본인인증을 완료해주세요.');
                    timer();
                    $('#can_cert_time').val('y'); // 이메일 인증 유효 시간 내
                    $('#correct_cert_num').val(data.cert_num);
                    $('#certify_num_area').show();
                    $("#user_id").attr("readonly", true);
                } else if (data.alert !== ''){
                    alert(data.alert);
                }else{
                    alert("오류가 발생했습니다.");
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
                alert("🎉본인인증이 완료되었습니다!");
                $('form').submit();
            });
        });
    });

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
</script>

</body>
</html>