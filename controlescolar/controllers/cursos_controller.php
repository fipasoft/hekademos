<?php


// Hekademos, Creado el 30/09/2008
/**
 * Cursos Controller
 *
 * @package
 * @author     mimeks <mimex@fipasoft.com.mx>
 * @copyright  2008 FiPa Software (contacto at fipasoft.com.mx)
 * @license    http://www.gnu.org/licenses/gpl.html  GNU General Public License (GPL)
 *
 */
Kumbia :: import('app.componentes.*');
Kumbia :: import('lib.excel.main');

class CursosController extends ApplicationController {
	public $template = "system";

	public function agregar($id = '') {
		$this->titulo = '';
		$this->option = '';
		$this->error = '';
		$gp_id = $this->post('gp_id');
		if (isset ($gp_id) && trim($gp_id) != '')
		$id = $gp_id;

		if ($id != '') {
			$ciclo_id = Session :: get_data('ciclo.id');
			$Ciclos = new Ciclos();
			$this->ciclo = $Ciclos->find($ciclo_id);
			$sigCiclo = new Ciclos();
			$sigCiclo = $sigCiclo->find_first("numero='" . $this->ciclo->anterior() . "'");
			if ($this->ciclo->id != '') {
				if($this->ciclo->abierto()){
					$agenda = new Agenda();
					if ($agenda->eventoValido('PLN-GEN', Session :: get_data('ciclo.id'), $this->controlador, $this->accion, Session :: get_data('usr.login')) || $agenda->eventoValido('PLN-GEN-ESP', Session :: get_data('ciclo.id'), $this->controlador, $this->accion, Session :: get_data('usr.login')) || ($sigCiclo->id != '' && ($agenda->eventoValido('PLN-GEN', $sigCiclo->id, $this->controlador, $this->accion, Session :: get_data('usr.login')) || $agenda->eventoValido('PLN-GEN-ESP', $sigCiclo->id, $this->controlador, $this->accion, Session :: get_data('usr.login'))))) {
						$grupo = new Grupos();
						$grupo = $grupo->find($id);
						if ($grupo->id != '') {
							$this->grupo = $grupo;
							// materias asignadas a otros cursos del mismo grupo
							$cursos = new Cursos();
							$cursos = $cursos->find("conditions: grupos_id = '" . $grupo->id . "'", "fields: materias_id");

							$c = '';
							foreach ($cursos as $curso) {
								$c .= "AND materias.id != '" . $curso->materias_id . "' ";
							}
							// materias
							$materias = new Materias();
							//$materias = $materias->find("conditions: semestre = '" . $grupo->grado . "' " . $c);
							$materias = $materias->porOferta($grupo->oferta_id, $grupo->grado, $c);
							if (count($materias) > 0) {
								$this->materias = $materias;
								// profesores
								$profesores = new Profesores();
								$profesores = $profesores->find("fields: id, codigo, ap, am, nombre", "order: ap, am, nombre");
								$this->profesores = $profesores;
								// dias
								$dias = new Dias();
								$dias = $dias->find("order: id");
								$this->dias = $dias;
								// aulas
								$aulas = new Aulas();
								$aulas = $aulas->find("order: clave");
								$this->aulas = $aulas;

								// salida
								$this->option = 'captura';
								$this->titulo = "Agregar Curso.";
							} else {
								$this->option = 'error';
								$this->error = ' No se pueden asignar mas cursos a este grupo.';
							}
						} else {
							$this->option = 'error';
							$this->error = ' El grupo no existe.';
						}
					} else {
						$this->option = 'error';
						$this->error = ' La agenda no permite agregar cursos.';
					}
				} else {
					$this->option = 'error';
					$this->error = ' El ciclo esta cerrado.';
				}
			} else {
				$this->option = 'error';
				$this->error .= ' Error al seleccionar el ciclo escolar.';
			}
		} else
		if ($this->post('grupos_id') != '') {
			$grupo = new Grupos();
			$grupo = $grupo->find($this->post('grupos_id'));
			if ($grupo->id != '') {
				$ciclo_id = $grupo->ciclos_id;
				$Ciclos = new Ciclos();
				$this->ciclo = $Ciclos->find($ciclo_id);
				if($this->ciclo->abierto()){
					$sigCiclo = new Ciclos();
					$sigCiclo = $sigCiclo->find_first("numero='" . $this->ciclo->anterior() . "'");
					$agenda = new Agenda();
					if ($agenda->eventoValido('PLN-GEN', $this->ciclo->id, $this->controlador, $this->accion, Session :: get_data('usr.login')) || $agenda->eventoValido('PLN-GEN-ESP', $this->ciclo->id, $this->controlador, $this->accion, Session :: get_data('usr.login')) || ($sigCiclo->id != '' && ($agenda->eventoValido('PLN-GEN', $sigCiclo->id, $this->controlador, $this->accion, Session :: get_data('usr.login')) || $agenda->eventoValido('PLN-GEN-ESP', $sigCiclo->id, $this->controlador, $this->accion, Session :: get_data('usr.login'))))) {
						$curso = new Cursos();
						$curso->grupos_id = $this->post('grupos_id');
						$curso->profesores_id = $this->post('profesores_id');
						$curso->materias_id = $this->post('materias_id');
						$curso->estado_id = 1;
						$fecha_valida=true;
						if($grupo->oferta_id==2){
							$fecha_valida=$curso->fechaInicioValido(Utils :: convierteFechaMySql($this->post('inicio')));
							if($fecha_valida){
								$curso->inicio=Utils :: convierteFechaMySql($this->post('inicio'));
							}
						}else{
							$agenda = new Agenda();
							$evento = new Eventos();
							$rol = new Roles();

							$evento = $evento->find_first(
								"conditions: clave = 'CRS-PER'"
								);

								$rol = $rol->find_first(
								"conditions: eventos_id = '" . $evento->id . "' "
								);

								$periodo = $agenda->find_first(
								"conditions: " .
							    "ciclos_id = '" . $this->ciclo->id . "' " .
								"AND roles_id = '" . $rol->id . "' "
								);

								$curso->inicio=$periodo->inicio;

						}

						/********** Revisar si no hay colisiones en el horario **************/
						$hvalido = true;
						foreach ($this->post('horarios') as $dia => $valores) {
							if ($valores['entrada'] != '' && $valores['salida'] != '' && $valores['dia'] != '') {
								$horario = new Horarios();
								$curso_id='';
								$grupos_id=$this->post('grupos_id');
								$profesores_id=$this->post('profesores_id');
								$materias_id=$this->post('materias_id');
								$dia=$valores['dia'];
								$entrada=$valores['entrada'];
								$salida=$valores['salida'];
								$aula=$valores['aula'];
								$inicio=$this->post('inicio');
								$validacion = $horario->valida($curso_id,$grupos_id,$profesores_id,$materias_id,$dia,$entrada,$salida,$aula,$inicio);
								if($validacion[0]==false){
									$this->option = 'conflicto';
									if(is_array($validacion[1])){
										$this->error = '<span class="false"> CONFLICTOS: </span>';
										$this->error .= '<ul style="color: black; font-weight:normal">';
										foreach($validacion[1] as $conflicto ){
											$this->error .= '<li>'.$conflicto.'</li>';
										}
										$this->error .= '</ul>';
									}
									$hvalido = false;
									break;

								}
							}
						}
						/**************************/

						if($hvalido){
							if($fecha_valida){
								if ($curso->save()) {
									$this->option = 'exito';
									foreach ($this->post('horarios') as $dia => $valores) {
										if ($valores['entrada'] != '' && $valores['salida'] != '' && $valores['dia'] != '') {
											$horario = new Horarios();
											$horario->cursos_id = $curso->id;
											$horario->dias_id = $valores['dia'];
											$horario->inicio = $valores['entrada'];
											$horario->fin = $valores['salida'];
											$horario->aulas_id = $valores['aula'];
											if (!$horario->save()) {
												$this->option = 'error';
												$this->error .= ' Error al guardar el horario en la BD.' . $curso->show_message();
											} else
											$curso->asignado(false);
										}
									}
									$historial=new Historial();
									$historial->ciclos_id= Session :: get_data('ciclo.id');
									$historial->usuario=Session :: get_data('usr.login');
									$materia=$curso->materia();
									$historial->descripcion='Se agrego el curso de '.$materia->nombre.' para el grupo '.$grupo->verInfo().' '.$grupo->verOferta();
									$historial->controlador="cursos";
									$historial->accion="agregar";
									$historial->saved_at=date("Y-m-d H:i:s");//2009-01-20 14:28:29
									$historial->save();
										
								} else {
									$this->option = 'error';
									$this->error .= ' Error al guardar en la BD.' ;
								}
							}else{
								$this->option = 'error';
								$this->error .= 'La fecha no encaja en el periodo ordinario de cursos.'.date('Y-m-d',$curso->fechaFin(Utils :: convierteFechaMySql($this->post('inicio')))->format('U'));
							}
						} else {
							$this->option = 'conflicto';
							$this->error = 'Se encontraron los siguientes '.$this->error;
						}
					} else {
						$this->option = 'error';
						$this->error = ' La agenda no permite agregar cursos.';
					}
				} else {
					$this->option = 'error';
					$this->error = ' El ciclo esta cerrado.';
				}
			} else {
				$this->option = 'error';
				$this->error = ' El grupo no existe.';
			}
		} else {
			$ciclo_id = Session :: get_data('ciclo.id');
			$Ciclos = new Ciclos();
			$this->ciclo = $Ciclos->find($ciclo_id);
			if($this->ciclo->abierto()){
				$sigCiclo = new Ciclos();
				$sigCiclo = $sigCiclo->find_first("numero='" . $this->ciclo->anterior() . "'");
				$agenda = new Agenda();
				if ($agenda->eventoValido('PLN-GEN', Session :: get_data('ciclo.id'), $this->controlador, $this->accion, Session :: get_data('usr.login')) || $agenda->eventoValido('PLN-GEN-ESP', Session :: get_data('ciclo.id'), $this->controlador, $this->accion, Session :: get_data('usr.login')) || ($sigCiclo->id != '' && ($agenda->eventoValido('PLN-GEN', $sigCiclo->id, $this->controlador, $this->accion, Session :: get_data('usr.login')) || $agenda->eventoValido('PLN-GEN-ESP', $sigCiclo->id, $this->controlador, $this->accion, Session :: get_data('usr.login'))))) {
					$this->option = 'grupo';
					$grupos = new Grupos();
					$this->grupos = $grupos->disponiblesParaAgregarCurso();
					$this->titulo = "Seleccione el grupo.";
				} else {
					$this->option = 'error';
					$this->error = ' La agenda no permite agregar cursos.';
				}
			} else {
				$this->option = 'error';
				$this->error = ' El ciclo esta cerrado.';
			}

		}
	}


