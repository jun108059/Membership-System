<?php
namespace App\Controllers;

use \Core\View;
use App\Models\Login;

class LoginController extends \Core\Controller
{
    public function loginCheckAction()
    {
        // 모델 에서 데이터 꺼내 오기
        $login = Login::getIdPassword();

        View::render('Login/loginCheck.php', [
            'user_id'    => $_POST["mem_user_id"],
            'user_pw' => $_POST["mem_password"]
        ]);
    }

    protected function before()
    {
        if (isset($_SESSION["user_id"])) {
            View::render('Home/index.php', []);
        }
    }

    /**
     * After filter
     * @return void
     */
    protected function after()
    {
//        echo " (after)";
    }

    /**
     * Show the index page
     * @return void
     */
    public function indexAction()
    {
        View::render('Login/index.php', []);
    }



}