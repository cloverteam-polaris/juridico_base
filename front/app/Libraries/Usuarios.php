<?php

namespace App\Libraries;

class Usuarios
{

    private $request;
    private $session;
    private $curl;

    public function __construct()
    {
        $this->request = service('request');
        $this->session = session();
        $this->curl = service('curlrequest');
    }

    public function getInfoUserSession($id, $token)
    {
        try {
            $response = $this->curl->request('GET', API . 'users/getusuariosession/' . $id, [
                'headers' => [
                    'User-Agent' => 'polaris/1.0',
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $token,
                    'Accept' => 'application/json',
                ],
                'http_errors' => false,
            ]);

            $statusCode = $response->getStatusCode();
            $body = $response->getBody(); // String directo

            if ($statusCode >= 400) {
                $errorData = json_decode($body, true);

                log_message('error', 'FastAPI Error: ' . json_encode([
                    'status' => $statusCode,
                    'url' => API . 'users/getusuariosession/' . $id,
                    'response' => $errorData,
                ]));

                return [
                    'success' => false,
                    'status' => $statusCode,
                    'error' => $errorData['detail'] ?? 'Error desconocido',
                    'full_response' => $errorData
                ];
            }

            return json_decode($body);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            $errorMsg = $e->getMessage();

            if ($e->hasResponse()) {
                $responseBody = $e->getResponse()->getBody(); // String
                $errorMsg .= " | Response: " . $responseBody;
            }

            log_message('error', 'FastAPI Request Error: ' . $errorMsg);

            return [
                'success' => false,
                'error' => $errorMsg,
                'type' => 'request_error'
            ];
        } catch (\Exception $e) {
            log_message('error', 'Error: ' . $e->getMessage());

            return [
                'success' => false,
                'error' => $e->getMessage(),
                'type' => 'general_error'
            ];
        }
    }





    public function saveUser($data, $token)
    {
        try {

            // 🔁 Mapear body exactamente como lo pide FastAPI
            $payload = [
                'usuario'   => $data['usuario'],
                'password'  => $data['password'],
                'nombre'    => $data['nombre'],
                'documento' => $data['documento'],
                'idperfil'  => (int) $data['idperfil'],
                'idestado'  => '1',
                'email'     => $data['correo'],
                'telefono'  => $data['telefono'],
            ];

            $response = $this->curl->request('POST', API . 'users/crearusuario', [
                'headers' => [
                    'User-Agent' => 'polaris/1.0',
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $token,
                    'Accept' => 'application/json',
                ],
                'json' => $payload,
                'http_errors' => false,
            ]);

            $statusCode = $response->getStatusCode();
            $body = $response->getBody();

            if ($statusCode >= 400) {
                $errorData = json_decode($body, true);

                log_message('error', 'FastAPI Error: ' . json_encode([
                    'status' => $statusCode,
                    'url' => API . 'users/crearusuario',
                    'payload' => $payload,
                    'response' => $errorData,
                ]));

                return [
                    'success' => false,
                    'status' => $statusCode,
                    'error' => $errorData['detail'] ?? 'Error desconocido',
                    'full_response' => $errorData
                ];
            }

            return json_decode($body);
        } catch (\GuzzleHttp\Exception\RequestException $e) {

            $errorMsg = $e->getMessage();

            if ($e->hasResponse()) {
                $responseBody = $e->getResponse()->getBody();
                $errorMsg .= " | Response: " . $responseBody;
            }

            log_message('error', 'FastAPI Request Error: ' . $errorMsg);

            return [
                'success' => false,
                'error' => $errorMsg,
                'type' => 'request_error'
            ];
        } catch (\Exception $e) {

            log_message('error', 'Error: ' . $e->getMessage());

            return [
                'success' => false,
                'error' => $e->getMessage(),
                'type' => 'general_error'
            ];
        }
    }




    public function editPassword($data, $token)
{
    try {

        // 🔁 Body exactamente como lo pide FastAPI
        $payload = [
            'idusuario' => (int) $data['idusuario'],
            'password'  => $data['password'],
        ];

        $response = $this->curl->request('POST', API . 'users/cambiarpassword', [
            'headers' => [
                'User-Agent'    => 'polaris/1.0',
                'Content-Type'  => 'application/json',
                'Authorization' => 'Bearer ' . $token,
                'Accept'        => 'application/json',
            ],
            'json' => $payload,
            'http_errors' => false,
        ]);

        $statusCode = $response->getStatusCode();
        $body = $response->getBody();

        if ($statusCode >= 400) {
            $errorData = json_decode($body, true);

            log_message('error', 'FastAPI Error: ' . json_encode([
                'status'   => $statusCode,
                'url'      => API . 'users/cambiarpassword',
                'payload'  => $payload,
                'response' => $errorData,
            ]));

            return [
                'success' => false,
                'status'  => $statusCode,
                'error'   => $errorData['detail'] ?? 'Error desconocido',
                'full_response' => $errorData
            ];
        }

        return json_decode($body);

    } catch (\GuzzleHttp\Exception\RequestException $e) {

        $errorMsg = $e->getMessage();

        if ($e->hasResponse()) {
            $responseBody = $e->getResponse()->getBody();
            $errorMsg .= " | Response: " . $responseBody;
        }

        log_message('error', 'FastAPI Request Error: ' . $errorMsg);

        return [
            'success' => false,
            'error'   => $errorMsg,
            'type'    => 'request_error'
        ];

    } catch (\Exception $e) {

        log_message('error', 'Error: ' . $e->getMessage());

        return [
            'success' => false,
            'error'   => $e->getMessage(),
            'type'    => 'general_error'
        ];
    }
}

    