	public function aulas(){
		if($this->post("ciclos_id")!=""){
			$this->option = "exito";
		}else{
			$ciclo_id = Session :: get_data('ciclo.id');
			$ciclo = new Ciclos();
			$ciclo = $ciclo->find($ciclo_id);
			if($ciclo->id!=''){
				$this->option = "captura";
				$this->ciclo = $ciclo;
					
			}else{
				$this->option = "error";
				$this->error="No se selecciono el ciclo.";
			}
		}
	}


	public function copiar(){
		if($this->post("ciclos_id")){
			$this->option = 'exito';
			$ciclo_id = $this->post('ciclos_id');
			$ciclo = new Ciclos();
			$ciclo = $ciclo->find($ciclo_id);
			$this->ciclo = $ciclo;
			$this->simular = $this->post('simular');
			if($ciclo->id==""){
				$this->option = "error";
				$this->error = "No se especifico el ciclo.";
			}
		}else{
			$this->option = 'captura';
			$ciclo_id = Session :: get_data('ciclo.id');
			$ciclo = new Ciclos();
			$ciclo = $ciclo->find($ciclo_id);
			$this->ciclo = $ciclo;
			if($ciclo->id==""){
				$this->option = "error";
				$this->error = "No se especifico el ciclo.";
			}
		}

	}

