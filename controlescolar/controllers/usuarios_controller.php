<?php
Kumbia :: import('app.componentes.*');
Kumbia :: import('lib.phpgacl.main');

/** SP5
 * Creado el 03/07/2008
 * Copyright (C) 2008 FiPa Software (contacto at fipasoft.com.mx)
 */

/*
class UsuariosController extends ApplicationController {	public $template = "system";

	public function agregar(){
		if($this->post('nombre') == ''){
			$this->option = 'captura';
			$gacl_x = new gacl_extra();
			$this->grupos = $gacl_x->get_all_groups();
		}else{
			$this->option = '';
			$this->error = '';
			$usuario = new Usuarios();
			$usuario->nombre = $this->post('nombre');
			$usuario->ap = $this->post('ap');
			$usuario->am = $this->post('am');
			$usuario->mail = $this->post('mail');
			$usuario->login = $this->post('login');
			$usuario->pass = sha1($this->post('pass'));
			$usuario->validates_uniqueness_of('login');
			if($usuario->save()){
				$this->option = 'exito';
				$gacl = new gacl_api();
				$grupos='';
				foreach($this->post('grupo') as $grupo){
					if($gacl->add_object('usuarios', $usuario->login, $usuario->login, 0, 0, 'ARO')){
						if(!$gacl->add_group_object($grupo, 'usuarios', $usuario->login)){
							$this->option = 'error';
							$this->error .= ' No se pudo agregar el usuario al grupo seleccionado.';

						}else{
						$data=$gacl->get_group_data($grupo);
						$grupos=$data[3].',';
						}
					}else{
						$this->option = 'error';
						$this->error .= ' No se pudo crear el ARO en la lista ACL.';
					}

				}
				$grupos=substr($grupos,0,strlen($grupos)-1);
				$gacl=new gacl_extra();
				$gacl->dbReset();

				$historial=new Historial();
				$historial->ciclos_id= Session :: get_data('ciclo.id');
				$historial->usuario=Session :: get_data('usr.login');
				$historial->descripcion="Se agrego el usuario ".$usuario->login." a los grupos: ".$grupos;
				$historial->controlador="usuarios";
				$historial->accion="agregar";
				$historial->saved_at=date("Y-m-d H:i:s");//2009-01-20 14:28:29
				$historial->save();
			}else{
				$this->option = 'error';
				$this->error .= ' Error al guardar en la BD.';
			}
		}
	}

	public function editar($id = ''){
		if($id != ''){
			$this->option = 'captura';
			$id = intval($id, 10);
			$sistema=new Sistemas();
			if(!$sistema->usuariodelsistema(1,$id)){
			$Usuarios = new Usuarios();
			$this->usuario = $Usuarios->find($id);
			$gacl_x = new gacl_extra();
			$usr_grupos = $gacl_x->get_user_groups($this->usuario->login);
			$this->usr_grupo = $usr_grupos[0];
			$this->grupos = $gacl_x->get_all_groups();
			if($this->usuario->id == ''){
				$this->option = 'error';
				$this->error = ' El usuario no existe.';
			}
			}else{
			$this->option = 'error';
			$this->error = ' El usuario especificado no existe.';
			}
		}else if($this->post('nombre') != ''){
			$this->option = '';
			$this->error = '';
			$usuario = new Usuarios();
			$usuario = $usuario->find($this->post('id'));
			$usuario->nombre = $this->post('nombre');
			$usuario->ap = $this->post('ap');
			$usuario->am = $this->post('am');
			$usuario->mail = $this->post('mail');
			if($usuario->id != ''){
				if($usuario->save()){
					$this->option = 'exito';
					$gacl = new gacl_api();
					$aro = $gacl->get_object_id('usuarios', $usuario->login, 'ARO');
					$gacl->del_object($aro, 'ARO', TRUE);
					$grupos='';
					foreach($this->post('grupo') as $grupo){
						if($gacl->add_object('usuarios', $usuario->login, $usuario->login, 0, 0, 'ARO')){
							if(!$gacl->add_group_object($grupo, 'usuarios', $usuario->login)){
								$this->option = 'error';
								$this->error .= ' No se pudo agregar el usuario al grupo seleccionado.';
							}else{
								$data=$gacl->get_group_data($grupo);
								$grupos=$data[3].',';
							}
						}else{
							$this->option = 'error';
							$this->error .= ' No se pudo crear el ARO en la lista ACL.';
						}

					}
						$grupos=substr($grupos,0,strlen($grupos)-1);
						$gacl=new gacl_extra();
							$gacl->dbReset();
							if($grupos!=''){
							$historial=new Historial();
							$historial->ciclos_id= Session :: get_data('ciclo.id');
							$historial->usuario=Session :: get_data('usr.login');
							$historial->descripcion="Se edito el usuario ".$usuario->login.", sus grupos actuales son: ".$grupos;
							$historial->controlador="usuarios";
							$historial->accion="agregar";
							$historial->saved_at=date("Y-m-d H:i:s");//2009-01-20 14:28:29
							$historial->save();
							}
				}else{
					$this->option = 'error';
					$this->error .= ' Error al guardar en la BD.';
				}
			}else{
				$this->option = 'error';
				$this->error = ' El usuario no existe.';
			}
		}else{
			$this->option = 'error';
			$this->error = ' El usuario no existe.';
		}
	}

	public function eliminar($id = ''){
		if($id != ''){
			$this->option = 'captura';
			$id = intval($id, 10);
			$Usuarios = new Usuarios();
			$this->usuario = $Usuarios->find($id);
			if($this->usuario->id == ''){
				$this->option = 'error';
				$this->error = ' El usuario no existe.';
			}
		}else if($this->post('id') != ''){
			$this->option = '';
			$this->error = '';
			$Usuarios = new Usuarios();
			$usuario = $Usuarios->find($this->post('id'));
			if($usuario->id != ''){
				// eliminado el usuario
				$login = $usuario->login;
				if($Usuarios->delete($this->post('id'))){
					$this->option = 'exito';
					$historial=new Historial();
					$historial->ciclos_id= Session :: get_data('ciclo.id');
					$historial->usuario=Session :: get_data('usr.login');
					$historial->descripcion="Se elimino el usuario ".$usuario->login;
					$historial->controlador="usuarios";
					$historial->accion="agregar";
					$historial->saved_at=date("Y-m-d H:i:s");//2009-01-20 14:28:29
					$historial->save();
				}else{
					$this->option = 'error';
					$this->error .= ' Error al intentar eliminar de la BD.';
				}

				// eliminandolo de sus grupos en ACL
				$gacl = new gacl_api();
				$aro = $gacl->get_object_id('usuarios', $login, 'ARO');
				// eliminadolo de la lista ACL
				if(!$gacl->del_object($aro, 'ARO', TRUE)){
					$this->option = 'error';
					$this->error .= ' No se pudo eliminar de la lista ACL.';
				}
			}else{
				$this->option = 'error';
				$this->error = ' El usuario no existe.';
			}
		}else{
			$this->option = 'error';
			$this->error = ' No se especific&oacute; el usuario a eliminar.';
		}
	}


	public function index($pag = ''){
		$Usuarios = new Usuarios();
		$controlador = $this->controlador;
		$accion = $this->accion;
		$path = $this->path = KUMBIA_PATH;
		$this->gacl_x = new gacl_extra();

		// busqueda
		$b = new Busqueda($controlador, $accion);
		$b->campos();
		$b->establecerCondicion(
					'nombre',
					"CONCAT(nombre, ' ', ap, ' ', am) LIKE '%" . $b->campo('nombre') . "%' "
		);
		// genera las condiciones
		$c = $b->condicion();
		$this->busqueda = $b;

		// cuenta todos los registros
		$sql="SELECT count(*) FROM usuarios
			INNER JOIN sistemasusuarios ON usuarios.id=sistemasusuarios.usuarios_id
			WHERE sistemasusuarios.sistemas_id='1' ". ($c == "" ? "" : " AND ".$c);

		$this->registros = $Usuarios->count_by_sql($sql);

		// paginacion
		$paginador = new Paginador($controlador, $accion);
		if($pag != ''){
			$paginador->guardarPagina($pag);
		}
		$paginador->estableceRegistros($this->registros);
		$paginador->generar();
		$this->paginador = $paginador;

		// ejecuta la consulta
				$sql="SELECT usuarios.* FROM usuarios
			INNER JOIN sistemasusuarios ON usuarios.id=sistemasusuarios.usuarios_id
			WHERE sistemasusuarios.sistemas_id='1' ". ($c == "" ? "" : " AND ".$c).
			"ORDER BY ap,am,nombre ".
			"LIMIT ". ($paginador->pagina() * $paginador->rpp()) . ', '
									  . $paginador->rpp();

			$this->usuarios = $Usuarios->find_all_by_sql($sql);
	}


	public function password($id = ''){
		if($id != ''){
			$this->option = 'captura';
			$id = intval($id, 10);
			$Usuarios = new Usuarios();
			$this->usuario = $Usuarios->find($id);
			if($this->usuario->id == ''){
				$this->option = 'error';
				$this->error = ' El usuario no existe.';
			}
		}else if($this->post('pass') != ''){
			$this->option = '';
			$this->error = '';
			$usuario = new Usuarios();
			$usuario = $usuario->find($this->post('id'));
			if($usuario->id != ''){
				if($this->post('pass') == $this->post('pass2')){
					if(strlen($this->post('pass')) >= 6){
						$usuario->pass = sha1($this->post('pass'));
						if($usuario->save()){
							$this->option = 'exito';
							$historial=new Historial();
							$historial->ciclos_id= Session :: get_data('ciclo.id');
							$historial->usuario=Session :: get_data('usr.login');
							$historial->descripcion="Se cambio el password del usuario ".$usuario->login;
							$historial->controlador="usuarios";
							$historial->accion="agregar";
							$historial->saved_at=date("Y-m-d H:i:s");//2009-01-20 14:28:29
							$historial->save();
						}else{
							$this->option = 'error';
							$this->error .= ' Error al guardar en la BD.';
						}
					}else{
						$this->option = 'error';
						$this->error .= ' La longitud m&iacute;nima del password es de 6 caracteres.';
					}
				}else{
					$this->option = 'error';
					$this->error .= ' No coincide la confirmaci&oacute;n del password.';
				}
			}else{
				$this->option = 'error';
				$this->error = ' El usuario no existe.';
			}
		}else{
			$this->option = 'error';
			$this->error = ' No se especific&oacute; el usuario.';
		}
	}

	public function validarLogin(){
		$this->set_response('view');
		$this->login = $login = $this->post('login');
		if($login != ''){
			$usuarios = new Usuarios();
			if($usuarios->count("login = '" . $login . "'") == 0){
				$this->disponible = true;
			}else{
				$this->disponible = false;
			}
		}else{
			$this->disponible = false;
		}
	}

	public function ver($id = ''){
		$id = intval($id, 10);
		$Usuarios = new Usuarios();
		$this->usuario = $Usuarios->find($id);
		$m = new Menu();
		$this->acceso = $m->menuPrincipal($this->usuario->login);
		$gacl_x = new gacl_extra();
		$this->grupos = $gacl_x->get_user_groups($this->usuario->login);
	}

	public function verAcceso(){
		$this->set_response('view');
		$this->grupo = $grupo = strtolower($this->post('grupo'));
		if($grupo != ''){
			$gacl_x = new gacl_extra();
			$this->acceso = $gacl_x->get_group_acos($grupo);
		}else{
			$this->acceso = array();
		}
	}
}*/


