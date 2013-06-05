<?php
/** SP5
 * Creado el 03/07/2008
 * Copyright (C) 2008 FiPa Software (contacto at fipasoft.com.mx)
 */

Kumbia :: import('app.componentes.*');
Kumbia :: import('lib.phpgacl.main');

class SesionController extends ApplicationController {

    public function index(){
        $this->redirect('sesion/autenticar', 0);
    }



    public function abrir() {
        $Usuarios = new Usuarios();
        $Profesores=new Profesores();
        $c =  "pass = '" . sha1($this->post('pass')) . "'" .
              "AND login = '" . $this->post('login') . "' " ;
        $cc= "SELECT count(profesores.id) FROM
                profesores
                INNER JOIN profesorespassword ON profesores.id=profesorespassword.profesores_id WHERE
                profesorespassword.pass = '" . sha1($this->post('pass')) . "' AND profesores.codigo = '" . $this->post('login') . "' " ;



        if($Usuarios->count($c) >= 1 && $this->post('login')=='root'){
            $Usuario = $Usuarios->find_first('conditions: ' . $c);
            $Ciclos = new Ciclos();
            $Ciclos = $Ciclos->find_first("conditions: activo = '1' ", "order: numero DESC");
            $m = new Menu();
            $gacl = new gacl_extra();

            Session :: unset_data(
                'prof.app.busqueda',
                'prof.app.paginador',
                'prof.grp.asignados',
                'prof.usr.tutorias'
            );

            Session :: set_data( 'prof.ciclo.id',    $Ciclos->id );
            Session :: set_data( 'prof.usr.id',      $Usuario->id );
            Session :: set_data( 'prof.usr.login',   $Usuario->login );
            Session :: set_data( 'prof.usr.nombre',  $Usuario->nombre );
            Session :: set_data( 'prof.usr.menu',    $m->menuSimple() );
            Session :: set_data( 'prof.usr.acceso',  $gacl->get_user_acos($Usuario->login) );
            Session :: set_data( 'prof.usr.grupos',  $gacl->get_user_groups($Usuario->login) );
            Session :: set_data( 'prof.usr.codigo',  '' );
            if( in_array('root', Session :: get_data('prof.usr.grupos')) ){
                Session :: set_data( 'prof.usr.grupos', $gacl->get_all_groups() );
            }

            $this->redirect('inicio', 0);
        }elseif($Profesores->count_by_sql($cc) == 1){
            $Profesores = $Profesores->find_first("conditions: codigo='" . $this->post('login')."'");
            $Ciclos = new Ciclos();
            $Ciclos = $Ciclos->find_first("conditions: activo = '1' ", "order: numero DESC");
            $m = new Menu();
            $gacl = new gacl_extra();

            Session :: unset_data(
                'prof.app.busqueda',
                'prof.app.paginador',
                'prof.grp.asignados',
                'prof.usr.tutorias'
            );

            Session :: set_data( 'prof.ciclo.id',    $Ciclos->id );
            Session :: set_data( 'prof.usr.id',      $Profesores->id );
            Session :: set_data( 'prof.usr.login',   'profesor' );
            Session :: set_data( 'prof.usr.nombre',  $Profesores->nombre );
            Session :: set_data( 'prof.usr.codigo',  $Profesores->codigo );
            Session :: set_data( 'prof.usr.menu',    $m->menuSimple() );
            Session :: set_data( 'prof.usr.acceso',  $gacl->get_user_acos('profesor') );
            Session :: set_data( 'prof.usr.grupos',  $gacl->get_user_groups('profesor') );
            Session :: set_data( 'prof.usr.tutorias', $Profesores->tutordelosgrupos());

            if( in_array('root', Session :: get_data('prof.usr.grupos')) ){
                Session :: set_data( 'prof.usr.grupos', $gacl->get_all_groups() );
            }

            $this->redirect('inicio', 0);
        }else{
            $this->redirect('sesion/autenticar/' . sha1('err_login'), 0);
        }
    }

    public function autenticar() {
        if(Session :: isset_data('prof.usr.id')    &&
           Session :: isset_data('prof.usr.login') &&
           Session :: isset_data('prof.usr.nombre') )
        {
            $usr = new Usuario();
            $usr->login = Session :: get_data('prof.usr.login');
            if( $usr->login != '' &&
                $usr->login != 'anonimo')
            {
                $this->redirect('inicio');
            }
        }
    }

    public function cerrar() {
        Session::unset_data(
            'prof.app.busqueda',
            'prof.app.paginador',
            'prof.ciclo.id',
            'prof.grp.asignados',
            'prof.crs.asignados',
            'prof.usr.id',
            'prof.usr.login',
            'prof.usr.nombre',
            'prof.usr.codigo',
            'prof.usr.menu',
            'prof.usr.acceso',
            'prof.usr.grupos',
            'prof.usr.tutorias'
        );
        $this->redirect('sesion/autenticar');
    }

    public function restringir() {

    }
}
?>