	public function editar($id = '') {
		$this->option = '';
		$this->error = '';
		$curso = new Cursos();
		if ($id != '') {
			$curso = $curso->find($id);
			if ($curso->id != '') {

				$Ciclos = new Ciclos();
				$this->ciclo = $curso->ciclo();
				if($this->ciclo->abierto()){
					$sigCiclo = new Ciclos();
					$sigCiclo = $sigCiclo->find_first("numero='" . $this->ciclo->anterior() . "'");
					$agenda = new Agenda();
					$grupo = new Grupos();
					$this->grupo=$grupo = $grupo->find($curso->grupos_id);
					if ($agenda->eventoValido('PLN-GEN', $this->ciclo->id, $this->controlador, $this->accion, Session :: get_data('usr.login')) || $agenda->eventoValido('PLN-GEN-ESP', $this->ciclo->id, $this->controlador, $this->accion, Session :: get_data('usr.login')) || ($sigCiclo->id != '' && ($agenda->eventoValido('PLN-GEN', $sigCiclo->id, $this->controlador, $this->accion, Session :: get_data('usr.login')) || $agenda->eventoValido('PLN-GEN-ESP', $sigCiclo->id, $this->controlador, $this->accion, Session :: get_data('usr.login'))))) {

						$this->usr_grupos = $usr_grupos = Session :: get_data('usr.grupos');
						if (in_array('root', $usr_grupos) || (in_array('plantilla', $usr_grupos) && ($curso->enProceso() || $curso->rechazado())) || ($curso->aprobado() && (in_array('director', $usr_grupos) || in_array('secretario', $usr_grupos)))) {
							$Ciclos = new Ciclos();
							$this->ciclo = $Ciclos->find($grupo->ciclos_id);
							$this->grupo = $grupo;

							$this->curso = $curso;
							// materias asignadas a otros cursos del mismo grupo
							$cursos = new Cursos();
							$cursos = $cursos->find("conditions: grupos_id = '" . $grupo->id . "'", "fields: materias_id");
							$c = '';
							foreach ($cursos as $curso) {
								$c .= "AND materias.id != '" . $curso->materias_id . "' ";
							}
							// materias
							$materias = new Materias();
							//$materias = $materias->find("conditions: semestre = '" . $grupo->grado . "' " . $c);
							$materias = $materias->porOferta($grupo->oferta_id, $grupo->grado, $c);
							$this->materias = $materias;
							// profesores
							$profesores = new Profesores();
							$profesores = $profesores->find("fields: id, codigo, ap, am, nombre", "order: ap, am, nombre");
							$this->profesores = $profesores;
							// dias
							$dias = new Dias();
							$dias = $dias->find("order: id");
							$this->dias = $dias;
							// aulas
							$aulas = new Aulas();
							$aulas = $aulas->find("order: clave");
							$this->aulas = $aulas;

							// salida
							$this->option = 'captura';
						} else {
							$this->option = 'error';
							$this->error = ' No tiene permiso para editar el curso.';
						}
					} else {
						$this->option = 'error';
						$this->error = ' La agenda no permite editar cursos.';
					}
				} else {
					$this->option = 'error';
					$this->error = ' El ciclo esta cerrado.';
				}

			} else {
				$this->option = 'error';
				$this->error = ' El curso no existe.';
			}
		} else
		if ($this->post('id') != '') {
			$curso = $curso->find($this->post('id'));
			if ($curso->id != '') {
				$Ciclos = new Ciclos();
				$this->ciclo = $curso->ciclo();
				if($this->ciclo->abierto()){
					$sigCiclo = new Ciclos();
					$sigCiclo = $sigCiclo->find_first("numero='" . $this->ciclo->anterior() . "'");
					$agenda = new Agenda();
					$grupo = new Grupos();
					$this->grupo=$grupo = $grupo->find($curso->grupos_id);
					if ($agenda->eventoValido('PLN-GEN', $this->ciclo->id, $this->controlador, $this->accion, Session :: get_data('usr.login')) || $agenda->eventoValido('PLN-GEN-ESP', $this->ciclo->id, $this->controlador, $this->accion, Session :: get_data('usr.login')) || ($sigCiclo->id != '' && ($agenda->eventoValido('PLN-GEN', $sigCiclo->id, $this->controlador, $this->accion, Session :: get_data('usr.login')) || $agenda->eventoValido('PLN-GEN-ESP', $sigCiclo->id, $this->controlador, $this->accion, Session :: get_data('usr.login'))))) {

						$usr_grupos = Session :: get_data('usr.grupos');
						$curso->profesores_id = $this->post('profesores_id');
						if (!$curso->aprobado() || in_array('root', $usr_grupos))
						$curso->materias_id = $this->post('materias_id');

						$fecha_valida=true;
						if($grupo->oferta_id==2 && ($curso->enProceso() || $curso->rechazado())){
							$fecha_valida=$curso->fechaInicioValido(Utils :: convierteFechaMySql($this->post('inicio')));
							if($fecha_valida){
								$curso->inicio=Utils :: convierteFechaMySql($this->post('inicio'));
							}
						}

						/********** Revisar si no hay colisiones en el horario **************/
						$hvalido = true;
						foreach ($this->post('horarios') as $dia => $valores) {
							if ($valores['entrada'] != '' && $valores['salida'] != '' && $valores['dia'] != '') {
								$horario = new Horarios();
								$curso_id=$curso->id;
								$grupos_id=$curso->grupos_id;
								$profesores_id=$this->post('profesores_id');
								$materias_id=$this->post('materias_id');
								$dia=$valores['dia'];
								$entrada=$valores['entrada'];
								$salida=$valores['salida'];
								$aula=$valores['aula'];
								$inicio=$this->post('inicio');
								$validacion = $horario->valida($curso_id,$grupos_id,$profesores_id,$materias_id,$dia,$entrada,$salida,$aula,$inicio);
								if($validacion[0]==false){
									$this->option = 'conflicto';
									if(is_array($validacion[1])){
										$this->error = '<span class="false"> CONFLICTOS: </span>';
										$this->error .= '<ul style="color: black; font-weight:normal">';
										foreach($validacion[1] as $conflicto ){
											$this->error .= '<li>'.$conflicto.'</li>';
										}
										$this->error .= '</ul>';
									}
									$hvalido = false;
									break;

								}
							}
						}
						/**************************/
						if($hvalido){
							if($fecha_valida){
								if ($curso->save()){
										$this->option = 'exito';
										// eliminando horarios anteriores
										$horarios = new Horarios();
										if ($horarios->delete("cursos_id = '" . $curso->id . "'")) {
											foreach ($this->post('horarios') as $dia => $valores) {
												if ($valores['entrada'] != '' && $valores['salida'] != '' && $valores['dia'] != '') {
													$horario = new Horarios();
													$horario->cursos_id = $curso->id;
													$horario->dias_id = $valores['dia'];
													$horario->inicio = $valores['entrada'];
													$horario->fin = $valores['salida'];
													$horario->aulas_id = $valores['aula'];
													if (!$horario->save()) {
														$this->option = 'error';
														$this->error .= ' Error al guardar el nuevo horario en la BD.' . $curso->show_message();
													}
												}
											}
										} else {
											$this->option = 'error';
											$this->error .= ' Error al eliminar los horarios anteriores de la BD.' . $curso->show_message();
										}
										$historial=new Historial();
										$historial->ciclos_id= Session :: get_data('ciclo.id');
										$historial->usuario=Session :: get_data('usr.login');
										$materia=$curso->materia();
										$historial->descripcion='Se edito el curso de '.$materia->nombre.' del grupo '.$grupo->verInfo().' '.$grupo->verOferta();
										$historial->controlador="cursos";
										$historial->accion="editar";
										$historial->saved_at=date("Y-m-d H:i:s");//2009-01-20 14:28:29
										$historial->save();
									
								} else {
									$this->option = 'error';
									$this->error .= ' Error al guardar en la BD.' . $curso->show_message();
								}
							} else {
								$this->option = 'error';
								$this->error .= 'La fecha no encaja en el periodo ordinario de cursos';
							}
						} else {
							$this->option = 'conflicto';
							$this->error = 'Se encontraron los siguientes '.$this->error;
						}
					} else {
						$this->option = 'error';
						$this->error = ' La agenda no permite editar cursos.';
					}
				} else {
					$this->option = 'error';
					$this->error = ' El ciclo esta cerrado.';
				}
			} else {
				$this->option = 'error';
				$this->error = ' El curso no existe.';
			}
		} else {
			$this->option = 'error';
			$this->error = ' No se especific&oacute; el curso.';
		}

	}

