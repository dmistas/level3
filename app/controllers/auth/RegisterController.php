<?php


namespace App\controllers\auth;


use App\controllers\Redirect;
use App\models\Mail;
use Delight\Auth\Auth;
use League\Plates\Engine;
use Tamtamchik\SimpleFlash\Flash;

class RegisterController
{
    protected $auth, $templates, $flash, $mail;

    public function __construct(Auth $auth, Engine $templates, Flash $flash, Mail $mail)
    {
        $this->auth = $auth;
        $this->templates = $templates;
        $this->flash = $flash;
        $this->mail = $mail;
    }

    public function register()
    {
        try {
            $userId = $this->auth->register($_POST['email'], $_POST['password'], $_POST['username'], function ($selector, $token) {
                $sendEmail = $this->mail->sendVerificationEmail($_POST['email'], $_POST['username'], $selector, $token);
//                echo $sendEmail;
            });
            $this->flash->message('Мы отправили Вам письмо для верификации email', 'success');
            Redirect::to('/login');
            exit();
        }
        catch (\Delight\Auth\InvalidEmailException $e) {
            $this->flash->message('Некорректный email адрес', 'error');
        }
        catch (\Delight\Auth\InvalidPasswordException $e) {
            $this->flash->message('Invalid password', 'error');
        }
        catch (\Delight\Auth\UserAlreadyExistsException $e) {
            $this->flash->message('Пользователь с таким email уже существует', 'error');
        }
        catch (\Delight\Auth\TooManyRequestsException $e) {
            $this->flash->message('Too many requests');
        }
        Redirect::to('/register');
        exit();
    }

    public function show()
    {
        echo $this->templates->render('register');

    }


    public function emailVerification()
    {
        try {
            $this->auth->confirmEmail($_GET['selector'], $_GET['token']);
            $this->flash->message('Email address has been verified', 'success');
            Redirect::to('/login');
        }
        catch (\Delight\Auth\InvalidSelectorTokenPairException $e) {
            die('Invalid token');
        }
        catch (\Delight\Auth\TokenExpiredException $e) {
            die('Token expired');
        }
        catch (\Delight\Auth\UserAlreadyExistsException $e) {
            die('Email address already exists');
        }
        catch (\Delight\Auth\TooManyRequestsException $e) {
            die('Too many requests');
        }
    }
}