    public function updateUser($data, $token)
{
    try {

        // 🔁 Mapear body exactamente como lo pide FastAPI
        $payload = [
            'idusuario' => (int) $data['idusuario'],
            'nombre'    => $data['nombre'],
            'documento' => $data['documento'],
            'idperfil'  => (int) $data['idperfil'],
            'idestado'  => '1',
            'email'     => $data['correo'],   // tu form trae "correo", API pide "email"
            'telefono'  => $data['telefono'],
        ];

        $response = $this->curl->request('POST', API . 'users/updateuser', [
            'headers' => [
                'User-Agent'    => 'polaris/1.0',
                'Content-Type'  => 'application/json',
                'Authorization' => 'Bearer ' . $token,
                'Accept'        => 'application/json',
            ],
            'json' => $payload,
            'http_errors' => false,
        ]);

        $statusCode = $response->getStatusCode();
        $body = $response->getBody();

        if ($statusCode >= 400) {
            $errorData = json_decode($body, true);

            log_message('error', 'FastAPI Error: ' . json_encode([
                'status'   => $statusCode,
                'url'      => API . 'users/updateuser',
                'payload'  => $payload,
                'response' => $errorData,
            ]));

            return [
                'success' => false,
                'status'  => $statusCode,
                'error'   => $errorData['detail'] ?? 'Error desconocido',
                'full_response' => $errorData
            ];
        }

        return json_decode($body);

    } catch (\GuzzleHttp\Exception\RequestException $e) {

        $errorMsg = $e->getMessage();

        if ($e->hasResponse()) {
            $responseBody = $e->getResponse()->getBody();
            $errorMsg .= " | Response: " . $responseBody;
        }

        log_message('error', 'FastAPI Request Error: ' . $errorMsg);

        return [
            'success' => false,
            'error'   => $errorMsg,
            'type'    => 'request_error'
        ];

    } catch (\Exception $e) {

        log_message('error', 'Error: ' . $e->getMessage());

        return [
            'success' => false,
            'error'   => $e->getMessage(),
            'type'    => 'general_error'
        ];
    }
}





    public function getUserById($iduser, $token)
    {
        try {
            $response = $this->curl->request(
                'GET',
                API . 'users/getuser/' . $iduser,
                [
                    'headers' => [
                        'User-Agent' => 'polaris/1.0',
                        'Content-Type' => 'application/json',
                        'Authorization' => 'Bearer ' . $token,
                        'Accept' => 'application/json',
                    ],
                    'http_errors' => false,
                ]
            );

            $statusCode = $response->getStatusCode();
            $body = $response->getBody(); // string JSON

            if ($statusCode >= 400) {
                $errorData = json_decode($body, true);

                log_message('error', 'FastAPI Error: ' . json_encode([
                    'status' => $statusCode,
                    'url' => API . 'users/getuser/' . $iduser,
                    'response' => $errorData,
                ]));

                return [
                    'success' => false,
                    'status' => $statusCode,
                    'error' => $errorData['detail'] ?? 'Error desconocido',
                    'full_response' => $errorData
                ];
            }

            return json_decode($body); // stdClass
        } catch (\GuzzleHttp\Exception\RequestException $e) {

            $errorMsg = $e->getMessage();

            if ($e->hasResponse()) {
                $responseBody = $e->getResponse()->getBody();
                $errorMsg .= " | Response: " . $responseBody;
            }

            log_message('error', 'FastAPI Request Error: ' . $errorMsg);

            return [
                'success' => false,
                'error' => $errorMsg,
                'type' => 'request_error'
            ];
        } catch (\Exception $e) {

            log_message('error', 'Error: ' . $e->getMessage());

            return [
                'success' => false,
                'error' => $e->getMessage(),
                'type' => 'general_error'
            ];
        }
    }



