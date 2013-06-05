<?php
Kumbia :: import('app.componentes.*');
Kumbia :: import('lib.kumbia.utils');

 class EscolarController extends ApplicationController {
     public $template = "system";
     
      public function index(){
         $categorias = array();
         
         $categorias[ '' ] = array(
            'materias' => 'Materias',
            'grupos' => 'Grupos',
            'cursos' => 'Cursos',
            'periodos' => utf8_decode('Selección de cursos'),
            'plantilla' => 'Plantilla'
         );
         
                 // acl
        $usr_login = Session :: get_data('usr.login');
        $this->acl = array ();
        $acl = new gacl_extra();
        $acos_arr = array (
            'materias' => array (
                'index'
                ),
            'grupos' => array (
                'index'
                ),
            'cursos' => array (
                'index'
            ),
            'periodos' => array (
                'index'
            ),
            'plantilla' => array (
                'index'
            ),
        );
        
        $this->acl = $acl->acl_check_multiple($acos_arr, $usr_login);
        $this->categorias   =  $categorias;
        $this->path         =  KUMBIA_PATH;
     }

 }