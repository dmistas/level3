<?php


namespace App\controllers\auth;


use Delight\Auth\Auth;

class LoginController
{
    public function __construct()
    {

    }

    public function login(Auth $auth)
    {
        try {
            $auth->login($_POST['email'], $_POST['password']);

            echo 'User is logged in';
        }
        catch (\Delight\Auth\InvalidEmailException $e) {
            die('Wrong email address');
        }
        catch (\Delight\Auth\InvalidPasswordException $e) {
            die('Wrong password');
        }
        catch (\Delight\Auth\EmailNotVerifiedException $e) {
            die('Email not verified');
        }
        catch (\Delight\Auth\TooManyRequestsException $e) {
            die('Too many requests');
        }
    }
}