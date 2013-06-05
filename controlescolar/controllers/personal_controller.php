<?php
Kumbia :: import('app.componentes.*');
Kumbia :: import('lib.kumbia.utils');
Kumbia :: import('lib.upload.main');
 Kumbia :: import('lib.excel.main');

/** SP5
 * Creado el 03/07/2008
 * Copyright (C) 2008 FiPa Software (contacto at fipasoft.com.mx)
 */

class PersonalController extends ApplicationController {
	public $template = "system";

	public function agregar(){
		$ciclo=new Ciclos();
		$ciclo=$ciclo->find(Session :: get_data('ciclo.id'));
		if($ciclo->abierto()){
		if($this->post('codigo') == ''){
			$this->tipos=new Tipopersonal();
			$this->tipos=$this->tipos->find();
			$this->option = 'captura';
		}else{
			$this->option = '';
			$this->error = '';
			$alumnos = new Alumnos();
			$profesores=new Profesores();
			$personal = new Personal();
			if($alumnos->count("codigo = '" . $this->post('codigo') . "'") == 0 &&
				$profesores->count("codigo = '" . $this->post('codigo') . "'") == 0 &&
				$personal->count("codigo = '" . $this->post('codigo') . "'") == 0)
				{
			$personal = new Personal();
			$personal->codigo = $this->post('codigo');
			$personal->nombre = $this->post('nombre');
			$personal->ap = $this->post('ap');
			$personal->am = $this->post('am');
			$personal->domicilio = $this->post('domicilio');
			$personal->tel = $this->post('tel');
			$personal->cel = $this->post('cel');
			$personal->mail = $this->post('mail');
			$personal->rfc = $this->post('rfc');
			$personal->curp = $this->post('curp');
			$personal->fnacimiento = Utils :: fecha_convertir($this->post('fnacimiento'));
			$personal->sexo = $this->post('sexo');
			$personal->foto = '';
			$personal->tipopersonal_id=$this->post('tipopersonal_id');
			$personal->validates_uniqueness_of('codigo');
			if($personal->save()){
				$Personal = new Personal();
				$Personal = $Personal->find_first($personal->id);
				$this->option = 'exito';
				// guardando img en el servidor
				if($_FILES['foto']['tmp_name'] != ''){
					$img = new Upload($_FILES['foto'], 'es_ES');
					if($img->uploaded){
						$Personal->foto = $Personal->codigo . '.jpg';
						$img->image_convert 		=  'jpg';
						$img->jpeg_quality 			=  100;
						$img->file_new_name_body  	=  $Personal->codigo;
						$img->image_resize          =  true;
		        		$img->image_ratio_y         =  true;
		        		$img->image_x               =  175;
						$img->file_overwrite		=  true;
						$img->file_auto_rename		=  false;
		        		$img->Process('./public/img/personal');
		        		if (!$img->processed){
		        			$Personal->foto = '';
		        			$this->option = 'error';
							$this->error .= 'No se pudo procesar el archivo de imagen: ' . $img->error;
		        		}
		        		// guardando el path de la imagen
						if(!$Personal->save()){
							$this->option = 'error';
							$this->error .= ' Error al guardar la direcci&oacute;n de la imagen en la BD.';
						}
					}else{
						$this->option = 'error';
						$this->error .= 'No se pudo subir el archivo de imagen: ' . $img->error;
					}
				}

			}else{
				$this->option = 'error';
				$this->error .= ' Error al guardar en la BD.';
			}
		}else{
				$this->option = 'error';
				$this->error .= ' <br/>El codigo '.$this->post('codigo').' ya existe.';
			}
		}
		} else {
					$this->option = 'error';
					$this->error = ' El ciclo esta cerrado.';
		}
	}

	public function disponible(){
		$this->valor = $valor = $this->post('valor');
		$this->invalido = false;
		$this->disponible = false;
		$this->set_response("view");
		if($valor != ''){
			$alumnos = new Alumnos();
			$profesores=new Profesores();
			$personal=new Personal();
			if($alumnos->count("codigo = '" . $valor . "'") == 0 &&
				$profesores->count("codigo = '" . $valor . "'") == 0 &&
				$personal->count("codigo = '" . $valor . "'") == 0)
				{
				$year = explode('-',$valor);
				$this->disponible = true;
			}
		}
	}

