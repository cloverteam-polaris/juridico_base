<?php
namespace App\Libraries;


class Administrativo{

    private $request;
    private $session;
    private $curl;


    public function __construct(){
        $this->request = service('request');
        $this->session = session();
        $this->curl = service('curlrequest');
        
    }



    public function createTipoProceso($descripcion, $token)
{
    try {

        $payload = [
            'idtipoproceso' => 0,
            'descripcion'   => $descripcion
        ];

        $response = $this->curl->request('POST', API . 'admin/crea-tipo-proceso', [
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
                'url'      => API . 'admin/crea-tipo-proceso',
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



public function createMicroetapa($idetapa, $descripcion, $idorden, $diasrevision, $token)
{
    try {

        $payload = [
            'idmicroetapa'  => 0,
            'idetapa'       => (int) $idetapa,
            'descripcion'   => $descripcion,
            'idorden'       => (int) $idorden,
            'diasrevision'  => (int) $diasrevision
        ];

        $response = $this->curl->request('POST', API . 'admin/crea-microetapa', [
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
                'url'      => API . 'admin/crea-microetapa',
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



public function getMicroetapas($token)
{
    try {
        $response = $this->curl->request('GET', API . 'admin/microetapas', [
            'headers' => [
                'User-Agent'    => 'polaris/1.0',
                'Content-Type'  => 'application/json',
                'Authorization' => 'Bearer ' . $token,
                'Accept'        => 'application/json',
            ],
            'http_errors' => false,
        ]);

        $statusCode = $response->getStatusCode();
        $body = $response->getBody(); // string

        if ($statusCode >= 400) {
            $errorData = json_decode($body, true);

            log_message('error', 'FastAPI Error: ' . json_encode([
                'status'   => $statusCode,
                'url'      => API . 'admin/macroetapas',
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








public function getMacroetapas($token)
{
    try {
        $response = $this->curl->request('GET', API . 'admin/macroetapas', [
            'headers' => [
                'User-Agent'    => 'polaris/1.0',
                'Content-Type'  => 'application/json',
                'Authorization' => 'Bearer ' . $token,
                'Accept'        => 'application/json',
            ],
            'http_errors' => false,
        ]);

        $statusCode = $response->getStatusCode();
        $body = $response->getBody(); // string

        if ($statusCode >= 400) {
            $errorData = json_decode($body, true);

            log_message('error', 'FastAPI Error: ' . json_encode([
                'status'   => $statusCode,
                'url'      => API . 'admin/macroetapas',
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








 public function editTipoProceso($data, $token)
{
    try {

        // 🔁 Body exacto como lo pide FastAPI
        $payload = [
            'idtipoproceso' => (int) $data['idtipoproceso'],
            'descripcion'   => $data['descripcion'],
        ];

        $response = $this->curl->request('POST', API . 'admin/edita-tipo-proceso', [
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
                'url'      => API . 'admin/edita-tipo-proceso',
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



  public function deleteMacroetapa($idmacroetapa, $token)
{
    try {

       

        $response = $this->curl->request('POST', API . '/admin/borra-macroetapa/' . $idmacroetapa, [
            'headers' => [
                'User-Agent'    => 'polaris/1.0',
                'Content-Type'  => 'application/json',
                'Authorization' => 'Bearer ' . $token,
                'Accept'        => 'application/json',
            ],
            
            'http_errors' => false,
        ]);

        $statusCode = $response->getStatusCode();
        $body = $response->getBody();

        if ($statusCode >= 400) {
            $errorData = json_decode($body, true);

            log_message('error', 'FastAPI Error: ' . json_encode([
                'status'   => $statusCode,
                'url'      => API . 'admin/elimina-tipo-proceso',
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








  public function deleteMicroetapa($idmicroetapa, $token)
{
    try {

       

        $response = $this->curl->request('POST', API . '/admin/borra-microetapa/' . $idmicroetapa, [
            'headers' => [
                'User-Agent'    => 'polaris/1.0',
                'Content-Type'  => 'application/json',
                'Authorization' => 'Bearer ' . $token,
                'Accept'        => 'application/json',
            ],
            
            'http_errors' => false,
        ]);


        $statusCode = $response->getStatusCode();
        $body = $response->getBody();

        if ($statusCode >= 400) {
            $errorData = json_decode($body, true);

            log_message('error', 'FastAPI Error: ' . json_encode([
                'status'   => $statusCode,
                'url'      => API . 'admin/borra-microetapa/' . $idmicroetapa,
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








    public function deleteTipoProceso($idtipoproceso, $token)
{
    try {

        $idtipoproceso = (int) $idtipoproceso;

        $response = $this->curl->request('POST', API . 'admin/elimina-tipo-proceso', [
            'headers' => [
                'User-Agent'    => 'polaris/1.0',
                'Content-Type'  => 'application/json',
                'Authorization' => 'Bearer ' . $token,
                'Accept'        => 'application/json',
            ],
            'query' => [ // 👈 parameters
                'idtipoproceso' => $idtipoproceso
            ],
            'http_errors' => false,
        ]);

        $statusCode = $response->getStatusCode();
        $body = $response->getBody();

        if ($statusCode >= 400) {
            $errorData = json_decode($body, true);

            log_message('error', 'FastAPI Error: ' . json_encode([
                'status'   => $statusCode,
                'url'      => API . 'admin/elimina-tipo-proceso',
                'params'   => ['idtipoproceso' => $idtipoproceso],
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


public function getTipoProcesoById(int $idproceso, string $token)
{
    try {

        $response = $this->curl->request('GET', API . 'admin/tipo-proceso/' . $idproceso, [
            'headers' => [
                'User-Agent'    => 'polaris/1.0',
                'Content-Type'  => 'application/json',
                'Authorization' => 'Bearer ' . $token,
                'Accept'        => 'application/json',
            ],
            'http_errors' => false,
        ]);

        $statusCode = $response->getStatusCode();
        $body = $response->getBody(); // string

        if ($statusCode >= 400) {

            $errorData = json_decode($body, true);

            log_message('error', 'FastAPI Error: ' . json_encode([
                'status'   => $statusCode,
                'url'      => API . 'admin/tipo-proceso/' . $idproceso,
                'response' => $errorData,
            ]));

            return [
                'success' => false,
                'status'  => $statusCode,
                'error'   => $errorData['detail'] ?? 'Error desconocido',
                'full_response' => $errorData
            ];
        }

        // ✅ devuelve objeto JSON
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




public function editMicroetapa($data, $token)
{
    try {

        // 🔁 Body exacto como lo pide FastAPI
        $payload = [
            'idmicroetapa' => (int) $data['idmicroetapa'],
            'idetapa'      => (int) $data['idetapa'],
            'descripcion'  => $data['descripcion'],
            'idorden'      => (int) $data['idorden'],
            'diasrevision' => (int) $data['diasnotificacion'],
        ];

        $response = $this->curl->request('POST', API . 'admin/edita-microetapa', [
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
                'url'      => API . 'admin/edita-microetapa',
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




public function editMacroetapa($data, $token)
{
    try {

        // 🔁 Body exacto como lo pide FastAPI
        $payload = [
            'idmacroetapa'     => (int) $data['idmacroetapa'],
            'idtipoproceso'    => (int) $data['idtipoproceso'],
            'descripcion'      => $data['descripcion'],
            'idorden'          => (int) $data['idorden'],
            'diasrevision'  => (int) $data['diasnotificacion'], // 👈 ojo: así lo pide el backend (con esa ortografía)
        ];

        $response = $this->curl->request('POST', API . 'admin/edita-macroetapa', [
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
                'url'      => API . 'admin/edita-macroetapa',
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





public function getMacroetapaEdit(int $idmacroetapa, string $token)
{
    try {

        $response = $this->curl->request('GET', API . 'admin/macroetapa/' . $idmacroetapa, [
            'headers' => [
                'User-Agent'    => 'polaris/1.0',
                'Content-Type'  => 'application/json',
                'Authorization' => 'Bearer ' . $token,
                'Accept'        => 'application/json',
            ],
            'http_errors' => false,
        ]);

        $statusCode = $response->getStatusCode();
        $body = $response->getBody(); // string

        if ($statusCode >= 400) {

            $errorData = json_decode($body, true);

            log_message('error', 'FastAPI Error: ' . json_encode([
                'status'   => $statusCode,
                'url'      => API . 'admin/macroetapa/' . $idmacroetapa,
                'response' => $errorData,
            ]));

            return [
                'success' => false,
                'status'  => $statusCode,
                'error'   => $errorData['detail'] ?? 'Error desconocido',
                'full_response' => $errorData
            ];
        }

        // ✅ devuelve objeto JSON
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








public function getMicroetapaEdit(int $idmicroetapa, string $token)
{
    try {

        $response = $this->curl->request('GET', API . 'admin/microetapa/' . $idmicroetapa, [
            'headers' => [
                'User-Agent'    => 'polaris/1.0',
                'Content-Type'  => 'application/json',
                'Authorization' => 'Bearer ' . $token,
                'Accept'        => 'application/json',
            ],
            'http_errors' => false,
        ]);

        $statusCode = $response->getStatusCode();
        $body = $response->getBody(); // string

        if ($statusCode >= 400) {

            $errorData = json_decode($body, true);

            log_message('error', 'FastAPI Error: ' . json_encode([
                'status'   => $statusCode,
                'url'      => API . 'admin/microetapa/' . $idmicroetapa,
                'response' => $errorData,
            ]));

            return [
                'success' => false,
                'status'  => $statusCode,
                'error'   => $errorData['detail'] ?? 'Error desconocido',
                'full_response' => $errorData
            ];
        }

        // ✅ devuelve objeto JSON
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






public function getMacroPorTipo(int $idproceso, string $token)
{
    try {

        $response = $this->curl->request('GET', API . 'admin/macroetapa-proceso/' . $idproceso, [
            'headers' => [
                'User-Agent'    => 'polaris/1.0',
                'Content-Type'  => 'application/json',
                'Authorization' => 'Bearer ' . $token,
                'Accept'        => 'application/json',
            ],
            'http_errors' => false,
        ]);

        $statusCode = $response->getStatusCode();
        $body = $response->getBody(); // string

        if ($statusCode >= 400) {

            $errorData = json_decode($body, true);

            log_message('error', 'FastAPI Error: ' . json_encode([
                'status'   => $statusCode,
                'url'      => API . 'admin/macroetapa-proceso/' . $idproceso,
                'response' => $errorData,
            ]));

            return [
                'success' => false,
                'status'  => $statusCode,
                'error'   => $errorData['detail'] ?? 'Error desconocido',
                'full_response' => $errorData
            ];
        }

        // ✅ devuelve objeto JSON
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







public function getMicroetapasPorMacro(int $idmacroetapa, string $token)
{
    try {

        $response = $this->curl->request('GET', API . 'admin/microetapa-macro/' . $idmacroetapa, [
            'headers' => [
                'User-Agent'    => 'polaris/1.0',
                'Content-Type'  => 'application/json',
                'Authorization' => 'Bearer ' . $token,
                'Accept'        => 'application/json',
            ],
            'http_errors' => false,
        ]);

        $statusCode = $response->getStatusCode();
        $body = $response->getBody(); // string

        if ($statusCode >= 400) {

            $errorData = json_decode($body, true);

            log_message('error', 'FastAPI Error: ' . json_encode([
                'status'   => $statusCode,
                'url'      => API . 'admin/microetapa-macro/' . $idmacroetapa,
                'response' => $errorData,
            ]));

            return [
                'success' => false,
                'status'  => $statusCode,
                'error'   => $errorData['detail'] ?? 'Error desconocido',
                'full_response' => $errorData
            ];
        }

        // ✅ devuelve objeto JSON
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






public function createMacroetapa($dias, $descripcion, $idtipoproceso, $token)
{
    try {

        // 🔁 Body como lo pide FastAPI
        $payload = [
            'idmacroetapa'    => 0,
            'idtipoproceso'   => (int) $idtipoproceso,
            'descripcion'     => $descripcion,
            'idorden'         => 0,
            'diasnotifiacion' => (int) $dias,
        ];

        $response = $this->curl->request('POST', API . 'admin/crea-macroetapa', [
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
                'url'      => API . 'admin/crea-macroetapa',
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



     
public function getTiposProcesos($token)
{
    try {
        $response = $this->curl->request('GET', API . 'admin/tipos-proceso', [
            'headers' => [
                'User-Agent'    => 'polaris/1.0',
                'Content-Type'  => 'application/json',
                'Authorization' => 'Bearer ' . $token,
                'Accept'        => 'application/json',
            ],
            'http_errors' => false,
        ]);

        $statusCode = $response->getStatusCode();
        $body = $response->getBody(); // string

        if ($statusCode >= 400) {
            $errorData = json_decode($body, true);

            log_message('error', 'FastAPI Error: ' . json_encode([
                'status'   => $statusCode,
                'url'      => API . 'admin/tipos-proceso',
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





}


?>