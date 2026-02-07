<?php

namespace App\Controllers;

use App\Libraries\Crypto;
use App\Libraries\Session;
use App\Libraries\Usuarios;
use App\Libraries\Administrativo;

use PHPUnit\Util\Json;

class Administrative extends BaseController{
    private $session;
    private $sessionLib;
    private $curl;
    private $token;
    private $administrative;

    private $usuarios;

    public function __construct(){
        $this->session = session();
        $this->curl = service('curlrequest');
        
        $this->sessionLib = new Session();
        $this->usuarios = new Usuarios();
        $this->administrative = new Administrativo();

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
        
        $sessionid = $this->sessionLib->getsession($token);
        $userinfo = $this->usuarios->getInfoUserSession($sessionid, $token);
        
        $sessiondata = [
            "idperfil" => $userinfo->idperfil,
            "idusuario" => $userinfo->idusuario,
            "idsession" => $userinfo->idsession,
            "usuario" => $userinfo->usuario,
            "nombre" => $userinfo->nombre,
            "token_session" => $userinfo->token
        ];

        $this->session->set($sessiondata);

    }

    public function modules(){            
        
        $request = service('request');
        $this->token =  $request->getCookie('token');      
        $data['userinfo'] = $this->session->get();

        echo view('templates/header', $data);
        echo view('templates/dashboard', $data);
        echo view('templates/footer', $data);
       
    }


    public function listaTipoProcesos()
    {
        helper('cookie');

        $request = service('request');
        $this->token =  $request->getCookie('token');
        $data['userinfo'] = $this->session->get();

        $data['tiposProcesos'] = $this->administrative->getTiposProcesos($this->token);



        echo view('templates/header.php', $data);
        echo view('administrative/listaTipoProcesos.php', $data);
        echo view('templates/footer.php', $data);
    }

   public function listaMicroetapas()
{
    helper('cookie');

    $request = service('request');
    $this->token = $request->getCookie('token');
    $data['userinfo'] = $this->session->get();

    // 1) Traer microetapas
    $microetapas = $this->administrative->getMicroetapas($this->token);

    // 2) Traer tipos procesos (para selects o vista)
    $data['tiposProcesos'] = $this->administrative->getTiposProcesos($this->token);

    // ❌ Si viene error desde FastAPI
    if (is_array($microetapas) && isset($microetapas['success']) && $microetapas['success'] === false) {
        return redirect()->back()->with('error', $microetapas['error']);
    }

    // 3) Recorrer microetapas y agregar nombre_macroetapa y nombre_tipo_proceso
    if (is_array($microetapas) || is_object($microetapas)) {

        foreach ($microetapas as &$micro) {

            // ⚠️ si no es objeto, lo saltamos
            if (!is_object($micro)) continue;

            $idmacroetapa = $micro->idetapa ?? null; // <- este es el ID de macroetapa

            // valores por defecto
            $micro->nombre_macroetapa = 'No asignado';
            $micro->nombre_tipo_proceso = 'No asignado';

            if (!empty($idmacroetapa)) {

                // 1) Traer macroetapa por ID
                $macroetapaResp = $this->administrative->getMacroetapaEdit((int)$idmacroetapa, $this->token);

                // 🔥 normalizar respuesta: si viene array => usar [0]
                if (is_array($macroetapaResp) && isset($macroetapaResp[0])) {
                    $macroetapaObj = $macroetapaResp[0];
                } else {
                    $macroetapaObj = $macroetapaResp;
                }

                // validar macroetapa
                if (is_object($macroetapaObj) && isset($macroetapaObj->descripcion)) {
                    $micro->nombre_macroetapa = $macroetapaObj->descripcion;
                }

                // 2) Traer tipo proceso
                if (is_object($macroetapaObj) && isset($macroetapaObj->idtipoproceso)) {

                    $tipoProcesoResp = $this->administrative->getTipoProcesoById((int)$macroetapaObj->idtipoproceso, $this->token);

                    // normalizar respuesta tipo proceso
                    if (is_array($tipoProcesoResp) && isset($tipoProcesoResp[0])) {
                        $tipoProcesoObj = $tipoProcesoResp[0];
                    } else {
                        $tipoProcesoObj = $tipoProcesoResp;
                    }

                    if (is_object($tipoProcesoObj) && isset($tipoProcesoObj->descripcion)) {
                        $micro->nombre_tipo_proceso = $tipoProcesoObj->descripcion;
                    }
                }
            }
        }

        unset($micro);
    }

    // 4) Mandar microetapas ya enriquecidas a la vista
    $data['microetapas'] = $microetapas;

    echo view('templates/header.php', $data);
    echo view('administrative/listaMicroetapas.php', $data);
    echo view('templates/footer.php', $data);
}


