<?php

namespace App\Libraries;


use Config\Encryption;

class Crypto{

    private $encrypter;

    public function __construct(){
        $this->encrypter = service('encrypter');
        
    }

    public function encrypt($val){
        return $this->encrypter->encrypt($val);
    }

    public function decrypt($val){
        return $this->encrypter->decrypt($val);
    }


}



?>