<?php
Kumbia :: import('app.componentes.*');
Kumbia :: import('lib.kumbia.utils');
Kumbia :: import('lib.upload.main');
Kumbia :: import('lib.excel.main');

/** SP5
 * Creado el 03/07/2008
 * Copyright (C) 2008 FiPa Software (contacto at fipasoft.com.mx)
 */

class AlumnosController extends ApplicationController {
	public $template = "system";

	public function amonestaciones($alumno_id ='', $pag = ''){
		if($alumno_id!=''){
			if($this->post('ciclo')!=''){
				Session :: set_data('ciclo.id', $this->post('ciclo'));
			}
            
			$alumno = new Alumnos();
			$alumno = $alumno->find($alumno_id);
			if($alumno->id!=''){
				$this->categorias = new Acategoria();
				$this->reglamento = new Reglamentos();
				$this->articulo = new Articulo();
				$ciclo_id = Session :: get_data('ciclo.id');
				$controlador = $this->controlador;
				$accion = $this->accion;

				$b = new Busqueda($controlador, $accion.'/'.$alumno_id);
				$b->establecerCondicion('fecha', "fecha = '" . Utils::convierteFechaMySql($b->campo('fecha')) . "' ");
				$b->establecerCondicion('aestado_id', "aestado_id = '" . $b->campo('aestado_id') . "' ");
				$b->quitarCondicion('ciclo');
				
				$b->campos();
				// genera las condiciones
				$c = $b->condicion();
				$amonestaciones = new Amonestacion();

				$this->registros=$amonestaciones->count_by_sql(
						"SELECT count(DISTINCT(amonestacion.id)) FROM
							 amonestacion
							INNER JOIN amonestados ON amonestacion.id = amonestados.amonestacion_id
							INNER JOIN alumnos ON amonestados.alumnos_id = alumnos.id 
								WHERE ".
				($c == "" ? " amonestacion.aestado_id='2' AND amonestacion.ciclos_id='".$ciclo_id."' AND amonestados.alumnos_id='".$alumno_id."' " : " amonestacion.aestado_id='2' AND amonestacion.ciclos_id='".$ciclo_id."' AND amonestados.alumnos_id='".$alumno_id."' AND ".$c));

				// paginacion
				$paginador = new Paginador($controlador, $accion);
				if($pag != ''){
					$paginador->guardarPagina($pag);
				}
				$paginador->estableceRegistros($this->registros);
				$paginador->establecePath($controlador . '/' . $accion . '/' . $alumno->id );
				$paginador->generar();


				$this->busqueda = $b;

				$this->amonestaciones=$amonestaciones->find_all_by_sql(
							"SELECT amonestacion.*,alumnos.id as alumno_id FROM
								amonestacion
							INNER JOIN amonestados ON amonestacion.id = amonestados.amonestacion_id
							INNER JOIN alumnos ON amonestados.alumnos_id = alumnos.id 
								WHERE ".
				($c == "" ? " amonestacion.aestado_id='2' AND amonestacion.ciclos_id='".$ciclo_id."' AND amonestados.alumnos_id='".$alumno_id."' " : " amonestacion.aestado_id='2' AND amonestacion.ciclos_id='".$ciclo_id."' AND amonestados.alumnos_id='".$alumno_id."' AND ".$c)." ORDER BY fecha DESC ".
							'limit ' . ($paginador->pagina() * $paginador->rpp()) . ', '
							. $paginador->rpp()
							);

							$this->paginador = $paginador;
							$this->option="vista";

							$estados = new Aestado();
							$this->estados = $estados->find();
							$Ciclos = new Ciclos();
							$this->ciclo = $Ciclos->find($ciclo_id);
							$Ciclos = $Ciclos->find("columns: id, numero", "conditions: abierto = '1' ", "order: numero DESC");
							$this->ciclos = $Ciclos;
							$this->alumno = $alumno;
							$usr_login = Session :: get_data('usr.login');
							$this->acl = array ();
							$acl = new gacl_extra();
							$acos_arr = array (
								'amonestaciones' => array (
									'ver'
									)
									);
									$this->acl = $acl->acl_check_multiple($acos_arr, $usr_login);
			}else{
				$this->option = 'error';
				$this->error = 'No se especifico el alumno.';
			}
		}else{
			$this->option = 'error';
			$this->error = 'No se especifico el alumno.';
		}

	}


