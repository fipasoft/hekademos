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
		$c =  "AND pass = '" . sha1($this->post('pass')) . "' " .
			  "AND login = '" . $this->post('login') . "' " ;
		$sql="SELECT count(*) FROM usuarios
			INNER JOIN sistemasusuarios ON usuarios.id=sistemasusuarios.usuarios_id
			WHERE sistemasusuarios.sistemas_id='1' ".$c;

		if($Usuarios->count_by_sql($sql) == 1){
			$sql="SELECT usuarios.* FROM usuarios
			INNER JOIN sistemasusuarios ON usuarios.id=sistemasusuarios.usuarios_id
			WHERE sistemasusuarios.sistemas_id='1' ".$c;

			$Usuario = $Usuarios->find_all_by_sql($sql);
			$Usuario=$Usuario[0];

			$Ciclos = new Ciclos();
			$Ciclos = $Ciclos->find_first("conditions: activo = '1' ", "order: numero DESC");
			
			$m = new Menu();
			$gacl = new gacl_extra();

			Session :: unset_data(
				'app.busqueda',	
				'app.paginador',
				'grp.asignados'
			);

			Session :: set_data( 'ciclo.id',    $Ciclos->id );
			Session :: set_data( 'usr.id',      $Usuario->id );
			Session :: set_data( 'usr.login',   $Usuario->login );
			Session :: set_data( 'usr.nombre',  $Usuario->nombre );
			Session :: set_data( 'usr.menu',    $m->menuSimple() );
			Session :: set_data( 'usr.acceso',  $gacl->get_user_acos($Usuario->login) );
			Session :: set_data( 'usr.grupos',  $gacl->get_user_groups($Usuario->login) );

			if( in_array('root', Session :: get_data('usr.grupos')) ){
				Session :: set_data( 'usr.grupos', $gacl->get_all_groups() );
			}
			
			
				$hoy = new Datetime();
					
				$visita = new Visitas();
				$visita->usuarios_id=$Usuario->id;
				$visita->fecha = $hoy->format("Y-m-d H:i:s");;
				$visita->tipo = 'e';
				$visita->save();

			$this->redirect('inicio', 0);
		}else{
			$this->redirect('sesion/autenticar/' . sha1('err_login'), 0);
		}
	}

	public function autenticar() {
		if(Session :: isset_data('usr.id')    &&
		   Session :: isset_data('usr.login') &&
		   Session :: isset_data('usr.nombre') )
		{
			$usr = new Usuario();
			$usr->login = Session :: get_data('usr.login');
			if( $usr->login != '' &&
				$usr->login != 'anonimo')
			{
				$this->redirect('inicio');
			}
		}
	}

	public function cerrar() {
		$hoy = new Datetime();
		$visita = new Visitas();
		$visita->usuarios_id = Session :: get_data( 'usr.id');
		$visita->fecha = $hoy->format("Y-m-d H:i:s");;
		$visita->tipo = 's';
		$visita->save();
		Session::unset_data(
			'app.busqueda',
			'app.paginador',
			'ciclo.id',
			'grp.asignados',
			'crs.asignados',
			'usr.id',
			'usr.login',
			'usr.nombre',
			'usr.menu',
			'usr.acceso',
			'usr.grupos',
			'sqlserver.tablas',
			'es.inconsistencias.fecha',
			'optativas.alumnos.tab'
		);
		$this->redirect('sesion/autenticar');
	}

	public function restringir() {

	}
}
?>