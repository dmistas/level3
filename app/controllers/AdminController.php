<?php

namespace App\controllers;

use App\Models\Mail;
use App\Models\QueryBuilder;
use Delight\Auth\Auth;
use Delight\Auth\Role;
use Delight\FileUpload\FileUpload;
use EasyCSRF\EasyCSRF;
use League\Plates\Engine;
use Tamtamchik\SimpleFlash\Flash;
use Faker\Factory;
use Valitron\Validator;

class AdminController extends UserController
{
    private $faker;

    public function __construct(
        QueryBuilder $query,
        Engine $templates,
        Auth $auth,
        FileUpload $upload,
        Flash $flash,
        Mail $mail,
        EasyCSRF $easyCSRF
    )
    {
        parent::__construct($query, $templates, $auth, $upload, $flash, $mail, $easyCSRF);
        $this->faker = Factory::create();
    }

    public function test()
    {
        echo "admin test";
    }

    public function seed()
    {
        $userCredential = [];

        for ($i = 1; $i <= 30; $i++) {
            $user = [];
            $user['username'] = $this->faker->name();
            $user['email'] = $this->faker->email();
            $user['password'] = '$10$Uh3sGZT8PG3pY8QQwen5oOw0SOaRaOHV9fM7eQqoR35hCinHNUSoS';
            $user['status'] = 0;
            $user['verified'] = 1;
            $user['resettable'] = 1;
            $user['roles_mask'] = 0;
            $user['registered'] = time();
            $user['last_login'] = 0;
            $user['force_logout'] = 0;
            $userCredential[] = $user;
        }

        $user_id = [];
        foreach ($userCredential as $user) {
            $user_id[] = $this->query->insert($user, 'users');
        }

        $user_info = [];

        foreach ($user_id as $id) {
            $single_user_info = [];
            $single_user_info['user_id'] = $id;
            $single_user_info['job_title'] = $this->faker->sentence(2);
            $single_user_info['avatar'] = $this->faker->imageUrl(360, 360, 'animals', true);
            $single_user_info['vk'] = $this->faker->url();
            $single_user_info['telegram'] = $this->faker->url();
            $single_user_info['instagram'] = $this->faker->url();
            $single_user_info['status_id'] = $this->faker->numberBetween(1, 3);
            $single_user_info['phone'] = $this->faker->phoneNumber();
            $single_user_info['address'] = $this->faker->address();
            $user_info[] = $single_user_info;
        }

        foreach ($user_info as $user) {
            $this->query->insert($user, 'users_info');
        }
    }

    private function changeUserPassword($id, $newPassword)
    {
        if (!$this->checkToken()) {
            Redirect::to('/');
            exit();
        }
        $isPasswordChanged = false;
        try {
            $this->auth->admin()->changePasswordForUserById($id, $newPassword);
            $isPasswordChanged = true;
        } catch (\Delight\Auth\UnknownIdException $e) {
            $this->flash->message('Unknown ID', 'error');
        } catch (\Delight\Auth\InvalidPasswordException $e) {
            $this->flash->message('Invalid password', 'error');
        }
        return $isPasswordChanged;
    }

    private function changeUserEmail($id)
    {
        if (!$this->checkToken()) {
            Redirect::to('/');
            exit();
        }
        $currentId = $this->auth->getUserId();
        $this->auth->admin()->logInAsUserById($id); // login as editing user

        $isEmailChanged = $this->changeEmail();

        $this->auth->admin()->logInAsUserById($currentId); // come back to admin account
        return $isEmailChanged;
    }

    public function editUserSecurity($vars)
    {
        if (!$this->checkToken()) {
            Redirect::to('/');
            exit();
        }
        $this->redirectIfNotAdmin();
        $emailChanged = false;
        $passwordChanged = false;
        $id = $vars['id'];

        if (isset($_POST['email']) && !empty($_POST['email'])) {
            $emailChanged = $this->changeUserEmail($id);
        }

        if (isset($_POST['password']) && !empty($_POST['password']) && $_POST['password'] === $_POST['password_confirm']) {
            $passwordChanged = $this->changeUserPassword($id, $_POST['password']);
        }

        if ($emailChanged || $passwordChanged) {
            $this->flash->success('Данные сохранены');
        } else {
            $this->flash->info('Данные не изменены');
        }

        Redirect::to("/");
        exit();

    }

    private function redirectIfNotAdmin()
    {
        if (!$this->auth->hasRole(Role::ADMIN)) {
            $this->flash->error('Недостаточно прав');
            Redirect::to('/');
            exit();
        }
    }

    public function showAddUser()
    {
        $this->redirectIfNotAdmin();
        $token = $this->easyCSRF->generate('csrf');
        echo $this->templates->render('nav_menu', ['auth' => $this->auth]);
        echo $this->templates->render('add_user', ['token' => $token]);
    }

    public function addUser()
    {
        if (!$this->checkToken()) {
            Redirect::to('/');
            exit();
        }
        $v = new Validator($_POST);
        $v->rules([
            'required' => ['email', 'password', 'username'],
            'email' => 'email',
        ]);
        if (!$v->validate()) {
            echo $this->templates->render('add_user', ['errors' => $v->errors()]);
            exit();
        }

        try {
            $newUserId = $this->auth->admin()->createUser($_POST['email'], $_POST['password'], $_POST['username']);
        } catch (\Delight\Auth\InvalidEmailException $e) {
            $this->flash->message('Некорректный email адрес', 'error');
        } catch (\Delight\Auth\InvalidPasswordException $e) {
            $this->flash->message('Invalid password', 'error');
        } catch (\Delight\Auth\UserAlreadyExistsException $e) {
            $this->flash->message('Пользователь с таким email уже существует', 'error');
        }

        if (!$newUserId) {
            $this->flash->error('Ошибка при создании пользователя');
            Redirect::to('/add-user');
        }

        // загружаем аватар или путь по умолчанию
        $imgUrl = $this->uploadImage();
        if (!$imgUrl) {
            $imgUrl = '/img/avatars/avatar.png';
        }

        $id_user_info = $this->query->insert([
            'user_id' => $newUserId,
            'job_title' => $_POST['job_title'],
            'avatar' => $imgUrl,
            'vk' => $_POST['vk'],
            'telegram' => $_POST['telegram'],
            'instagram' => $_POST['instagram'],
            'status_id' => $_POST['status'],
            'phone' => $_POST['phone'],
            'address' => $_POST['address']
        ], 'users_info');

        $this->flash->success('Пользователь добавлен');
        Redirect::to('/');
        exit();
    }


}