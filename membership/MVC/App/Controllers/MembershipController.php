<?php

namespace App\Controllers;

use App\Models\Login;
use App\Models\Membership;
use App\Service\MailerService;
use App\Service\SessionManager;
use \Core\View;
use DateTime;


class MembershipController extends \Core\Controller
{
    /**
     * 1번 : 이메일 인증 Page
     * Render - View
     */
    public function signUpEmailAction()
    {
        // View 페이지 렌더링 해주기
        View::render('Membership/1.signUpEmail.php',);
    }

    /**
     * 2번 : 이메일 인증 번호 전송
     * Check - 메일 중복 & 인증 번호 전송
     * Render - VIew
     * @throws \Exception
     */
    public function sendMailAction()
    {
        $resultArray = ['result' => 'fail', 'alert' => ''];

        if (empty($_POST['email']) || empty($_POST['emAddress'])) {
            $resultArray['alert'] = '🧨이메일을 입력해주세요';
            echo json_encode($resultArray);
            exit();
        }

        $userMail = $_POST['email'] . '@' . $_POST['emAddress'];

        if (Membership::isEmailExisted($userMail)) {
            $resultArray['alert'] = '🟡 이미 사용 중인 Email 입니다. 🟡';
            echo json_encode($resultArray);
            exit();
        }

        $certify = random_int(100000, 999999); // 인증 번호 random 생성

//        $mailReturn = MailerService::mail($userMail, $certify);
        $mailReturn = true;
//        echo("Mailer 함수 주석처리");
//        echo("인증번호 = ".$certify);

        if ($mailReturn) {
            $resultArray['result'] = 'success';
            $resultArray['cert_num'] = $certify;
        }

        echo json_encode($resultArray);
        exit;
    }

    /**
     * 3번 : 회원 가입 Page Render
     * Render - View Sign Up
     */
    public function signUpAction()
    {
        if (empty($_POST['email']) || empty($_POST['emadress'])) {
            echo '<script> alert("🧨이메일 정보를 입력해주세요"); history.back(); </script>';
            exit();
        }
        $userMail = $_POST['email'] . "@" . $_POST['emadress'];

        // View 페이지 렌더링 해주기
        View::render('Membership/2.signUp.php', [
            'mail' => $userMail
        ]);
    }

    /** 4번 : 가입 완료 버튼 -> DB data 넣기 */
    public function signUpDBAction()
    {
        // 비밀 번호 유효성 검사 - Script 에서 튕기는 코드 작성 후 삭제
        MembershipController::passwordCheck($_POST['password']);

        // 필수 값 검사
        if (empty($_POST['userId']) || empty($_POST['email']) || empty($_POST['password'])
            || empty($_POST['name']) || empty($_POST['phone'])) {
            return false;
        }

        $now = (new DateTime())->format('Y-m-d H:i:s');
        $userData = [
            'mem_user_id' => $_POST['userId'],
            'mem_email' => $_POST['email'],
            'mem_password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
            'mem_status' => 'Y', // enum 타입 - 정상 가입
            'mem_cert' => 'Y', // enum 타입 - 본인 인증 여부
            'mem_name' => $_POST['name'],
            'mem_phone' => $_POST['phone'],
            'mem_gender' => (!empty($_POST['gender'])) ?: '', // enum 타입
            'mem_level' => 4, // 일반 사용자 level 4
            'mem_reg_dt' => $now, // 회원 가입 일시
            'mem_log_dt' => $now, // 마지막 로그인 일시
            'mem_pw_dt' => $now, // 마지막 비밀 번호 변경 일시
        ];

        /**
         * 데이터 Insert
         * 서비스 할 때 사용자 한테 회원가입 page로 다시 안내
         * 관리자는 로그 파일로 에러 처리할 수 있도록 if else 설계
         */
        Membership::insertInfo($userData);

        // SignUp 완료 -> rendering
        View::render('Membership/3.signUpOK.php', [
            'id' => $userData['mem_user_id'],
            'name' => $userData['mem_name'],
            'email' => $userData['mem_email']
        ]);
        return true;
    }

    /***************************** 중복 검사 시작  ************************************/

    /**
     * ID 중복 검사
     */
    public function checkIdAction()
    {
        if (!empty($_POST['userId'])) {
            if (Membership::isUserExisted($_POST['userId'])) {
//            echo '<script> alert("🔴 이미 사용 중인 ID입니다. 🔴"); history.back(); </script>';
                echo "<span class='status-not-available'> 🔴이미 사용 중인 ID입니다.🔴</span>";
//            exit();
            } else {
//                echo "사용 가능한 ID 입니다";
                echo "<span class='status-available'> 🟢사용 가능한 ID 입니다.🟢</span>";
            }
        }
    }