    public function getPerfiles($token)
    {
        try {
            $response = $this->curl->request(
                'GET',
                API . 'users/getperfiles',
                [
                    'headers' => [
                        'User-Agent' => 'polaris/1.0',
                        'Content-Type' => 'application/json',
                        'Authorization' => 'Bearer ' . $token,
                        'Accept' => 'application/json',
                    ],
                    'http_errors' => false,
                ]
            );

            $statusCode = $response->getStatusCode();
            $body = $response->getBody(); // JSON string

            if ($statusCode >= 400) {
                $errorData = json_decode($body, true);

                log_message('error', 'FastAPI Error: ' . json_encode([
                    'status' => $statusCode,
                    'url' => API . 'users/getperfiles',
                    'response' => $errorData,
                ]));

                return [
                    'success' => false,
                    'status' => $statusCode,
                    'error' => $errorData['detail'] ?? 'Error desconocido',
                    'full_response' => $errorData
                ];
            }

            return json_decode($body); // array|stdClass según API
        } catch (\GuzzleHttp\Exception\RequestException $e) {

            $errorMsg = $e->getMessage();

            if ($e->hasResponse()) {
                $responseBody = $e->getResponse()->getBody();
                $errorMsg .= " | Response: " . $responseBody;
            }

            log_message('error', 'FastAPI Request Error: ' . $errorMsg);

            return [
                'success' => false,
                'error' => $errorMsg,
                'type' => 'request_error'
            ];
        } catch (\Exception $e) {

            log_message('error', 'Error: ' . $e->getMessage());

            return [
                'success' => false,
                'error' => $e->getMessage(),
                'type' => 'general_error'
            ];
        }
    }


    public function eliminarUsuario($idusuario, $token)
{
    try {
        $response = $this->curl->request(
            'POST',
            API . 'users/eliminarusuario/' . $idusuario,
            [
                'headers' => [
                    'User-Agent' => 'polaris/1.0',
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $token,
                    'Accept' => 'application/json',
                ],
                'http_errors' => false,
            ]
        );

        $statusCode = $response->getStatusCode();
        $body = $response->getBody();

        if ($statusCode >= 400) {
            $errorData = json_decode($body, true);

            log_message('error', 'FastAPI Error: ' . json_encode([
                'status' => $statusCode,
                'url' => API . 'users/eliminarusuario/' . $idusuario,
                'response' => $errorData,
            ]));

            return [
                'success' => false,
                'status' => $statusCode,
                'error' => $errorData['detail'] ?? 'Error al eliminar usuario',
                'full_response' => $errorData
            ];
        }

        // ✔️ Retorno exitoso normalizado
        return [
            'success' => true,
            'status' => $statusCode,
            'response' => json_decode($body, true)
        ];

    } catch (\GuzzleHttp\Exception\RequestException $e) {

        $errorMsg = $e->getMessage();

        if ($e->hasResponse()) {
            $responseBody = $e->getResponse()->getBody();
            $errorMsg .= " | Response: " . $responseBody;
        }

        log_message('error', 'FastAPI Request Error: ' . $errorMsg);

        return [
            'success' => false,
            'error' => $errorMsg,
            'type' => 'request_error'
        ];

    } catch (\Exception $e) {

        log_message('error', 'Error: ' . $e->getMessage());

        return [
            'success' => false,
            'error' => $e->getMessage(),
            'type' => 'general_error'
        ];
    }
}





    public function getAllUsers($token)
    {
        try {
            $response = $this->curl->request('GET', API . 'users/getallusers', [
                'headers' => [
                    'User-Agent' => 'polaris/1.0',
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $token,
                    'Accept' => 'application/json',
                ],
                'http_errors' => false,
            ]);

            $statusCode = $response->getStatusCode();
            $body = $response->getBody(); // String directo

            if ($statusCode >= 400) {
                $errorData = json_decode($body, true);

                log_message('error', 'FastAPI Error: ' . json_encode([
                    'status' => $statusCode,
                    'url' => API . 'users/getallusers',
                    'response' => $errorData,
                ]));

                return [
                    'success' => false,
                    'status' => $statusCode,
                    'error' => $errorData['detail'] ?? 'Error desconocido',
                    'full_response' => $errorData
                ];
            }

            return json_decode($body);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            $errorMsg = $e->getMessage();

            if ($e->hasResponse()) {
                $responseBody = $e->getResponse()->getBody();
                $errorMsg .= " | Response: " . $responseBody;
            }

            log_message('error', 'FastAPI Request Error: ' . $errorMsg);

            return [
                'success' => false,
                'error' => $errorMsg,
                'type' => 'request_error'
            ];
        } catch (\Exception $e) {
            log_message('error', 'Error: ' . $e->getMessage());

            return [
                'success' => false,
                'error' => $e->getMessage(),
                'type' => 'general_error'
            ];
        }
    }




    public function getModulosInfo()
    {


        $token = $this->request->getCookie('token');
        try {
            $response = $this->curl
                ->request('GET', API . 'users/getmodulosinfo', [
                    'debug' => true,
                    'headers' => [
                        'User-Agent' => 'polaris/1.0',
                        'content-type' => 'application/json',
                        'Authorization' => 'Bearer ' . $token,
                        'Accept'     => 'application/json',
                    ]
                ]);
            $resp = $response->getBody();
            $dataresp = json_decode($resp);

            return $dataresp;
        } catch (\Exception $e) {
            //return redirect()->to(getenv('ROOT_URL'));
            //exit($e->getMessage());
            if ($token == NULL) {
                return redirect()->to(getenv('ROOT_URL'));
            } else {
                echo $e->getMessage();
            }
        }
    }
}