	public function eliminar($id = '') {

		if ($id != '') {
			$this->option = 'captura';
			$id = intval($id, 10);
			$Cursos = new Cursos();
			$this->curso = $Cursos->find($id);
			if ($this->curso->id != '') {
				$Ciclos = new Ciclos();
				$this->ciclo = $this->curso->ciclo();
				if($this->ciclo->abierto()){
					$sigCiclo = new Ciclos();
					$sigCiclo = $sigCiclo->find_first("numero='" . $this->ciclo->anterior() . "'");
					$agenda = new Agenda();
					if ($agenda->eventoValido('PLN-GEN', $this->ciclo->id, $this->controlador, $this->accion, Session :: get_data('usr.login')) || $agenda->eventoValido('PLN-GEN-ESP', $this->ciclo->id, $this->controlador, $this->accion, Session :: get_data('usr.login')) || ($sigCiclo->id != '' && ($agenda->eventoValido('PLN-GEN', $sigCiclo->id, $this->controlador, $this->accion, Session :: get_data('usr.login')) || $agenda->eventoValido('PLN-GEN-ESP', $sigCiclo->id, $this->controlador, $this->accion, Session :: get_data('usr.login'))))) {

						$usr_grupos = Session :: get_data('usr.grupos');
						if (!in_array('root', $usr_grupos) && !(in_array('plantilla', $usr_grupos) && ($this->curso->enProceso() || $this->curso->rechazado())) && !($this->curso->aprobado() && (in_array('director', $usr_grupos) || in_array('secretario', $usr_grupos)))) {
							$this->option = 'error';
							$this->error = ' No tiene permiso para eliminar el curso.';
						}

					} else {
						$this->option = 'error';
						$this->error = ' La agenda no permite eliminar cursos.';
					}
				} else {
					$this->option = 'error';
					$this->error = ' El ciclo esta cerrado.';
				}
			} else {
				$this->option = 'error';
				$this->error = ' El curso no existe.';
			}
		} else
		if ($this->post('id') != '') {
			$this->option = '';
			$this->error = '';
			$Cursos = new Cursos();
			$curso = $Cursos->find($this->post('id'));
			if ($curso->id != '') {
				$Ciclos = new Ciclos();
				$this->ciclo = $curso->ciclo();
				if($this->ciclo->abierto()){
					$sigCiclo = new Ciclos();
					$sigCiclo = $sigCiclo->find_first("numero='" . $this->ciclo->anterior() . "'");
					$agenda = new Agenda();
					if ($agenda->eventoValido('PLN-GEN', $this->ciclo->id, $this->controlador, $this->accion, Session :: get_data('usr.login')) || $agenda->eventoValido('PLN-GEN-ESP', $this->ciclo->id, $this->controlador, $this->accion, Session :: get_data('usr.login')) || ($sigCiclo->id != '' && ($agenda->eventoValido('PLN-GEN', $sigCiclo->id, $this->controlador, $this->accion, Session :: get_data('usr.login')) || $agenda->eventoValido('PLN-GEN-ESP', $sigCiclo->id, $this->controlador, $this->accion, Session :: get_data('usr.login'))))) {

						$usr_grupos = Session :: get_data('usr.grupos');
						try {
							// eliminado el curso
							$grupo=$curso->grupo();
							if (in_array('root', $usr_grupos)) {
								mysql_query("BEGIN") or die("CUR_ELI_1");
								$horarios = new Horarios();
								$horarios->delete('cursos_id=' . $curso->id);

								$asistencias = new Asistencias();
								$asistencias->delete('cursos_id=' . $curso->id);

								$calificaciones = new Calificaciones();
								$calificaciones->delete('cursos_id=' . $curso->id);

								$parciales = new Calificacionesparciales();
								$parciales->delete('cursos_id=' . $curso->id);

								$alumnoscursos = new Alumnoscursos();
								$alumnoscursos = $alumnoscursos->find('cursos_id=' . $curso->id);
								foreach ($alumnoscursos as $ac) {
									$faltas = new Faltas();
									$faltas->delete("alumnoscursos_id=" . $ac->id);

									$alumnoarticulo = new Alumnosconarticulo();
									$alumnoarticulo->delete("alumnoscursos_id=" . $ac->id);
									$ac->delete();
								}

								if ($Cursos->delete($this->post('id'))) {
									$this->option = 'exito';
									$historial=new Historial();
									$historial->ciclos_id= Session :: get_data('ciclo.id');
									$historial->usuario=Session :: get_data('usr.login');
									$materia=$curso->materia();
									$historial->descripcion='Se elimino el curso de '.$materia->nombre.' del grupo '.$grupo->verInfo().' '.$grupo->verOferta();
									$historial->controlador="cursos";
									$historial->accion="eliminar";
									$historial->saved_at=date("Y-m-d H:i:s");//2009-01-20 14:28:29
									$historial->save();
									mysql_query("COMMIT") or die("CUR_ELI_1");

								} else {
									$this->option = 'error';
									$this->error .= ' Error al intentar eliminar de la BD.';
									mysql_query("ROLLBACK") or die("CUR_ELI_1");
								}

							}
							elseif ((in_array('plantilla', $usr_grupos) && ($this->curso->enProceso() || $this->curso->rechazado())) || ($this->curso->aprobado() && (in_array('director', $usr_grupos) || in_array('secretario', $usr_grupos)))) {
								$asistencias = new Asistencias();
								$calificaciones = new Calificaciones();
								$parciales = new Calificacionesparciales();
								if (!$asistencias->exists('cursos_id=' . $curso->id) && !$calificaciones->exists('cursos_id=' . $curso->id) && !$parciales->exists('cursos_id=' . $curso->id)) {
									mysql_query("BEGIN") or die("CUR_ELI_1");
									$horarios = new Horarios();
									$horarios->delete('cursos_id=' . $curso->id);
									$alumnoscursos = new Alumnoscursos();
									$alumnoscursos = $alumnoscursos->find('cursos_id=' . $curso->id);
									foreach ($alumnoscursos as $ac) {
										$faltas = new Faltas();
										$faltas->delete("alumnoscursos_id=" . $ac->id);

										$alumnoarticulo = new Alumnosconarticulo();
										$alumnoarticulo->delete("alumnoscursos_id=" . $ac->id);
										$ac->delete();
									}

									$cc = $curso->ccategoria();
									if($cc->delete($cc->id)){
											
										if ($Cursos->delete($this->post('id'))) {
											$this->option = 'exito';
											$historial=new Historial();
											$historial->ciclos_id= Session :: get_data('ciclo.id');
											$historial->usuario=Session :: get_data('usr.login');
											$materia=$curso->materia();
											$historial->descripcion='Se elimino el curso de '.$materia->nombre.' del grupo '.$grupo->verInfo().' '.$grupo->verOferta();
											$historial->controlador="cursos";
											$historial->accion="eliminar";
											$historial->saved_at=date("Y-m-d H:i:s");//2009-01-20 14:28:29
											$historial->save();
											mysql_query("COMMIT") or die("CUR_ELI_1");
										} else {
											$this->option = 'error';
											$this->error .= ' Error al intentar eliminar de la BD.';
											mysql_query("ROLLBACK") or die("CUR_ELI_1");
										}
									} else {
										$this->option = 'error';
										$this->error .= ' Error al intentar eliminar de la BD.';
										mysql_query("ROLLBACK") or die("CUR_ELI_1");
									}
								} else {
									$this->option = 'error';
									$this->error .= ' No se pudo eliminar al curso debido a que cuenta con calificaciones <br/> y/o asistencias. Solo un usuario del grupo root puede eliminarlo.';

								}
							} else {
								$this->option = 'error';
								$this->error .= ' No tiene permiso para eliminar el curso.';

							}

						} catch (Exception $e) {
							mysql_query("ROLLBACK") or die("CUR_ELI_1");
							$this->option = 'error';
							$this->error .= ' Error al intentar eliminar de la BD.';
						}

					} else {
						$this->option = 'error';
						$this->error = ' La agenda no permite eliminar cursos.';
					}
				} else {
					$this->option = 'error';
					$this->error = ' El ciclo esta cerrado.';
				}
			} else {
				$this->option = 'error';
				$this->error = ' El curso no existe.';
			}
		} else {
			$this->option = 'error';
			$this->error = ' No se especific&oacute; el curso a eliminar.';
		}

	}

	public function exportar($grp_id = '') {
		$this->set_response("view");
		require ('app/reportes/xls.cursos.php');
		$ciclo_id = Session :: get_data('ciclo.id');
		$reporte = new XLSCursos($ciclo_id, $grp_id);
		$reporte->generar();
	}

	public function grupoexportar($grp_id = '') {
		$this->set_response("view");
		require ('app/reportes/xls.grupoexportar.php');
		$reporte = new XLSGrupoexportar($grp_id);
		$reporte->generar();
	}

	public function fecha(){
		$this->set_response("view");
		$materias_id=$this->post('materias_id');
		$ciclo_id=$this->post('ciclos_id');
		$materia=new Materias();
		$this->materia=$materia=$materia->find($materias_id);
		if($materia->id!=''){
			$oferta=new Ofertasmaterias();
			$this->oferta=$oferta->find_first("materias_id=".$materia->id);
			$agenda=new Agenda();
			$evento = new Eventos();
			$rol = new Roles();

			$evento = $evento->find_first(
			"conditions: clave = 'CRS-INT-INI'"
			);
			$rol = $rol->find_first(
			"conditions: eventos_id = '" . $evento->id . "' "
			);
			$periodo = $agenda->find_first(
			"conditions: " .
			    "ciclos_id = '" . $ciclo_id . "' " .
				"AND roles_id = '" . $rol->id . "' " .
				"AND activo = '1' "
				);

				$this->crs_ini=$periodo;

				$agenda=new Agenda();
				$evento = new Eventos();
				$rol = new Roles();

				$evento = $evento->find_first(
			"conditions: clave = 'CRS-PER-INI'"
			);
			$rol = $rol->find_first(
			"conditions: eventos_id = '" . $evento->id . "' "
			);
			$periodo = $agenda->find_first(
			"conditions: " .
			    "ciclos_id = '" . $ciclo_id . "' " .
				"AND roles_id = '" . $rol->id . "' " .
				"AND activo = '1' "
				);

				$this->crs_ini_2=$periodo;

				$agenda=new Agenda();
				$evento = new Eventos();
				$rol = new Roles();

				$evento = $evento->find_first(
			"conditions: clave = 'CRS-PER'"
			);
			$rol = $rol->find_first(
			"conditions: eventos_id = '" . $evento->id . "' "
			);
			$periodo = $agenda->find_first(
			"conditions: " .
			    "ciclos_id = '" . $ciclo_id . "' " .
				"AND roles_id = '" . $rol->id . "' " .
				"AND activo = '1' "
				);
				$this->crs=$periodo;

		}
	}

