<?php

namespace App\controllers;

use App\models\Mail;
use App\Models\QueryBuilder;
use Delight\Auth\Auth;
use Delight\Auth\Role;
use Delight\FileUpload\FileUpload;
use League\Plates\Engine;
use mysql_xdevapi\Exception;
use Tamtamchik\SimpleFlash\Flash;


class UserController
{
    protected $query, $templates, $auth, $upload, $flash, $mail;

    public function __construct(
        QueryBuilder $query,
        Engine $templates,
        Auth $auth,
        FileUpload $upload,
        Flash $flash,
        Mail $mail
    )
    {
        $this->query = $query;
        $this->templates = $templates;
        $this->auth = $auth;
        $this->upload = $upload;
        $this->flash = $flash;
        $this->mail = $mail;
    }

    public function index()
    {
        $users = $this->query->getAllUsers();
        echo $this->templates->render('users', ['users' => $users, 'auth' => $this->auth]);

    }

    private function hasAccessRights($id)
    {
        if (!(($this->auth->getUserId() == $id) || $this->auth->hasRole(Role::ADMIN))) {
            $this->flash->error('Недостаточно прав');
            Redirect::to('/');
            exit();
        }
    }

    public function showUserProfile($vars = null)
    {
        $user = $this->query->getUser($vars['id']);
        echo $this->templates->render('user_profile', ['user' => $user]);
    }

    public function showEditSecurity($vars)
    {
        $user = $this->query->getUser($vars['id']);
        echo $this->templates->render('edit_user_security', ['user' => $user, 'auth' => $this->auth]);
    }

    protected function changeEmail()
    {
        $isEmailChanged = false;

        if (!($this->auth->getEmail() === $_POST['email'])) {

            try {
                $this->auth->changeEmail($_POST['email'], function ($selector, $token) {
                    $sendEmail = $this->mail->sendVerificationEmail($_POST['email'], $this->auth->getUsername(), $selector, $token);
                });
                $this->flash->success('На указанную почту отправлен email для верификации');
                $isEmailChanged = true;
            } catch (\Delight\Auth\InvalidEmailException $e) {
                $this->flash->message('Некорректный email адрес', 'error');
            } catch (\Delight\Auth\UserAlreadyExistsException $e) {
                $this->flash->message('Email address already exists', 'error');
            } catch (\Delight\Auth\EmailNotVerifiedException $e) {
                $this->flash->message('Account not verified', 'error');
            } catch (\Delight\Auth\NotLoggedInException $e) {
                die('Not logged in');
            } catch (\Delight\Auth\TooManyRequestsException $e) {
                die('Too many requests');
            }
        }
        return $isEmailChanged;
    }

    private function changePassword()
    {
        $isPasswordChanged = false;
        try {
            $this->auth->changePassword($_POST['password'], $_POST['password_new']);
            $isPasswordChanged = true;
            $this->flash->success('Пароль успешно изменен');
        } catch (\Delight\Auth\NotLoggedInException $e) {
            $this->flash->error('Not logged in');
        } catch (\Delight\Auth\InvalidPasswordException $e) {
            $this->flash->error('Invalid password(s)');
        } catch (\Delight\Auth\TooManyRequestsException $e) {
            $this->flash->error('Too many requests');
        }
        return $isPasswordChanged;
    }

    public function editSecurity()
    {
        echo "edit security user";
        if (isset($_POST['email']) && !empty($_POST['email'])) {
            $this->changeEmail();
        }

        if (!empty($_POST['password']) && !empty($_POST['password_new'])) {
            $this->changePassword();
        }
        Redirect::to("/edit/security/{$this->auth->getUserId()}");
        exit();
    }

    public function showEditProfile($vars)
    {
        $this->hasAccessRights($vars['id']);
        $user = $this->query->getUser($vars['id']);
        echo $this->templates->render('edit_user_profile', ['user' => $user]);
    }

    public function editProfile($vars)
    {
        $id = $vars['id'];
        $user = $this->query->getUser($id);
        $username = $_POST['username'] ?? $user['username'];
        $job_title = $_POST['job_title'] ?? $user['job_title'];
        $phone = $_POST['phone'] ?? $user['phone'];
        $address = $_POST['address'] ?? $user['address'];

        $this->query->update(['username' => $username], $id, 'users');
        $this->query->update([
            'job_title' => $job_title,
            'phone' => $phone,
            'address' => $address,
        ], $user['user_info_id'], 'users_info');

        $this->flash->success('Данные обновлены');
        Redirect::to('/');
        exit();
    }

    public function showEditStatus($vars)
    {
        $this->hasAccessRights($vars['id']);
        $user = $this->query->getUser($vars['id']);
        echo $this->templates->render('edit_user_status', ['user' => $user]);
    }

    public function editStatus($vars)
    {
        if (isset($_POST['status'])) {
            $statusId = $this->query->getStatusId($_POST['status']);
            $isUpdateStatus = $this->query->update(['status_id' => $statusId], $vars['id'], 'users_info');
            if ($isUpdateStatus) {
                $this->flash->success('Статус изменен');
            }
        } else {
            $this->flash->info('Статус не изменен');
        }
        Redirect::to('/');
    }

    public function showUploadAvatar($vars)
    {
        $this->hasAccessRights($vars['id']);
        $user = $this->query->getUser($vars['id']);
        echo $this->templates->render('edit_user_avatar', ['user' => $user]);
    }

    public function uploadAvatar($vars)
    {
        $currentImageUrl = $this->query->getUser($vars['id'])['avatar'];
        $imgUrl = $this->uploadImage();
        if ($imgUrl){
            $this->query->update(['avatar'=>$imgUrl], $vars['id'], 'users_info');
            if(file_exists($_SERVER['DOCUMENT_ROOT'].$currentImageUrl)){
                unlink($_SERVER['DOCUMENT_ROOT'].$currentImageUrl);
            }else{
                $this->flash->warning('file not found');
            }
            $this->flash->success('Аватар обновлен');
        }
        Redirect::to('/');
    }

    protected function uploadImage()
    {
        $this->upload->withTargetDirectory($_SERVER['DOCUMENT_ROOT'] . '/img/avatars');
        $this->upload->from('avatar');

        try {
            $uploadedFile = $this->upload->save();
            // если файл загружен меняем дефолтный путь
            $imgUrl = '/img/avatars/' . $uploadedFile->getFilenameWithExtension();
            $this->flash->success('Файл загружен');
            return $imgUrl;

        } catch (\Delight\FileUpload\Throwable\InputNotFoundException $e) {
            $this->flash->error('input not found');
        } catch (\Delight\FileUpload\Throwable\InvalidFilenameException $e) {
            $this->flash->error('invalid filename');
        } catch (\Delight\FileUpload\Throwable\InvalidExtensionException $e) {
            $this->flash->error('invalid extension');
        } catch (\Delight\FileUpload\Throwable\FileTooLargeException $e) {
            $this->flash->error('file too large');
        } catch (\Delight\FileUpload\Throwable\UploadCancelledException $e) {
            $this->flash->error('upload cancelled');
        }
        return false;

    }
}