    public function eliminarTipoProceso($idtproceso)
    {
        helper('cookie');

        $request = service('request');
        $this->token =  $request->getCookie('token');
        $data['userinfo'] = $this->session->get();


        $response = $this->administrative->deleteTipoProceso($idtproceso, $this->token);
        // ❌ error (array)
    if (is_array($response) && isset($response['success']) && $response['success'] === false) {
        return redirect()->back()->with('error', $response['error']);
    }

    // ❌ error (objeto)
    if (is_object($response) && isset($response->success) && $response->success === false) {
        return redirect()->back()->with('error', $response->error ?? 'Error desconocido');
    }

return redirect()->to(base_url('administracion/listaTprocesos')) ->with('success', 'Tipo de proceso eliminado correctamente');
    }





     public function eliminarMacroetapa($idmacroetapa)
    {
        helper('cookie');

        $request = service('request');
        $this->token =  $request->getCookie('token');
        $data['userinfo'] = $this->session->get();


        $response = $this->administrative->deleteMacroetapa($idmacroetapa, $this->token);
        // ❌ error (array)
    if (is_array($response) && isset($response['success']) && $response['success'] === false) {
        return redirect()->back()->with('error', $response['error']);
    }

    // ❌ error (objeto)
    if (is_object($response) && isset($response->success) && $response->success === false) {
        return redirect()->back()->with('error', $response->error ?? 'Error desconocido');
    }

return redirect()->to(base_url('administracion/listaMacroetapas')) ->with('success', 'Macroetapa eliminada correctamente');
    }






     public function eliminarMicroetapa($idmicroetapa)
    {
        helper('cookie');

        $request = service('request');
        $this->token =  $request->getCookie('token');
        $data['userinfo'] = $this->session->get();


        $response = $this->administrative->deleteMicroetapa($idmicroetapa, $this->token);
        // ❌ error (array)
    if (is_array($response) && isset($response['success']) && $response['success'] === false) {
        return redirect()->back()->with('error', $response['error']);
    }

    // ❌ error (objeto)
    if (is_object($response) && isset($response->success) && $response->success === false) {
        return redirect()->back()->with('error', $response->error ?? 'Error desconocido');
    }

return redirect()->to(base_url('administracion/listaMicroetapas')) ->with('success', 'Microetapa eliminada correctamente');
    }






    public function getTipoProcesoEdit($id)
{
    helper('cookie');
    $request = service('request');
    $token = $request->getCookie('token');

    $response = $this->administrative->getTipoProcesoById((int)$id, $token);

    return $this->response->setJSON($response);
}



public function getMacroetapaEdit($id)
{
    helper('cookie');
    $request = service('request');
    $token = $request->getCookie('token');

    $response = $this->administrative->getMacroetapaEdit((int)$id, $token);

    return $this->response->setJSON($response);
}





public function getMicroetapaEdit($id)
{
    helper('cookie');
    $request = service('request');
    $token = $request->getCookie('token');

    $response = $this->administrative->getMicroetapaEdit((int)$id, $token);

    return $this->response->setJSON($response);
}


       public function agregarTipoProceso()
    {
        helper('cookie');

        $request = service('request');
        $this->token =  $request->getCookie('token');
        $data['userinfo'] = $this->session->get();



        $tProceso     = $request->getPost('nombre');

      
        $response = $this->administrative->createTipoProceso($tProceso, $this->token);

       if (isset($response->success) && $response->success === false) {
    $data['error'] = $response->error;
} else {
    $data['success'] = 'tipo de proceso creado correctamente';
    return redirect()->to(base_url('administracion/listaTprocesos'))
                 ->with('success', 'Tipo de proceso agregado correctamente');

}

    }