	public function agregar() {
		$ciclo = new Ciclos();
		$ciclo = $ciclo->find(Session :: get_data('ciclo.id'));
		if ($ciclo->abierto()) {
			$agenda = new Agenda();
			if ($agenda->eventoValido('ALU-NVO', Session :: get_data('ciclo.id'), $this->controlador, $this->accion, Session :: get_data('usr.login')) || $agenda->eventoValido('ALU-NVO-ESP', Session :: get_data('ciclo.id'), $this->controlador, $this->accion, Session :: get_data('usr.login')) || $agenda->eventoValido('ALU-INS', Session :: get_data('ciclo.id'), $this->controlador, $this->accion, Session :: get_data('usr.login')) || $agenda->eventoValido('ALU-INS-ESP', Session :: get_data('ciclo.id'), $this->controlador, $this->accion, Session :: get_data('usr.login'))) {
				if ($this->post('codigo') == '') {
					$this->option = 'captura';
					$situaciones = new Situaciones();
					$this->situaciones = $situaciones->disponiblesEnAgenda();
					$grupos = new Grupos();
					$this->grupos = $grupos->disponiblesParaAgregarAlumno();
				} else {
					$this->option = '';
					$this->error = '';
					$alumno = new Alumnos();
					$alumno->codigo = $this->post('codigo');
					$alumno->nombre = $this->post('nombre');
					$alumno->ap = $this->post('ap');
					$alumno->am = $this->post('am');
					$alumno->domicilio = $this->post('domicilio');
					$alumno->tel = $this->post('tel');
					$alumno->cel = $this->post('cel');
					$alumno->mail = $this->post('mail');
					$alumno->curp = $this->post('curp');
					$alumno->fnacimiento = Utils :: fecha_convertir($this->post('fnacimiento'));
					$alumno->sexo = $this->post('sexo');
					$alumno->situaciones_id = $this->post('situaciones_id');
					$alumno->admision = Utils :: fecha_convertir($this->post('admision'));
					$alumno->foto = '';
					$alumno->promedio = $this->post('promedio');
					$alumno->aprobadas = $this->post('aprobadas');
					$alumno->validates_uniqueness_of('codigo');
					if ($alumno->save()) {
						$alumnos = new Alumnos();
						$this->alumno = $alumno = $alumnos->find($alumno->id);
						$this->option = 'exito';
						// guardando img en el servidor
						if ($_FILES['foto']['tmp_name'] != '') {
							$img = new Upload($_FILES['foto'], 'es_ES');
							if ($img->uploaded) {
								$alumno->foto = $alumno->codigo . '.jpg';
								$img->image_convert = 'jpg';
								$img->jpeg_quality = 100;
								$img->file_new_name_body = $alumno->codigo;
								$img->image_resize = true;
								$img->image_ratio_y = true;
								$img->image_x = 175;
								$img->file_overwrite = true;
								$img->file_auto_rename = false;
								$img->Process('./public/img/alumnos');
								if (!$img->processed) {
									$alumno->foto = '';
									$this->option = 'error';
									$this->error .= 'No se pudo procesar el archivo de imagen: ' . $img->error;
								}
								// guardando el path de la imagen
								if (!$alumno->save()) {
									$this->option = 'error';
									$this->error .= ' Error al guardar la direcci&oacute;n de la imagen en la BD.';
								}
							} else {
								$this->option = 'error';
								$this->error .= 'No se pudo subir el archivo de imagen: ' . $img->error;
							}
						}

						$grupos_id = $this->post('grupo');
						$grupo = new Grupos();
						$grupo = $grupo->find($grupos_id);
						if ($grupo->id != '') {
							$alumnosgrupo = new Alumnosgrupo();
							$alumnosgrupo->grupos_id = $grupo->id;
							$alumnosgrupo->alumnos_id = $alumno->id;

							if ($alumnosgrupo->save()) {

								foreach ($grupo->cursos() as $curso) {
									$alumnoscursos = new Alumnoscursos();
									$alumnoscursos->alumnos_id = $alumno->id;
									$alumnoscursos->cursos_id = $curso->id;
									if (!$alumnoscursos->save()) {
										$this->option = 'error';
										$this->error .= '<br />No se pudo inscribir a ' . $curso->materia;
									}
								}

							} else {
								$this->option = 'error';
								$this->error .= ' No se pudo inscribir el alumno al grupo.';
							}

						} else {
							$this->option = 'error';
							$this->error .= 'El id de grupo no es v&aacute;lido.';
						}

					} else {
						$this->option = 'error';
						$this->error .= ' Error al guardar en la BD.';
					}
				}
			} else {
				$this->option = 'error';
				$this->error = ' La agenda no permite agregar alumnos.';
			}
		} else {
			$this->option = 'error';
			$this->error = ' El ciclo esta cerrado.';
		}
	}

	public function cursos($id = '') {
		if ($id != '') {
			$alumno = new Alumnos();
			$this->alumno = $alumno = $alumno->find($id);
			if ($alumno->id != '') {
				$this->cursos = $alumno->cursos(Session :: get_data("ciclo.id"));

				$this->registros = count($this->cursos);
				$usr_login = Session :: get_data('usr.login');
				$this->acl = array ();
				$acl = new gacl_extra();
				$acos_arr = array (
					'cursos' => array (
						'ver',
						'grupo',

				)
				);
				$this->acl = $acl->acl_check_multiple($acos_arr, $usr_login);
				$this->acl = $this->acl['cursos'];

				$this->articulos = array ();
				$articulos = new Alumnosconarticulo();
				$articulos = $articulos->articulosAlumno($alumno->id);
				foreach ($articulos as $art) {
					$this->articulos[$art->cursos_id] = $art;
				}

			} else {
				$this->option = 'error';
				$this->error = "El alumno no existe";

			}

		} else {
			$this->option = 'error';
			$this->error = "No tiene permiso para ver la pagina";

		}
	}

	public function disponible() {
		$this->set_response('view');
		$tabla = $this->post('tabla');
		$campo = $this->post('campo');
		$id = $this->post('id');
		$this->valor = $valor = $this->post('valor');
		$this->invalido = false;
		$this->disponible = false;
		if ($valor != '') {
			$registros = new $tabla ();
			if ($registros->count($campo . " = '" . $valor . "'" .
			($id != "" ? " AND id != '" . $id . "' " : "")) == 0) {
				$this->disponible = true;
			}
		}
	}

