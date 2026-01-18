<?php

namespace App\Controllers;

use App\Libraries\Crypto;
use App\Libraries\Session;
use App\Libraries\Usuarios;
use PHPUnit\Util\Json;

class Administrative extends BaseController{
    private $session;
    private $sessionLib;
    private $curl;
    private $token;
    private $usuarios;

    public function __construct(){
        $this->session = session();
        $this->curl = service('curlrequest');
        
        $this->sessionLib = new Session();
        $this->usuarios = new Usuarios();
        
    }
    public function register(){

        $token = $this->request->getPost('token');
        $response = service('response');    

        $response->setcookie([
            'name'     => 'token',
            'value'    => urlencode($token),
            'expire'   => 3600 * 9,
            'domain'   => DOMAIN,
            'path'     => '/',
            'httponly' => true
        ]);
        $response->send();

    }

    public function modules(){            
        
        $request = service('request');
        $this->token =  $request->getCookie('token'); 
         
       
        $sessionid = $this->sessionLib->getsession($this->token);


        $sessioninfo = $this->sessionLib->getInfoUserSession($sessionid, $this->token);
        $modulesinfo = $this->usuarios->getModulosInfo();
        
        
        $data['modulos'] = json_decode($sessioninfo->modulos, true);
        $data['modulos_info'] = $modulesinfo;

        
        echo view('templates/header', $data);
        echo view('administrative/modules', $data);
        echo view('templates/footer', $data);
       
    }
}

?>