/** SP5
 * Creado el 03/07/2008
 * Copyright (C) 2008 FiPa Software (contacto at fipasoft.com.mx)
 */

class UsuariosController extends ApplicationController {
	public $template = "system";

	public function agregar(){
		if($this->post('nombre') == ''){
			$this->option = 'captura';
			$gacl_x = new gacl_extra();
			$this->grupos = $gacl_x->get_all_groups();
		}else{
			$this->option = '';
			$this->error = '';
			$sistema=new Sistemas();
			if(!$sistema->existslogin(1,$this->post('login'))){
			mysql_query("BEGIN");
			$usuario = new Usuarios();
			$usuario->nombre = $this->post('nombre');
			$usuario->ap = $this->post('ap');
			$usuario->am = $this->post('am');
			$usuario->mail = $this->post('mail');
			$usuario->login = $this->post('login');
			$usuario->pass = sha1($this->post('pass'));
			//$usuario->validates_uniqueness_of('login');
			if($usuario->save()){
				$sistemausuario=new Sistemasusuarios();
				$sistemausuario->usuarios_id=$usuario->id;
				$sistemausuario->sistemas_id='1';
				if($sistemausuario->save()){
				mysql_query("COMMIT");
				$this->option = 'exito';
				$gacl = new gacl_api();
				$grupos='';
				foreach($this->post('grupo') as $grupo){
					if($gacl->add_object('usuarios', $usuario->login, $usuario->login, 0, 0, 'ARO')){
						if(!$gacl->add_group_object($grupo, 'usuarios', $usuario->login)){
							$this->option = 'error';
							$this->error .= ' No se pudo agregar el usuario al grupo seleccionado.';

						}else{
						$data=$gacl->get_group_data($grupo);
						$grupos=$data[3].',';
						}
					}else{
						$this->option = 'error';
						$this->error .= ' No se pudo crear el ARO en la lista ACL.';
					}

				}
				$grupos=substr($grupos,0,strlen($grupos)-1);
				$gacl=new gacl_extra();
				$gacl->dbReset();

				$cl=new Ciclos();
				$historial=new Historial();
				$historial->ciclos_id= $cl->id;
				$historial->usuario=Session :: get_data('usr.login');
				$historial->descripcion="Se agrego el usuario ".$usuario->login." a los grupos: ".$grupos;
				$historial->controlador="usuarioscoordinacion";
				$historial->accion="agregar";
				$historial->saved_at=date("Y-m-d H:i:s");//2009-01-20 14:28:29
				$historial->save();
			}else{
				$this->option = 'error';
				$this->error .= ' Error al guardar en la BD.';
				mysql_query("ROLLBACK");
			}
			}else{
				$this->option = 'error';
				$this->error .= ' Error al guardar en la BD.';
				mysql_query("ROLLBACK");
			}
			}else{
				$this->option = 'error';
				$this->error .= ' El nombre de usuario ya existe, seleccione otro.';

			}
		}
	}