	public function editar($id = '') {
		$ciclo = new Ciclos();
		$ciclo = $ciclo->find(Session :: get_data('ciclo.id'));
		if ($ciclo->abierto()) {
			if ($id != '') {
				$this->option = 'captura';
				$alumno = new Alumnos();
				$alumno = $alumno->find(intval($id, 10));
				if ($alumno->id != '') {
					if ($alumno->asignado()) {
						$situaciones = new Situaciones();
						$this->situaciones = $situaciones->disponiblesEnAgenda();
						$this->alumno = $alumno;
					} else {
						$this->option = 'error';
						$this->error = ' El alumno no est&aacute; dentro de su asignaci&oacute;n.';
					}
				} else {
					$this->option = 'error';
					$this->error = ' El id del alumno no existe.';
				}
			} else
			if ($this->post('id') != '') {
				$this->option = '';
				$this->error = '';
				$alumno = new Alumnos();
				$alumno = $alumno->find($this->post('id'));
				if ($alumno->id != '') {
					if ($alumno->asignado()) {
						$alumno->codigo = $this->post('codigo');
						$alumno->nombre = $this->post('nombre');
						$alumno->ap = $this->post('ap');
						$alumno->am = $this->post('am');
						$alumno->domicilio = $this->post('domicilio');
						$alumno->tel = $this->post('tel');
						$alumno->cel = $this->post('cel');
						$alumno->mail = $this->post('mail');
						$alumno->curp = $this->post('curp');
						$alumno->fnacimiento = Utils :: fecha_convertir($this->post('fnacimiento'));
						$alumno->sexo = $this->post('sexo');
						$alumno->admision = Utils :: fecha_convertir($this->post('admision'));
						$alumno->situaciones_id = $this->post('situaciones_id');
						$alumno->promedio = $this->post('promedio');
						$alumno->aprobadas = $this->post('aprobadas');
						if ($alumno->save()) {
							$this->option = 'exito';
							$this->alumno = $alumno;
							// guardando img en el servidor
							if ($this->post('cambiarImagen') == 'true') {
								$img = new Upload($_FILES['foto'], 'es_ES');
								if ($img->uploaded) {
									$alumno->foto = $alumno->codigo . '.jpg';
									$img->image_convert = 'jpg';
									$img->jpeg_quality = 100;
									$img->file_new_name_body = $alumno->codigo;
									$img->image_resize = true;
									$img->image_ratio_y = true;
									$img->image_x = 175;
									$img->file_overwrite = true;
									$img->file_auto_rename = false;
									$img->Process('./public/img/alumnos');
									if (!$img->processed) {
										$this->option = 'error';
										$this->error .= 'No se pudo subir el archivo de imagen: ' . $img->error;
									}
								} else {
									$f = getcwd() . '/public/img/alumnos/' . $alumno->foto;
									if ($alumno->foto != '' && file_exists($f)) {
										unlink($f);
									}
									$alumno->foto = '';
								}
								// guardando el path de la imagen
								if (!$alumno->save()) {
									$this->option = 'error';
									$this->error .= ' Error al guardar la direcci&oacute;n de la imagen en la BD.';
								}
							}
						} else {
							$this->option = 'error';
							$this->error .= ' Error al guardar en la BD.';
						}
					} else {
						$this->option = 'error';
						$this->error = 'El alumno seleccionado no pertenece a su asignaci&oacute;n';
					}
				} else {
					$this->option = 'error';
					$this->error = ' El alumno no existe.';
				}
			} else {
				$this->option = 'error';
				$this->error = ' El alumno no existe.';
			}
		} else {
			$this->option = 'error';
			$this->error = ' El ciclo esta cerrado.';
		}
	}

	public function eliminar($id = '') {
		$ciclo = new Ciclos();
		$ciclo = $ciclo->find(Session :: get_data('ciclo.id'));
		if ($ciclo->abierto()) {
			if ($id != '') {
				$this->option = 'captura';
				$id = intval($id, 10);
				$alumnos = new Alumnos();
				$this->alumno = $alumnos->find($id);
				if ($this->alumno->id == '') {
					$this->option = 'error';
					$this->error = ' El c&oacute;digo de alumno no existe.';
				}
			} else
			if ($this->post('id') != '') {
				$this->option = '';
				$this->error = '';
				$alumnos = new Alumnos();
				$alumno = $alumnos->find($this->post('id'));
				$f = $alumno->foto;
				if ($alumno->id != '') {
					if ($alumno->asignado()) {
						try {
							$usr_grupos = Session :: get_data('usr.grupos');
							if (in_array('root', $usr_grupos)) {
								mysql_query("BEGIN") or die("ALU_ELI_1");
								// eliminando vinculos de curso
								$asistencias = new Asistencias();
								$asistencias->delete('alumnos_id=' . $alumno->id);

								$calificaciones = new Calificaciones();
								$calificaciones->delete('alumnos_id=' . $alumno->id);

								$parciales = new Calificacionesparciales();
								$parciales->delete('alumnos_id=' . $alumno->id);

								$alumnoGrupo = new Alumnosgrupo();
								$alumnoGrupo->delete('alumnos_id=' . $alumno->id);
								$alumnoscursos = new Alumnoscursos();
								$alumnoscursos = $alumnoscursos->find('alumnos_id=' . $alumno->id);
								foreach ($alumnoscursos as $ac) {
									$faltas = new Faltas();
									$faltas->delete("alumnoscursos_id=" . $ac->id);

									$alumnoarticulo = new Alumnosconarticulo();
									$alumnoarticulo->delete("alumnoscursos_id=" . $ac->id);
									$ac->delete();
								}

								$password = new Alumnospassword();
								$password->delete('alumnos_id=' . $alumno->id);

								$tutoria = new Tutoria();
								$tutoria->delete('alumnos_id=' . $alumno->id);

								if ($alumnos->delete($this->post('id'))) {
									$this->option = 'exito';
									// eliminando imagen
									$f = getcwd() . '/public/img/alumnos/' . $f;
									if ($alumno->foto != '' && file_exists($f)) {
										if (!unlink($f)) {
											$this->option = 'error';
											$this->error .= ' No se pudo eliminar la foto del alumno.';
										}
									}
									mysql_query("COMMIT") or die("ALU_ELI_1");
								} else {
									$this->option = 'error';
									$this->error .= ' No se pudo eliminar al alumno.';
									mysql_query("ROLLBACK") or die("ALU_ELI_1");
								}

							}
							elseif (in_array('director', $usr_grupos) || in_array('secretario', $usr_grupos) || in_array('oficial', $usr_grupos)) {
								$asistencias = new Asistencias();
								$calificaciones = new Calificaciones();
								$parciales = new Calificacionesparciales();
								if (!$asistencias->exists('alumnos_id=' . $alumno->id) && !$calificaciones->exists('alumnos_id=' . $alumno->id) && !$parciales->exists('alumnos_id=' . $alumno->id)) {
									mysql_query("BEGIN") or die("ALU_ELI_1");
									// eliminando vinculos de curso
									$alumnoGrupo = new Alumnosgrupo();
									$alumnoGrupo->delete('alumnos_id=' . $alumno->id);
									$alumnoscursos = new Alumnoscursos();
									$alumnoscursos = $alumnoscursos->find('alumnos_id=' . $alumno->id);
									foreach ($alumnoscursos as $ac) {
										$faltas = new Faltas();
										$faltas->delete("alumnoscursos_id=" . $ac->id);

										$alumnoarticulo = new Alumnosconarticulo();
										$alumnoarticulo->delete("alumnoscursos_id=" . $ac->id);
										$ac->delete();
									}

									$password = new Alumnospassword();
									$password->delete('alumnos_id=' . $alumno->id);

									$tutoria = new Tutoria();
									$tutoria->delete('alumnos_id=' . $alumno->id);

									if ($alumnos->delete($this->post('id'))) {
										$this->option = 'exito';
										// eliminando imagen
										$f = getcwd() . '/public/img/alumnos/' . $f;
										if ($alumno->foto != '' && file_exists($f)) {
											if (!unlink($f)) {
												$this->option = 'error';
												$this->error .= ' No se pudo eliminar la foto del alumno.';
											}
										}
										mysql_query("COMMIT") or die("ALU_ELI_1");
									} else {
										$this->option = 'error';
										$this->error .= ' No se pudo eliminar al alumno.';
										mysql_query("ROLLBACK") or die("ALU_ELI_1");
									}

								} else {
									$this->option = 'error';
									$this->error .= ' No se pudo eliminar al alumno debido a que cuenta con calificaciones <br/> y/o asistencias. Solo un usuario del grupo root puede eliminarlo.';

								}
							} else {
								$this->option = 'error';
								$this->error .= ' No cuenta con los permisos para eliminar alumnos.';
							}

						} catch (Exception $e) {
							mysql_query("ROLLBACK") or die("ALU_ELI_1");
							$this->option = 'error';
							$this->error .= ' Error al intentar eliminar de la BD.';
						}
					} else {
						$this->option = 'error';
						$this->error = 'El alumno seleccionado no pertenece a su asignaci&oacute;n';
					}
				} else {
					$this->option = 'error';
					$this->error = ' El c&oacute;digo de alumno no existe.';
				}
			} else {
				$this->option = 'error';
				$this->error = ' No se especific&oacute; el alumno a eliminar.';
			}
		} else {
			$this->option = 'error';
			$this->error = ' El ciclo esta cerrado.';
		}
	}

