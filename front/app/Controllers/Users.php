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
        $data['perfiles'] = $this->usuarios->getPerfiles($this->token);

        echo view('templates/header.php', $data);
        echo view('usuarios/crearUsuario.php', $data);
        echo view('templates/footer.php', $data);
    }



    public function editarUsuario($idusuario)
    {
        helper('cookie');

        $request = service('request');
        $this->token =  $request->getCookie('token');
        $data['userinfo'] = $this->session->get();

        $data['usuario'] = $this->usuarios->getUserById($idusuario, $this->token);
        $data['perfiles'] = $this->usuarios->getPerfiles($this->token);


        echo view('templates/header.php', $data);
        echo view('usuarios/editarUsuario.php', $data);
        echo view('templates/footer.php', $data);
    }




    public function eliminarUsuario($idusuario)
    {
        helper('cookie');

        $request = service('request');
        $this->token =  $request->getCookie('token');
        $data['userinfo'] = $this->session->get();


        $response = $this->usuarios->eliminarUsuario($idusuario, $this->token);
          if (!$response['success']) {
    $this->session->setFlashdata('error', $response['error']);
} else {
    $this->session->setFlashdata('success', 'Usuario eliminado correctamente');
}

return redirect()->to(base_url('usuarios/listaUsuarios'));
    }




    public function editUsuario()
{
    helper('cookie');

    $request = service('request');
    $this->token = $request->getCookie('token');
    $data['userinfo'] = $this->session->get();

    // Recibir datos del formulario
    $idusuario   = $request->getPost('idusuario');
    $usuario     = $request->getPost('usuario');
    $contrasena  = $request->getPost('contrasena'); // opcional
    $nombre      = $request->getPost('nombre');
    $documento   = $request->getPost('documento');
    $correo      = $request->getPost('correo');
    $telefono    = $request->getPost('telefono');
    $tipoPerfil  = $request->getPost('tipo_perfil');

    // Payload para editar usuario (sin password)
    $userData = [
        'idusuario'  => $idusuario,
        'usuario'    => $usuario,
        'nombre'     => $nombre,
        'documento'  => $documento,
        'correo'     => $correo,
        'telefono'   => $telefono,
        'idperfil'   => $tipoPerfil
    ];

 
$response = $this->usuarios->updateUser($userData, $this->token);

// ❌ si falla (array)
if (is_array($response) && isset($response['success']) && $response['success'] === false) {
    return redirect()->back()->with('error', $response['error']);
}

// ❌ si falla (objeto)
if (is_object($response) && isset($response->success) && $response->success === false) {
    return redirect()->back()->with('error', $response->error ?? 'Error desconocido');
}

    // 2) Si viene contraseña -> editar password
    if (!empty($contrasena)) {

        $passwordHash = md5($contrasena);

        $passData = [
            'idusuario' => $idusuario,
            'password'  => $passwordHash
        ];

        $responsePass = $this->usuarios->editPassword($passData, $this->token);

if (is_array($responsePass) && isset($responsePass['success']) && $responsePass['success'] === false) {
    return redirect()->back()->with('error', $responsePass['error']);
}

if (is_object($responsePass) && isset($responsePass->success) && $responsePass->success === false) {
    return redirect()->back()->with('error', $responsePass->error ?? 'Error desconocido');
}

    }

    return redirect()->to(base_url('usuarios/listaUsuarios'))
                     ->with('success', 'Usuario actualizado correctamente');
}











    public function saveUsuario()
    {
        helper('cookie');

        $request = service('request');
        $this->token =  $request->getCookie('token');
        $data['userinfo'] = $this->session->get();



        // Recibir datos del formulario
        $usuario     = $request->getPost('usuario');
        $contrasena  = $request->getPost('contrasena');
        $nombre      = $request->getPost('nombre');
        $documento   = $request->getPost('documento');
        $correo      = $request->getPost('correo');
        $telefono    = $request->getPost('telefono');
        $tipoPerfil  = $request->getPost('tipo_perfil');

        // 🔐 Encriptar contraseña
        $passwordHash = md5($contrasena);




        // Armar payload para la API
        $userData = [
            'usuario'    => $usuario,
            'password'   => $passwordHash,
            'nombre'     => $nombre,
            'documento'  => $documento,
            'correo'     => $correo,
            'telefono'   => $telefono,
            'idperfil' => $tipoPerfil
        ];


        // Enviar datos a la librería
        $response = $this->usuarios->saveUser($userData, $this->token);

       if (isset($response->success) && $response->success === false) {
    $data['error'] = $response->error;
} else {
    $data['success'] = 'Usuario creado correctamente';
    return redirect()->to(base_url('usuarios/listaUsuarios'));
}

    }
}
