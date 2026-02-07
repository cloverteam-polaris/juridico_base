<?php

namespace App\Controllers;

class Login extends BaseController
{
    public function index(): string
    {
        helper('cookie');
        delete_cookie('auth', DOMAIN);
        delete_cookie('token', DOMAIN);

        return view('login/login.php');
    }
}
