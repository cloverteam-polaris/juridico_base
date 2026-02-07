<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('admin/modules', 'Administrative::modules');
$routes->post('admin/register', 'Administrative::register');
$routes->get('cerrarsesion', 'Home::cerrarsession');


$routes->get('usuarios/crearUsuario', 'Users::crearUsuario');
$routes->get('usuarios/listaUsuarios', 'Users::listaUsuarios');
$routes->post('usuarios/saveUser', 'Users::saveUsuario');
$routes->get('usuarios/editarUsuario/(:num)', 'Users::editarUsuario/$1');
$routes->get('usuarios/eliminarUsuario/(:num)', 'Users::eliminarUsuario/$1');
$routes->post('usuarios/editUser', 'Users::editUsuario');




$routes->get('administracion/listaTprocesos', 'Administrative::listaTipoProcesos');
$routes->get('administracion/listaMacroetapas', 'Administrative::listaMacroetapas');
$routes->get('administracion/listaMicroetapas', 'Administrative::listaMicroetapas');

$routes->get('administracion/eliminarTipoProceso/(:num)', 'Administrative::eliminarTipoProceso/$1');
$routes->post('administracion/agregarTipoProceso', 'Administrative::agregarTipoProceso');
$routes->get('administracion/getTipoProcesoEdit/(:num)', 'Administrative::getTipoProcesoEdit/$1');
$routes->post('administracion/editTipoProceso', 'Administrative::editTipoProceso');

$routes->get('administracion/getMacroPorTipo/(:num)', 'Administrative::getMacroPorTipo/$1');
$routes->get('administracion/getMicroetapasPorMacro/(:num)', 'Administrative::getMicroetapasPorMacro/$1');


$routes->get('administracion/eliminarMacroetapa/(:num)', 'Administrative::eliminarMacroetapa/$1');
$routes->post('administracion/agregarMacroetapa', 'Administrative::agregarMacroetapa');
$routes->get('administracion/getMacroetapaEdit/(:num)', 'Administrative::getMacroetapaEdit/$1');
$routes->post('administracion/editMacroetapa', 'Administrative::editMacroetapa');



$routes->get('administracion/eliminarMicroetapa/(:num)', 'Administrative::eliminarMicroetapa/$1');
$routes->post('administracion/agregarMicroetapa', 'Administrative::agregarMicroetapa');
$routes->get('administracion/getMicroetapaEdit/(:num)', 'Administrative::getMicroetapaEdit/$1');
$routes->post('administracion/editMicroetapa', 'Administrative::editMicroetapa');

$routes->get('/', 'Login::index');