	public function concentrado($grp_id = '') {
		require ('app/reportes/xls.concentrado.php');
		$ciclo_id = Session :: get_data('ciclo.id');

		$grupos = new Grupos();
		$ciclos = new Ciclos();
		$ciclo = $ciclos->find($ciclo_id);
		$this->ciclo = $ciclo;

		if ($grp_id != '') {
			$this->set_response("view");
			$grupo = $grupos->find($grp_id);
			$reporte = new XLSConcentrado($grp_id);
			$reporte->generar();
		} else {
			$b = new Busqueda('cursos', 'index');
			$c = $b->cargar();

			$from = "cursos " .
			"INNER JOIN grupos ON cursos.grupos_id = grupos.id " .
			"INNER JOIN materias ON cursos.materias_id  = materias.id " .
			"INNER JOIN profesores ON cursos.profesores_id  = profesores.id ";

			$grupos = $grupos->find_all_by_sql("SELECT " .
			"grupos.id, " .
			"grupos.grado, " .
			"grupos.letra, " .
			"grupos.turno " .
			"FROM " . $from .
			"WHERE grupos.ciclos_id = '" . $ciclo->id . "' " .
			($c == "" ? "" : "AND " . $c . " ") .
			"GROUP BY grupos.id " .
			"ORDER BY grupos.turno, grupos.grado, grupos.letra, materias.nombre ");
			if (count($grupos) > 0) {
				$this->set_response("view");
				if (!file_exists('./public/concentrados/')) {
					mkdir('./public/concentrados/');
				}
				$salida = '';
				foreach ($grupos as $grupo) {
					$encargados = $grupo->encargados();
					if (is_array($encargados)) {
						foreach ($encargados as $encargado) {
							$lgn = $encargado->login();
							ob_end_clean();
							ob_start();
							$reporte = new XLSConcentrado($grupo->id);
							$n = $reporte->getNombre();
							if (!file_exists('./public/concentrados/' . $lgn . '/')) {
								mkdir('./public/concentrados/' . $lgn . '/');
							}
							$f = fopen('./public/concentrados/' . $lgn . '/' . $n, "w");
							$reporte->close();
							fwrite($f, ob_get_contents());
							fclose($f);
							$salida .= 'GRUPO ' . $grupo->verInfo() . ', OK!<br />';
						}
					}
				}
				ob_end_clean();
				echo $salida;
			}
		}
	}

	public function generar($id = '') {
		$this->error = '';
		$ciclo_id = Session :: get_data('ciclo.id');
		$ciclos = new Ciclos();
		$ciclo = $ciclos->find($ciclo_id);
		$Profesores = new Profesores();
		$profesor = $Profesores->find_first("conditions: codigo = 'STAFF'");
		$cursos = new Cursos();
		$this->cursos = array ();
		$this->grupo_id = $id;

		if ($id == '') {
			$Grupos = new Grupos();
			$Grupos = $Grupos->find("conditions: ciclos_id = '" . $ciclo->id . "'", "order: turno, grado, letra");

			foreach ($Grupos as $grupo) {
				if ($cursos->count("grupos_id = '" . $grupo->id . "'") == 0) {
					$Materias = new Materias();
					$mats = $Materias->find("conditions: semestre = '" . $grupo->grado . "' " .
					"AND tipo != 'OPT' " .
					"AND tipo != 'PRO' ");
					foreach ($mats as $materia) {
						$curso = new Cursos();
						$curso->grupos_id = $grupo->id;
						$curso->materias_id = $materia->id;
						$curso->profesores_id = $profesor->id;
						if ($curso->save()) {
							$this->error = 'No se pudo crear el curso ' .
							$materia->nombre . ' ' .
							$profesor->nombre();
						}
						$this->cursos[] = $curso;
					}
				} else {
					$this->error .= 'Existe informaci&oacute;n de cursos para grupo ' .
					$grupo->verInfo() . '.<br />';
				}
			}
		} else {
			$grupo = new Grupos();
			$grupo = $grupo->find($id);
			if ($grupo->id != '') {
				if ($cursos->count("grupos_id = '" . $grupo->id . "'") == 0) {
					$Materias = new Materias();
					$mats = $Materias->find("conditions: semestre = '" . $grupo->grado . "' " .
					"AND tipo != 'OPT' " .
					"AND tipo != 'PRO' ");
					foreach ($mats as $materia) {
						$curso = new Cursos();
						$curso->grupos_id = $grupo->id;
						$curso->materias_id = $materia->id;
						$curso->profesores_id = $profesor->id;
						$curso->save();
						$this->cursos[] = $curso;
					}
				} else {
					$this->error = 'No se pudo generar. ' .
					'Ya existen cursos en el grupo ' .
					$grupo->verInfo() . '.';
				}
			} else {
				$this->error = 'El grupo no existe.';
			}
		}
	}

	public function grupo($id, $pag) {
		if (isset ($id) && trim($id) != '') {
			$Cursos = new Cursos();
			$controlador = $this->controlador;
			$accion = $this->accion;
			$path = $this->path = KUMBIA_PATH;
			$ciclo_id = Session :: get_data('ciclo.id');
			$this->acl = array ();
			$this->grp = $id;
			$this->usr_grupos = Session :: get_data('usr.grupos');

			$acceso = Session :: get_data('usr.acceso');
			$acl = new gacl();

			// acl
			$usr_login = Session :: get_data('usr.login');
			$this->acl = array ();
			$acl = new gacl_extra();
			$acos_arr = array (
				'cursos' => array (
					'agregar',
					'exportar',
					'generar',
					'buscar',
					'ver',
					'grupo',
					'editar',
					'eliminar'
					)
					);
					$this->acl = $acl->acl_check_multiple($acos_arr, $usr_login);

					$acos_arr = array (
				'inscripcion' => array (
					'agregar',
					'eliminar'
					)
					);

					$this->acl_inscripcion = $acl->acl_check_multiple($acos_arr, $usr_login);
					$grupos = new Grupos();
					$grupo = $this->grupo = $grupos->find($id);
					if ($this->acl['agregar']) {
						if ($grupo->nCursos() <= 0) {
							$this->acl['agregar'] = false;
						}
					}

					// busqueda
					$b = new Busqueda($controlador, $accion);
					$b->establecerCondicion('profesor', "CONCAT(profesores.nombre, ' ', profesores.ap, ' ', profesores.am) " .
			"LIKE '%" . $b->campo('profesor') . "%' ");

					$c .= ($c == '' ? '' : 'AND ') . "grupos_id = '" . $grupo->id . "'";

					$this->busqueda = $b;

					// cuenta todos los registros
					$from = "cursos " .
			"INNER JOIN grupos ON cursos.grupos_id = grupos.id " .
			"INNER JOIN materias ON cursos.materias_id  = materias.id " .
			"INNER JOIN profesores ON cursos.profesores_id  = profesores.id ";
					$this->registros = $Cursos->count_by_sql("SELECT COUNT(*) " .
			"FROM " . $from .
			"WHERE grupos.ciclos_id = '" . $ciclo_id . "' " .
					($c == '' ? '' : 'AND ' . $c));

					// paginacion
					$paginador = new Paginador($controlador, $accion, $id);

					if ($pag != '') {
						$paginador->guardarPagina($pag);
					}

					$paginador->estableceRegistros($this->registros);
					$paginador->generar();
					$this->paginador = $paginador;

					// ejecuta la consulta
					$this->cursos = $Cursos->find_all_by_sql("SELECT " .
			"materias.nombre as materia, " .
			"cursos.id, cursos.grupos_id, " .
			"cursos.materias_id," .
			"cursos.estado_id, " .
			"cursos.profesores_id, " .
			"cursos.inicio " .
			"FROM " . $from .
			"WHERE grupos.ciclos_id = '" . $ciclo_id . "' " .
					($c == "" ? "" : "AND " . $c . " ") .
			"ORDER BY grupos.turno, grupos.grado, grupos.letra, materias.nombre " .
			"LIMIT " . ($paginador->pagina() * $paginador->rpp()) . ', ' . $paginador->rpp());
					$Ciclos = new Ciclos();
					$this->ciclo = $Ciclos->find($ciclo_id);
					$Ciclos = $Ciclos->find('order: numero DESC');
					$this->ciclos = $Ciclos;
					$this->acceso = $acceso;
					$this->option = 'vista';
					$this->acl = $this->acl['cursos'];
					$this->acl_inscripcion = $this->acl_inscripcion['inscripcion'];

		} else {
			$this->option = 'error';
			$this->error = 'No tiene permiso para accesar a la pagina.';
		}

	}

