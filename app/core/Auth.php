<?php

namespace app\core;

use app\models\User;
use app\core\Session;
use app\core\View;
use app\enums\Role;


class Auth
{
    private User $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }
    protected function redirect($path)
    {
        header("Location: {$path}");
        exit;
    }

    public function register()
    {
        if (Session::isLoggedIn()) {
            if ($_SESSION['user']['role'] == Role::ADMIN->value) {
                $this->redirect("/admin/dashboard");
            }
            $this->redirect("/");
        }

        $token = $_POST['csrf_token'] ?? '';
        if (!Security::verifyCSRFToken($token)) {
            Session::setFlashMessage('error', 'CSRF token validation failed. Possible CSRF attack.');
            Security::regenerateCSRFToken();
            $this->redirect('/login');
        }

        $name = Security::XSS($_POST['name']);
        $email = Security::XSS($_POST['email']);
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        //  Validator 
        $validator = new Validator();

        // validation
        $validator->validateString($name, 'name');
        $validator->validateEmail($email);
        $validator->validatePassword($password);
        $validator->validateConfirmPassword($password, $confirm_password);


        if ($validator->isValid()) {

            $this->userModel->name = $name;
            $this->userModel->email = $email;
            $this->userModel->password = password_hash($password, PASSWORD_BCRYPT);

            if ($this->userModel->create()) {
                Session::setFlashMessage('message', 'Registration successful. Please login.');
                $this->redirect('/login');
            } else {
                Session::setFlashMessage('error', 'Registration failed. Please try again.');
                $this->redirect('/register');
            }
        } else {

            $_SESSION['errors'] = $validator->getErrors();
            $_SESSION['old'] = [
                'name' => $name,
                'email' => $email
            ];
            $this->redirect('/register');
        }
    }

    public function login()
    {
        // Redirect if the user is already logged in
        if (Session::isLoggedIn()) {
            if ($_SESSION['user']['role'] == Role::ADMIN->value) {
                $this->redirect("/admin/dashboard");
            }
            $this->redirect("/");
        }


        // Verify CSRF token
        $token = $_POST['csrf_token'] ?? '';
        if (!Security::verifyCSRFToken($token)) {
            Session::setFlashMessage('error', 'CSRF token validation failed. Possible CSRF attack.');
            Security::regenerateCSRFToken();
            $this->redirect('/login');
        }

        // Sanitize inputs
        $email = Security::XSS($_POST['email']);
        $password = $_POST['password'];

        // Validate inputs
        $validator = new Validator();
        $validator->validateEmail($email);
        $validator->validatePassword($password);

        if ($validator->isValid()) {
            // Attempt to log in the user
            $user = $this->userModel->login($email, $password);

            if ($user) {
                // Store user data in session
                Session::setData('user', $user);
                // Set success message
                Session::setFlashMessage('message', 'Login successful. Welcome back! ' . $user['name']);

                // Redirect based on role
                if ($user['role'] === Role::ADMIN->value) {
                    $this->redirect('/admin/dashboard');
                } else {
                    $this->redirect('/');
                }
            } else {
                // Invalid credentials
                Session::setFlashMessage('error', 'Invalid email or password.');
                $this->redirect('/login');
            }
        } else {
            // Validation failed
            Session::setData('errors', $validator->getErrors());
            Session::setData(
                'old',
                ['email' => $email,]
            );

            $this->redirect('/login');
        }
    }

    public function logout()
    {
        if (Session::isLoggedIn()) {
            session_unset();
            session_destroy();
            session_start();
            $_SESSION['message'] = 'You have been logged out successfully';
        }
        $this->redirect('/login');
    }
}
