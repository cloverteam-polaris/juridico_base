<?php

namespace App\Controllers;

use App\Libraries\Session;
use App\Libraries\Usuarios;

class Users extends BaseController
{
    private $session;
    private $sessionLib;
    private $curl;
    private $token;
    private $usuarios;

    public function __construct()
    {
        $this->session = session();
        $this->curl = service('curlrequest');

        $this->sessionLib = new Session();
        $this->usuarios = new Usuarios();
    }

    public function listaUsuarios()
    {
        helper('cookie');

        $request = service('request');
        $this->token =  $request->getCookie('token');
        $data['userinfo'] = $this->session->get();

        $data['usuarios'] = $this->usuarios->getAllUsers($this->token);

        print_r($data['usuarios']);
        exit;

        echo view('templates/header.php', $data);
        echo view('usuarios/listaUsuarios.php', $data);
        echo view('templates/footer.php', $data);
    }


    public function crearUsuario()
    {
        helper('cookie');

        $request = service('request');
        $this->token =  $request->getCookie('token');
        $data['userinfo'] = $this->session->get();

        echo view('templates/header.php', $data);
        echo view('usuarios/crearUsuario.php', $data);
        echo view('templates/footer.php', $data);
    }
}
