<?php
Kumbia :: import('app.componentes.*');
Kumbia :: import('lib.kumbia.utils');
Kumbia :: import('lib.upload.main');
Kumbia :: import('lib.excel.main');

/** SP5
 * Creado el 03/07/2008
 * Copyright (C) 2008 FiPa Software (contacto at fipasoft.com.mx)
 */

class ProfesoresController extends ApplicationController {
	public $template = "system";

	public function agregar(){
		$ciclo=new Ciclos();
		$ciclo=$ciclo->find(Session :: get_data('ciclo.id'));
		if($ciclo->abierto()){
			if($this->post('codigo') == ''){
				$this->option = 'captura';
			}else{
				$this->option = '';
				$this->error = '';
				$profesor = new Profesores();
				$profesor->codigo = $this->post('codigo');
				$profesor->nombre = $this->post('nombre');
				$profesor->ap = $this->post('ap');
				$profesor->am = $this->post('am');
				$profesor->domicilio = $this->post('domicilio');
				$profesor->tel = $this->post('tel');
				$profesor->cel = $this->post('cel');
				$profesor->mail = $this->post('mail');
				$profesor->rfc = $this->post('rfc');
				$profesor->curp = $this->post('curp');
				$profesor->fnacimiento = Utils :: fecha_convertir($this->post('fnacimiento'));
				$profesor->sexo = $this->post('sexo');
				$profesor->foto = '';
				$profesor->validates_uniqueness_of('codigo');
				if($profesor->save()){
					$profesores = new Profesores();
					$profesor = $profesores->find($profesor->id);
					$this->option = 'exito';
					// guardando img en el servidor
					if($_FILES['foto']['tmp_name'] != ''){
						$img = new Upload($_FILES['foto'], 'es_ES');
						if($img->uploaded){
							$profesor->foto = $profesor->codigo . '.jpg';
							$img->image_convert 		=  'jpg';
							$img->jpeg_quality 			=  100;
							$img->file_new_name_body  	=  $profesor->codigo;
							$img->image_resize          =  true;
							$img->image_ratio_y         =  true;
							$img->image_x               =  175;
							$img->file_overwrite		=  true;
							$img->file_auto_rename		=  false;
							$img->Process('./public/img/profesores');
							if (!$img->processed){
								$profesor->foto = '';
								$this->option = 'error';
								$this->error .= 'No se pudo procesar el archivo de imagen: ' . $img->error;
							}
							// guardando el path de la imagen
							if(!$profesor->save()){
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
			}
		} else {
			$this->option = 'error';
			$this->error = ' El ciclo esta cerrado.';
		}
	}

	public function disponible(){
		$this->set_response('view');
		$tabla = $this->post('tabla');
		$campo = $this->post('campo');
		$this->valor = $valor = $this->post('valor');
		$this->invalido = false;
		$this->disponible = false;
		if($valor != ''){
			$registros = new $tabla();
			if($registros->count($campo . " = '" . $valor . "'") == 0){
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
				$profesores = new Profesores();
				$this->profesor = $profesores->find($id);
				if($this->profesor->id == ''){
					$this->option = 'error';
					$this->error = ' El id del profesor no existe.';
				}else{
					$this->profesor->fnacimiento = str_replace('-', '/', Utils :: fecha_convertir($this->profesor->fnacimiento));
					if(strcmp($this->profesor->fnacimiento, '00/00/0000') == 0){
						$this->profesor->fnacimiento = '';
					}
				}
			}else if($this->post('id') != ''){
				$this->option = '';
				$this->error = '';
				$profesor = new Profesores();
				$profesor = $profesor->find($this->post('id'));
				if($profesor->id != ''){
					$profesor->nombre = $this->post('nombre');
					$profesor->ap = $this->post('ap');
					$profesor->am = $this->post('am');
					$profesor->domicilio = $this->post('domicilio');
					$profesor->tel = $this->post('tel');
					$profesor->cel = $this->post('cel');
					$profesor->mail = $this->post('mail');
					$profesor->rfc = $this->post('rfc');
					$profesor->curp = $this->post('curp');
					$profesor->fnacimiento = Utils :: fecha_convertir($this->post('fnacimiento'));
					$profesor->sexo = $this->post('sexo');

					if($profesor->save()){
						$this->option = 'exito';
						// guardando img en el servidor
						if($this->post('cambiarImagen') == 'true'){
							$img = new Upload($_FILES['foto'], 'es_ES');
							if($img->uploaded){
								$profesor->foto = $profesor->codigo . '.jpg';
								$img->image_convert 		=  'jpg';
								$img->jpeg_quality 			=  100;
								$img->file_new_name_body  	=  $profesor->codigo;
								$img->image_resize          =  true;
								$img->image_ratio_y         =  true;
								$img->image_x               =  175;
								$img->file_overwrite		=  true;
								$img->file_auto_rename		=  false;
								$img->Process('./public/img/profesores');
								if (!$img->processed){
									$this->option = 'error';
									$this->error .= 'No se pudo subir el archivo de imagen: ' . $img->error;
								}
							}else{
								$f = getcwd() . '/public/img/profesores/' . $profesor->foto;
								if($profesor->foto != '' && file_exists($f)){
									unlink($f);
								}
								$profesor->foto = '';
							}
							// guardando el path de la imagen
							if(!$profesor->save()){
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
					$this->error = ' El profesor no existe.';
				}
			}else{
				$this->option = 'error';
				$this->error = ' El profesor no existe.';
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
				$profesores = new Profesores();
				$this->profesor = $profesores->find($id);
				if($this->profesor->id == ''){
					$this->option = 'error';
					$this->error = ' El c&oacute;digo de profesor no existe.';
				}
			}else if($this->post('id') != ''){
				$this->option = '';
				$this->error = '';
				$profesores = new Profesores();
				$profesor = $profesores->find($this->post('id'));
				$f = $profesor->foto;
				if($profesor->id != ''){
					// eliminado el profesor
					try{
						$staff=new Profesores();
						$staff=$staff->staff();
						if($staff->id!=''){
							$staff->cambioaStaff($this->post('id'),$staff->id);
							if($profesores->delete($this->post('id'))){
								$this->option = 'exito';
								// eliminando imagen
								$f = getcwd() . '/public/img/profesores/' . $f;
								if($profesor->foto != '' && file_exists($f)){
									unlink($f);
								}

							}else{
								$this->option = 'error';
								$this->error .= ' Error al intentar eliminar de la BD.';
							}
						}else{
							$this->option = 'error';
							$this->error .= ' No existe el profesor staff.';
						}
					} catch (Exception $e) {
						$this->option = 'error';
						$this->error .= ' El profesor esta ligado a un curso.';
					}
				}else{
					$this->option = 'error';
					$this->error = ' El c&oacute;digo de profesor no existe.';
				}
			}else{
				$this->option = 'error';
				$this->error = ' No se especific&oacute; el profesor a eliminar.';
			}
		} else {
			$this->option = 'error';
			$this->error = ' El ciclo esta cerrado.';
		}
	}

	public function exportar($grp_id = ''){
		$this->set_response("view");
		require('app/reportes/xls.profesores.php');
		$ciclo_id = Session :: get_data('ciclo.id');
		$reporte = new XLSProfesores($ciclo_id);
		$reporte->generar();
	}

	public function exportartodos(){
		require('app/reportes/xls.profesoreshorario.php');
		$ciclo_id = Session :: get_data('ciclo.id');

		ob_end_clean();
		ob_start();

		$profesores = new Profesores();
		$profesores = $profesores->find();

		if(!file_exists('./logs/horarios/')){
			mkdir('./logs/horarios/');
		}
			
		foreach($profesores as $p){
			$reporte = new XLSProfesoreshorario($ciclo_id,$p->id);
			$n = $reporte->getNombre();

			$f = fopen('./logs/horarios/'. $n, "w");
			$reporte->close();
			fwrite($f, ob_get_contents());
			fclose($f);
			ob_end_clean();
			ob_start();
		}

	}


	public function index($pag = ''){
		$profesores = new Profesores();
		$controlador = $this->controlador;
		$accion = $this->accion;
		$path = $this->path = KUMBIA_PATH;
		$ciclo_id = Session :: get_data('ciclo.id');

		// busqueda
		$b = new Busqueda($controlador, $accion);

		// genera las condiciones
		$b->establecerCondicion(
			'nombre',
			"CONCAT(TRIM(nombre), ' ', TRIM(ap), ' ', TRIM(am)) LIKE '%" . $b->campo('nombre') . "%' "
			);

			$c = $b->condicion();
			$this->busqueda = $b;

			// cuenta todos los registros
			$this->registros = $profesores->count(($c == '' ? '' : $c));

			// paginacion
			$paginador = new Paginador($controlador, $accion);
			if($pag != ''){
				$paginador->guardarPagina($pag);
			}
			$paginador->estableceRegistros($this->registros);
			$paginador->generar();
			$this->paginador = $paginador;

			// ejecuta la consulta
			$pros = $profesores->find(
							'conditions: ' . ($c == "" ? "1" : $c),
							'order: ap, am, nombre ',
							'limit: ' . ($paginador->pagina() * $paginador->rpp()) . ', '
							. $paginador->rpp()
							);
							$this->profesores = array();
							foreach($pros as $p){
								$p->foto = KUMBIA_PATH . 'img/' . ($p->foto == '' ? 'sp5/persona.png' : 'profesores/'.$p->foto .'?d=' . time());
								$this->profesores[] = $p;
							}

							// acl
							$usr_login = Session :: get_data('usr.login');
							$this->acl = array ();
							$acl = new gacl_extra();
							$acos_arr = array (
			'profesores' => array (
				'agregar',
				'editar',
				'eliminar',
				'index',
				'ver',
				'exportar',
				'password',
				'buscar',
				'horarioexcel',
				'horario'
				)
				);
				$this->acl = $acl->acl_check_multiple($acos_arr, $usr_login);
				$this->acl=$this->acl["profesores"];
				$ciclos = new Ciclos();
				$this->ciclo = $ciclos->find($ciclo_id);
				$this->ciclos = $ciclos->find("columns: id, numero", "conditions: abierto = '1' ", "order: numero DESC");

	}

	public function horarioexcel($id=''){
		$this->set_response("view");
		require('app/reportes/xls.profesoreshorario.php');
		$ciclo_id = Session :: get_data('ciclo.id');
		$reporte = new XLSProfesoreshorario($ciclo_id,$id);
		$reporte->generar();
	}

	public function horario($id=''){
		if($id!=''){
			$profesor=new Profesores();
			$profesor=$profesor->find($id);
			if($profesor->id!=""){

				$ciclo=new Ciclos();
				$ciclo = $ciclo->find(Session :: get_data('ciclo.id'));
				$this->ciclo = $ciclo;
				$cursos = new Cursos();

				$from = "cursos " .
		"INNER JOIN grupos ON cursos.grupos_id = grupos.id " .
		"INNER JOIN materias ON cursos.materias_id  = materias.id " .
		"INNER JOIN profesores ON cursos.profesores_id  = profesores.id ";
				$cursos = new Cursos();
				$cursos = $cursos->find_all_by_sql("SELECT " .
		"materias.nombre as materia, " .
		"cursos.id, cursos.grupos_id, " .
		"cursos.materias_id," .
		"cursos.profesores_id, " .
		"cursos.estado_id, " .
		"cursos.observaciones," .
		"cursos.inicio  " .
		"FROM " . $from .
		"WHERE cursos.profesores_id = '" . $id . "' AND grupos.ciclos_id='".$this->ciclo->id."' " . // AND cursos.estado_id='3'
		"ORDER BY cursos.inicio,materias.nombre ");
				$cc=array();
				foreach($cursos as $curso){
					$cc[$curso->id]["c"]=$curso;
					$cc[$curso->id]["h"]=$curso->horarios();
				}
				$this->cursos=$cc;
				$this->profesor=$profesor;
				$dias=new Dias();
				$this->dias=$dias->find("id!='7' ORDER BY id");
				$this->option="vista";
			}else{
				$this->option="error";
				$this->error="No existe el profesor.";
			}
		}else{
			$this->option="error";
			$this->error="No existe el profesor.";
		}

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

	public function laboral(){
		try{
			if($this->post("datos")==""){
				$profesores = new Profesores();
				$profesores = $profesores->find("1 ORDER BY ap,am,nombre");

				$lcategorias = new Lcategoria();
				$lcategorias = $lcategorias->find();

				$this->lcategorias = $lcategorias;
				$this->profesores = $profesores;
				$this->option = "captura";
			}else{
				$datos = $this->post("datos");
				if(is_array($datos)){
					$this->option = "exito";
					foreach($datos as $p_id => $lc_id){
						if($lc_id!=""){
							$profesor = new Profesores();
							$profesor = $profesor->find($p_id);
							if($profesor->id!=""){
								$laboral = $profesor->laboral();
									
								if($laboral->id == ""){
									$laboral = new Laboral();
									$laboral->profesores_id = $p_id;
								}
									
								$laboral->lcategoria_id = $lc_id;
								$laboral->save();
							}
						}else{
							$laboral = new Laboral();
							$laboral->delete("profesores_id='".$p_id."'");
						}
					}
				}else{
					throw new Exception("Datos no validos");
				}
			}
		}catch (Exception $e){
			$this->option = "error";
			$this->error = $e->getMessage();
		}
	}

	public function password($id = ''){
		$ciclo=new Ciclos();
		$ciclo=$ciclo->find(Session :: get_data('ciclo.id'));
		if($ciclo->abierto()){

			if($id != ''){
				$this->option = 'captura';
				$id = intval($id, 10);
				$profesor = new Profesores();
				$this->profesor = $profesor->find($id);
				if($this->profesor->id == ''){
					$this->option = 'error';
					$this->error = ' El usuario no existe.';
				}
			}else if($this->post('pass') != ''){
				$this->option = '';
				$this->error = '';
				$profesor = new Profesores();
				$profesor = $profesor->find($this->post('id'));
				if($profesor->id != ''){
					if($this->post('pass') == $this->post('pass2')){
						if(strlen($this->post('pass')) >= 6){
							$password=new Profesorespassword();
							$password=$password->find_first("profesores_id=".$profesor->id);
							if($password->id != ''){
								$password->pass = sha1($this->post('pass'));
							}else{
								$password=new Profesorespassword();
								$password->profesores_id=$profesor->id;
								$password->pass = sha1($this->post('pass'));
							}

							if($password->save()){
								$this->option = 'exito';
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
					$this->error = ' El alumno no existe.';
				}
			}else{
				$this->option = 'error';
				$this->error = ' No se especific&oacute; el alumno.';
			}
		} else {
			$this->option = 'error';
			$this->error = ' El ciclo esta cerrado.';
		}
	}



	public function ver($id = ''){
		$id = intval($id, 10);
		$Profesores = new Profesores();
		$profesor = $Profesores->find($id);
		$profesor->fnacimiento = Utils :: fecha_convertir($profesor->fnacimiento);
		$profesor->fnacimiento = str_replace('-', '/', $profesor->fnacimiento);
		if($profesor->sexo != ''){
			$profesor->sexo = ($profesor->sexo == 'H' ? 'Hombre' : 'Mujer');
		}
		$this->profesor = $profesor;

		$usr_login = Session :: get_data('usr.login');
		$this->acl = array ();
		$acl = new gacl_extra();
		$acos_arr = array (
			'profesores' => array (
				'editar',
				'eliminar',
				'password',
				'horario'
				)
				);
				$this->acl = $acl->acl_check_multiple($acos_arr, $usr_login);
				$this->acl=$this->acl["profesores"];
	}


}
?>