     public function agregarMacroetapa()
{
    helper('cookie');

    $request = service('request');
    $this->token =  $request->getCookie('token');
    $data['userinfo'] = $this->session->get();

    // Recibir 3 valores del form
    $idtipoproceso        = $request->getPost('tipoProceso');
    $descripcion   = $request->getPost('nombreMacroetapa');
    $dias = $request->getPost('diasNotificacion');

    // Enviar los 3 valores a la librería
    $response = $this->administrative->createMacroetapa($dias, $descripcion, $idtipoproceso, $this->token);

    if (isset($response->success) && $response->success === false) {
        $data['error'] = $response->error;
    } else {
        $data['success'] = 'macroetapa creada correctamente';
        return redirect()->to(base_url('administracion/listaMacroetapas'))
                     ->with('success', 'Macroetapa agregada correctamente');
    }
}




public function agregarMicroetapa()
{
    helper('cookie');

    $request = service('request');
    $this->token = $request->getCookie('token');
    $data['userinfo'] = $this->session->get();

    // Recibir valores del form
    $idtipoproceso     = $request->getPost('tipoProceso');          // select tipoProcesoM
    $idmacroetapa      = $request->getPost('macroetapa');           // select macroetapa
    $idorden           = $request->getPost('ordenMicroetapa');       // select ordenMicroetapa
    $descripcion       = $request->getPost('nombreMicroetapa');      // input nombreMicroetapa
    $dias              = $request->getPost('diasNotificacion');      // input diasNotificacion

    // (Opcional) Validación rápida
    if(empty($idtipoproceso) || empty($idmacroetapa) || empty($descripcion) || empty($dias)){
        return redirect()->back()->with('error', 'Faltan datos obligatorios')->withInput();
    }

    // Enviar datos a la librería
    // 👇 OJO: aquí depende de cómo esté definida tu función createMicroetapa
    $response = $this->administrative->createMicroetapa(
        $idmacroetapa,
        $descripcion,
        $idorden,
        $dias,
        $this->token
    );

    if (isset($response->success) && $response->success === false) {
        return redirect()->back()->with('error', $response->error)->withInput();
    }

    return redirect()->to(base_url('administracion/listaMicroetapas'))
        ->with('success', 'Microetapa agregada correctamente');
}





public function getMacroPorTipo($idtipoproceso)
{
    helper('cookie');

    $request = service('request');
    $this->token =  $request->getCookie('token');
    $data['userinfo'] = $this->session->get();

   
    // Enviar los 3 valores a la librería
    $response = $this->administrative->getMacroPorTipo($idtipoproceso, $this->token);

    if (isset($response->success) && $response->success === false) {
        $data['error'] = $response->error;
    } else {
        $data['success'] = 'macroetapa creada correctamente';
        return $this->response->setJSON($response);
    }
}






public function getMicroetapasPorMacro($idmacroetapa)
{
    helper('cookie');

    $request = service('request');
    $this->token =  $request->getCookie('token');
    $data['userinfo'] = $this->session->get();

   
    // Enviar los 3 valores a la librería
    $response = $this->administrative->getMicroetapasPorMacro($idmacroetapa, $this->token);

    if (isset($response->success) && $response->success === false) {
        $data['error'] = $response->error;
    } else {
        $data['success'] = 'microetapas obtenidas correctamente';
        return $this->response->setJSON($response);
    }
}


     public function editTipoProceso()
    {
        helper('cookie');

        $request = service('request');
        $this->token =  $request->getCookie('token');
        $data['userinfo'] = $this->session->get();


        $id     = $request->getPost('idTipo');
        $tProceso     = $request->getPost('nombre');

        $data = [
        'idtipoproceso' => (int) $id,
        'descripcion'   => $tProceso
    ];
        $response = $this->administrative->editTipoProceso($data, $this->token);

       
    // ❌ error (array)
    if (is_array($response) && isset($response['success']) && $response['success'] === false) {
        return redirect()->back()->with('error', $response['error']);
    }

    // ❌ error (objeto)
    if (is_object($response) && isset($response->success) && $response->success === false) {
        return redirect()->back()->with('error', $response->error ?? 'Error desconocido');
    }
    $data['success'] = 'tipo de proceso editado correctamente';
    return redirect()->to(base_url('administracion/listaTprocesos'))
                 ->with('success', 'Tipo de proceso editado correctamente');



    }






    