    /**
     * 이메일 중복 검사
     */
    public function checkEmailAction()
    {
        if (!empty($_POST['email'])) {
            if (Membership::isEmailExisted($_POST['email'])) {
//            echo '<script> alert("🔴 이미 사용 중인 ID입니다. 🔴"); history.back(); </script>';
                echo "<span class='status-not-available'> 🔴이미 가입된 이메일입니다.🔴</span>";
//            exit();
            } else {
//                echo "사용 가능한 ID 입니다";
                echo "<span class='status-available'> 🟢사용 가능한 이메일입니다.🟢</span>";
            }
        }
    }

    /**
     * 핸드폰 번호 중복 검사
     */
    public function checkPhoneAction()
    {
        if (!empty($_POST['phone'])) {
            if (Membership::isPhoneExisted($_POST['phone'])) {
                echo "<span class='status-not-available'> 🔴이미 가입된 번호입니다.🔴</span>";
            } else {
                echo "<span class='status-available'> 🟢사용 가능한 번호입니다.🟢</span>";
            }
        }
    }

    /***************************** 중복 검사 끝    ************************************/
    /***************************** ID/PW 찾기 시작 ************************************/

    /**
     * 아이디/비밀번호 찾기 Page Render
     * Render - View findMyInfo
     */
    public function findMyInfoAction()
    {
        View::render('Membership/findMyInfo.php', []);
    }

    /**
     * ID 찾기
     * render - ID Show
     */
    public function findIdAction()
    {
        // front 에서 검사 하도록 변경할 수 있음
        if (empty($_POST['email']) || empty($_POST['emadress'])) {
            echo "<script>alert('🧨이메일 정보를 입력해주세요 ❗'); history.back();</script>";
            exit();
        } elseif (empty($_POST['name'])) {
            echo "<script>alert('🧨이름를 입력해주세요 ❗'); history.back();</script>";
            exit();
        } elseif (Membership::isEmailExisted($_POST['email'] . "@" . $_POST['emadress'])) {
            // 입력한 이메일 - DB에 존재 한다면
            $userMail = $_POST['email'] . "@" . $_POST['emadress'];
            // 이름 <-> Email 정보 일치 여부 확인 (T/F)
            $checkNameEmailRight = Membership::checkNameEmailRight($_POST['name'], $userMail);
            // 일치 한다면
            if ($checkNameEmailRight) {
                $userId = Membership::findId($userMail);
                // View 페이지 렌더링 해주기
                View::render('Membership/findAndShowID.php', [
                    'userId' => $userId
                ]);
            } else {
                // 이름 <-> 이메일 = 일치하지 않는다면
                echo "<script>alert('🧨가입된 이메일과 이름이 일치하지 않습니다.❗'); history.back();</script>";
                exit();
            }
        } else {
            // 존재하지 않는 Email인 경우 튕겨주기
            echo '<script> alert("🧨가입된 이메일이 아닙니다.❗"); history.back(); </script>';
        }
    }

    /**
     * PW 찾기를 위한 인증 메일 전송
     */
    public function emailForFindPwAction()
    {
        $resultArray = ['result' => 'fail', 'alert' => ''];

        if (empty($_POST['user_id'])) {
            // User Id값 없이 접근한 경우
            $resultArray['alert'] = '🧨올바른 접근이 아닙니다.';
            echo json_encode($resultArray);
            exit();
        } elseif (Membership::isUserExisted($_POST['user_id'])) {
            // Id값 존재 && 올바른 User ID를 입력한 경우
            // 비밀 번호 찾을 User 조회
            $user = Login::getUserData($_POST['user_id']);
            // 본인 인증 보낼 메일 값
            $userMail = $user['mem_email'];

            // 본인 인증할 이메일 전송 하기
            // 고객님이 가입하셨던 이메일은 ***이렇습니다.
            // 본인 인증 메일을 전송할까요?

            $certify = random_int(100000, 999999); // 인증 번호 random 생성

//        $mailReturn = MailerService::mail($userMail, $certify);
            $mailReturn = true;
//        echo("Mailer 함수 주석처리");
//        echo("인증번호 = ".$certify);

            if ($mailReturn) {
                $resultArray['result'] = 'success';
                $resultArray['cert_num'] = $certify;
            }

            echo json_encode($resultArray);
            exit;
        } else {
            $resultArray['alert'] = '🧨존재하지 않는 아이디입니다.';
            echo json_encode($resultArray);
            exit();
        }
    }

    /**
     * 비밀번호 재설정 Page Render
     * Render - View PasswordChange
     */
    public function passwordChangeAction()
    {
        // (추가하기) 세션 유지중인지 확인하고 error 출력하고 튕기게

        if (!empty($_POST['user_id']) && Membership::isUserExisted($_POST['user_id'])) {
            // Id값 존재 && 올바른 User ID를 입력한 경우
            // user 정보 조회
            $user = Login::getUserData($_POST['user_id']);

            View::render('Membership/passwordChange.php', [
                'user_id' => $user['mem_user_id'],
                'user_pw' => $user['mem_password']
            ]);
        }
        else {
            echo "<script>alert('🧨잘못된 접근입니다❗'); history.back();</script>";
        }

    }

