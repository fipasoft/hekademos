<?php
Kumbia :: import('app.componentes.*');
Kumbia :: import('lib.kumbia.utils');

 class InformacionController extends ApplicationController {
 	public $template = "system";
 	
  	public function index(){
 		$categorias = array();
 		
 		$categorias[ '' ] = array(
			'estadisticas' => 'Estadisticas',
			'reportes' => 'Reportes'
 		);
 		
 				// acl
		$usr_login = Session :: get_data('usr.login');
		$this->acl = array ();
		$acl = new gacl_extra();
		$acos_arr = array (
			'estadisticas' => array (
				'index'
				),
			'reportes' => array (
				'index'
				)
		);
		
		$this->acl = $acl->acl_check_multiple($acos_arr, $usr_login);
		$this->categorias   =  $categorias;
		$this->path         =  KUMBIA_PATH;
 	}

 }