	public function index($pag = '') {
		$Cursos = new Cursos();
		$controlador = $this->controlador;
		$accion = $this->accion;
		$path = $this->path = KUMBIA_PATH;
		$ciclo_id = Session :: get_data('ciclo.id');
		$this->acl = array ();
		$acceso = Session :: get_data('usr.acceso');
		$acl = new gacl();

		$this->usr_grupos = Session :: get_data('usr.grupos');

		$this->obs = 'no_';
		if (in_array('director', $this->usr_grupos) || in_array('secretario', $this->usr_grupos) || in_array('plantilla', $this->usr_grupos))
		$this->obs = '';
		// acl
		$this->login = $usr_login = Session :: get_data('usr.login');
		$this->acl = array ();
		$acl = new gacl_extra();
		$acos_arr = array (
			'cursos' => array (
				'agregar',
				'exportar',
				'generar',
				'buscar',
				'ver',
				'grupo',
				'editar',
				'eliminar',
				'copiar',
				'estado'
				)
				);
				$this->acl = $acl->acl_check_multiple($acos_arr, $usr_login);

				$acos_arr = array (
			'inscripcion' => array (
				'agregar',
				'eliminar'
				)
				);

				$this->acl_inscripcion = $acl->acl_check_multiple($acos_arr, $usr_login);
				// busqueda
				$b = new Busqueda($controlador, $accion);
				$b->establecerCondicion('profesor', "CONCAT(profesores.nombre, ' ', profesores.ap, ' ', profesores.am) " .
		"LIKE '%" . $b->campo('profesor') . "%' ");

				$b->establecerCondicion('edo_id', " 1 ");
				if (in_array('director', $this->usr_grupos) || in_array('secretario', $this->usr_grupos) AND $this->login != 'root') {
					if (strlen($b->campo('estado_id')) > 0) {
						$b->establecerCondicion('estado_id', " cursos.estado_id=" .
						$b->campo('estado_id'));
					} else {
						$b->establecerCondicion('estado_id', " cursos.estado_id=2 OR cursos.estado_id=3 ");
					}
				}
				// genera las condiciones
				$c = $b->condicion(array (
			'estado_id',
			'oferta_id'
			));
			if (in_array('director', $this->usr_grupos) || in_array('secretario', $this->usr_grupos) AND $this->login != 'root') {

				if (trim($c) == '') {
					$c = "(cursos.estado_id=2 OR cursos.estado_id=3) ";
				} else {
					if (strlen($b->campo('estado_id')) == 0)
					$c .= " AND (cursos.estado_id=2 OR cursos.estado_id=3) ";

				}
			}
			elseif (!in_array('plantilla', $this->usr_grupos)) {
				if (trim($c) == '') {
					$c = "cursos.estado_id=3 ";

				} else {
					if (strlen($b->campo('estado_id')) == 0)
					$c .= " AND cursos.estado_id=3 ";

				}

			}

			$b->guardar();
			$this->busqueda = $b;

			// cuenta todos los registros
			$from = "cursos " .
		"INNER JOIN grupos ON cursos.grupos_id = grupos.id " .
		"INNER JOIN materias ON cursos.materias_id  = materias.id " .
		"INNER JOIN profesores ON cursos.profesores_id  = profesores.id ";
			$this->registros = $Cursos->count_by_sql("SELECT COUNT(*) " .
		"FROM " . $from .
		"WHERE grupos.ciclos_id = '" . $ciclo_id . "' " .
		 ($c == '' ? '' : 'AND ' . $c));

		 // paginacion
		 $paginador = new Paginador($controlador, $accion);
		 if ($pag != '') {
		 	$paginador->guardarPagina($pag);
		 }
		 $paginador->estableceRegistros($this->registros);
		 $paginador->generar();
		 $this->paginador = $paginador;
		 $this->s = "SELECT " .
		"materias.nombre as materia, " .
		"cursos.id, cursos.grupos_id, " .
		"cursos.materias_id," .
		"cursos.profesores_id, " .
		"cursos.estado_id, " .
		"cursos.observaciones, " .
		"cursos.inicio " .
		"FROM " . $from .
		"WHERE grupos.ciclos_id = '" . $ciclo_id . "' " .
		 ($c == "" ? "" : "AND " . $c . " ") .
		"ORDER BY grupos.turno, grupos.grado, grupos.letra, materias.nombre " .
		"LIMIT " . ($paginador->pagina() * $paginador->rpp()) . ', ' . $paginador->rpp();
		 // ejecuta la consulta
		 $this->cursos = $Cursos->find_all_by_sql("SELECT " .
		"materias.nombre as materia, " .
		"cursos.id, cursos.grupos_id, " .
		"cursos.materias_id," .
		"cursos.profesores_id, " .
		"cursos.estado_id, " .
		"cursos.observaciones, " .
		"cursos.inicio " .
		"FROM " . $from .
		"WHERE grupos.ciclos_id = '" . $ciclo_id . "' " .
		 ($c == "" ? "" : "AND " . $c . " ") .
		"ORDER BY grupos.turno, grupos.grado, grupos.letra, materias.nombre " .
		"LIMIT " . ($paginador->pagina() * $paginador->rpp()) . ', ' . $paginador->rpp());
		 $Ciclos = new Ciclos();
		 $this->ciclo = $Ciclos->find($ciclo_id);
		 $Ciclos = $Ciclos->find("columns: id, numero", "conditions: abierto = '1' ", "order: numero DESC");
		 $this->ciclos = $Ciclos;
		 $this->acceso = $acceso;
		 $this->acl = $this->acl['cursos'];
		 $this->acl_inscripcion = $this->acl_inscripcion['inscripcion'];
		 $this->estados = new Estado();
		 $this->estados = $this->estados->find();
		 $this->ofertas = new Oferta();
		 $this->ofertas = $this->ofertas->find();

	}

	public function not_found($params = '') {
		$this->redirect('cursos');
	}

