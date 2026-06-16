<?php

class LoginController extends Controller
{
    public function index()
    {
        if (isset($_SESSION["userinfo"])) {
            $this->redirect('/news/list');
        }
        $this->renderLogin('login/index');
    }

    public function auth()
    {
        if ($this->isPost()) {
            $model = $this->loadModel('LoginModel');
            $user = $model->getLoginInfo(
                $this->postParam('id'),
                $this->postParam('pw')
            );

            if ($user) {
                $_SESSION["userinfo"] = $user;
                $this->redirect('/news/list');
            } else {
                echo "<script>alert('입력하신 정보가 일치하지 않습니다.');location.href='" . BASE_URL . "/login';</script>";
            }
        }
    }
}
