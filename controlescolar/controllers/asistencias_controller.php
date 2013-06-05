<?php
// Hekademos, Creado el 18/10/2008
/**
 * Asistencias Controller
 *
 * @package    Hekademos
 * @author     mimeks <mimex@fipasoft.com.mx>
 * @copyright  2008 FiPa Software (contacto at fipasoft.com.mx)
 * @license    http://www.gnu.org/licenses/gpl.html  GNU General Public License (GPL)
 *
 */
 Kumbia :: import('app.componentes.*');
 Kumbia :: import('lib.excel.main');

 class AsistenciasController extends ApplicationController{
 	protected $persistance = false;
 	public $template = "system";

	public function agregar(){
		$this->error = '';
		if($this->post('cursos_id')){
			$curso = new Cursos();
			$curso = $curso->find($this->post('cursos_id'));
			if($curso->id != ''){
				if($curso->asignado()){
					$ciclo=$curso->ciclo();
					if($ciclo->abierto()){
						$agenda=new Agenda();
						$materia=$curso->materia();
					if(
						($materia->duracion==7
							&&
						(
						$agenda->eventoValido('CAS-CAP',$ciclo->id,$this->controlador,$this->accion,Session :: get_data('usr.login'))
							||
					 	$agenda->eventoValido('CAS-CAP-ESP',$ciclo->id,$this->controlador,$this->accion,Session :: get_data('usr.login'))
					 	 )
						)
							||
					   (
					   ($materia->duracion!=7
							&&
						(
					   $agenda->eventoValido('AST-CAP',$ciclo->id,$this->controlador,$this->accion,Session :: get_data('usr.login'))
							||
					   $agenda->eventoValido('AST-CAP-ESP',$ciclo->id,$this->controlador,$this->accion,Session :: get_data('usr.login'))
						)
						)
					   )
						){
					if($this->post('fechas')){
						$this->option = 'captura';
						$this->curso    	   =   $curso;
						$this->grupo    	   =   $curso->grupo();
						$this->ciclo    	   =   $this->grupo->ciclo();
						if($this->curso->aprobado()){

						$this->alumnos  	   =   $curso->alumnosgrupo();
						$this->fechas 		   =   $this->post('fechas');
						$this->fechasHdr = array();

						$disponibles = array();
						foreach($curso->asistenciasFechas() as $fcs){
							$disponibles[] = $fcs->dia;
						}

						if(count($this->alumnos) <= 0){
							$this->option = 'alert';
							$this->alert = ' No hay alumnos asignados a este curso.';
						}else{
							foreach($this->fechas as $fecha){
								if(in_array($fecha, $disponibles)){
									$f = explode('-', $fecha);
									$mes = substr(Utils :: mes_espanol($f[1]), 0, 3) . ' ' . $f[0];
									$this->fechasHdr[$mes][] = intval($f[2], 10);
								}else{
									$this->option = 'error';
									$this->error .= 'El sistema no permite el registro de asistencias para ' .
													$fecha . '.';
									break;
								}
							}

						}
					}else{
					$this->option = 'alert';
					$this->alert = ' El curso no esta aprobado aun.';
					}
					}else if($this->post('asistencias')){
						if($curso->aprobado()){
						$asistencias = $this->post('asistencias');
						$this->option = 'exito';
						$this->exito = 'Los registros de asistencia se guardaron en forma correcta.';
						if(is_array($asistencias)){
							set_time_limit(0);

							$disponibles = array();
							foreach($curso->asistenciasFechas() as $fcs){
								$disponibles[] = $fcs->dia;
							}

							$alumnos = array();
							foreach($curso->alumnos() as $alumno){
								$alumnos[] = $alumno->id;
							}

							mysql_query("BEGIN") or die("AST_EDI_1");
							foreach($asistencias as $alumnos_id => $fechas){
								if(in_array($alumnos_id, $alumnos)){
									if(is_array($fechas)){
										foreach($fechas as $fecha => $valor){
											if(in_array($fecha, $disponibles)){
												if($valor  == '0' || $valor == '1'){
													$a = new Asistencias();
													$a->alumnos_id           =   $alumnos_id;
													$a->cursos_id            =   $curso->id;
													$a->dia                  =   $fecha;
													$a->asistenciasvalor_id  =   $valor;
													if(!$a->save()){
														$this->option = 'error';
														$this->error .= ' No se pudo agregar la asistencia del ' .
																		$fecha . ' para ' . $alumnos_id . '.';
														break 2;
													}
												}else{
													$this->option = 'error';
													$this->error .= 'Los datos son inconsistentes';
													break 2;
												}
											}else{
												$this->option = 'error';
												$this->error .= 'El sistema no permite el registro de asistencias para ' .
																$fecha . '.';
												break 2;
											}
										}
									}
								}else{
									$this->option = 'error';
									$this->error .= 'El sistema no permite el registro de asistencias para el alumno ' .
														$alumnos_id . '.';
									break;
								}
							}
							if($this->error == ''){
								$cambios=array();
								foreach($alumnos as $alumno){

									$calificaciones=new Calificaciones();
									$calificaciones=$calificaciones->find("cursos_id='".$curso->id."' AND alumnos_id='".$alumno."' ORDER BY oportunidades_id ASC");
									if($calificaciones!=null){
										//existen calificaciones
										$resumen=$this->curso->asistenciasResumenAlumno($alumno);
										$materiaTipo=$curso->materiaTipo();
										$ordinarios=array();
										foreach($calificaciones as $cal){

										if($resumen['porcentaje']<60){
												if($cal->oportunidades_id==1){
													if(strToLower($cal->valor)!="sd"){
													$cal->valor="SD";
													$cal->update();
													$alm=new Alumnos();
													$alm=$alm->find($alumno);
													$cambios[]="Modificacion Ordinario ".$alm->codigo." - ".$alm->nombre('reversa');
													}
													$ordinarios[$alumno]=$cal->valor;

												}elseif($cal->oportunidades_id==2){
													if(strToLower($cal->valor)!="sd"){
														$cal->valor="SD";
														$cal->update();
														$alm=new Alumnos();
														$alm=$alm->find($alumno);
														$cambios[]="Modificacion Extraordinario ".$alm->codigo." - ".$alm->nombre('reversa');

													}

												}
										}elseif($resumen['porcentaje']>=60 && $resumen['porcentaje']<80){
												if($cal->oportunidades_id==1){
													if(strToLower($cal->valor)!="sd"){
													$cal->valor="SD";
													$cal->update();
													$alm=new Alumnos();
													$alm=$alm->find($alumno);
													$cambios[]="Modificacion Ordinario ".$alm->codigo." - ".$alm->nombre('reversa');
													}
													$ordinarios[$alumno]=$cal->valor;
												}elseif($cal->oportunidades_id==2){
													if(strToLower($cal->valor)=="sd"){
														if(strToUpper($materiaTipo)=="TLR")
															$cal->valor="NA";
															else
															$cal->valor=50;

															$cal->update();

															$alm=new Alumnos();
															$alm=$alm->find($alumno);
															$cambios[]="Modificacion Extraordinario ".$alm->codigo." - ".$alm->nombre('reversa');
													}
												}
										}elseif($resumen['porcentaje']>=80){
												if($cal->oportunidades_id==1){
													if(strToLower($cal->valor)=="sd"){
															if(strToUpper($materiaTipo)=="TLR")
															$cal->valor="NA";
															else
															$cal->valor=50;

															$cal->update();

															$alm=new Alumnos();
															$alm=$alm->find($alumno);
															$cambios[]="Modificacion Ordinario ".$alm->codigo." - ".$alm->nombre('reversa');

													}
													$ordinarios[$alumno]=$cal->valor;
												}elseif($cal->oportunidades_id==2){
													$ordinario=$ordinarios[$alumno];
														//$this->exito.='<br/>Alumno '.$alumno.' ordinario '.$ordinario.' - '.$cal->id;


														if((strToLower($cal->valor)=='sd') || (strToLower($ordinario)!='sd' && strToLower($ordinario)!='na' && $ordinario>=60)){
														$cal->delete();
														$alm=new Alumnos();
														$alm=$alm->find($alumno);
														$cambios[]="Eliminacion Extraordinario ".$alm->codigo." - ".$alm->nombre('reversa');
														}




												}
										}

										//$this->exito.='<br/>Alumno '.$alumno.' % '.$resumen['porcentaje'].' - '.$cal->id;

										}
									}

								}
								mysql_query("COMMIT") or die("AST_EDI_2");
								$historial=new Historial();
								$historial->ciclos_id= Session :: get_data('ciclo.id');
								$historial->usuario=Session :: get_data('usr.login');
								$c=$this->curso->verMateriaNombre().' '.$this->grupo->grado.$this->grupo->letra.$this->grupo->turno.' '.$this->grupo->verOferta();
								$historial->descripcion="Se agregaron asistencias del curso: ".$c.".";
								$historial->controlador="asistencias";
								$historial->accion="agregar";
								$historial->saved_at=date("Y-m-d H:i:s");//2009-01-20 14:28:29
								$historial->save();
								$this->cambios=$cambios;
							}else{
								mysql_query("ROLLBACK") or die("AST_EDI_3");
							}
						}else{
							$this->option = 'alert';
							$this->error = ' No se especificaron valores para capturar asistencias.';
						}

					}else{
					$this->option = 'alert';
					$this->alert = ' El curso no esta aprobado aun.';
					}
					}else{
						$this->option = 'error';
						$this->error = ' Faltan datos para procesar la solicitud.';
					}

					}else{
					$this->option = 'alert';
					$this->alert = ' La agenda no permite el registro de asistencias .';
					}
					} else {
					$this->option = 'error';
					$this->error = ' El ciclo esta cerrado.';

					}


				}else{
					$this->option = 'error';
					$this->error = ' No tiene asignado este curso.';
				}
			}else{
				$this->option = 'error';
				$this->error = ' El curso no existe.';
			}
		}else{
			$this->option = 'error';
			$this->error = ' No se especific&oacute; el curso.';
		}
	}

	public function editar($id = ''){
		$this->error = '';
		if($this->post('cursos_id')){
			$curso = new Cursos();
			$curso = $curso->find($this->post('cursos_id'));
			if($curso->id != ''){
				$ciclo=$curso->ciclo();
				if($ciclo->abierto()){
				if($curso->asignado()){
					if($curso->aprobado()){
					$agenda=new Agenda();
					$materia=$curso->materia();
					if(
						($materia->duracion==7
							&&
						(
						$agenda->eventoValido('CAS-CAP',$ciclo->id,$this->controlador,$this->accion,Session :: get_data('usr.login'))
							||
					 	$agenda->eventoValido('CAS-CAP-ESP',$ciclo->id,$this->controlador,$this->accion,Session :: get_data('usr.login'))
					 	 )
						)
							||
					   (
					   ($materia->duracion!=7
							&&
						(
					   $agenda->eventoValido('AST-CAP',$ciclo->id,$this->controlador,$this->accion,Session :: get_data('usr.login'))
							||
					   $agenda->eventoValido('AST-CAP-ESP',$ciclo->id,$this->controlador,$this->accion,Session :: get_data('usr.login'))
						)
						)
					   )
						){
					if($this->post('fechas')){
						$this->option = 'captura';
						$this->curso    	   =  $curso;
						$this->grupo    	   =  $curso->grupo();
						$this->ciclo    	   =  $this->grupo->ciclo();
						$this->alumnos  	   =  $curso->alumnosgrupo();
						$this->fechas 		   =  $this->post('fechas');
						$this->fechasHdr       =  array();

						$disponibles = array();
						foreach($curso->asistenciasFechasEdicion() as $fcs){
							$disponibles[] = $fcs->dia;
						}

						foreach($this->fechas as $fecha){
							if(in_array($fecha, $disponibles)){
								$f = explode('-', $fecha);
								$mes = substr(Utils :: mes_espanol($f[1]), 0, 3) . ' ' . $f[0];
								$this->fechasHdr[$mes][] = intval($f[2], 10);
							}else{
								$this->option = 'error';
								$this->error .= 'El sistema no permite el registro de asistencias para ' .
												$fecha . '.';
								break;
							}
						}
						if(count($this->alumnos) <= 0){
							$this->option = 'alert';
							$this->alert = ' No hay alumnos asignados a este curso.';
						}
					}else if($this->post('asistencias')){
						set_time_limit(0);
						$asistencias = null;
						$asistencias = $this->post('asistencias');
						$this->option = 'exito';
						$this->exito = 'Los registros de asistencia se actualizaron en forma correcta.';

						if(is_array($asistencias)){
							mysql_query("BEGIN") or die("AST_AGR_1");

							$disponibles = array();
							foreach($curso->asistenciasFechasEdicion() as $fcs){
								$disponibles[] = $fcs->dia;
							}

							$alumnos=array();

							foreach($asistencias as $id => $valor){
								$a = new Asistencias();
								$a = $a->find($id);
								if($a->id != ''){
									if($a->cursos_id == $curso->id){
										if(in_array($a->dia, $disponibles)){
											if($valor  == '0' || $valor == '1'){
												$a->asistenciasvalor_id  = $valor;
												$alumnos[$a->alumnos_id]=$a->alumnos_id;
												if(!$a->save()){
													$this->option = 'error';
													$this->error .= ' No se pudo actualizar la asistencia ' . $id . '. ';
													break;
												}
											}else{
												$this->option = 'error';
												$this->error .= 'Los datos son inconsistentes';
												break;
											}
										}else{
											$this->option = 'error';
											$this->error .= 'El sistema no permite el registro de asistencias para ' .
															$a->dia . '.';
											break;
										}
									}else{
										$this->option = 'error';
										$this->error .= '<br/>Id de curso inconsistente. '.$a->id." ".$a->cursos_id." == ".$curso->id;

									}
								}else{
									$this->option = 'error';
									$this->error .= ' La edici&oacute;n se cancel&oacute; porque existen inconsistencias en la captura. ';
									break;
								}
							}
							if($this->error == ''){
								//revisar calificaciones
								$cambios=array();
								foreach($alumnos as $alumno){

									$calificaciones=new Calificaciones();
									$calificaciones=$calificaciones->find("cursos_id='".$curso->id."' AND alumnos_id='".$alumno."' ORDER BY oportunidades_id ASC");
									if($calificaciones!=null){
										//existen calificaciones
										$resumen=$this->curso->asistenciasResumenAlumno($alumno);
										$materiaTipo=$curso->materiaTipo();
										$ordinarios=array();
										foreach($calificaciones as $cal){

										if($resumen['porcentaje']<60){
												if($cal->oportunidades_id==1){
													if(strToLower($cal->valor)!="sd"){
													$cal->valor="SD";
													$cal->update();
													$alm=new Alumnos();
													$alm=$alm->find($alumno);
													$cambios[]="Modificacion Ordinario ".$alm->codigo." - ".$alm->nombre('reversa');
													}
													$ordinarios[$alumno]=$cal->valor;

												}elseif($cal->oportunidades_id==2){
													if(strToLower($cal->valor)!="sd"){
														$cal->valor="SD";
														$cal->update();
														$alm=new Alumnos();
														$alm=$alm->find($alumno);
														$cambios[]="Modificacion Extraordinario ".$alm->codigo." - ".$alm->nombre('reversa');

													}

												}
										}elseif($resumen['porcentaje']>=60 && $resumen['porcentaje']<80){
												if($cal->oportunidades_id==1){
													if(strToLower($cal->valor)!="sd"){
													$cal->valor="SD";
													$cal->update();
													$alm=new Alumnos();
													$alm=$alm->find($alumno);
													$cambios[]="Modificacion Ordinario ".$alm->codigo." - ".$alm->nombre('reversa');
													}
													$ordinarios[$alumno]=$cal->valor;
												}elseif($cal->oportunidades_id==2){
													if(strToLower($cal->valor)=="sd"){
														if(strToUpper($materiaTipo)=="TLR")
															$cal->valor="NA";
															else
															$cal->valor=50;

															$cal->update();

															$alm=new Alumnos();
															$alm=$alm->find($alumno);
															$cambios[]="Modificacion Extraordinario ".$alm->codigo." - ".$alm->nombre('reversa');
													}
												}
										}elseif($resumen['porcentaje']>=80){
												if($cal->oportunidades_id==1){
													if(strToLower($cal->valor)=="sd"){
															if(strToUpper($materiaTipo)=="TLR")
															$cal->valor="NA";
															else
															$cal->valor=50;

															$cal->update();

															$alm=new Alumnos();
															$alm=$alm->find($alumno);
															$cambios[]="Modificacion Ordinario ".$alm->codigo." - ".$alm->nombre('reversa');

													}
													$ordinarios[$alumno]=$cal->valor;
												}elseif($cal->oportunidades_id==2){
													$ordinario=$ordinarios[$alumno];
														//$this->exito.='<br/>Alumno '.$alumno.' ordinario '.$ordinario.' - '.$cal->id;


														if((strToLower($cal->valor)=='sd') || (strToLower($ordinario)!='sd' && strToLower($ordinario)!='na' && $ordinario>=60)){
														$cal->delete();
														$alm=new Alumnos();
														$alm=$alm->find($alumno);
														$cambios[]="Eliminacion Extraordinario ".$alm->codigo." - ".$alm->nombre('reversa');
														}




												}
										}

										//$this->exito.='<br/>Alumno '.$alumno.' % '.$resumen['porcentaje'].' - '.$cal->id;

										}
									}

								}
								mysql_query("COMMIT") or die("AST_AGR_2");
								$historial=new Historial();
								$historial->ciclos_id= Session :: get_data('ciclo.id');
								$historial->usuario=Session :: get_data('usr.login');
								$c=$this->curso->verMateriaNombre().' '.$this->grupo->grado.$this->grupo->letra.$this->grupo->turno.' '.$this->grupo->verOferta();
								$historial->descripcion="Se editaron asistencias del curso: ".$c.".";
								$historial->controlador="asistencias";
								$historial->accion="editar";
								$historial->saved_at=date("Y-m-d H:i:s");//2009-01-20 14:28:29
								$historial->save();
								$this->cambios=$cambios;
							}else{
								mysql_query("ROLLBACK") or die("AST_AGR_3");
							}
						}else{
							$this->option = 'alert';
							$this->alert = ' No se especificaron valores para capturar asistencias.';
						}
					}else{
						$this->option = 'alert';
						$this->alert = ' No se modificaron registros.';
					}
					}else{
					$this->option = 'alert';
					$this->alert = ' La agenda no permite la edicion de asistencias.';
					}
					}else{
					$this->option = 'alert';
					$this->alert = ' El curso no esta aprobado aun.';
					}

				}else{
					$this->option = 'error';
					$this->error = ' No tiene asignado este curso.';
				}
			} else {
					$this->option = 'error';
					$this->error = ' El ciclo esta cerrado.';

					}
			}else{
				$this->option = 'error';
				$this->error = ' El curso no existe.';
			}
		}else{
			$this->option = 'error';
			$this->error = ' No se especific&oacute; el curso.';
		}
	}

	public function eliminar() {
		$this->error = '';
		if( $this->post('cursos_id') ){
			$curso = new Cursos();
			$curso = $curso->find($this->post('cursos_id'));
			if($curso->id != ''){
				$ciclo=$curso->ciclo();
				if($ciclo->abierto()){
				if($curso->asignado()){
					if($curso->aprobado()){
					$agenda=new Agenda();

						$materia=$curso->materia();
					if(
						($materia->duracion==7
							&&
						(
						$agenda->eventoValido('CAS-CAP',$ciclo->id,$this->controlador,$this->accion,Session :: get_data('usr.login'))
							||
					 	$agenda->eventoValido('CAS-CAP-ESP',$ciclo->id,$this->controlador,$this->accion,Session :: get_data('usr.login'))
					 	 )
						)
							||
					   (
					   ($materia->duracion!=7
							&&
					   (
					   $agenda->eventoValido('AST-CAP',$ciclo->id,$this->controlador,$this->accion,Session :: get_data('usr.login'))
							||
					   $agenda->eventoValido('AST-CAP-ESP',$ciclo->id,$this->controlador,$this->accion,Session :: get_data('usr.login'))
						)
						)
					   )
						){
					if($this->post('asistencias')){
						set_time_limit(0);
						$fechas = $this->post('asistencias');
						$this->option = 'exito';
						$this->exito = 'Los registros de asistencia se eliminaron en forma correcta.';
						$asistencias = new Asistencias();

						$disponibles = array();
						foreach($curso->asistenciasFechasEdicion() as $fcs){
							$disponibles[] = $fcs->dia;
						}

						mysql_query("BEGIN") or die("AST_ELI_1");

						$alumnos=array();
						foreach($fechas as $fecha){
							if(in_array($fecha, $disponibles)){
								try{
									$asis=new Asistencias();
									$asis=$asis->find(
											"cursos_id = '" . $curso->id . "' " .
											" GROUP BY alumnos_id "
									);

									$asistencias->delete(
											"cursos_id = '" . $curso->id . "' " .
											"AND dia = '" . $fecha . "' "
									);
								}catch(dbException $e){
									$this->option = 'error';
									$this->error .= ' Error al intentar eliminar las asistencias del ' . $fecha . '. ';
									break;
								}
							}else{
								$this->option = 'error';
								$this->error .= 'El sistema no permite la eliminaci&oacute;n de asistencias para ' .
												$fecha . '.';
								break;
							}
						}
						if($this->error == ''){
							//revisar calificaciones
							$asis_existen=new Asistencias();
							$count=$asis_existen->count(
											"cursos_id = '" . $curso->id . "' "
									);

							if($count==0){
								$calificaciones=new Calificaciones();
								$calificaciones->delete("cursos_id='".$curso->id."'");
								$this->exito.="<br/>Se eliminaron todas las calificaciones.";
							}else{
							$ordinarios=array();
							$cambios=array();
								foreach($asis as $as){
									$alumno=$as->alumnos_id;
									$calificaciones=new Calificaciones();
									$calificaciones=$calificaciones->find("cursos_id='".$curso->id."' AND alumnos_id='".$alumno."' ORDER BY oportunidades_id ASC");
									if($calificaciones!=null){
										//existen calificaciones
										$resumen=$this->curso->asistenciasResumenAlumno($alumno);
										$materiaTipo=$curso->materiaTipo();
										$ordinarios=array();
										foreach($calificaciones as $cal){

										if($resumen['porcentaje']<60){
												if($cal->oportunidades_id==1){
													if(strToLower($cal->valor)!="sd"){
													$cal->valor="SD";
													$cal->update();
													$alm=new Alumnos();
													$alm=$alm->find($alumno);
													$cambios[]="Modificacion Ordinario ".$alm->codigo." - ".$alm->nombre('reversa');
													}
													$ordinarios[$alumno]=$cal->valor;

												}elseif($cal->oportunidades_id==2){
													if(strToLower($cal->valor)!="sd"){
														$cal->valor="SD";
														$cal->update();
														$alm=new Alumnos();
														$alm=$alm->find($alumno);
														$cambios[]="Modificacion Extraordinario ".$alm->codigo." - ".$alm->nombre('reversa');

													}

												}
										}elseif($resumen['porcentaje']>=60 && $resumen['porcentaje']<80){
												if($cal->oportunidades_id==1){
													if(strToLower($cal->valor)!="sd"){
													$cal->valor="SD";
													$cal->update();
													$alm=new Alumnos();
													$alm=$alm->find($alumno);
													$cambios[]="Modificacion Ordinario ".$alm->codigo." - ".$alm->nombre('reversa');
													}
													$ordinarios[$alumno]=$cal->valor;
												}elseif($cal->oportunidades_id==2){
													if(strToLower($cal->valor)=="sd"){
														if(strToUpper($materiaTipo)=="TLR")
															$cal->valor="NA";
															else
															$cal->valor=50;

															$cal->update();

															$alm=new Alumnos();
															$alm=$alm->find($alumno);
															$cambios[]="Modificacion Extraordinario ".$alm->codigo." - ".$alm->nombre('reversa');
													}
												}
										}elseif($resumen['porcentaje']>=80){
												if($cal->oportunidades_id==1){
													if(strToLower($cal->valor)=="sd"){
															if(strToUpper($materiaTipo)=="TLR")
															$cal->valor="NA";
															else
															$cal->valor=50;

															$cal->update();

															$alm=new Alumnos();
															$alm=$alm->find($alumno);
															$cambios[]="Modificacion Ordinario ".$alm->codigo." - ".$alm->nombre('reversa');

													}
													$ordinarios[$alumno]=$cal->valor;
												}elseif($cal->oportunidades_id==2){
													$ordinario=$ordinarios[$alumno];
														//$this->exito.='<br/>Alumno '.$alumno.' ordinario '.$ordinario.' - '.$cal->id;


														if((strToLower($cal->valor)=='sd') || (strToLower($ordinario)!='sd' && strToLower($ordinario)!='na' && $ordinario>=60)){
														$cal->delete();
														$alm=new Alumnos();
														$alm=$alm->find($alumno);
														$cambios[]="Eliminacion Extraordinario ".$alm->codigo." - ".$alm->nombre('reversa');
														}




												}
										}

										//$this->exito.='<br/>Alumno '.$alumno.' % '.$resumen['porcentaje'].' - '.$cal->id;

										}
									}

								}
							}
							mysql_query("COMMIT") or die("AST_ELI_2");
							$historial=new Historial();
								$historial->ciclos_id= Session :: get_data('ciclo.id');
								$historial->usuario=Session :: get_data('usr.login');

								$c=$this->curso->verMateriaNombre().' '.$this->grupo->grado.$this->grupo->letra.$this->grupo->turno.' '.$this->grupo->verOferta();
								$historial->descripcion="Se eliminaron asistencias del curso: ".$c.".";
								$historial->controlador="asistencias";
								$historial->accion="eliminar";
								$historial->saved_at=date("Y-m-d H:i:s");//2009-01-20 14:28:29
								$historial->save();
								$this->cambios=$cambios;
						}else{
							mysql_query("ROLLBACK") or die("AST_ELI_3");
						}
					}else if( $this->post('fechas') ){
						$fechas = $this->post('fechas');
						if( count($fechas) > 0 ){
							$this->option = 'confirm';
							$this->confirm = 'Se dispone a eliminar los registros de asistencias de este curso para las siguientes fechas: <br />';
							$i = 0;
							foreach($fechas as $fecha){
								$this->confirm .= ($i == 0 ? '' : ', ') . Utils :: fecha_convertir($fecha);
								$i ++ ;
							}
							$this->curso = $curso;
							$this->confirm .= '<br /><strong>Desea continuar?</strong>';
							$this->fechas = $this->post('fechas');
						}else{
							$this->option = 'error';
							$this->error = ' No se seleccionaron fechas para eliminar.';
						}
					}else{
						$this->option = 'error';
						$this->error = ' Faltan datos para procesar la solicitud.';
					}
				}else{
					$this->option = 'error';
					$this->error = ' La agenda no permite la eliminacion de asistencias.';
					}
				}else{
					$this->option = 'error';
					$this->error = ' El curso no esta aprobado aun.';
					}
				}else{
					$this->option = 'error';
					$this->error = ' No tiene asignado este curso.';
				}
			} else {
					$this->option = 'error';
					$this->error = ' El ciclo esta cerrado.';

					}
			}else{
				$this->option = 'error';
				$this->error = ' El curso no existe.';
			}
		}else{
			$this->option = 'error';
			$this->error = ' No se especific&oacute; el curso.';
		}
	}

 	public function exportar($grp_id = ''){
		$this->set_response("view");
		require('app/reportes/xls.cursos.php');
		$ciclo_id = Session :: get_data('ciclo.id');
		$reporte = new XLSCursos($ciclo_id, $grp_id);
		$reporte->generar();
 	}

 	public function faltas($id=''){
		$this->error = '';
		if($id!=''){
		$curso = new Cursos();
		$curso = $curso->find($id);
		if($curso->id != ''){
			$this->curso        =   $curso;
			$this->grupo        =   $curso->grupo();
			$this->ciclo        =   $this->grupo->ciclo();

			if($this->ciclo->abierto()){
			if($curso->asignado()){
				if($curso->aprobado()){

					$capturadas=array();
					$faltas=new Faltas();
					$faltas=$faltas->find_all_by_sql(
								"SELECT alumnoscursos.alumnos_id,faltas.* FROM
								faltas
								INNER JOIN alumnoscursos ON faltas.alumnoscursos_id=alumnoscursos.id
								INNER JOIN cursos ON alumnoscursos.cursos_id=cursos.id
								WHERE cursos.id='".$curso->id."'"
					);

					foreach($faltas as $f){
					$capturadas[$f->alumnos_id]=$f->cantidad;
					}
					$this->capturadas=$capturadas;
			 		$this->option       =  'captura';
					$this->materia      =   $curso->materia();
					$this->alumnos  	   =  $curso->alumnosgrupo();

				}else{
					$this->option = 'alert';
					$this->alert = ' El curso no esta aprobado aun.';
				}
			}else{
				$this->option = 'error';
				$this->error = ' No tiene asignado este curso.';
			}
			} else {
				$this->option = 'error';
				$this->error = ' El ciclo esta cerrado.';
			}
		}else{
 			$this->option = 'error';
			$this->error = ' No se especific&oacute; un id de curso v&aacute;lido.';
 		}
		}else if($this->post('cursos_id')!='' && $this->post('faltas')){
		$faltas = $this->post('faltas');
		$curso = new Cursos();
		$curso = $curso->find($this->post('cursos_id'));
		if($curso->id != ''){
			$this->curso        =   $curso;
			$this->grupo        =   $curso->grupo();
			$this->ciclo        =   $this->grupo->ciclo();
			if($this->ciclo->abierto()){
			if($curso->asignado()){
				$alumnos 			= 	$curso->alumnosIds();

		 			$this->option = 'exito';
		 			$this->exito = 'El registro de faltas se guardo en forma correcta.<br/>';
					mysql_query("BEGIN") or die("CAL_EDI_1");
					$e=false;
					foreach($faltas as $id => $fcant){
						$ac=new Alumnoscursos();
						$ac=$ac->find_first("alumnos_id='".$id."' AND cursos_id='".$curso->id."'");
						if($ac->id!=""){
							$fta=new Faltas();
							$fta=$fta->find_first("alumnoscursos_id='".$ac->id."'");
							if($fta->id!=""){
							$fta->cantidad=$fcant;
							if(!$fta->save()){
								$e=true;
								break;
							}
							}else{
							$fta=new Faltas();
							$fta->alumnoscursos_id=$ac->id;
							$fta->cantidad=$fcant;
							if(!$fta->save()){
								$e=true;
								break;
							}
							}
						}
					}

					if(!$e){
					mysql_query("COMMIT") or die("CAL_EDI_2");

								$historial=new Historial();
								$historial->ciclos_id= Session :: get_data('ciclo.id');
								$historial->usuario=Session :: get_data('usr.login');

								$historial->descripcion="Se editaron las faltas del curso ".$curso->verMateriaNombre()." ".$curso->verGrupo();
								$historial->controlador="asistencias";
								$historial->accion="faltas";
								$historial->saved_at=date("Y-m-d H:i:s");//2009-01-20 14:28:29
								$historial->save();
					}else{
						$this->option = 'error';
						$this->error = ' Ocurrio un error al guardar en la base de datos.';
					}

					}else{
					$this->option = 'alert';
					$this->alert = ' El curso no esta aprobado aun.';
				}
			}else{
				$this->option = 'error';
				$this->error = ' No tiene asignado este curso.';
			}
			} else {
				$this->option = 'error';
				$this->error = ' El ciclo esta cerrado.';
			}
		}else{
			$this->option = 'error';
			$this->error = ' No se especific&oacute; un id de curso v&aacute;lido.';
		}


	}

 	public function index(){
 		$this->redirect('grupos', 0);
 	}

 	public function selector($accion2 = '', $id = ''){
 		$this->error = '';
 		if($accion2 != '' && $id != ''){
				$this->option = 'selector';
				$id = intval($id, 10);
				$Cursos = new Cursos();
				$curso = $Cursos->find($id);
				$this->curso    =  $curso;
				$this->grupo    =  $curso->grupo();
				$this->ciclo    =  $this->grupo->ciclo();

				if($curso->id != ''){
					if($this->ciclo->abierto()){
					if($curso->asignado()){
						if($curso->aprobado()){
							$agenda=new Agenda();
						$materia=$curso->materia();
					if(
						($materia->duracion==7
							&&
						(
						$agenda->eventoValido('CAS-CAP',$this->ciclo->id,$this->controlador,$accion2,Session :: get_data('usr.login'))
							||
					 	$agenda->eventoValido('CAS-CAP-ESP',$this->ciclo->id,$this->controlador,$accion2,Session :: get_data('usr.login'))
					 	 )
						)
							||
					   (
					   ($materia->duracion!=7
							&&
						(
					   $agenda->eventoValido('AST-CAP',$this->ciclo->id,$this->controlador,$accion2,Session :: get_data('usr.login'))
							||
					   $agenda->eventoValido('AST-CAP-ESP',$this->ciclo->id,$this->controlador,$accion2,Session :: get_data('usr.login'))
						)
						)
					   )
						){
						$asistencias = new Asistencias();
						if($accion2 == 'agregar'){
							$fechas = $curso->asistenciasFechas();
						}else{
							$fechas = $curso->asistenciasFechasEdicion();
						}
						if(count($fechas) > 0){
							$this->accion_hdr = ucfirst($accion2);
							$this->accion2 = $accion2;
							$fch = array();
							foreach($fechas as $f){
								$fch[ $f->mesA() ][] = $f;
							}
							$this->fechas = $fch;
						}else{
				 			$this->option = 'alert';
							$this->alert = ' No hay fechas disponibles para ' . $accion2 . ' asistencias.';
						}
						}else{
						$this->option = 'error';
						$this->error = ' La agenda no permite la acción.';
						}
						}else{
						$this->option = 'alert';
						$this->alert = ' El curso no esta aprobado aun.';
						}
					}else{
						$this->option = 'error';
						$this->error = ' No tiene asignado este curso.';
					}
				} else {
					$this->option = 'error';
					$this->error = ' El ciclo esta cerrado.';

					}
				}else{
					$this->option = 'error';
					$this->error = ' El curso no existe.';
				}
 		}else{
 			$this->option = 'error';
			$this->error = ' Faltan datos para procesar la solicitud.';
 		}
 	}

	public function ver($id = ''){
		$this->error = '';
		if($id != ''){
			$this->option = 'vista';
			$id = intval($id, 10);
			$Cursos = new Cursos();
			$curso = $Cursos->find($id);
			if(!$curso->id == ''){
			$this->curso    	   =  $curso;
			$this->grupo    	   =  $curso->grupo();
			$this->ciclo    	   =  $this->grupo->ciclo();
				if($curso->asignado()){
					if($curso->aprobado()){
					$this->alumnos  	   =  $curso->alumnosgrupo();
					if(count($this->alumnos) > 0){
						$this->hdrAsistencias  =  $curso->asistenciasHdr();
						$this->nAsistencias    =  count($this->hdrAsistencias);
						$this->fechasHdr = array();
						foreach($this->hdrAsistencias as $fecha){
							$f = explode('-', $fecha->dia);
							$mes = substr(Utils :: mes_espanol($f[1]), 0, 3) . ' ' . $f[0];
							$this->fechasHdr[$mes][] = intval($f[2], 10);
						}
						$usr_login = Session :: get_data('usr.login');

						$this->acl_asistencias = array ();
						$acl = new gacl_extra();
						$acos_arr = array (
							'asistencias' => array (
								'agregar','editar','eliminar'
							)
						);

						$this->acl_asistencias = $acl->acl_check_multiple($acos_arr, $usr_login);
						$this->acl_asistencias =$this->acl_asistencias['asistencias'];
					}else{
						$this->option = 'alert';
						$this->alert = ' No hay alumnos asignados a este curso.';
					}
				}else{
					$this->option = 'alert';
					$this->alert = ' El curso no esta aprobado aun.';
					}
				}else{
					$this->option = 'error';
					$this->error = ' No tiene asignado este curso.';
				}
			}else{
				$this->option = 'error';
				$this->error = ' El curso no existe.';
			}
		}else{
			$this->option = 'error';
			$this->error = ' No se especific&oacute; el curso.';
		}
	}
 }
?>