	public function estado(){
		try{
			if($this->post("edo_id") == ""){
				$this->option = "captura";
				$Cursos = new Cursos();
				$controlador = $this->controlador;
				$accion = 'index';
				$ciclo_id = Session :: get_data('ciclo.id');
				$status = new Estado();
				$this->status = $status->find();
				// busqueda
				$b = new Busqueda($controlador, $accion);
				$b->establecerCondicion('profesor', "CONCAT(profesores.nombre, ' ', profesores.ap, ' ', profesores.am) " .
		"LIKE '%" . $b->campo('profesor') . "%' ");
				$b->establecerCondicion('edo_id', " 1 ");

				// genera las condiciones
				$c = $b->condicion(array (
			'estado_id',
			'oferta_id'
			));

			$this->busqueda = $b;

			// cuenta todos los registros
			$from = "cursos " .
			"INNER JOIN grupos ON cursos.grupos_id = grupos.id " .
			"INNER JOIN materias ON cursos.materias_id  = materias.id " .
			"INNER JOIN profesores ON cursos.profesores_id  = profesores.id ";
			$this->registros = $Cursos->count_by_sql("SELECT COUNT(*) " .
			"FROM " . $from .
			"WHERE grupos.ciclos_id = '" . $ciclo_id . "' " .
			($c == '' ? '' : 'AND ' . $c));
			}else{
				$this->option = "exito";
				$Cursos = new Cursos();
				$controlador = $this->controlador;
				$accion = 'index';
				$ciclo_id = Session :: get_data('ciclo.id');
				$status = new Estado();
				$this->status = $status->find();
				// busqueda
				$b = new Busqueda($controlador, $accion);
				$b->establecerCondicion('profesor', "CONCAT(profesores.nombre, ' ', profesores.ap, ' ', profesores.am) " .
		"LIKE '%" . $b->campo('profesor') . "%' ");
				$b->establecerCondicion('edo_id', " 1 ");

				// genera las condiciones
				$c = $b->condicion(array (
			'estado_id',
			'oferta_id'
			));

			$this->busqueda = $b;

			mysql_query("BEGIN") or die("Error en el begin");
				
			$estado = new Estado();
			$estado = $estado->find($this->post("edo_id"));
			if($estado->id == ''){
				mysql_query("ROLLBACK") or die("Error en el rollback");
				throw new Exception("El estado no es valido.");
			}
			$from = "cursos " .
			"INNER JOIN grupos ON cursos.grupos_id = grupos.id " .
			"INNER JOIN materias ON cursos.materias_id  = materias.id " .
			"INNER JOIN profesores ON cursos.profesores_id  = profesores.id ";
			$cursos = $Cursos->find_all_by_sql("SELECT " .
			"cursos.* ".
			"FROM " . $from .
			"WHERE grupos.ciclos_id = '" . $ciclo_id . "' " .
			($c == "" ? "" : "AND " . $c . " ") .
			"ORDER BY grupos.turno, grupos.grado, grupos.letra, materias.nombre ");

			foreach($cursos as $curso){
				$curso->estado_id = $estado->id;
				if(!$curso->save()){
					mysql_query("ROLLBACK") or die("Error en el rollback");
					throw new Exception("Error al guardar el curso.");
				}
			}

			$historial=new Historial();
			$historial->ciclos_id= Session :: get_data('ciclo.id');
			$historial->usuario=Session :: get_data('usr.login');
			$historial->descripcion='Se cambio el estado de '.count($cursos)." curso".(count($cursos) != 1? 's' : '')." a ".$estado->nombre;
			$historial->controlador="cursos";
			$historial->accion="estado";
			$historial->saved_at=date("Y-m-d H:i:s");//2009-01-20 14:28:29
			$historial->save();
				
			mysql_query("COMMIT") or die("Error en el commit");
			}

		}catch (Exception $e){
			$this->option = "error";
			$this->error = $e->getMessage();
		}
	}

