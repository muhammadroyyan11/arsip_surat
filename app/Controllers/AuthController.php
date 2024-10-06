<?php

namespace App\Controllers;

use App\Models\User_model;
//use Couchbase\User;

class AuthController extends BaseController
{
    private User_model $login;

    public function __construct() {
        $db                 = db_connect();
        $this->session = \Config\Services::session();
    }

    public function loginForm()
    {
        return view('auth/login');
    }

    public function login()
    {
        $model = new User_model();

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        // Validate the credentials
        $user = $model->where('email', $email)->first();

        if ($user && password_verify($password, $user['password'])) {
            // Login success
            $sessionData = [
                'id'       => $user['id'],
                'name' => $user['name'],
                'email'    => $user['email'],
                'loggedIn' => true
            ];
            $this->session->set($sessionData);

//            var_dump($sessionData);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Login successful!',
                'redirect' => base_url('dashboard') // Redirect URL after login
            ]);
        } else {
            // Login failed
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid email or password.'
            ]);
        }
    }

    public function storeLogin()
    {
        $userModel = new UserModel();
        $email     = $this->request->getPost('email');
        $password  = $this->request->getPost('password');

        // Find user by email
        $user = $userModel->where('email', $email)->first();

        if ($user) {
            // Verify the password
            if (password_verify($password, $user['password'])) {
                session()->set('logged_in', true);
                session()->set('user_id', $user['id']);
                return redirect()->to('/dashboard')->with('success', 'Login successful');
            } else {
                return redirect()->back()->with('error', 'Invalid login credentials');
            }
        } else {
            return redirect()->back()->with('error', 'User not found');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login')->with('success', 'Logged out successfully');
    }

    public function register()
    {
        return view('auth/register');
    }

    public function storeRegister()
    {

        $userModel = new User_model();

        // Validate the input
        $validation = $this->validate([
            'username' => 'required',
            'email'    => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[8]',
        ]);

        if (!$validation) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Hash the password
        $hashedPassword = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);

        // Insert the new user
        $userModel->save([
            'username' => $this->request->getPost('username'),
            'email'    => $this->request->getPost('email'),
            'password' => $hashedPassword,
        ]);

        return redirect()->to('/login')->with('success', 'Registration successful. Please login.');
    }
}