	public function editar($id = ''){
		if($id != ''){
			$this->option = 'captura';
			$id = intval($id, 10);
			$sistema=new Sistemas();
			if($sistema->usuariodelsistema(1,$id)){
			$Usuarios = new Usuarios();
			$this->usuario = $Usuarios->find($id);
			$gacl_x = new gacl_extra();
			$usr_grupos = $gacl_x->get_user_groups($this->usuario->login);
			$this->usr_grupo = $usr_grupos[0];
			$this->grupos = $gacl_x->get_all_groups();
			if($this->usuario->id == ''){
				$this->option = 'error';
				$this->error = ' El usuario no existe.';
			}
			}else{
			$this->option = 'error';
			$this->error = ' El usuario especificado no existe.';
			}
		}else if($this->post('nombre') != ''){
			$this->option = '';
			$this->error = '';
			$sistema=new Sistemas();
			if($sistema->usuariodelsistema(1,$this->post('id'))){
			$usuario = new Usuarios();
			$usuario = $usuario->find($this->post('id'));
			$usuario->nombre = $this->post('nombre');
			$usuario->ap = $this->post('ap');
			$usuario->am = $this->post('am');
			$usuario->mail = $this->post('mail');
			if($usuario->id != ''){
				if($usuario->save()){
					$this->option = 'exito';
					$gacl = new gacl_api();
					$aro = $gacl->get_object_id('usuarios', $usuario->login, 'ARO');
					$gacl->del_object($aro, 'ARO', TRUE);
					$grupos='';
					foreach($this->post('grupo') as $grupo){
						if($gacl->add_object('usuarios', $usuario->login, $usuario->login, 0, 0, 'ARO')){
							if(!$gacl->add_group_object($grupo, 'usuarios', $usuario->login)){
								$this->option = 'error';
								$this->error .= ' No se pudo agregar el usuario al grupo seleccionado.';
							}else{
								$data=$gacl->get_group_data($grupo);
								$grupos=$data[3].',';
							}
						}else{
							$this->option = 'error';
							$this->error .= ' No se pudo crear el ARO en la lista ACL.';
						}

					}
						$grupos=substr($grupos,0,strlen($grupos)-1);
						$gacl=new gacl_extra();
							$gacl->dbReset();
							if($grupos!=''){
							$cl=new Ciclos();
							$historial=new Historial();
							$historial->ciclos_id= $cl->id;
							$historial->usuario=Session :: get_data('usr.login');
							$historial->descripcion="Se edito el usuario ".$usuario->login.", sus grupos actuales son: ".$grupos;
							$historial->controlador="usuarioscoordinacion";
							$historial->accion="agregar";
							$historial->saved_at=date("Y-m-d H:i:s");//2009-01-20 14:28:29
							$historial->save();
							}
				}else{
					$this->option = 'error';
					$this->error .= ' Error al guardar en la BD.';
				}
			}else{
				$this->option = 'error';
				$this->error = ' El usuario no existe.';
			}
			}else{
			$this->option = 'error';
			$this->error = ' El usuario especificado no existe.';
			}
		}else{
			$this->option = 'error';
			$this->error = ' El usuario no existe.';
		}
	}
	public function eliminar($id = ''){
		if($id != ''){
			$sistema=new Sistemas();
			if($sistema->usuariodelsistema(1,$id)){
			$this->option = 'captura';
			$id = intval($id, 10);
			$Usuarios = new Usuarios();
			$this->usuario = $Usuarios->find($id);
			if($this->usuario->id == ''){
				$this->option = 'error';
				$this->error = ' El usuario no existe.';
			}
			}else{
			$this->option = 'error';
			$this->error = ' El usuario especificado no existe.';
			}
		}else if($this->post('id') != ''){
			$this->option = '';
			$this->error = '';

			$sistema=new Sistemas();
			if($sistema->usuariodelsistema(1,$this->post('id'))){
			$Usuarios = new Usuarios();
			$usuario = $Usuarios->find($this->post('id'));
			if($usuario->id != ''){
				// eliminado el usuario
				mysql_query("BEGIN");
				$login = $usuario->login;
				$usuariosistema=new Sistemasusuarios();
				if($usuariosistema->delete("usuarios_id='".$this->post('id')."'")){
				if($Usuarios->delete($this->post('id'))){

					$this->option = 'exito';

					$cl=new Ciclos();
					$historial=new Historial();
					$historial->ciclos_id= $cl->id;
					$historial->usuario=Session :: get_data('usr.login');
					$historial->descripcion="Se elimino el usuario ".$usuario->login;
					$historial->controlador="usuarioscoordinacion";
					$historial->accion="agregar";
					$historial->saved_at=date("Y-m-d H:i:s");//2009-01-20 14:28:29
					$historial->save();
					mysql_query("COMMIT");

					// eliminandolo de sus grupos en ACL
					$gacl = new gacl_api();
					$aro = $gacl->get_object_id('usuarios', $login, 'ARO');
					// eliminadolo de la lista ACL
					if(!$gacl->del_object($aro, 'ARO', TRUE)){
						$this->option = 'error';
						$this->error .= ' No se pudo eliminar de la lista ACL.';
					}


				}else{
					$this->option = 'error';
					$this->error .= ' Error al intentar eliminar de la BD.';
					mysql_query("ROLLBACK");
				}


			}else{
				$this->option = 'error';
				$this->error = 'Error al intentar eliminar de la BD.';
				mysql_query("ROLLBACK");
			}
			}else{
				$this->option = 'error';
				$this->error = ' El usuario no existe.';
			}
			}else{
			$this->option = 'error';
			$this->error = ' El usuario especificado no existe.';
			}
		}else{
			$this->option = 'error';
			$this->error = ' No se especific&oacute; el usuario a eliminar.';
		}
	}