	public function exportar($grp_id = '') {
		$this->set_response("view");
		require ('app/reportes/xls.alumnos.php');
		$ciclo_id = Session :: get_data('ciclo.id');
		$reporte = new XLSAlumnos($ciclo_id);
		$reporte->generar();
	}
	
	
	public function exportar_amonestaciones($id = '') {
		$this->set_response("view");
		require ('app/reportes/xls.alumnosamonestaciones.php');
		$ciclo_id = Session :: get_data('ciclo.id');
		$reporte = new XLSAlumnosamonestaciones($ciclo_id, $id);
		$reporte->generar();
	}

	public function importar() {
		$ciclo_id = Session :: get_data('ciclo.id');
		$this->ciclo = new Ciclos();
		$this->ciclo = $this->ciclo->find($ciclo_id);

		if (isset ($_FILES["archivo"])) {
			Kumbia :: import('app.scripts.*');
			$this->option = "confirma";
			$ext = strtolower(substr($_FILES["archivo"]['name'], strripos($_FILES["archivo"]['name'], ".") + 1));

			if ($ext == "xls" || $ext == "csv") {
				$this->idFile = time() . "." . $ext;
				$nm = $this->idFile;
				$a = htmlspecialchars("public/files/" . $nm);
				move_uploaded_file($_FILES["archivo"]['tmp_name'], $a);
				$this->importar = new ImportarAlumnos($ciclo_id, $a, $this->post('tutor'), $this->post('grupo'), 2);
				$this->importar->carga();

				$this->grupos = new Grupos();
				$this->grupos = $this->grupos->find("ciclos_id='" . $ciclo_id . "' ORDER BY turno,grado,letra");

			} else {
				$this->option = "error";
				$this->error = "El formato del archivo no es correcto." . $ext;
			}
		}
		elseif ($this->post("id_file") != "") {
			$this->option = "error";
			$this->error = "Importado." . $this->post("id_file");
			$file = "public/files/" . $this->post("id_file");
			if (file_exists($file)) {
				Kumbia :: import('app.scripts.*');
				$this->tutor = $this->post('tutor');
				$this->grupo = $this->post('grupo');
				//$this->nombre = $this->post('nombre');

				$this->importar = new ImportarAlumnos($ciclo_id, $file, $this->tutor, $this->grupo, 3);
				$this->importar->carga();
				$this->datos = $this->importar->aBD();

				$this->option = "exito";

			} else {
				$this->option = "error";
				$this->error = "El archivo no es valido.";
			}
		} else {
			$this->option = "captura";
		}

	}

	public function promedios() {
		$ciclo_id = Session :: get_data('ciclo.id');
		$this->ciclo = new Ciclos();
		$this->ciclo = $this->ciclo->find($ciclo_id);

		Kumbia :: import('app.scripts.*');
		$this->option = "exito";
		$nm = "promedios.xls";
		$a = htmlspecialchars("public/files/" . $nm);

		$this->importar = new Importador($a);
		$this->importar->carga();
		$this->datos = $this->importar->aBD();

	}

