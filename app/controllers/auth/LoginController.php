<?php


namespace App\controllers\auth;


use App\controllers\Redirect;
use Delight\Auth\Auth;
use League\Plates\Engine;
use Tamtamchik\SimpleFlash\Flash;

class LoginController
{
    private $templates, $auth, $flash;

    public function __construct(Engine $templates, Auth $auth, Flash $flash)
    {
        $this->auth = $auth;
        $this->templates = $templates;
        $this->flash = $flash;

    }

    public function show()
    {
        echo $this->templates->render('login');
    }

    public function login()
    {
        $remember = isset($_POST['remember']);
        $rememberDuration = $remember ? (int)(60 * 60 * 24 * 30) : null;

        try {
            $this->auth->login($_POST['email'], $_POST['password'], $rememberDuration);
            $this->flash->message("Вы были успешно авторизованы!", "success");
            Redirect::to('/');
            exit();

        } catch (\Delight\Auth\InvalidEmailException $e) {
            $this->flash->message('Wrong email address', 'error');
        } catch (\Delight\Auth\InvalidPasswordException $e) {
            $this->flash->message('Wrong password', 'error');
        } catch (\Delight\Auth\EmailNotVerifiedException $e) {
            $this->flash->message('Email not verified', 'error');
        } catch (\Delight\Auth\TooManyRequestsException $e) {
            $this->flash->message('Too many requests', 'error');
        }
        Redirect::to('/login');
        exit();
    }

    public function logout()
    {
        $this->auth->logOut();
        Redirect::to('/login');
    }


}