     public function editMacroetapa()
    {
        helper('cookie');

        $request = service('request');
        $this->token =  $request->getCookie('token');
        $data['userinfo'] = $this->session->get();


        $id     = $request->getPost('idMacroetapa');
        $tProceso     = $request->getPost('tipoProceso');
        $orden     = $request->getPost('ordenMacroproceso');
        $descripcion     = $request->getPost('nombreMacroetapa');
        $dias     = $request->getPost('diasNotificacion');



        $data = [
        'idmacroetapa' => (int) $id,
        'idtipoproceso' => (int) $tProceso,
        'descripcion' => $descripcion,
        'idorden' => (int) $orden,
        'diasnotificacion' => (int) $dias
    ];
        $response = $this->administrative->editMacroetapa($data, $this->token);
       
    // ❌ error (array)
    if (is_array($response) && isset($response['success']) && $response['success'] === false) {
        return redirect()->back()->with('error', $response['error']);
    }

    // ❌ error (objeto)
    if (is_object($response) && isset($response->success) && $response->success === false) {
        return redirect()->back()->with('error', $response->error ?? 'Error desconocido');
    }
    $data['success'] = 'macroetapa editada correctamente';
    return redirect()->to(base_url('administracion/listaMacroetapas'))
                 ->with('success', 'Macroetapa editada correctamente');



    }



    
     public function editMicroetapa()
    {
        helper('cookie');

        $request = service('request');
        $this->token =  $request->getCookie('token');
        $data['userinfo'] = $this->session->get();

        $id     = $request->getPost('idMicroetapa');
        $tProceso     = $request->getPost('macroetapa');
        $orden     = $request->getPost('ordenMicroetapa');
        $descripcion     = $request->getPost('nombreMicroetapa');
        $dias     = $request->getPost('diasNotificacion');



        $data = [
        'idmicroetapa' => (int) $id,
        'idetapa' => (int) $tProceso,
        'descripcion' => $descripcion,
        'idorden' => (int) $orden,
        'diasnotificacion' => (int) $dias
    ];
        $response = $this->administrative->editMicroetapa($data, $this->token);
       
    // ❌ error (array)
    if (is_array($response) && isset($response['success']) && $response['success'] === false) {
        return redirect()->back()->with('error', $response['error']);
    }

    // ❌ error (objeto)
    if (is_object($response) && isset($response->success) && $response->success === false) {
        return redirect()->back()->with('error', $response->error ?? 'Error desconocido');
    }
    $data['success'] = 'microetapa editada correctamente';
    return redirect()->to(base_url('administracion/listaMicroetapas'))
                 ->with('success', 'Microetapa editada correctamente');



    }




    














    public function listaMacroetapas()
    {
        helper('cookie');

        $request = service('request');
        $this->token =  $request->getCookie('token');
        $data['userinfo'] = $this->session->get();

        // 1) Traer macroetapas
    $macroetapas = $this->administrative->getMacroetapas($this->token);
    $data['tiposProcesos'] = $this->administrative->getTiposProcesos($this->token);

    // Si el response viene con error
    if (is_array($macroetapas) && isset($macroetapas['success']) && $macroetapas['success'] === false) {
        return redirect()->back()->with('error', $macroetapas['error']);
    }

    // 2) Recorrer y agregar nombre_tipoproceso
    if (is_array($macroetapas) || is_object($macroetapas)) {

        foreach ($macroetapas as &$macro) {
            
            $idtipoproceso = $macro->idtipoproceso ?? null;

            $macro->nombre_tipoproceso = 'No asignado';

            if (!empty($idtipoproceso)) {

                $tipoProceso = $this->administrative->getTipoProcesoById((int)$idtipoproceso, $this->token);
               
                // Si viene bien
                if (is_object($tipoProceso) && isset($tipoProceso->descripcion)) {
                    $macro->nombre_tipoproceso = $tipoProceso->descripcion;
                }

                // Si viene en array por error
                if (is_array($tipoProceso) && isset($tipoProceso['success']) && $tipoProceso['success'] === false) {
                    $macro->nombre_tipoproceso = 'Error consultando';
                }
            }
        }
        unset($macro);
    }

    $data['macroetapas'] = $macroetapas;



        echo view('templates/header.php', $data);
        echo view('administrative/listaMacroetapas.php', $data);
        echo view('templates/footer.php', $data);
    }


}

?>