	public function status($curso_id = '') {
		$this->option = '';
		$this->error = '';
		$this->confirm = '';

		if ($curso_id != '') {

			$curso = new Cursos();

			$this->curso = $curso = $curso->find($curso_id);
			if (!$curso->id == '') {
				if ($curso->asignado()) {
					$this->curso = $curso;
					$this->grupo = $curso->grupo();
					$this->ciclo = $this->grupo->ciclo();
					if($this->ciclo->abierto()){

						$usr_grupos = Session :: get_data('usr.grupos');
						$status = new Estado();
						if (in_array('root', $usr_grupos)) {
							$this->option = "confirmar";
							$this->status = $status->find();
						}
						elseif (in_array('plantilla', $usr_grupos)) {
							if ($curso->estado_id == 1 || $curso->estado_id == 4) {
								$this->option = "confirmar";
								$this->status = $status->find("id=2");
							} else {
								$this->option = "error";
								$this->error = "El cambio de estado no es valido.";

							}

						}
						elseif (in_array('director', $usr_grupos) || in_array('secretario', $usr_grupos)) {
							if ($curso->estado_id == 2) {
								$this->option = "confirmar";
								$this->status = $status->find("id=3 OR id=4");
							} else {
								$this->option = "error";
								$this->error = "El cambio de estado no es valido.";

							}
						} else {
							$this->option = "error";
							$this->error = "No tiene permiso para realizar la accion.";
						}
					} else {
						$this->option = 'error';
						$this->error = ' El ciclo esta cerrado.';

					}

				} else {
					$this->option = 'error';
					$this->error = ' No tiene asignado este curso.';

				}

			} else {
				$this->option = "error";
				$this->error = "El curso no existe.";
			}

		} else {
			$c_id = $this->post('curso_id');
			$e_id = $this->post('estado_id');
			$observaciones = $this->post('observaciones');

			if ($c_id != '' && $e_id != '') {

				$curso = new Cursos();

				$this->curso = $curso = $curso->find($c_id);
				if (!$curso->id == '') {
					if ($curso->asignado()) {
						$this->curso = $curso;
						$this->grupo = $curso->grupo();
						$this->ciclo = $this->grupo->ciclo();
						if($this->ciclo->abierto()){
							$estado = new Estado();
							$this->estado = $estado = $estado->find($e_id);
							if (!$estado->id == '') {
								$usr_grupos = Session :: get_data('usr.grupos');
								$grupo=$curso->grupo();
								if (in_array('root', $usr_grupos)) {
									$estado_ant=new Estado();
									$estado_ant=$estado_ant->find($curso->estado_id);
									$curso->estado_id = $estado->id;
									$historial=new Historial();
									$historial->ciclos_id= Session :: get_data('ciclo.id');
									$historial->usuario=Session :: get_data('usr.login');
									$materia=$curso->materia();
									$historial->descripcion='Se cambio el status de '.$estado_ant->nombre.' a '.$estado->nombre.' del curso de '.$materia->nombre.' del grupo '.$grupo->verInfo().' '.$grupo->verOferta();
									$historial->controlador="cursos";
									$historial->accion="status";
									$historial->saved_at=date("Y-m-d H:i:s");//2009-01-20 14:28:29
									$historial->save();
									if (strlen(trim($observaciones)) > 0) {
										$curso->observaciones = str_replace('\n', '\\\\n', $curso->observaciones) . Session :: get_data('usr.login') . ' : ' . $observaciones . ' \\\\n';
									} else {
										if (strlen($curso->observaciones) > 0)
										$curso->observaciones = str_replace('\n', '\\\\n', $curso->observaciones);
										else
										$curso->observaciones = ' ';
									}
									if ($curso->update()) {
										$this->option = "exito";
										if($curso->aprobado())
										$curso->inscribirAlumnosdelGrupo();
									} else {
										$this->option = "error";
										$this->error = var_dump($curso);

									}

								} else {
									switch ($estado->id) {
										case 1 :
											break;

										case 2 : //echo var_dump($usr_grupos);
											if (in_array('plantilla', $usr_grupos)) {
												if (($curso->estado_id == 1 || $curso->estado_id == 4) && $estado->id == 2) {
													$estado_ant=new Estado();
													$estado_ant=$estado_ant->find($curso->estado_id);
													$curso->estado_id = $estado->id;
													$historial=new Historial();
													$historial->ciclos_id= Session :: get_data('ciclo.id');
													$historial->usuario=Session :: get_data('usr.login');
													$materia=$curso->materia();
													$historial->descripcion='Se cambio el status de '.$estado_ant->nombre.' a '.$estado->nombre.' del curso de '.$materia->nombre.' del grupo '.$grupo->verInfo().' '.$grupo->verOferta();
													$historial->controlador="cursos";
													$historial->accion="status";
													$historial->saved_at=date("Y-m-d H:i:s");//2009-01-20 14:28:29
													$historial->save();

													if (strlen(trim($observaciones)) > 0) {
														$curso->observaciones = str_replace('\n', '\\\\n', $curso->observaciones) . Session :: get_data('usr.login') . ' : ' . $observaciones . ' \\\\n';
													} else {
														if (strlen($curso->observaciones) > 0)
														$curso->observaciones = str_replace('\n', '\\\\n', $curso->observaciones);
														else
														$curso->observaciones = ' ';
													}
													if ($curso->update()) {
														$this->option = "exito";
													} else {
														$this->option = "error";
														$this->error = var_dump($curso);

													}
												} else {
													$this->option = "error";
													$this->error = "El cambio de estado no es valido.";

												}

											} else {
												$this->option = "error";
												$this->error = "No tiene permiso para realizar la accion.";
											}
											break;

										case 3 :
											if (in_array('director', $usr_grupos) || in_array('secretario', $usr_grupos)) {
												if ($curso->estado_id == 2 && ($estado->id == 4 || $estado->id == 3)) {
													$estado_ant=new Estado();
													$estado_ant=$estado_ant->find($curso->estado_id);
													$curso->estado_id = $estado->id;
													$historial=new Historial();
													$historial->ciclos_id= Session :: get_data('ciclo.id');
													$historial->usuario=Session :: get_data('usr.login');
													$materia=$curso->materia();
													$historial->descripcion='Se cambio el status de '.$estado_ant->nombre.' a '.$estado->nombre.' del curso de '.$materia->nombre.' del grupo '.$grupo->verInfo().' '.$grupo->verOferta();
													$historial->controlador="cursos";
													$historial->accion="status";
													$historial->saved_at=date("Y-m-d H:i:s");//2009-01-20 14:28:29
													$historial->save();

													if (strlen(trim($observaciones)) > 0) {
														$curso->observaciones = str_replace('\n', '\\\\n', $curso->observaciones) . Session :: get_data('usr.login') . ' : ' . $observaciones . ' \\\\n';
													} else {
														if (strlen($curso->observaciones) > 0)
														$curso->observaciones = str_replace('\n', '\\\\n', $curso->observaciones);
														else
														$curso->observaciones = ' ';
													}
													if ($curso->update()) {
														$this->option = "exito";
														if($curso->aprobado())
														$curso->inscribirAlumnosdelGrupo();

													} else {
														$this->option = "error";
														$this->error = var_dump($curso);

													}
												} else {
													$this->option = "error";
													$this->error = "El cambio de estado no es valido.";

												}
											} else {
												$this->option = "error";
												$this->error = "No tiene permiso para realizar la accion.";
											}
											break;

										case 4 :
											if (in_array('director', $usr_grupos) || in_array('secretario', $usr_grupos)) {
												if ($curso->estado_id == 2) {
													$estado_ant=new Estado();
													$estado_ant=$estado_ant->find($curso->estado_id);
													$curso->estado_id = $estado->id;
													$historial=new Historial();
													$historial->ciclos_id= Session :: get_data('ciclo.id');
													$historial->usuario=Session :: get_data('usr.login');
													$materia=$curso->materia();
													$historial->descripcion='Se cambio el status de '.$estado_ant->nombre.' a '.$estado->nombre.' del curso de '.$materia->nombre.' del grupo '.$grupo->verInfo().' '.$grupo->verOferta();
													$historial->controlador="cursos";
													$historial->accion="status";
													$historial->saved_at=date("Y-m-d H:i:s");//2009-01-20 14:28:29
													$historial->save();

													if (strlen(trim($observaciones)) > 0) {
														$curso->observaciones = str_replace('\n', '\\\\n', $curso->observaciones) . Session :: get_data('usr.login') . ' : ' . $observaciones . ' \\\\n';
													} else {
														if (strlen($curso->observaciones) > 0)
														$curso->observaciones = str_replace('\n', '\\\\n', $curso->observaciones);
														else
														$curso->observaciones = ' ';
													}
													if ($curso->update()) {
														$this->option = "exito";
													} else {
														$this->option = "error";
														$this->error = var_dump($curso);

													}
												} else {
													$this->option = "error";
													$this->error = "El cambio de estado no es valido.";

												}
											} else {
												$this->option = "error";
												$this->error = "No tiene permiso para realizar la accion.";
											}
											break;

									}
								}

							} else {
								$this->option = "error";
								$this->error = "El estado no es valido.";
							}
						} else {
							$this->option = 'error';
							$this->error = ' El ciclo esta cerrado.';

						}
					} else {
						$this->option = 'error';
						$this->error = ' No tiene asignado este curso.';

					}

				} else {
					$this->option = "error";
					$this->error = "2El curso no existe.";
				}
			} else {
				$this->option = "error";
				$this->error = "No tiene permiso para ver el contenido.";

			}

		}
	}

	public function ver($id = '') {
		if ($id != '') {
			$this->option = 'vista';
			$id = intval($id, 10);
			$Cursos = new Cursos();
			$curso = $Cursos->find($id);
			if (!$curso->id == '') {
				if ($curso->asignado()) {
					$this->curso = $curso;
					$this->grupo = $curso->grupo();
					$this->ciclo = $this->grupo->ciclo();
					$this->alumnos = $curso->alumnosgrupo();
					if (count($this->alumnos) > 0) {
						$this->calificaciones = $curso->calificaciones();
						$this->parciales = $curso->parcialesResumen();
						$this->nParciales = count($this->hdrParciales);
						$alumnoscursos = new Alumnoscursos();
						$alumnoscursos = $alumnoscursos->find("cursos_id=" . $this->curso->id);
						$inscripciones = array ();
						foreach ($alumnoscursos as $ac) {
							$inscripciones[$ac->alumnos_id] = $ac->id;
						}
						$this->inscripciones = $inscripciones;

						$usr_login = Session :: get_data('usr.login');
						$this->acl_asistencias = array ();
						$this->acl_calificaciones = array ();
						$this->acl_grupos = array ();
						$acl = new gacl_extra();
						$acos_arr = array (
							'calificaciones' => array (
								'ver'
								)
								);
								$this->acl_calificaciones = $acl->acl_check_multiple($acos_arr, $usr_login);

								$acos_arr = array (
							'asistencias' => array (
								'ver',
								'faltas'
								)
								);

								$this->acl_asistencias = $acl->acl_check_multiple($acos_arr, $usr_login);

								$acos_arr = array (
							'grupos' => array (
								'index'
								)
								);

								$this->acl_grupos = $acl->acl_check_multiple($acos_arr, $usr_login);

								$acos_arr = array (
							'inscripcion' => array (
								'agregar',
								'eliminar',
								'articulo'
								)
								);

								$this->acl_inscripcion = $acl->acl_check_multiple($acos_arr, $usr_login);

								$this->acl_calificaciones = $this->acl_calificaciones['calificaciones'];
								$this->acl_asistencias = $this->acl_asistencias['asistencias'];
								$this->acl_grupos = $this->acl_grupos['grupos'];
								$this->acl_inscripcion = $this->acl_inscripcion['inscripcion'];

					} else {
						$this->option = 'alert';
						$this->alert = ' No hay alumnos asignados a este curso.';
					}
				} else {
					$this->option = 'error';
					$this->error = ' No tiene asignado este curso.';

				}
			} else {
				$this->option = 'error';
				$this->error = ' El curso no existe.';
			}
		} else {
			$this->option = 'error';
			$this->error = ' No se especific&oacute; el curso.';
		}
	}
}
?>