	public function editar($id = ''){
		$ciclo=new Ciclos();
		$ciclo=$ciclo->find(Session :: get_data('ciclo.id'));
		if($ciclo->abierto()){
		if($id != ''){
			$this->option = 'captura';
			$id = intval($id, 10);
			$personal = new Personal();
			$this->personal = $personal->find($id);
			if($this->personal->id == ''){
				$this->option = 'error';
				$this->error = ' El id del personal no existe.';
			}else{
				$this->personal->fnacimiento = str_replace('-', '/', Utils :: fecha_convertir($this->personal->fnacimiento));
				if(strcmp($this->personal->fnacimiento, '00/00/0000') == 0){
					$this->personal->fnacimiento = '';
				}
			}
		}else if($this->post('id') != ''){
				$this->option = '';
				$this->error = '';
				$personal = new Personal();
				$personal = $personal->find($this->post('id'));
				if($personal->id != ''){
					$personal->nombre = $this->post('nombre');
					$personal->ap = $this->post('ap');
					$personal->am = $this->post('am');
					$personal->domicilio = $this->post('domicilio');
					$personal->tel = $this->post('tel');
					$personal->cel = $this->post('cel');
					$personal->mail = $this->post('mail');
					$personal->rfc = $this->post('rfc');
					$personal->curp = $this->post('curp');
					$personal->fnacimiento = Utils :: fecha_convertir($this->post('fnacimiento'));
					$personal->sexo = $this->post('sexo');
					$personal->tipopersonal_id=$this->post('tipopersonal_id');

					if($personal->save()){
						$this->option = 'exito';
						// guardando img en el servidor
						if($this->post('cambiarImagen') == 'true'){
							$img = new Upload($_FILES['foto'], 'es_ES');
							if($img->uploaded){
								$personal->foto = $personal->codigo . '.jpg';
								$img->image_convert 		=  'jpg';
								$img->jpeg_quality 			=  100;
								$img->file_new_name_body  	=  $personal->codigo;
								$img->image_resize          =  true;
				        		$img->image_ratio_y         =  true;
				        		$img->image_x               =  175;
				        		$img->file_overwrite		=  true;
				        		$img->file_auto_rename		=  false;
				        		$img->Process('./public/img/personal');
				        		if (!$img->processed){
				        			$this->option = 'error';
									$this->error .= 'No se pudo subir el archivo de imagen: ' . $img->error;
				        		}
							}else{
								$f = getcwd() . '/public/img/personal/' . $personal->foto;
								if($personal->foto != '' && file_exists($f)){
									unlink($f);
								}
								$personal->foto = '';
							}
							// guardando el path de la imagen
							if(!$personal->save()){
								$this->option = 'error';
								$this->error .= ' Error al guardar la direcci&oacute;n de la imagen en la BD.';
							}
						}
					}else{
						$this->option = 'error';
						$this->error .= ' Error al guardar en la BD.';
					}
			}else{
				$this->option = 'error';
				$this->error = ' El personal no existe.';
			}
		}else{
			$this->option = 'error';
			$this->error = ' El personal no existe.';
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
			$personal = new Personal();
			$this->personal = $personal->find($id);
			if($this->personal->id == ''){
				$this->option = 'error';
				$this->error = ' El c&oacute;digo de personal no existe.';
			}
		}else if($this->post('id') != ''){
			$this->option = '';
			$this->error = '';
			$personal = new Personal();
			$personal = $personal->find($this->post('id'));
			$f = $personal->foto;
			if($personal->id != ''){
				// eliminado el profesor
				if($personal->delete($this->post('id'))){
					$this->option = 'exito';
					// eliminando imagen
					$f = getcwd() . '/public/img/personal/' . $f;
					if($personal->foto != '' && file_exists($f)){
						unlink($f);
					}

				}else{
					$this->option = 'error';
					$this->error .= ' Error al intentar eliminar de la BD.';
				}


			}else{
				$this->option = 'error';
				$this->error = ' El c&oacute;digo de personal no existe.';
			}
		}else{
			$this->option = 'error';
			$this->error = ' No se especific&oacute; el personal a eliminar.';
		}
		} else {
					$this->option = 'error';
					$this->error = ' El ciclo esta cerrado.';
		}
	}

	public function exportar($grp_id = ''){
		$this->set_response("view");
		require('app/reportes/xls.personal.php');
		$reporte = new XLSPersonal();
		$reporte->generar();
 	}


	public function index($pag = ''){
		$elementos = new Personal();
		$controlador = $this->controlador;
		$accion = $this->accion;
		$path = $this->path = KUMBIA_PATH;

		// busqueda
		$b = new Busqueda($controlador, $accion);

		// genera las condiciones
		$b->establecerCondicion(
			'nombre',
			"CONCAT(TRIM(nombre), ' ', TRIM(ap), ' ', TRIM(am)) LIKE '%" . $b->campo('nombre') . "%' "
		);

		$c = $b->condicion(array("tipopersonal_id"));
		$this->busqueda = $b;

		// cuenta todos los registros
		$this->registros = $elementos->count(($c == '' ? '' : $c));

		// paginacion
		$paginador = new Paginador($controlador, $accion);
		if($pag != ''){
			$paginador->guardarPagina($pag);
		}
		$paginador->estableceRegistros($this->registros);
		$paginador->generar();
		$this->paginador = $paginador;

		// ejecuta la consulta
		$elems = $elementos->find(
							'conditions: ' . ($c == "" ? "1" : $c),
							'order: ap, am, nombre ',
							'limit: ' . ($paginador->pagina() * $paginador->rpp()) . ', '
									  . $paginador->rpp()
						  );
		$this->elementos = array();
		foreach($elems as $p){
			$p->foto = KUMBIA_PATH . 'img/' . ($p->foto == '' ? 'sp5/persona.png' : 'personal/'.$p->foto .'?d=' . time());
			$this->elementos[] = $p;
		}

		// acl
		$usr_login = Session :: get_data('usr.login');
		$this->acl = array ();
		$acl = new gacl_extra();
		$acos_arr = array (
			'personal' => array (
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
		$this->acl=$this->acl["personal"];
	}


	public function info(){
		$this->set_response('view');
		$tabla = $this->post('tabla');
		$campo = $this->post('campo');
		$info=$this->info=$this->post('info');
		if(!isset($tabla))$tabla='profesores';
		if(!isset($campo))$campo='codigo';

		if(!isset($info))$this->info=$info=false;

		$this->valor = $valor = $this->post('valor');
		$this->invalido = false;
		$this->disponible = false;
		if($valor != ''){
			$registros = new $tabla();
			$registro = $registros->find_first($campo . " = '" . $valor . "'");
			if($registro->id != ''){
				$this->registro = $registro;
				$this->disponible = true;
			}
		}
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