    /** 현재 비밀번호 실시간 확인! */
    public function checkPwAction()
    {
        if (!empty($_POST['userId']) && !empty($_POST['currPw'])) {
            $user_id = $_POST['userId'];
            $user_pw = $_POST['currPw'];
            // 모델 에서 데이터 꺼내 오기
            $user = Login::getUserData($user_id);

            $pw_check = $user['mem_password'];

            if (password_verify($user_pw, $pw_check)) {
                echo "<span class='status-available'> 🟢일치합니다.🟢</span>";
            } else { // 비밀번호가 같지 않다면 알림창을 띄우고 전 페이지로 돌아갑니다
                echo "<span class='status-not-available'> 🔴비밀번호가 일치하지 않습니다.🔴</span>";
            }
//            $currPassword = password_hash($_POST['currPw'], PASSWORD_DEFAULT);
//            if (Membership::checkPassword($_POST['userId'], $currPassword)) {
        }
    }

    /**
     * 💥 비밀번호 재설정 DB 저장!
     *
     */
    public function newPwToDBAction()
    {
        // 비밀 번호 유효성 검사 - Script 에서 튕기는 코드 작성 후 삭제
        MembershipController::passwordCheck($_POST['password']);

        // 필수 값 검사
        if (empty($_POST['user_id']) || empty($_POST['password'])) {
            return false;
        }

        $now = (new DateTime())->format('Y-m-d H:i:s');
        $userData = [
            'mem_user_id' => $_POST['user_id'],
            'mem_password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
            'mem_pw_dt' => $now, // 마지막 비밀 번호 변경 일시
        ];

        /**
         * 데이터 Update
         */
        Membership::changePassword($userData);

        return true;

    }

    /***************************** ID/PW 찾기 끝 ************************************/
    /***************************** 개인정보수정 시작 **********************************/

    /**
     * 💥 개인정보 변경 DB 저장!
     *
     */
    public function newInfoToDBAction()
    {
        // 필수 값 검사
        if (empty($_POST['password']) || empty($_POST['name']) || empty($_POST['phone'])) {
            return false;
        }

        // 비밀 번호 유효성 검사 - Script 에서 튕기는 코드 작성 후 삭제
        MembershipController::passwordCheck($_POST['password']);

        $now = (new DateTime())->format('Y-m-d H:i:s');
        $userData = [
            'mem_user_id'   => $_POST['user_id'],
            'mem_password'  => password_hash($_POST['password'], PASSWORD_DEFAULT),
            'mem_name'      => $_POST['name'],
            'mem_phone'     => $_POST['phone'],
            'mem_gender'    => $_POST['gender'],
            'mem_pw_dt'     => $now, // 마지막 비밀 번호 변경 일시
            'mem_log_dt'    => $now
        ];

        /**
         * 데이터 Update
         */
        Membership::changeInfo($userData);
        View::render('Home/index.php', [
            'user_id' => $user['mem_user_id'],
            'user_pw' => $user['mem_password']
        ]);
        return true;

    }

    /***************************** 개인정보수정 끝 **********************************/

    /**
     * Before filter
     *
     */
    protected function before()
    {
//        $session_manager = new SessionManager();
//        // 로그인 되어 있으면 튕기기
//        if ($session_manager->isValidAccess()) {
//            echo '<script> alert("잘못된 접근입니다."); history.back(); </script>';
//            return false;
//        } else {
//            return true;
//        }

    }

    /**
     * (삭제 예정) 비밀 번호 유효성 검사 함수
     * @param $_password
     */
    protected function passwordCheck($_password)
    {
        $pw = $_password;
        $num = preg_match('/[0-9]/u', $pw);
        $eng = preg_match('/[a-z]/u', $pw);
        $spe = preg_match("/[\!\@\#\$\%\^\&\*]/u", $pw);

        if (strlen($pw) < 8 || strlen($pw) > 21) {
            echo '<script> alert("🔴 비밀번호는 8자리 ~ 20자리 이내로 입력해주세요. 🔴"); history.back(); </script>';
            exit();
        }

        if (preg_match("/\s/u", $pw) == true) {
            echo '<script> alert("🟡 비밀번호는 공백없이 입력해주세요. 🟡"); history.back(); </script>';
            exit();
        }
        // 테스트 때문에 삭제 - 특수 문자 그냥 뺄지 고민 중
//        if ($num == 0 || $eng == 0 || $spe == 0) {
//            echo '<script> alert("🟠 비밀번호는 영문, 숫자, 특수문자를 혼합하여 입력해주세요. 🟠"); history.back(); </script>';
//            exit();
//        }
    }

}