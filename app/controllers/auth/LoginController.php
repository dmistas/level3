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
            $this->flash->message("I've been successfully login!", "success");
            Redirect::to('/');

        } catch (\Delight\Auth\InvalidEmailException $e) {
            die('Wrong email address');
        } catch (\Delight\Auth\InvalidPasswordException $e) {
            die('Wrong password');
        } catch (\Delight\Auth\EmailNotVerifiedException $e) {
            die('Email not verified');
        } catch (\Delight\Auth\TooManyRequestsException $e) {
            die('Too many requests');
        }
    }

    public function logout()
    {
        $this->auth->logOut();
        Redirect::to('/login');
    }


}