	public function index($pag = '') {
		$alumnos = new Alumnos();
		$controlador = $this->controlador;
		$accion = $this->accion;
		$path = $this->path = KUMBIA_PATH;
		$ciclo_id = Session :: get_data('ciclo.id');

		$this->ofertas = new Oferta();
		$this->ofertas = $this->ofertas->find();

		// busqueda
		$b = new Busqueda($controlador, $accion);
		$b->establecerCondicion('nombre', "CONCAT(TRIM(alumnos.nombre), ' ', TRIM(ap), ' ', TRIM(am)) LIKE '%" . $b->campo('nombre') . "%' ");
		$b->establecerCondicion('situacion', "situaciones.id  = '" . $b->campo('situacion') . "' ");
		$b->establecerCondicion('oferta_id', "grupos.oferta_id  = '" . $b->campo('oferta_id') . "' ");
		// acl
		$usr_login = Session :: get_data('usr.login');
		$this->acl = array ();
		$acl = new gacl_extra();
		$acos_arr = array (
			'alumnos' => array (
				'agregar',
				'editar',
				'eliminar',
				'ver',
				'ubicar',
				'exportar',
				'password',
				'kardex',
				'buscar',
				'cursos',
				'trayectoria',
				'amonestaciones'
				)
				);
				$this->acl = $acl->acl_check_multiple($acos_arr, $usr_login);
				//$this->acl['exportar']=true; //quitar al dar de alta en acl

				// asignados
				$grupo = new Grupos();
				$this->asignados = $grupo->asignados();

				// genera las condiciones
				$c = $b->condicion();
				$this->busqueda = $b;

				// cuenta todos los registros
				$this->registros = $alumnos->count_by_sql("SELECT " .
		"COUNT(*) " .
		"FROM " .
		"(SELECT " .
		"alumnos.id " .
		"FROM " .
		"ciclos " .
		"Inner Join grupos ON ciclos.id = grupos.ciclos_id " .
		"Inner Join alumnosgrupo ON grupos.id = alumnosgrupo.grupos_id " .
		"Inner Join alumnos ON alumnosgrupo.alumnos_id = alumnos.id " .
		"Inner Join situaciones ON alumnos.situaciones_id = situaciones.id " .
		"WHERE " .
		"ciclos.id = '" . $ciclo_id . "'" .
				($c == "" ? "" : "AND " . $c) . " ) " .
		"AS t1 ");

				// paginacion
				$paginador = new Paginador($controlador, $accion);
				if ($pag != '') {
					$paginador->guardarPagina($pag);
				}
				$paginador->estableceRegistros($this->registros);
				$paginador->generar();
				$this->paginador = $paginador;

				// ejecuta la consulta
				$alos = $alumnos->find_all_by_sql("SELECT " .
		"alumnos.codigo, " .
		"alumnos.id, " .
		"alumnos.nombre, " .
		"alumnos.am, " .
		"alumnos.ap, " .
		"grupos.id AS grupos_id, " .
		"grupos.grado, " .
		"grupos.letra," .
		"grupos.turno, " .
		"alumnos.foto, " .
		"situaciones.nombre AS situacion " .
		"FROM " .
		"ciclos " .
		"Inner Join grupos ON ciclos.id = grupos.ciclos_id " .
		"Inner Join alumnosgrupo ON grupos.id = alumnosgrupo.grupos_id " .
		"Inner Join alumnos ON alumnosgrupo.alumnos_id = alumnos.id " .
		"Inner Join situaciones ON alumnos.situaciones_id = situaciones.id " .
		"WHERE " .
		"ciclos.id = '" . $ciclo_id . "'" .
				($c == "" ? "" : "AND " . $c) . " " .
		"ORDER BY " .
		"grupos.turno, grupos.grado, grupos.letra, alumnos.ap, alumnos.am, alumnos.nombre " .
		"LIMIT " .
				($paginador->pagina() * $paginador->rpp()) . ', ' .
				$paginador->rpp() . " ");
				$this->alumnos = array ();

				foreach ($alos as $a) {
					$a->foto = KUMBIA_PATH . 'img/' . ($a->foto == '' ? 'sp5/persona.png' : 'alumnos/' . $a->foto . '?d=' . time());
					$this->alumnos[] = $a;
				}

				$ciclos = new Ciclos();
				$this->ciclo = $ciclos->find($ciclo_id);
				$this->ciclos = $ciclos->find("columns: id, numero", "conditions: abierto = '1' ", "order: numero DESC");
				$situaciones = new Situaciones();
				$this->situaciones = $situaciones->find("conditions: clave != '' AND clave != 'EGR' ");
	}

	public function info() {
		$this->set_response('view');
		$tabla = $this->post('tabla');
		$campo = $this->post('campo');
		$this->valor = $valor = $this->post('valor');
		$this->arreglo = $this->post('arreglo');
		$this->invalido = false;
		$this->disponible = false;
		if ($valor != '') {
			$registros = new $tabla ();
			$registro = $registros->find_first($campo . " = '" . $valor . "'");
			if ($registro->id != '') {
				$this->registro = $registro;
				$this->disponible = true;
			}
		}
	}

