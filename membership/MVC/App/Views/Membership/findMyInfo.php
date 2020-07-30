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
    <form id="findID" action="/Membership/findId" method="post" >
        <h1>회원계정 찾기🔎</h1>
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
    <form method="post" action="Membership/findPw">
        <fieldset>
            <legend>비밀번호 찾기</legend>
            <table>
                <tr>
                    <td>아이디</td>
                    <td><input type="text" size="35" name="user_id" placeholder="아이디"></td>
                </tr>
            </table>
            <input type="submit" value="비밀번호 찾기" />
        </fieldset>
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
            }else if (email === '') {
                alert('이메일 주소를 입력해주세요');
            }

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
                $('form').submit();
            });
        });
    });
</script>

</body>
</html>