	public function index($pag = ''){
		$Usuarios = new Usuarios();
		$controlador = $this->controlador;
		$accion = $this->accion;
		$path = $this->path = KUMBIA_PATH;
		$this->gacl_x = new gacl_extra();

		// busqueda
		$b = new Busqueda($controlador, $accion);
		$b->campos();
		$b->establecerCondicion(
					'nombre',
					"CONCAT(nombre, ' ', ap, ' ', am) LIKE '%" . $b->campo('nombre') . "%' "
		);
		// genera las condiciones
		$c = $b->condicion();
		$this->busqueda = $b;

		// cuenta todos los registros
		$sql="SELECT count(*) FROM usuarios
			INNER JOIN sistemasusuarios ON usuarios.id=sistemasusuarios.usuarios_id
			WHERE sistemasusuarios.sistemas_id='1' ". ($c == "" ? "" : " AND ".$c);

		$this->registros = $Usuarios->count_by_sql($sql);

		// paginacion
		$paginador = new Paginador($controlador, $accion);
		if($pag != ''){
			$paginador->guardarPagina($pag);
		}
		$paginador->estableceRegistros($this->registros);
		$paginador->generar();
		$this->paginador = $paginador;

		// ejecuta la consulta
				$sql="SELECT usuarios.* FROM usuarios
			INNER JOIN sistemasusuarios ON usuarios.id=sistemasusuarios.usuarios_id
			WHERE sistemasusuarios.sistemas_id='1' ". ($c == "" ? "" : " AND ".$c).
			"ORDER BY ap,am,nombre ".
			"LIMIT ". ($paginador->pagina() * $paginador->rpp()) . ', '
									  . $paginador->rpp();

			$this->usuarios = $Usuarios->find_all_by_sql($sql);
	}