	public function kardex($id = '') {
		if ($id != '') {
			$alumno = new Alumnos();
			$this->alumno = $alumno->find($id);
			if ($this->alumno->id != '') {
				$this->option = "vista";
				$db = $db = db :: raw_connect();
				$calificaciones = $db->in_query(" SELECT
											ciclos.id as ciclo_id,ciclos.numero,ciclos.inicio,ciclos.fin,
				 							grupos.id AS grupos_id,grupos.grado,grupos.letra,grupos.turno,
				 							cursos.id AS cursos_id,cursos.materias_id,cursos.profesores_id,
											materias.clave,materias.nombre AS nombre_materia,materias.tipo,
				 							profesores.id AS profesores_id,profesores.nombre AS nombre_profesor,profesores.ap AS ap_profesor,profesores.am AS am_profesor,
				 							calificaciones.alumnos_id,calificaciones.valor,calificaciones.oportunidades_id,
				 							oportunidades.nombre AS oportunidad
				 							FROM
											ciclos
											INNER JOIN grupos ON grupos.ciclos_id=ciclos.id
											INNER JOIN cursos ON cursos.grupos_id=grupos.id
											INNER JOIN alumnoscursos ON alumnoscursos.cursos_id=cursos.id
											INNER JOIN calificaciones ON calificaciones.cursos_id=alumnoscursos.cursos_id
											INNER JOIN materias ON cursos.materias_id=materias.id
											INNER JOIN profesores ON cursos.profesores_id=profesores.id
											INNER JOIN oportunidades ON oportunidades.id=calificaciones.oportunidades_id
											WHERE alumnoscursos.alumnos_id=" .
				$this->alumno->id . " AND calificaciones.alumnos_id=" . $this->alumno->id . "
											ORDER BY ciclos.inicio,grupos.grado,materias.nombre,calificaciones.oportunidades_id");
				$datos = array ();
				$resumen = array ();
				$resumen['extra'] = false;
				$resumen['aprobadas'] = 0;
				$resumen['promedio'] = 0;

				$puntaje = 0;

				foreach ($calificaciones as $cal) {
					$datos[$cal['numero']][$cal['cursos_id']][$cal['oportunidades_id']] = $cal;

					if ($cal['oportunidades_id'] == 1) {
						if (strToUpper($cal['tipo']) != 'TLR') {
							if ($cal['valor'] > 59) {
								$resumen['aprobadas']++;
								$puntaje += $cal['valor'];
							}
						}
					}
					elseif ($cal['oportunidades_id'] == 2) {
						$resumen[$cal['numero']]['extra'] = true;
						if (strToUpper($cal['tipo']) != 'TLR') {
							if ($cal['valor'] > 59) {
								$resumen['aprobadas']++;
								$puntaje += $cal['valor'];
							}
						}
					}
				}

				$resumen['promedio'] = $this->alumno->promedio;
				$resumen['aprobadas'] = $this->alumno->aprobadas;
				$this->datos = $datos;
				$this->resumen = $resumen;

				$this->acl_cursos = array ();
				$acl = new gacl_extra();
				$acos_arr = array (
					'cursos' => array (
						'ver'
						)
						);
						$usr_login = Session :: get_data('usr.login');
						$this->acl_cursos = $acl->acl_check_multiple($acos_arr, $usr_login);
						$this->acl_cursos = $this->acl_cursos['cursos'];

						$this->acl_grupos = array ();
						$acos_arr = array (
					'grupos' => array (
						'ver',
						'curso'
						)
						);
						$this->acl_grupos = $acl->acl_check_multiple($acos_arr, $usr_login);
						$this->acl_grupos = $this->acl_grupos['grupos'];

			} else {
				$this->option = 'error';
				$this->error = ' El alumno no existe.';
			}
		} else {
			$this->option = 'error';
			$this->error = ' No tiene permiso para ver la pagina.';
		}
	}

	public function password($id = '') {
		$ciclo = new Ciclos();
		$ciclo = $ciclo->find(Session :: get_data('ciclo.id'));
		if ($ciclo->abierto()) {

			if ($id != '') {
				$this->option = 'captura';
				$id = intval($id, 10);
				$alumno = new Alumnos();
				$this->alumno = $alumno->find($id);
				if ($this->alumno->id == '') {
					$this->option = 'error';
					$this->error = ' El usuario no existe.';
				}
			} else
			if ($this->post('pass') != '') {
				$this->option = '';
				$this->error = '';
				$alumno = new Alumnos();
				$alumno = $alumno->find($this->post('id'));
				if ($alumno->id != '') {
					if ($this->post('pass') == $this->post('pass2')) {
						if (strlen($this->post('pass')) >= 6) {
							$password = new Alumnospassword();
							$password = $password->find_first("alumnos_id=" . $alumno->id);
							if ($password->id != '') {
								$password->pass = sha1($this->post('pass'));
							} else {
								$password = new Alumnospassword();
								$password->alumnos_id = $alumno->id;
								$password->pass = sha1($this->post('pass'));
							}

							if ($password->save()) {
								$this->option = 'exito';
							} else {
								$this->option = 'error';
								$this->error .= ' Error al guardar en la BD.';
							}
						} else {
							$this->option = 'error';
							$this->error .= ' La longitud m&iacute;nima del password es de 6 caracteres.';
						}
					} else {
						$this->option = 'error';
						$this->error .= ' No coincide la confirmaci&oacute;n del password.';
					}
				} else {
					$this->option = 'error';
					$this->error = ' El alumno no existe.';
				}
			} else {
				$this->option = 'error';
				$this->error = ' No se especific&oacute; el alumno.';
			}
		} else {
			$this->option = 'error';
			$this->error = ' El ciclo esta cerrado.';
		}
	}

	public function trayectoria($id = '') {
		$ciclo = new Ciclos();
		$ciclo = $ciclo->find(Session :: get_data('ciclo.id'));

		if ($ciclo->abierto()) {
			$agenda = new Agenda();
			//if ($agenda->eventoValido('ALU-GRP', Session :: get_data('ciclo.id'), $this->controlador, $this->accion, Session :: get_data('usr.login')) || $agenda->eventoValido('ALU-GRP-ESP', Session :: get_data('ciclo.id'), $this->controlador, $this->accion, Session :: get_data('usr.login'))) {

			if ($id != '') {
				$Alumnos = new Alumnos();
				$alumno = $Alumnos->find(intval($id, 10));
				if ($alumno->id != '') {
					if($alumno->bachillerato()==2){
						$this->option = 'vista';
						$this->alumno = $alumno;
						$this->grupo = $this->alumno->obtenerGrupo();
						if ($this->grupo == null) {
							$this->option = "error";
							$this->error = "El alumno " . $alumno->nombre('reversa') . ' no esta inscrito a ningun grupo.';
						} else {
							$trayectorias=new Trayectoriaespecializante();
							$this->accesibles = $trayectorias->todas();
							$alumnotrayectoria=new Alumnotrayectoria();
							$this->alumnotrayectoria=$alumnotrayectoria->find_first("alumnos_id='".$alumno->id."'");

						}
					} else {
						$this->option = "error";
						$this->error = "El alumno no cursa bachillerato por competencias.";
					}

				} else {
					$this->option = "error";
					$this->error = "El alumno no existe.";
				}

			}
			elseif ($this->post('alumno') != '' && $this->post('trayectoria') != '' ) {
				$this->option = "confirmar";

				$this->alumno = new Alumnos();
				$this->alumno = $this->alumno->find($this->post('alumno'));

				$this->trayectoria = new Trayectoriaespecializante();
				$this->trayectoria = $this->trayectoria->find($this->post('trayectoria'));

			}elseif ($this->post('alumno_id') != '' && $this->post('trayectoria_id') != '') {
				$alumno_id = $this->post('alumno_id');
				$trayectoria_id = $this->post('trayectoria_id');
				$trayectoria = new Trayectoriaespecializante();
				$alumno = new Alumnos();
				if ($trayectoria->exists($trayectoria_id) && $alumno->exists($alumno_id)) {
					$this->option = "error";
					$this->error = " " . $alumno_id;
					$this->error .= " " . $trayectoria_id;

					$alumno = $alumno->find(intval($alumno_id, 10));
					$bachillerato=$alumno->bachillerato();
					if($bachillerato==2){
						$trayectoria = $trayectoria->find(intval($trayectoria_id, 10));
						$this->option = "exito";
						$this->error = "Exito El alumno ";
						$alumnotrayectoria=new Alumnotrayectoria();
						$alumnotrayectoria=$alumnotrayectoria->find_first("alumnos_id='".$alumno->id."'");
						if($alumnotrayectoria->id!=""){
							$alumnotrayectoria->trayectoriaespecializante_id=$trayectoria->id;
							$alumnotrayectoria->save();
						}else{
							$alumnotrayectoria=new Alumnotrayectoria();
							$alumnotrayectoria->alumnos_id=$alumno->id;
							$alumnotrayectoria->trayectoriaespecializante_id=$trayectoria->id;
							$alumnotrayectoria->save();
						}

						$historial = new Historial();
						$historial->ciclos_id = Session :: get_data('ciclo.id');
						$historial->usuario = Session :: get_data('usr.login');

						$historial->descripcion = 'Liga de trayectoria especializante para el alumno ' .$alumno->codigo. ' y trayectoria '.$trayectoria->nombre.'.';
						$historial->controlador = "alumnos";
						$historial->accion = "trayectoria";
						$historial->saved_at = date("Y-m-d H:i:s"); //2009-01-20 14:28:29
						$historial->save();

						$this->trayectoria=$trayectoria;
						$this->alumno=$alumno;
					} else {
						$this->option = "error";
						$this->error = "El alumno no cursa bachillerato por competencias.";
					}
				} else {
					$this->option = "error";
					$this->error = "Datos incorrectos.";
				}

			} else {
				$this->option = "error";
				$this->error = "No tiene permiso para ver la pagina.";
			}
			//} else {
			//	$this->option = 'error';
			//	$this->error = ' La agenda no permite la accion.';
			//}
		} else {
			$this->option = 'error';
			$this->error = ' El ciclo esta cerrado.';
		}

	}

	public function ubicar($id = '') {
		$ciclo = new Ciclos();
		$ciclo = $ciclo->find(Session :: get_data('ciclo.id'));
		if ($ciclo->abierto()) {

			if (isset ($id) && trim($id) != '') {
				$Alumnos = new Alumnos();
				$alumno = $Alumnos->find(intval($id, 10));
				if ($alumno->id != '') {
					$agenda = new Agenda();
					if ($agenda->eventoValido('ALU-GRP', Session :: get_data('ciclo.id'), $this->controlador, $this->accion, Session :: get_data('usr.login')) || $agenda->eventoValido('ALU-GRP-ESP', Session :: get_data('ciclo.id'), $this->controlador, $this->accion, Session :: get_data('usr.login'))) {
						$this->option = 'vista';
						$this->alumno = $alumno;
						$this->grupo = $this->alumno->obtenerGrupo();
						if ($this->grupo == null) {
							$this->option = "error";
							$this->error = "El alumno " . $alumno->nombre('reversa') . ' no esta inscrito a ningun grupo.';
						} else {
							$this->accesibles = $this->grupo->accesiblesParaAlumno($alumno->id, Session :: get_data('usr.grupos'));
						}
					} else {
						$this->option = 'error';
						$this->error = ' La agenda no permite ubicar alumnos.';
					}
				} else {
					$this->option = "error";
					$this->error = "El alumno no existe.";
				}
			}
			elseif ($this->post('alumno') != '' && $this->post('grupo') != '' && $this->post('actual') != '') {
				$this->option = "confirmar";

				$this->alumno = new Alumnos();
				$this->alumno = $this->alumno->find($this->post('alumno'));

				$this->grupo = new Grupos();
				$this->grupo = $this->grupo->find($this->post('grupo'));

				$this->actual = new Grupos();
				$this->actual = $this->actual->find($this->post('actual'));
			}
			elseif ($this->post('alumno_id') != '' && $this->post('grupo_id') != '' && $this->post('actual') != '') {
				$alumno_id = $this->post('alumno_id');
				$grupo = $this->post('grupo_id');
				$actual = $this->post('actual');
				$grupos = new Grupos();
				if ($grupos->exists($grupo) && $grupos->exists($actual)) {
					$agenda = new Agenda();
					if ($agenda->eventoValido('ALU-GRP', Session :: get_data('ciclo.id'), $this->controlador, $this->accion, Session :: get_data('usr.login')) || $agenda->eventoValido('ALU-GRP-ESP', Session :: get_data('ciclo.id'), $this->controlador, $this->accion, Session :: get_data('usr.login'))) {
						$this->option = "error";
						$this->error = " " . $alumno_id;
						$this->error .= " " . $grupo;
						$this->error .= " " . $actual;
						$alumno = new Alumnos();
						$alumno = $alumno->find(intval($alumno_id, 10));

						$alumnoGrupo = new Alumnosgrupo();
						if ($alumnoGrupo->exists('alumnos_id=' . $alumno_id . ' AND grupos_id=' . $actual)) {
							$g = new Grupos();
							$g = $g->find($actual);
							if ($g->cambioValido($actual, $grupo, $alumno_id, Session :: get_data('usr.grupos'))) {
								$alumnoGrupo = $alumnoGrupo->find_first('alumnos_id=' . $alumno_id . ' AND grupos_id=' . $actual);
								$alumnoGrupo->baja();

								$grpB = new Grupos();
								$this->grupo = $grpB = $grpB->find($actual);

								$alumnoGrupo = new Alumnosgrupo();
								$alumnoGrupo->alta($alumno_id, $grupo);

								$grpA = new Grupos();
								$grpA = $grpA->find($grupo);

								$this->option = "exito";
								$this->error = "Exito El alumno ";

								$historial = new Historial();
								$historial->ciclos_id = Session :: get_data('ciclo.id');
								$historial->usuario = Session :: get_data('usr.login');

								$historial->descripcion = 'Cambio de  grupo de ' . $grpB->verInfo() . ' ' . $grpB->verOferta() . ' a ' . $grpA->verInfo() . ' ' . $grpA->verOferta() . ' al alumno ' . $alumno->codigo . '.';
								$historial->controlador = "alumnos";
								$historial->accion = "ubicar";
								$historial->saved_at = date("Y-m-d H:i:s"); //2009-01-20 14:28:29
								$historial->save();

							} else {
								$this->option = "error";
								$this->error = "El cambio de grupo no es valido.";
							}
						} else {
							$this->option = "error";
							$this->error = "El alumno ya no esta inscrito al grupo.";
						}
					} else {
						$this->option = 'error';
						$this->error = ' La agenda no permite ubicar alumnos.';
					}

				} else {
					$this->option = "error";
					$this->error = "Datos incorrectos.";
				}

			} else {
				$this->option = "error";
				$this->error = "No tiene permiso para ver la pagina.";
			}
		} else {
			$this->option = 'error';
			$this->error = ' El ciclo esta cerrado.';
		}
	}

	public function ver($id = '') {
		$Alumnos = new Alumnos();
		$alumno = $Alumnos->find(intval($id, 10));
		if ($alumno->id != '') {
			$this->option = 'vista';
			$this->alumno = $alumno;
			// acl
			$usr_login = Session :: get_data('usr.login');
			$this->acl = array ();
			$acl = new gacl_extra();
			$acos_arr = array (
				'alumnos' => array (
					'agregar',
					'editar',
					'eliminar',
					'ubicar',
					'password',
					'kardex',
					'cursos'
					)
					);
					$this->acl = $acl->acl_check_multiple($acos_arr, $usr_login);

		} else {
			$this->option = 'error';
			$this->error = 'El id de alumno no es v&aacute;lido.';
		}
	}


	public function recuperar(){
		try{
			if($this->post("boton")){
				$alumnos = new Alumnos();
				$alumnos = $alumnos->find();
				$recuperados = array();
				mysql_query("BEGIN");
				foreach($alumnos as $alumno){
					$al5 = new Alumnos5();
					$al5 = $al5->find($alumno->id);
					if($al5->id!=""){
						$cambio = false;

						if(trim($alumno->domicilio)==''){
							if(trim($al5->domicilio)!=''){
								$alumno->domicilio = $al5->domicilio;
								$cambio = true;
							}
						}

						if(trim($alumno->tel)==''){
							if(trim($al5->tel)!=''){
								$alumno->tel = $al5->tel;
								$cambio = true;
							}
						}

						if(trim($alumno->cel)==''){
							if(trim($al5->cel)!=''){
								$alumno->cel = $al5->cel;
								$cambio = true;
							}
						}

						if(trim($alumno->mail)==''){
							if(trim($al5->mail)!=''){
								$alumno->mail = $al5->mail;
								$cambio = true;
							}
						}

						if(trim($alumno->curp)==''){
							if(trim($al5->curp)!=''){
								$alumno->curp = $al5->curp;
								$cambio = true;
							}
						}

						if(trim($alumno->fnacimiento)=='' || trim($alumno->fnacimiento)=='0000-00-00'){
							if(trim($al5->fnacimiento)!='' && trim($al5->fnacimiento)!='0000-00-00'){
								$alumno->fnacimiento = $al5->fnacimiento;
								$cambio = true;
							}
						}

						if(trim($alumno->sexo)==''){
							if(trim($al5->sexo)!=''){
								$alumno->sexo = $al5->sexo;
								$cambio = true;
							}
						}

						if(trim($alumno->admision)=='' || trim($alumno->admision)=='0000-00-00'){
							if(trim($al5->admision)!='' && trim($al5->admision)!='0000-00-00' ){
								$alumno->admision = $al5->admision;
								$cambio = true;
							}
						}

						if($cambio){
							if(!$alumno->save()){
								throw new Exception( 'Ocurrio un error al guardar el alumno .'.$alumno->codigo );
							}
							$recuperados[$alumno->id] = $alumno;
						}
					}

					$al26 = new Alumnos26();
					$al26 = $al26->find($alumno->id);
					if($al26->id!=""){
						$cambio = false;

						if(trim($alumno->domicilio)==''){
							if(trim($al26->domicilio)!=''){
								$alumno->domicilio = $al26->domicilio;
								$cambio = true;
							}
						}

						if(trim($alumno->tel)==''){
							if(trim($al26->tel)!=''){
								$alumno->tel = $al26->tel;
								$cambio = true;
							}
						}

						if(trim($alumno->cel)==''){
							if(trim($al26->cel)!=''){
								$alumno->cel = $al26->cel;
								$cambio = true;
							}
						}

						if(trim($alumno->mail)==''){
							if(trim($al26->mail)!=''){
								$alumno->mail = $al26->mail;
								$cambio = true;
							}
						}

						if(trim($alumno->curp)==''){
							if(trim($al26->curp)!=''){
								$alumno->curp = $al26->curp;
								$cambio = true;
							}
						}

						if(trim($alumno->fnacimiento)=='' || trim($alumno->fnacimiento)=='0000-00-00'){
							if(trim($al26->fnacimiento)!='' && trim($al26->fnacimiento)!='0000-00-00'){
								$alumno->fnacimiento = $al26->fnacimiento;
								$cambio = true;
							}
						}

						if(trim($alumno->sexo)==''){
							if(trim($al26->sexo)!=''){
								$alumno->sexo = $al26->sexo;
								$cambio = true;
							}
						}

						if(trim($alumno->admision)=='' || trim($alumno->admision)=='0000-00-00'){
							if(trim($al26->admision)!='' && trim($al26->admision)!='0000-00-00' ){
								$alumno->admision = $al26->admision;
								$cambio = true;
							}
						}

						if($cambio){
							if(!$alumno->save()){
								throw new Exception( 'Ocurrio un error al guardar el alumno .'.$alumno->codigo );
							}
							$recuperados[$alumno->id] = $alumno;
						}
					}
				}

				mysql_query("COMMIT");

				$this->recuperados = $recuperados;
				$this->option = "exito";
			}else{
				$this->option = "captura";
			}
		}catch(Exception $e){
			$this->option = "error";
			$this->error = "Ocurrio un error.".$e;
		}
	}
}
?>