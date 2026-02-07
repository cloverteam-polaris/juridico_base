<?php
namespace App\Libraries;


class Session{

    private $request;
    private $session;
    private $curl;


    public function __construct(){
        $this->request = service('request');
        $this->session = session();
        $this->curl = service('curlrequest');
        
    }

     public function getInfoUserSession($id, $token){
        try {
            $response = $this->curl
                ->request('GET', API . 'users/getusuariosession/' . $id . '', [
                    'debug' => true,
                    'headers' => [
                        'User-Agent' => 'polaris/1.0',
                        'Authorization' => 'Bearer ' . $token,
                        'Accept'     => 'application/json',

                    ]
                ]);
            $resp = $response->getBody();
            $dataresp = json_decode($resp);

            return $dataresp;
        } catch (\Exception $e) {
            echo $e->getMessage();
            die();
        }
    }


    public function getsession($token){
         try {

                $response = $this->curl
                    ->request('GET', API . 'users/getsession', [
                        'debug' => true,
                        'headers' => [
                            'User-Agent' => 'polaris/1.0',
                            'Authorization' => 'Bearer ' . $token,
                            'Accept'     => 'application/json',
                        ]
                    ]);
                $resp = $response->getBody();
                
                $datasessionFilter = json_decode($resp);

                if($datasessionFilter->idestado == 0){
                    return redirect()->to(getenv('ROOT_URL'));
                }else{
                    return $datasessionFilter->idsession;        
                }
            
        } catch (\Exception $e) {
            if ($token == NULL || $token == "") {
                return redirect()->to(getenv('ROOT_URL'));
            } else {
                echo $e->getMessage();
                die();
            }
        }
    }

}


?>