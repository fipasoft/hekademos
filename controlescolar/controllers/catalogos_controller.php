<?php
Kumbia :: import('app.componentes.*');
Kumbia :: import('lib.kumbia.utils');

 class CatalogosController extends ApplicationController {
     public $template = "system";
     
      public function index(){
         $categorias = array();
         
         $categorias[ '' ] = array(
            'alumnos' => 'Alumnos',
            'profesores' => 'Profesores',
            'tutores' => 'Tutores',
            'personal' => 'Personal',
            'aulas' => 'Aulas'
         );
         
                 // acl
        $usr_login = Session :: get_data('usr.login');
        $this->acl = array ();
        $acl = new gacl_extra();
        $acos_arr = array (
            'alumnos' => array (
                'index'
                ),
            'profesores' => array (
                'index'
                ),
            'tutores' => array (
                'index'
            ),
            'personal' => array (
                'index'
            ),
            'aulas' => array (
                'index'
            ),
        );
        
        $this->acl = $acl->acl_check_multiple($acos_arr, $usr_login);
        $this->categorias   =  $categorias;
        $this->path         =  KUMBIA_PATH;
     }

 }