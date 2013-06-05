<?php
Kumbia :: import('app.componentes.*');
Kumbia :: import('lib.kumbia.utils');
Kumbia :: import('lib.upload.main');
 Kumbia :: import('lib.excel.main');

/** SP5
 * Creado el 03/07/2008
 * Copyright (C) 2008 FiPa Software (contacto at fipasoft.com.mx)
 */

class TipopersonalController extends ApplicationController {
	public $template = "system";

	public function agregar(){
		$ciclo=new Ciclos();
		$ciclo=$ciclo->find(Session :: get_data('ciclo.id'));
		if($ciclo->abierto()){
		if($this->post('nombre') == ''){
			$this->tipos=new Tipopersonal();
			$this->tipos=$this->tipos->find();
			$this->option = 'captura';
		}else{
			$this->option = '';
			$this->error = '';
			$personal = new Tipopersonal();
			if(!$personal->exists("nombre='".$this->post('nombre')."'")){
			$personal->nombre = $this->post('nombre');
			$personal->validates_uniqueness_of('nombre');
			if(!$personal->save()){
				$this->option = 'error';
				$this->error .= ' Error al guardar en la BD.';
			}else{
				$this->option="exito";
			}
			}else{
			$this->option = 'error';
			$this->error .= ' El nombre '.$this->post('nombre').' ya esta dado de alta.';
			}
		}

		} else {
					$this->option = 'error';
					$this->error = ' El ciclo esta cerrado.';
		}
	}

	public function editar($id = ''){
		$ciclo=new Ciclos();
		$ciclo=$ciclo->find(Session :: get_data('ciclo.id'));
		if($ciclo->abierto()){
		if($id != ''){
			$this->option = 'captura';
			$id = intval($id, 10);
			$personal = new Tipopersonal();
			$this->personal = $personal->find($id);
			if($this->personal->id == ''){
				$this->option = 'error';
				$this->error = ' El id del tipo de personal no existe.';
			}

		}else if($this->post('id') != ''){
				$this->option = '';
				$this->error = '';
				$personal = new Tipopersonal();
				if(!$personal->exists("nombre='".$this->post('nombre')."'")){
				$personal = $personal->find($this->post('id'));
				if($personal->id != ''){
					$personal->nombre = $this->post('nombre');

					if($personal->save()){
						$this->option = 'exito';
					}else{
						$this->option = 'error';
						$this->error .= ' Error al guardar en la BD.';
					}
			}else{
				$this->option = 'error';
				$this->error = ' El tipo de personal no existe.';
			}
			}else{
				$this->option = 'error';
				$this->error .= ' El nombre '.$this->post('nombre').' ya esta dado de alta.';
			}
		}else{
			$this->option = 'error';
			$this->error = ' El tipo de personal no existe.';
		}
		} else {
					$this->option = 'error';
					$this->error = ' El ciclo esta cerrado.';
		}
	}

	public function eliminar($id = ''){
		$ciclo=new Ciclos();
		$ciclo=$ciclo->find(Session :: get_data('ciclo.id'));
		if($ciclo->abierto()){
		if($id != ''){
			$this->option = 'captura';
			$id = intval($id, 10);
			$personal = new Tipopersonal();
			$this->personal = $personal->find($id);
			if($this->personal->id == ''){
				$this->option = 'error';
				$this->error = ' El id del tipo de personal no existe.';
			}
		}else if($this->post('id') != ''){
			$this->option = '';
			$this->error = '';
			$personal=new Personal();
			if(!$personal->exists("tipopersonal_id='".$this->post('id')."'")){
			$personal = new Tipopersonal();
			$personal = $personal->find($this->post('id'));
			$f = $personal->foto;
			if($personal->id != ''){
				// eliminado el profesor
				if($personal->delete($this->post('id'))){
					$this->option = 'exito';

				}else{
					$this->option = 'error';
					$this->error .= ' Error al intentar eliminar de la BD.';
				}


			}else{
				$this->option = 'error';
				$this->error = ' El id del tipo de personal no existe.';
			}
			}else{
				$this->option = 'error';
				$this->error = ' El id del tipo esta ligado con personal. Cambie el tipo a el personal primero.';
			}
		}else{
			$this->option = 'error';
			$this->error = ' No se especific&oacute; el tipo de personal a eliminar.';
		}
		} else {
					$this->option = 'error';
					$this->error = ' El ciclo esta cerrado.';
		}
	}

	public function index($pag = ''){
		$elementos = new Personal();
		$controlador = $this->controlador;
		$accion = $this->accion;
		$path = $this->path = KUMBIA_PATH;

		// busqueda
		$b = new Busqueda($controlador, $accion);


		$this->c=$c = $b->condicion();
		$this->busqueda = $b;

		$tipos=new Tipopersonal();
		$this->registros=$tipos->count(($c == '1' ? '' : $c));

		// paginacion
		$paginador = new Paginador($controlador, $accion);
		if($pag != ''){
			$paginador->guardarPagina($pag);
		}
		$paginador->estableceRegistros($this->registros);
		$paginador->generar();
		$this->paginador = $paginador;

		// ejecuta la consulta
		$tipos=new Tipopersonal();
		$this->tipos=$tipos->find_all_by_sql(
		"SELECT * FROM tipopersonal WHERE ".
		($c == '' ? '1' : $c));

		// acl
		$usr_login = Session :: get_data('usr.login');
		$this->acl = array ();
		$acl = new gacl_extra();
		$acos_arr = array (
			'tipopersonal' => array (
				'agregar',
				'editar',
				'eliminar',
				'index',
				'ver',
				'exportar',
				'buscar'
			)
		);
		$this->acl = $acl->acl_check_multiple($acos_arr, $usr_login);
		$this->acl=$this->acl["tipopersonal"];
	}

	public function ver($id = ''){
		$id = intval($id, 10);
		$Personal = new Personal();
		$personal = $Personal->find($id);
		$personal->fnacimiento = Utils :: fecha_convertir($personal->fnacimiento);
		$personal->fnacimiento = str_replace('-', '/', $personal->fnacimiento);
		if($personal->sexo != ''){
			$personal->sexo = ($personal->sexo == 'H' ? 'Hombre' : 'Mujer');
		}
		$this->personal = $personal;
		$tipo=new Tipopersonal();
		$tipo=$tipo->find_first($personal->tipopersonal_id);
		$this->tipo=$tipo;

		$usr_login = Session :: get_data('usr.login');
		$this->acl = array ();
		$acl = new gacl_extra();
		$acos_arr = array (
			'personal' => array (
				'editar',
				'eliminar'
			)
		);
		$this->acl = $acl->acl_check_multiple($acos_arr, $usr_login);
		$this->acl=$this->acl["personal"];
	}


}
?>