	public function password($id = ''){
		if($id != ''){
			$sistema=new Sistemas();
			if($sistema->usuariodelsistema(1,$id)){
			$this->option = 'captura';
			$id = intval($id, 10);
			$Usuarios = new Usuarios();
			$this->usuario = $Usuarios->find($id);
			if($this->usuario->id == ''){
				$this->option = 'error';
				$this->error = ' El usuario no existe.';
			}
			}else{
			$this->option = 'error';
			$this->error = ' El usuario especificado no existe.';
			}
		}else if($this->post('pass') != ''){
			$this->option = '';
			$this->error = '';
			$sistema=new Sistemas();
			if($sistema->usuariodelsistema(1,$this->post('id'))){
			$usuario = new Usuarios();
			$usuario = $usuario->find($this->post('id'));
			if($usuario->id != ''){
				if($this->post('pass') == $this->post('pass2')){
					if(strlen($this->post('pass')) >= 6){
						$usuario->pass = sha1($this->post('pass'));
						if($usuario->save()){
							$this->option = 'exito';

							$cl=new Ciclos();
							$historial=new Historial();
							$historial->ciclos_id= $cl->id;
							$historial->usuario=Session :: get_data('usr.login');
							$historial->descripcion="Se cambio el password del usuario ".$usuario->login;
							$historial->controlador="usuarioscoordinacion";
							$historial->accion="agregar";
							$historial->saved_at=date("Y-m-d H:i:s");//2009-01-20 14:28:29
							$historial->save();
						}else{
							$this->option = 'error';
							$this->error .= ' Error al guardar en la BD.';
						}
					}else{
						$this->option = 'error';
						$this->error .= ' La longitud m&iacute;nima del password es de 6 caracteres.';
					}
				}else{
					$this->option = 'error';
					$this->error .= ' No coincide la confirmaci&oacute;n del password.';
				}
			}else{
				$this->option = 'error';
				$this->error = ' El usuario no existe.';
			}
			}else{
			$this->option = 'error';
			$this->error = ' El usuario especificado no existe.';
			}
		}else{
			$this->option = 'error';
			$this->error = ' No se especific&oacute; el usuario.';
		}
	}

