<?php
// Hekademos, Creado el 18/10/2008
/**
 * Asistencias Controller
 *
 * @package    hekademos
 * @author     mimeks <mimex@fipasoft.com.mx>
 * @copyright  2008 FiPa Software (contacto at fipasoft.com.mx)
 * @license    http://www.gnu.org/licenses/gpl.html  GNU General Public License (GPL)
 *
 */
 Kumbia :: import('app.componentes.*');
 Kumbia :: import('lib.excel.main');

 class CalificacionesController extends ApplicationController{
 	public $persistance = false;
 	public $template = "system";

	public function agregar(){
		$curso = new Cursos();
		$curso = $curso->find($this->post('cursos_id'));
		$this->error = '';
		if($curso->id != ''){
			$this->curso    =  $curso;
			$this->grupo    =  $curso->grupo();
			$this->ciclo    =  $this->grupo->ciclo();
			if($this->ciclo->abierto()){
			if($curso->asignado()){
				if($curso->aprobado()){
				$disp = $curso->calificacionesCaptura('agregar');
				$disponibles = array();
				foreach($disp as $tipo){
					foreach($tipo as $d){
						$disponibles[] = $d->clave;
					}
				}

			 	if(is_array($this->post('eventos'))){
			 		$this->option   = 'captura';

					$this->materia  =  $curso->materia();

					$eventos = $this->post('eventos');
					$parciales = array();
					$calificaciones = array();
					$nParciales = count($eventos['PRC']);
					if($nParciales==0)
						$nParciales = count($eventos['CPC']);


					$nCalificaciones = count($eventos['CAL']);
					if($nCalificaciones==0)
						$nCalificaciones = count($eventos['CCA']);


					foreach($eventos as $tipo => $cals){
						foreach($cals as $cal){
							if(in_array($cal, $disponibles) ){
								$clave = explode('-', $cal);
								$n = $clave[1];
								if($tipo == 'CAL' || $tipo == 'CCA'){
									$calificaciones[$n] = $cal;
								}else{
									$n = intval($n, 10);
									$parciales[$n] = $cal;
								}
							}else{
								$this->option = 'error';
								$this->error = ' El sistema no permite el registro para ' . $cal . '';
								break;
							}
						}
					}

					if($this->option != 'error'){
						$this->nParciales        =    $nParciales;
						$this->parciales         =    $parciales;
						$this->nCalificaciones   =    $nCalificaciones;
						$this->calificaciones    =    $calificaciones;
					}

		 		}else if( is_array($this->post('calificaciones')) ){
		 			$calificaciones = $this->post('calificaciones');
		 			$this->option = 'exito';
		 			$this->exito = 'El registro de calificaciones se realiz&oacute; en forma correcta.';

		 			$alumnos = array();
					foreach($curso->alumnosgrupo() as $alumno){
						$alumnos[] = $alumno->id;
					}

		 			ini_set("memory_limit","64M");
		 			mysql_query("BEGIN") or die("CAL_AGR_1");


		 			$calificacion = new Calificaciones();
		 			$oportunidades = $calificacion->oportunidadClaves();
		 			foreach($calificaciones as $alumnos_id => $valores){
		 				if(in_array($alumnos_id, $alumnos)){
		 					if(is_array($valores)){
		 						foreach($valores as $clave => $valor){

									if(in_array($clave, $disponibles)){
										$clave = explode('-', $clave);
										$tipo = $clave[0];
										$n = $clave[1];
						 				if( $tipo == 'PRC' || $tipo=='CPC'){

						 					$parcial = new Calificacionesparciales();
						 					$parcial->valor = $valor;
						 					if( $parcial->valido() ){
							 					$parcial->cursos_id = $curso->id;
							 					$parcial->alumnos_id = $alumnos_id;
							 					$parcial->periodo = intval($n, 10);
							 					if( !$parcial->save() ){
								 					$this->option = 'error';
													$this->error .= 'No se pudo guardar la calificaci&oacute;n parcial del alumno ' .
													                 $alumnos_id . '.';
													break 2;
							 					}


						 					}else{
							 					$this->option = 'error';
												$this->error .= 'El valor introducido no es asignable.';
												break 2;
						 					}

						 				}else if( $tipo == 'CAL' || $tipo=='CCA' ){
						 					$calificacion = new Calificaciones();
						 					$calificacion->valor = $valor;
						 					if( $calificacion->valida( $curso->materiaTipo() ) ){
							 					$calificacion->cursos_id = $curso->id;
							 					$calificacion->alumnos_id = $alumnos_id;
							 					$oportunidad = $curso->oportunidadClaveAlumno($alumnos_id);
							 					if(  $oportunidad  == $n ||
							 					    ($oportunidad == 'SIN' && $valor == 'SD') ||
							 					    ($n == 'ORD' && $oportunidad == 'EXT' && $valor == 'SD') )
							 					{
							 						$calificacion->oportunidades_id = $oportunidades[$n];
								 					if( !$calificacion->save() ){
									 					$this->option = 'error';
														$this->error .= 'No se pudo guardar la calificaci&oacute;n del alumno ' .
														                 $alumnos_id . '.';
														break 2;
								 					}
							 					}else{
								 					$this->option = 'error';
								 					$alumno = new Alumnos();
													$alumno->find($alumnos_id, "columns: codigo");
													$this->error .= 'El alumno ' . $alumno->codigo . ' no puede ser evaluado en ' .
													 				$n . '.';
													break 2;
							 					}
						 					}else{
							 					$this->option = 'error';
												$this->error .= 'El valor introducido no es asignable.';
												break 2;
						 					}
						 				}else{
						 					$this->option = 'error';
											$this->error .= 'Los datos son inconsistentes';
											break 2;
						 				}
					 				}else{
					 					$this->option = 'error';
										$this->error .= 'El sistema no permite el registro de calificaciones para ' .
														$clave . '.';
										//break 2;
					 				}
								}
		 					}

		 				}else{
		 					$this->option = 'error';
							$this->error .= 'El sistema no permite el registro de calificaciones para el alumno ' .
												$alumnos_id . '.';
							break;
		 				}
		 			}



		 			if($this->error == ''){
						mysql_query("COMMIT") or die("CAL_AGR_2");
						$historial=new Historial();
						$historial->ciclos_id= Session :: get_data('ciclo.id');;
						$historial->usuario=Session :: get_data('usr.login');
						$c=$this->materia->nombre.' '.$this->grupo->grado.$this->grupo->letra.$this->grupo->turno.' '.$this->grupo->verOferta();
						$l='';
						if( $tipo == 'PRC' || $tipo=='CPC'){
						$l=" parciales ";
						}elseif( $tipo == 'CAL' || $tipo=='CCA' ){
						$l=" finales ";
						}
						$historial->descripcion="Se agregaron calificaciones".$l." del curso: ".$c.".";
						$historial->controlador="calificaciones";
						$historial->accion="agregar";
						$historial->saved_at=date("Y-m-d H:i:s");//2009-01-20 14:28:29
						$historial->save();
					}else{
						mysql_query("ROLLBACK") or die("CAL_AGR_3");

					}

		 		}else{
		 			$this->option = 'error';
					$this->error = ' Faltan datos para procesar la solicitud.';
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
			$this->error = ' No se especific&oacute; un id de curso v&aacute;lido.';
 		}
	}

	public function editar(){
		$curso = new Cursos();
		$curso = $curso->find($this->post('cursos_id'));
		$this->error = '';
		if($curso->id != ''){
			$this->curso        =   $curso;
			$this->grupo        =   $curso->grupo();
			$this->ciclo        =   $this->grupo->ciclo();
			if($this->ciclo->abierto()){
			if($curso->asignado()){
				if($curso->aprobado()){
				$disp = $curso->calificacionesCaptura('editar');
				$disponibles = array();
				foreach($disp as $tipo){
					foreach($tipo as $d){
						$disponibles[] = $d->clave;
					}
				}

			 	if(is_array($this->post('eventos'))){
			 		$this->option       =  'captura';

					$this->materia      =   $curso->materia();

					$eventos = $this->post('eventos');
					$parciales = array();
					$calificaciones = array();
					$nParciales = count($eventos['PRC']);
					if($nParciales==0)
						$nParciales = count($eventos['CPC']);


					$nCalificaciones = count($eventos['CAL']);
					if($nCalificaciones==0)
						$nCalificaciones = count($eventos['CCA']);

					foreach($eventos as $tipo => $cals){
						foreach($cals as $cal){
							if(in_array($cal, $disponibles) ){
								$clave = explode('-', $cal);
								$n = $clave[1];
								if($tipo == 'CAL' || $tipo == 'CCA'){
									$calificaciones[$n] = $cal;
								}else{
									$n = intval($n, 10);
									$parciales[$n] = $cal;
								}
							}else{
								$this->option = 'error';
								$this->error = ' El sistema no permite el registro para ' . $cal . '';
								break;
							}
						}
					}

					if($this->option != 'error'){
						$this->nParciales         =    $nParciales;
						$this->parciales          =    $parciales;
						$this->nCalificaciones    =    $nCalificaciones;
						$this->calificaciones     =    $calificaciones;

						$this->ordExtSimultaneos = false;
						if( (in_array('CAL-EXT', $eventos['CAL']) && in_array('CAL-ORD', $eventos['CAL'])) || (in_array('CCA-EXT', $eventos['CCA']) && in_array('CCA-ORD', $eventos['CCA'])) ){
							$this->ordExtSimultaneos = true;
						}

						if($nParciales > 0){
							$this->vParcial	   	  =    $curso->parciales();
						}
						if($nCalificaciones > 0){
							$this->vCalificacion  =    $curso->calificaciones();
						}


					}

		 		}else if( is_array($this->post('parciales')) || is_array($this->post('calificaciones'))){
		 			$calificaciones = $this->post('calificaciones');
		 			$parciales = $this->post('parciales');
					$calificacion = new Calificaciones();
					$parcial = new Calificacionesparciales();
					$alumnos = $curso->alumnosIds();
					$oportunidades = $calificacion->oportunidadClaves();

		 			$this->option = 'exito';
		 			$this->exito = 'El registro de calificaciones se actualiz&oacute; en forma correcta.';

		 			if( $calificacion->editables($calificaciones, $disponibles) &&
		 			    $parcial->editables($parciales, $disponibles) )
		 			{
		 				mysql_query("BEGIN") or die("CAL_EDI_1");

			 			$oportunidades = $calificacion->oportunidadClaves();

			 			foreach($parciales as $modo => $claves){

			 					foreach($claves as $clave => $ids){
			 						$periodo = $parcial->deClaveAPeriodo($clave);

			 						foreach($ids as $id => $valor){
				 						if($modo == 'EDI'){
				 							$parcial->find($id);
				 							$alumnos_id = $parcial->alumnos_id;
				 						}else{
				 							$parcial = new Calificacionesparciales();
				 							$parcial->cursos_id = $curso->id;
				 							$parcial->alumnos_id = $id;
				 							$parcial->periodo = $periodo;
				 							$alumnos_id = $id;
				 						}
				 						$parcial->valor = $valor;
				 						if( $parcial->cursos_id != $curso->id ){
							 				$this->option = 'error';
											$this->error = ' Los datos son inconsistentes';
											break 3;
				 						}
				 						if( !in_array($alumnos_id, $alumnos) ){
							 				$this->option = 'error';
							 				$alumno = new Alumnos();
											$alumno->find($alumnos_id, "columns: codigo");
											$this->error = ' El alumno ' . $alumno->codigo . ' no pertenece a este curso.';
											break 3;
				 						}
				 						if( !$parcial->valido() ){
				 							$this->option = 'error';
											$this->error = ' El valor ' . $valor . ' no es asignable.';
											break 3;
				 						}
				 						if( !$parcial->save() ){
						 					$this->option = 'error';
											$this->error .= 'No se pudo guardar la calificaci&oacute;n parcial del alumno ' .
										                 $alumnos_id . '.';
											break 3;
					 					}
			 						}

			 					}

			 			}

						$modificados = array();
			 			foreach($calificaciones as $modo => $claves){
					 			ksort($claves);
								$claves = array_reverse($claves);

			 					foreach($claves as $clave => $ids){
									$opr = $calificacion->deClaveAOportunidad($clave);

			 						foreach($ids as $id => $valor){
				 						if($modo == 'EDI'){
				 							$calificacion->find($id);
				 							$alumnos_id = $calificacion->alumnos_id;
				 						}else{
				 							$calificacion = new Calificaciones();
				 							$calificacion->cursos_id = $curso->id;
				 							$calificacion->alumnos_id = $id;
				 							$calificacion->oportunidades_id = $oportunidades[$opr];
				 							$alumnos_id = $id;
				 						}
				 						$calificacion->valor = $valor;
				 						if( $calificacion->cursos_id != $curso->id ){
							 				$this->option = 'error';
											$this->error = ' No se permite asignar calificaciones de otros cursos.';
											break 3;
				 						}
				 						if( !in_array($alumnos_id, $alumnos) ){
							 				$this->option = 'error';
							 				$alumno = new Alumnos();
											$alumno->find($alumnos_id, "columns: codigo");
											$this->error = ' El alumno ' . $alumno->codigo . ' no pertenece a este curso.';
											break 3;
				 						}
				 						if( !$calificacion->valida($curso->materiaTipo()) ){
				 							$this->option = 'error';
											$this->error = ' El valor ' . $valor . ' no es asignable.';
											break 3;
				 						}
				 						$oportunidad = $curso->oportunidadClaveAlumno($alumnos_id, 'AST');

				 						if( ($oportunidad == 'SIN' && $valor != 'SD')                   ||
				 						    ($opr == 'ORD' && $oportunidad == 'ORD' && $valor == 'SD')  ||
				 						    ($opr == 'ORD' && $oportunidad == 'EXT' && $valor != 'SD')  ||
				 						    ($opr == 'EXT' && $oportunidad == 'ORD' && $valor == 'SD')  ||
				 						    ($opr == 'EXT' && $oportunidad == 'EXT' && $valor == 'SD')     )
				 						{
											$this->option = 'error';
											$alumno = new Alumnos();
											$alumno->find($alumnos_id, "columns: codigo");
											$this->error .= 'El alumno ' . $alumno->codigo . ' (' . $oportunidad . ') no puede ser evaluado con ' .
															$valor . ' en ' . $opr . '.';
											break 3;
				 						}
				 						if( !$calificacion->save() ){
						 					$this->option = 'error';
											$this->error .= 'No se pudo guardar la calificaci&oacute;n del alumno ' .
										                 $alumnos_id . '.';
											break 3;
					 					}
					 					$modificados[$alumnos_id] = $oportunidad;
			 						}
			 					}

			 			}

			 			if($this->error == ''){

			 				$adicionales = '';
			 				// en caso de que ya esten capturados ORDINARIOS Y EXTRAORDINARIOS se verifica su integridad
			 				$capturadas = $curso->calificacionesCapturadas();
			 				if( (in_array('CAL-ORD', $capturadas) &&
			 				    in_array('CAL-EXT', $capturadas))
			 				     ||
			 				    (in_array('CCA-ORD', $capturadas) &&
			 				    in_array('CCA-EXT', $capturadas))    )
			 				{
								foreach($modificados as $id => $opr_ast){
									$alumno = new Alumnos();
									$alumno->find($id);
									try{
										$resp = $alumno->validarCalificacionesFinales($curso->id, $opr_ast, $oportunidades);
									}catch(dbException $e){
										$this->option = 'error';
										$this->error .=  'Error al validar las calificaciones';
									}
									if($resp['ERROR'] != ''){
										$this->error .=  'Error al validar las calficaciones del alumno ' . $resp['CODIGO'] . ': ' . $resp['ERROR'];
										break;
									}else{
										if($resp['EXITO'] != ''){
											$adicionales .= '<br />' . $resp['CODIGO'] . ': ' . $resp['EXITO'];
										}
									}
								}
			 				}
			 				if($this->error == ''){
				 				mysql_query("COMMIT") or die("CAL_EDI_2");
				 				if($adicionales != ''){
				 					$this->exito .= '<br />Para garantizar la integridad del registro de calificaciones ' .
				 									'se hicieron los siguientes ajustes: ' . $adicionales;
				 				}
				 				$historial=new Historial();
								$historial->ciclos_id= Session :: get_data('ciclo.id');
								$historial->usuario=Session :: get_data('usr.login');
								$c=$this->materia->nombre.' '.$this->grupo->grado.$this->grupo->letra.$this->grupo->turno.' '.$this->grupo->verOferta();
								$l='';
								if( is_array($this->post('parciales')) ){
								$l=" parciales ";
								}elseif( is_array($this->post('calificaciones')) ){
								$l=" finales ";
								}
								$historial->descripcion="Se editaron calificaciones".$l." del curso: ".$c.".";
								$historial->controlador="calificaciones";
								$historial->accion="editar";
								$historial->saved_at=date("Y-m-d H:i:s");//2009-01-20 14:28:29
								$historial->save();
			 				}else{
								mysql_query("ROLLBACK") or die("CAL_EDI_3");
							}

						}else{
							mysql_query("ROLLBACK") or die("CAL_EDI_4");
						}
		 			}else{
		 				$this->option = 'error';
						$this->error = ' No es posible modificar las calificaciones.';
		 			}

		 		}else{
		 			$this->option = 'alert';
					$this->alert = ' No se realizaron cambios al registro de calificaciones.';
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

	public function eliminar() {
		$curso = new Cursos();
		$curso = $curso->find($this->post('cursos_id'));
		$this->error = '';
		if($curso->id != ''){
			$this->curso    =  $curso;
			$this->grupo    =  $curso->grupo();
			$this->ciclo    =  $this->grupo->ciclo();
			if($this->ciclo->abierto()){
			if($curso->asignado()){
				if($curso->aprobado()){

				$this->option   =  'exito';
				$this->exito    =  'Las calificaciones se eliminaron en forma correcta.';

				$disp = $curso->calificacionesCaptura('eliminar');
				$disponibles = array();
				foreach($disp as $tipo){
					foreach($tipo as $d){
						$disponibles[] = $d->clave;
					}
				}

			 	if( is_array($this->post('calificaciones')) ){
					$eventos = $this->post('calificaciones');

					mysql_query("BEGIN") or die("CAL_ELI_1");

					$calificaciones = new Calificaciones();
					$parciales = new Calificacionesparciales();
					$oportunidades = $calificaciones->oportunidadClaves();

					foreach($eventos as $tipo => $cals){
						foreach($cals as $cal){
							if(in_array($cal, $disponibles) ){
								$clave = explode('-', $cal);
								$n = $clave[1];
								try{
									if($tipo == 'PRC' || $tipo== 'CPC'){
										$n = intval($n, 10);
										$parciales->delete(
												"cursos_id = '" . $curso->id . "' " .
												"AND periodo = '" . $n . "' "
										);
									}else if($tipo == 'CAL' || $tipo== 'CCA'){
										$oportunidad = $oportunidades[$n];
										$calificaciones->delete(
												"cursos_id = '" . $curso->id . "' " .
												"AND oportunidades_id = '" . $oportunidad . "' "
										);
									}else{
										$this->option = 'error';
										$this->error .= 'Los datos son inconsistentes';
										break 2;
									}
								}catch(dbException $e){
									$this->option = 'error';
									$this->error .= ' Error al intentar eliminar las calificaciones de ' . $cal . '. ';
									break 2;
								}
							}else{
								$this->option = 'error';
								$this->error = ' El sistema no permite modificaciones a ' . $cal . '';
								break 2;
							}
						}
					}

					if($this->option == 'exito'){
						mysql_query("COMMIT") or die("CAL_ELI_2");
								$historial=new Historial();
								$historial->ciclos_id= Session :: get_data('ciclo.id');
								$historial->usuario=Session :: get_data('usr.login');
								$c=$this->materia->nombre.' '.$this->grupo->grado.$this->grupo->letra.$this->grupo->turno.' '.$this->grupo->verOferta();
								$l='';
								if( $tipo == 'PRC' || $tipo=='CPC'){
								$l=" parciales ";
								}elseif($tipo == 'CAL' || $tipo=='CCA'){
								$l=" finales ";
								}
								$historial->descripcion="Se eliminaron calificaciones".$l." del curso: ".$c.".";
								$historial->controlador="calificaciones";
								$historial->accion="eliminar";
								$historial->saved_at=date("Y-m-d H:i:s");//2009-01-20 14:28:29
								$historial->save();
					}else{
						mysql_query("ROLLBACK") or die("CAL_ELI_3");
					}

		 		}else if( is_array($this->post('eventos')) ){
		 			$calificaciones = $this->post('eventos');
					if( count($calificaciones) > 0 ){
						$this->option = 'confirm';
						$this->confirm = 'Se dispone a eliminar los siguientes registros de calificaciones de este curso: <br />';
						$i = 0;
						foreach($calificaciones as $tipo => $cals){
							foreach($cals as $cal){
								$this->confirm .= ($i == 0 ? '' : ', ') . $cal;
								$i ++ ;
							}
						}
						$this->curso = $curso;
						$this->confirm .= '<br /><strong>Desea continuar?</strong>';
						$this->calificaciones = $this->post('eventos');
					}else{
						$this->option = 'error';
						$this->error = ' No se seleccionaron registros para eliminar.';
					}
		 		}else{
		 			$this->option = 'error';
					$this->error = ' Faltan datos para procesar la solicitud.';
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
			$this->error = ' No se especific&oacute; un id de curso v&aacute;lido.';
 		}
	}

 	public function exportar($grp_id = ''){
		$this->set_response("view");
		require('app/reportes/xls.cursos.php');
		$ciclo_id = Session :: get_data('ciclo.id');
		$reporte = new XLSCursos($ciclo_id, $grp_id);
		$reporte->generar();
 	}

 	public function selector($accion2 = '', $id = ''){
 		if($accion2 != '' && $id != ''){
				$this->option = 'selector';
				$id = intval($id, 10);
				$Cursos = new Cursos();
				$curso = $Cursos->find($id);
				$this->accion_hdr = ucfirst($accion2);

				if($curso->id != ''){
					$this->curso    =  $curso;
					$this->grupo    =  $curso->grupo();
					$this->ciclo    =  $this->grupo->ciclo();
					if($this->ciclo->abierto()){
					if($curso->asignado()){
						if($curso->aprobado()){

						if($accion2 == 'agregar'){
							$eventos = $curso->calificacionesCaptura($accion2);
						}else{
							$eventos = $curso->calificacionesCaptura($accion2);
						}

						if(count($eventos) > 0){
							$this->accion2 = $accion2;
							$this->eventos = $eventos;
						}else{
				 			$this->option = 'alert';
							$this->alert = ' En este momento no le est&aacute; permitido ' . $accion2 . ' calificaciones.';
						}
					}else{
					$this->option = 'alert';
					$this->alert = ' El curso no esta aprobado aun.';
					}
					}else{
						$this->option = 'error';
						$this->error = ' No est&aacute; asignado a este curso.';
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
		if($id != ''){
			$this->option = 'vista';
			$id = intval($id, 10);
			$Cursos = new Cursos();
			$curso = $Cursos->find($id);
			if($curso->id != ''){
				$this->curso    	   =  $curso;
					$this->grupo    	   =  $curso->grupo();
					$this->ciclo    	   =  $this->grupo->ciclo();
					$this->alumnos  	   =  $curso->alumnosgrupo();
					$this->materia         =  $curso->materia();
				if($curso->asignado()){
					if($curso->aprobado()){

					if(count($this->alumnos) > 0){
						$this->parciales 	   =  $curso->parciales();
						$this->calificaciones  =  $curso->calificaciones();
						$this->hdrParciales    =  $curso->parcialesHdr();
						$this->nParciales 	   =  count($this->hdrParciales);

						$usr_login = Session :: get_data('usr.login');

						$this->acl_calificaciones = array ();
						$acl = new gacl_extra();
						$acos_arr = array (
							'calificaciones' => array (
								'agregar','editar','eliminar'
							)
						);

						$this->acl_calificaciones = $acl->acl_check_multiple($acos_arr, $usr_login);
						$this->acl_calificaciones =$this->acl_calificaciones['calificaciones'];

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
