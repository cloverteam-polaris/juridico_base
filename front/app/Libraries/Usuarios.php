<?php

namespace App\Libraries;

class Usuarios{

    private $request;
    private $session;
    private $curl;

    public function __construct(){
        $this->request = service('request');
        $this->session = session();
        $this->curl = service('curlrequest');
        
    }



    public function getModulosInfo(){


         $token = $this->request->getCookie('token');
        try{
            $response = $this->curl
            ->request('GET', API.'users/getmodulosinfo', [
                'debug' => true,
                'headers' => [
                    'User-Agent' => 'polaris/1.0',
                    'Authorization' => 'Bearer '.$token,
                    'Accept'     => 'application/json',   
                ]
            ]);
            $resp = $response->getBody();
            $dataresp = json_decode($resp);
            
            return $dataresp;
        
        }catch(\Exception $e){
           //return redirect()->to(getenv('ROOT_URL'));
           //exit($e->getMessage());
           if($token == NULL){
                return redirect()->to(getenv('ROOT_URL'));
           }else{
                 echo $e->getMessage();
           }
          
        }


    }
}


?>