	public function validarLogin(){
		$this->set_response('view');
		$this->login = $login = $this->post('login');
		if($login != ''){
			$usuarios = new Usuarios();
			$sql="SELECT count(*) FROM usuarios
			INNER JOIN sistemasusuarios ON usuarios.id=sistemasusuarios.usuarios_id
			WHERE sistemasusuarios.sistemas_id='1' AND login = '" . $login . "'";

		if($usuarios->count_by_sql($sql) == 0){
				$this->disponible = true;
			}else{
				$this->disponible = false;
			}
		}else{
			$this->disponible = false;
		}
	}

	public function ver($id = ''){
		$id = intval($id, 10);
		$sistema=new Sistemas();
		$this->usuario=null;
		if($sistema->usuariodelsistema(1,$id)){
		$Usuarios = new Usuarios();
		$this->usuario = $Usuarios->find($id);
		$m = new Menu();
		$this->acceso = $m->menuPrincipal($this->usuario->login);
		$gacl_x = new gacl_extra();
		$this->grupos = $gacl_x->get_user_groups($this->usuario->login);
		}else{
			$this->option = 'error';
			$this->error = ' El usuario especificado no existe.';
		}
	}

	public function verAcceso(){
		$this->set_response('view');
		$this->grupo = $grupo = strtolower($this->post('grupo'));
		if($grupo != ''){
			$gacl_x = new gacl_extra();
			$this->acceso = $gacl_x->get_group_acos($grupo);
		}else{
			$this->acceso = array();
		}
	}
}
?>
