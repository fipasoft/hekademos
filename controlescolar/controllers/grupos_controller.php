<?php
// Hekademos, Creado el 27/09/2008
/**
 * Grupos
 *
 * @package
 * @author     mimeks <mimex@fipasoft.com.mx>
 * @copyright  2008 FiPa Software (contacto at fipasoft.com.mx)
 * @license    http://www.gnu.org/licenses/gpl.html  GNU General Public License (GPL)
 *
 */
Kumbia :: import('app.componentes.*');
Kumbia :: import('lib.phpgacl.main');
Kumbia :: import('lib.excel.main');

class GruposController extends ApplicationController{
	public $template = "system";

	public function agregar(){
		$ciclo_id = Session :: get_data('ciclo.id');
		$Ciclos = new Ciclos();
		$this->ciclo = $Ciclos->find($ciclo_id);
		if($this->ciclo->abierto()){
			$sigCiclo = new Ciclos();
			$sigCiclo = $sigCiclo->find_first("numero='" . $this->ciclo->anterior() . "'");
			$agenda = new Agenda();
			if(
			$agenda->eventoValido('PLN-GEN', Session :: get_data('ciclo.id'), $this->controlador, $this->accion, Session :: get_data('usr.login'))
			||
			$agenda->eventoValido('PLN-GEN-ESP', Session :: get_data('ciclo.id'), $this->controlador, $this->accion, Session :: get_data('usr.login'))
			||
			($sigCiclo->id != '' && ($agenda->eventoValido('PLN-GEN', $sigCiclo->id, $this->controlador, $this->accion, Session :: get_data('usr.login'))
			|| $agenda->eventoValido('PLN-GEN-ESP', $sigCiclo->id, $this->controlador, $this->accion, Session :: get_data('usr.login'))))) {

				if($this->post('grado') == ''){
					$ciclo_id = Session :: get_data('ciclo.id');
					$Ciclos = new Ciclos();
					$this->ciclo = $Ciclos->find($ciclo_id);
					$oferta=new Oferta();
					$this->ofertas=$oferta->todas();
					if($this->ciclo->id != ''){
						$this->option = 'captura';
					}else{
						$this->option = 'error';
						$this->error .= ' Error al seleccionar el ciclo escolar.';
					}
				}else{
					$this->option = '';
					$this->error = '';
					$grupo = new Grupos();
					$grupo->ciclos_id = $this->post('ciclos_id');
					$grupo->grado = $this->post('grado');
					$grupo->letra = $this->post('letra');
					$grupo->turno = $this->post('turno');
					$grupo->oferta_id = $this->post('oferta');
					if($grupo->save()){
						$this->option = 'exito';
						$historial=new Historial();
						$historial->ciclos_id= Session :: get_data('ciclo.id');
						$historial->usuario=Session :: get_data('usr.login');
						$historial->descripcion='Se agrego el grupo '.$grupo->verInfo().', '.$grupo->verOferta();
						$historial->controlador="grupos";
						$historial->accion="agregar";
						$historial->saved_at=date("Y-m-d H:i:s");//2009-01-20 14:28:29
						$historial->save();
					}else{
						$this->option = 'error';
						$this->error .= ' Error al guardar en la BD.';
					}

				}
			} else {
				$this->option = 'error';
				$this->error = ' La agenda no permite agregar grupos.';
			}
		} else {
			$this->option = 'error';
			$this->error = ' El ciclo esta cerrado.';

		}

	}

	public function asignartodos(){
		try{
		$gacl = new gacl_api();
		$objects = $gacl->get_group_objects($gacl->get_group_id('secretarias'));
		$c = '';
		foreach($objects as $sections => $users){
			foreach($users as $login){
				$c .= ($c == '' ? '' : 'OR ') . "login = '" . $login . "' ";
			}
		}
		$gacl = null;
		$gacl_x = new gacl_extra();
		$gacl_x->dbReset();
		if($c != ''){
			$db = db::raw_connect();
			$usuarios = new Usuarios();
			$usuarios = $usuarios->find("conditions: " . $c, "order: ap, am, nombre");

		}

		mysql_query("BEGIN") or die("Error en begin");
		$grupos = new Grupos();
		$grupos = $grupos->todos();
		foreach($grupos as $g){
			foreach($usuarios as $u){
				$asignacion = new Asignar();
				$asignacion = $asignacion->find_first("usuarios_id='".$u->id."' AND grupos_id='".$g->id."'");
				if($asignacion->id==""){
					$asignacion = new Asignar();
					$asignacion->grupos_id = $g->id;
					$asignacion->usuarios_id = $u->id;
					if(!$asignacion->save()){
						mysql_query("ROLLBACK") or die("Error en rollback");
						throw new Exception("Error al guardar la asignacion");
					}else{
						var_dump("MSJ: Se asigno: ".$u->login." ".$g->ver('html')."<br/>");
					}
				}else{
					var_dump("WARNING: La asignacion ya existe: ".$u->login." ".$g->ver('html')."<br/>");
				}
			}
		}
		var_dump("MSJ: Tarea finalizada con exito.<br/>");
		mysql_query("COMMIT") or die("Error en commit");
		}catch (Exception $e){
			var_dump("ERROR :".$e->getMessage()."<br/>");
		}
		exit;
	}

	public function asignar($id = ''){
		if($id != ''){
			$this->option = 'captura';
			$id = intval($id, 10);
			$Grupos = new Grupos();
			$this->grupo = $Grupos->find($id);
			$Ciclos = new Ciclos();
			$this->ciclo = $Ciclos->find($this->grupo->ciclos_id);

			if($this->grupo->id != ''){
				if($this->ciclo->abierto()){
					$gacl = new gacl_api();
					$objects = $gacl->get_group_objects($gacl->get_group_id('secretarias'));
					$c = '';
					$this->usuarios = array();
					foreach($objects as $sections => $users){
						foreach($users as $login){
							$c .= ($c == '' ? '' : 'OR ') . "login = '" . $login . "' ";
						}
					}
					$gacl = null;
					$gacl_x = new gacl_extra();
					$gacl_x->dbReset();
					if($c != ''){
						$db = db::raw_connect();
						$usuarios = new Usuarios();
						$usuarios = $usuarios->find("conditions: " . $c, "order: ap, am, nombre");
						$this->usuarios = $usuarios;
					}
				} else {
					$this->option = 'error';
					$this->error = ' El ciclo esta cerrado.';

				}

			}else{
				$this->option = 'error';
				$this->error = ' El grupo no existe.';
			}
		}else if($this->post('grupos_id') != ''){
			if($this->post('modo') == 'ajax'){
				$this->option = 'ajax';
				$this->set_response('view');
			}else{
				$this->option = '';
				$this->error = '';
				$Grupos = new Grupos();
				$grupo = $Grupos->find($this->post('grupos_id'));
				if($grupo->id != ''){
					// eliminando las asignaciones anteriores del grupo
					$asignaciones = new Asignar();
					if($asignaciones->delete("grupos_id = '" . $grupo->id . "'")){
						$this->option = 'exito';
						// asignando el grupo
						$usuarios = array_unique($this->post('usuarios'));
						foreach($usuarios as $usuario_id){
							if($usuario_id != ''){
								$asignacion = new Asignar();
								$asignacion->grupos_id = $grupo->id;
								$asignacion->usuarios_id = $usuario_id;
								if(!$asignacion->save()){
									$this->option = 'error';
									$this->error .= ' Error al asignar el usuario ' . $usuario_id . '.';
								}
							}
						}
					}else{
						$this->option = 'error';
						$this->error .= ' Error al intentar eliminar las asignaciones del grupo ' .
						$grupo->verInfo() . ' en la BD.';
					}
				}else{
					$this->option = 'error';
					$this->error = ' El grupo no existe.';
				}
			}
		}else{
			$this->option = 'error';
			$this->error = ' No se especific&oacute; el grupo.';
		}
	}

	public function boleta($grupos_id = ''){
		require('app/reportes/xls.boleta.php');
		$ciclo_id = Session :: get_data('ciclo.id');
		$grupos_id = ( $grupos_id == '' ? $this->post('grupos_id') : $grupos_id);

		$grupos = new Grupos();
		$ciclos = new Ciclos();
		$ciclo = $ciclos->find($ciclo_id);
		$this->ciclo = $ciclo;

		if($grupos_id != ''){
			$this->set_response("view");
			$grupo = $grupos->find($grupos_id);
			$reporte = new XLSBoleta($grupos_id);
			$reporte->generar();
		}else{
			$b = new Busqueda('cursos', 'index');
			$c = $b->cargar();
			$from = "cursos " .
					"INNER JOIN grupos ON cursos.grupos_id = grupos.id " .
					"INNER JOIN materias ON cursos.materias_id  = materias.id " .
					"INNER JOIN profesores ON cursos.profesores_id  = profesores.id ";

			$grupos = $grupos->find_all_by_sql(
								"SELECT " .
									"grupos.id, " .
									"grupos.grado, " .
									"grupos.letra, " .
									"grupos.turno " .
								"FROM " . $from .
								"WHERE grupos.ciclos_id = '" . $ciclo->id . "' " .
			($c == "" ? "" : "AND " . $c . " ")  .
								"GROUP BY grupos.id " .
								"ORDER BY grupos.turno, grupos.grado, grupos.letra, materias.nombre "
					   );
					   if(count($grupos) > 0){
					   	$this->set_response("view");
					   	if(!file_exists('./public/boletas/')){
					   		mkdir('./public/boletas/');
					   	}
					   	$salida = '';
					   	foreach($grupos as $grupo){
					   		$encargados = $grupo->encargados();
					   		if(is_array($encargados)){
					   			foreach($encargados as $encargado){
					   				$lgn = $encargado->login();
					   				ob_end_clean();
					   				ob_start();
					   				$reporte = new XLSBoleta($grupo->id);
					   				$n = $reporte->getNombre();
					   				if(!file_exists('./public/boletas/' . $lgn . '/')){
					   					mkdir('./public/boletas/' . $lgn . '/');
					   				}
					   				$f = fopen('./public/boletas/' . $lgn . '/' . $n, "w");
					   				$reporte->close();
					   				fwrite($f, ob_get_contents());
					   				fclose($f);
					   				$salida .= 'BOLETAS DEL GRUPO ' . $grupo->verInfo() . ', OK!<br />';
					   			}
					   		}
					   	}
					   	ob_end_clean();
					   	echo $salida;
					   }
		}
	}

	public function curso($id = ''){
		if($id != ''){
			$this->option = 'vista';
			$id = intval($id, 10);
			$Cursos = new Cursos();
			$curso = $Cursos->find($id);
			if(!$curso->id == ''){
				$this->curso    	   =  $curso;
				$this->grupo    	   =  $curso->grupo();
				$this->ciclo    	   =  $this->grupo->ciclo();
				$this->alumnos  	   =  $curso->alumnosgrupo();
				if($this->curso->aprobado()){
					$alumnoscursos=new Alumnoscursos();
					$alumnoscursos=$alumnoscursos->find("cursos_id=".$this->curso->id);
					$inscripciones=array();
					foreach($alumnoscursos as $ac){
						$inscripciones[$ac->alumnos_id]=$ac->id;
					}
					$this->inscripciones=$inscripciones;

					if(count($this->alumnos) > 0){
						$this->calificaciones  =  $curso->calificaciones();
						$this->asistencias  =  $curso->asistenciasResumen();
						$this->parciales 	   =  $curso->parcialesResumen();
						$this->nParciales 	   =  count($this->hdrParciales);

					 // acl
						$usr_login = Session :: get_data('usr.login');
						$this->acl_asistencias = array();
						$this->acl_calificaciones = array();
						$this->acl_grupos = array();
						$acl = new gacl_extra();
						$acos_arr = array(
            				'calificaciones' => array(
                			'ver'
                			)

                			);
                			$this->acl_calificaciones = $acl->acl_check_multiple($acos_arr, $usr_login);

                			$acos_arr=array(
					'asistencias' => array(
                			'ver',
                			'faltas'
                			)
                			);

                			$this->acl_asistencias=$acl->acl_check_multiple($acos_arr,$usr_login);


                			$acos_arr=array(
					'grupos' => array(
                			'index'
                			)
                			);

                			$this->acl_grupos=$acl->acl_check_multiple($acos_arr,$usr_login);

                			$acos_arr=array(
					'inscripcion' => array(
						'agregar', 'eliminar', 'articulo'
						)
						);

						$this->acl_inscripcion=$acl->acl_check_multiple($acos_arr,$usr_login);

						$this->acl_calificaciones=$this->acl_calificaciones['calificaciones'];
						$this->acl_asistencias=$this->acl_asistencias['asistencias'];
						$this->acl_grupos=$this->acl_grupos['grupos'];
						$this->acl_inscripcion=$this->acl_inscripcion['inscripcion'];

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
				$this->error = ' El curso no existe.';
			}
		}else{
			$this->option = 'error';
			$this->error = ' No se especific&oacute; el curso.';
		}
	}

	public function disponible(){
		$this->set_response('view');
		$tabla = $this->post('tabla');
		$ciclos_id = $this->post('ciclos_id');
		$grado = $this->post('grado');
		$letra = $this->post('letra');
		$turno = $this->post('turno');
		$oferta= $this->post('oferta');
		if($grado != '' && $letra != '' && $turno != '' && $oferta != ''){
			$this->valor = $valor = $grado . $letra . $turno . $oferta;
		}else{
			$this->valor = $valor = '';
		}
		$this->invalido = false;
		$this->disponible = false;
		if($valor != ''){
			$registros = new Grupos();
			$n = $registros->count("ciclos_id = '" . $ciclos_id . "' AND " .
								       "grado = '" .  $grado   . "' AND " .
								       "letra = '" .  $letra   . "' AND " .
								       "turno = '" .  $turno   . "' AND " .
								       "oferta_id='". $oferta   ."'"
								       );
								       if($n == 0){
								       	$this->disponible = true;
								       }
		}
	}

	public function editar($id = ''){
		$this->option = 'error';
		$this->error = '';
		if($id != ''){
			$Grupos = new Grupos();
			$grupo = $Grupos->find($id);
			if($grupo->id != ''){
				$ciclo_id = $grupo->ciclos_id;
				$Ciclos = new Ciclos();
				$this->ciclo = $Ciclos->find($ciclo_id);
				if($this->ciclo->abierto()){
					$sigCiclo = new Ciclos();
					$sigCiclo = $sigCiclo->find_first("numero='" . $this->ciclo->anterior() . "'");
					$agenda = new Agenda();
					if ($agenda->eventoValido('PLN-GEN', $ciclo_id, $this->controlador, $this->accion, Session :: get_data('usr.login')) || $agenda->eventoValido('PLN-GEN-ESP', $ciclo_id, $this->controlador, $this->accion, Session :: get_data('usr.login')) || ($sigCiclo->id != '' && ($agenda->eventoValido('PLN-GEN', $sigCiclo->id, $this->controlador, $this->accion, Session :: get_data('usr.login')) || $agenda->eventoValido('PLN-GEN-ESP', $sigCiclo->id, $this->controlador, $this->accion, Session :: get_data('usr.login'))))) {

						$this->option = 'captura';
						$this->grupo = $grupo;
						$Ciclos = new Ciclos();
						$this->ciclo = $Ciclos->find($grupo->ciclos_id);
						$oferta=new Oferta();
						$this->ofertas=$oferta->todas();
					} else {
						$this->option = 'error';
						$this->error = ' La agenda no permite editar grupos.';
					}
				} else {
					$this->option = 'error';
					$this->error = ' El ciclo esta cerrado.';

				}

			}else{
				$this->error = ' El grupo especificado no existe.';
			}
		}else if($this->post('id') != ''){
			$Grupos = new Grupos();
			$grupo = $Grupos->find($this->post('id'));
			$gant=$grupo->verInfo().', '.$grupo->verOferta();

			$grupo->grado = $this->post('grado');
			$grupo->letra = $this->post('letra');
			$grupo->turno = $this->post('turno');
			//$grupo->oferta_id = $this->post('oferta');
			if($grupo->id != ''){
				$ciclo_id = $grupo->ciclos_id;
				$Ciclos = new Ciclos();
				$this->ciclo = $Ciclos->find($ciclo_id);
				if($this->ciclo->abierto()){
					$sigCiclo = new Ciclos();
					$sigCiclo = $sigCiclo->find_first("numero='" . $this->ciclo->anterior() . "'");
					$agenda = new Agenda();
					if ($agenda->eventoValido('PLN-GEN', $ciclo_id, $this->controlador, $this->accion, Session :: get_data('usr.login')) || $agenda->eventoValido('PLN-GEN-ESP', $ciclo_id, $this->controlador, $this->accion, Session :: get_data('usr.login')) || ($sigCiclo->id != '' && ($agenda->eventoValido('PLN-GEN', $sigCiclo->id, $this->controlador, $this->accion, Session :: get_data('usr.login')) || $agenda->eventoValido('PLN-GEN-ESP', $sigCiclo->id, $this->controlador, $this->accion, Session :: get_data('usr.login'))))) {

						if($grupo->save()){
							$this->option = 'exito';
							$historial=new Historial();
							$historial->ciclos_id= Session :: get_data('ciclo.id');
							$historial->usuario=Session :: get_data('usr.login');
							$historial->descripcion='Se edito el grupo '.$gant.' a '.$grupo->verInfo().', '.$grupo->verOferta();
							$historial->controlador="grupos";
							$historial->accion="editar";
							$historial->saved_at=date("Y-m-d H:i:s");//2009-01-20 14:28:29
							$historial->save();
						}else{
							$this->error .= ' Error al guardar en la BD.'. $grupo->show_message();
						}
					} else {
						$this->option = 'error';
						$this->error = ' La agenda no permite editar grupos.';
					}
				} else {
					$this->option = 'error';
					$this->error = ' El ciclo esta cerrado.';

				}

			}else{
				$this->error = ' El grupo no existe.';
			}

		}else{
			$this->error .= ' No se especific&oacute; el grupo.';
		}
	}

	public function eliminar($id = ''){
		if($id != ''){
			$this->option = 'captura';
			$id = intval($id, 10);
			$Grupos = new Grupos();
			$this->grupo = $Grupos->find($id);
			$Ciclos = new Ciclos();
			$this->ciclo = $Ciclos->find($this->grupo->ciclos_id);
			if($this->grupo->id != ''){
				$ciclo_id = $this->grupo->ciclos_id;
				$Ciclos = new Ciclos();
				$this->ciclo = $Ciclos->find($ciclo_id);
				if($this->ciclo->abierto()){
					$sigCiclo = new Ciclos();
					$sigCiclo = $sigCiclo->find_first("numero='" . $this->ciclo->anterior() . "'");
					$agenda = new Agenda();
					if (!($agenda->eventoValido('PLN-GEN', $ciclo_id, $this->controlador, $this->accion, Session :: get_data('usr.login')) || $agenda->eventoValido('PLN-GEN-ESP', $ciclo_id, $this->controlador, $this->accion, Session :: get_data('usr.login')) || ($sigCiclo->id != '' && ($agenda->eventoValido('PLN-GEN', $sigCiclo->id, $this->controlador, $this->accion, Session :: get_data('usr.login')) || $agenda->eventoValido('PLN-GEN-ESP', $sigCiclo->id, $this->controlador, $this->accion, Session :: get_data('usr.login')))))) {
						$this->option = 'error';
						$this->error = ' La agenda no permite eliminar grupos.';
					}
				} else {
					$this->option = 'error';
					$this->error = ' El ciclo esta cerrado.';

				}

			}else{
				$this->option = 'error';
				$this->error = ' El grupo no existe.';

			}
		}else if($this->post('id') != ''){
			$this->option = '';
			$this->error = '';
			$Grupos = new Grupos();
			$grupo = $Grupos->find($this->post('id'));
			if($grupo->id != ''){
				$ciclo_id = $grupo->ciclos_id;
				$Ciclos = new Ciclos();
				$this->ciclo = $Ciclos->find($ciclo_id);
				if($this->ciclo->abierto()){
					$sigCiclo = new Ciclos();
					$sigCiclo = $sigCiclo->find_first("numero='" . $this->ciclo->anterior() . "'");
					$agenda = new Agenda();
					if ($agenda->eventoValido('PLN-GEN', $ciclo_id, $this->controlador, $this->accion, Session :: get_data('usr.login')) || $agenda->eventoValido('PLN-GEN-ESP', $ciclo_id, $this->controlador, $this->accion, Session :: get_data('usr.login')) || ($sigCiclo->id != '' && ($agenda->eventoValido('PLN-GEN', $sigCiclo->id, $this->controlador, $this->accion, Session :: get_data('usr.login')) || $agenda->eventoValido('PLN-GEN-ESP', $sigCiclo->id, $this->controlador, $this->accion, Session :: get_data('usr.login'))))) {

						// eliminando el grupo
						try{
							$Grupos->delete( $grupo->id );
							$this->option = 'exito';
							$historial=new Historial();
							$historial->ciclos_id= Session :: get_data('ciclo.id');
							$historial->usuario=Session :: get_data('usr.login');
							$historial->descripcion='Se elimino el grupo '.$grupo->verInfo().', '.$grupo->verOferta();
							$historial->controlador="grupos";
							$historial->accion="eliminar";
							$historial->saved_at=date("Y-m-d H:i:s");//2009-01-20 14:28:29
							$historial->save();
						}catch(dbException $e){
							$this->option = 'error';
							$this->error .= ' Error al intentar eliminar de la BD. ' .
									'Posiblemente existan datos vinculados al grupo (alumnos, asistencias, calificaciones, etc).';
						}
					}else{
						$this->option = 'error';
						$this->error = ' La agenda no permite eliminar grupos.';
					}
				} else {
					$this->option = 'error';
					$this->error = ' El ciclo esta cerrado.';

				}

			}else{
				$this->option = 'error';
				$this->error = ' El grupo no existe.';
			}
		}else{
			$this->option = 'error';
			$this->error = ' No se especific&oacute; el grupo a eliminar.';
		}
	}

	public function exportartodos(){
		require ('app/reportes/xls.grupoexportar.php');

		$ciclo_id = Session :: get_data('ciclo.id');

		ob_end_clean();
		ob_start();

		$grupos = new Grupos();
		$grupos = $grupos->todos();

		if(!file_exists('./logs/hgrupos/')){
			mkdir('./logs/hgrupos/');
		}
			
		foreach($grupos as $g){
			$reporte = new XLSGrupoexportar($g->id);
			$n = $reporte->getNombre();

			$f = fopen('./logs/hgrupos/'. $n, "w");
			$reporte->close();
			fwrite($f, ob_get_contents());
			fclose($f);
			ob_end_clean();
			ob_start();
		}

	}

	public function generar(){
		$this->error = '';
		$ciclos = new Ciclos();
		$ciclo = $ciclos->find(Session :: get_data('ciclo.id'));
		$grupos =  new Grupos();

		if($grupos->count("ciclos_id = '" . $ciclo->id . "'") == 0){
			$anterior = $ciclos->find_first("conditions: numero = '" . $ciclo->anterior() . "'");
			$this->grps = array();
			if($anterior->id != '' && $grupos->count("ciclos_id = '" . $anterior->id . "'") > 0){
				$grupos =  $grupos->find("conditions: ciclos_id = '" . $anterior->id . "'", 'order: turno, grado, letra');
				foreach($grupos as $grupo){
					$g = new Grupos();
					$g->ciclos_id = $ciclo->id;
					$g->grado = ($grupo->grado < 6 ? ($grupo->grado + 1) : 1);
					$g->letra = $grupo->letra;
					$g->turno = $grupo->turno;
					if(!$g->save()){
						$this->error .= 'No se pudo guardar en la BD el grupo ' . $g->verInfo() . '.';
					}else{
						foreach($grupo->encargados() as $encargado){
							$asignacion = new Asignar();
							$asignacion->usuarios_id = $encargado->usuarios_id;
							$asignacion->grupos_id = $g->id;
							if(!$asignacion->save()){
								$this->error .= 'No se pudo guardar en la BD la asignaci&oacute;n al grupo ' . $g->verInfo() . '.';
							}
						}
					}
					$this->grps[] = $g;
				}
			}else{
				$grupos = array();
				$grupos['M']['1'] = array('A', 'B', 'C', 'D', 'E');
				$grupos['M']['2'] = array('A', 'B', 'C', 'D', 'E');
				$grupos['M']['3'] = array('A', 'B', 'C', 'D', 'E');
				$grupos['M']['4'] = array('A', 'B', 'C', 'D', 'E');
				$grupos['M']['5'] = array('A', 'B', 'C', 'D', 'E');
				$grupos['M']['6'] = array('A', 'B', 'C', 'D', 'E');
				$grupos['V']['1'] = array('A', 'B', 'C', 'D', 'E');
				$grupos['V']['2'] = array('A', 'B', 'C', 'D', 'E');
				$grupos['V']['3'] = array('A', 'B', 'C', 'D', 'E');
				$grupos['V']['4'] = array('A', 'B', 'C', 'D', 'E');
				$grupos['V']['5'] = array('A', 'B', 'C', 'D', 'E');
				$grupos['V']['6'] = array('A', 'B', 'C', 'D', 'E');
				$grupos['N']['1'] = array('A', 'B');
				foreach($grupos as $turno => $grados){
					foreach($grados as $grado => $letras){
						foreach($letras as $letra){
							$g = new Grupos();
							$g->ciclos_id = $ciclo->id;
							$g->grado = $grado;
							$g->letra = $letra;
							$g->turno = $turno;
							$g->save();
							$this->grps[] = $g;
						}
					}
				}
			}
		}else{
			$this->error = 'No se puede generar. Ya existen grupos para el ciclo escolar ' . $ciclo->numero . '.';
		}

	}

	public function horario($id = ''){
		$grupo = new Grupos();
		$grupo->find($id);
		if($grupo->id != ''){
			if($grupo->asignado()){
				$this->grupo = $grupo;
				// ejecuta la consulta
				$Cursos = new Cursos();
				$cursos = $Cursos->find_all_by_sql(
					"SELECT " .
						"cursos.id, " .
						"cursos.grupos_id, " .
						"cursos.materias_id," .
						"cursos.profesores_id, " .
						"cursos.estado_id, ".
						"cursos.inicio,  " .
						"materias.nombre AS materia," .
						"materias.tipo AS materiaTipo," .
						"CONCAT(profesores.ap, ' ', profesores.am, ', ', profesores.nombre) AS profesor " .
					"FROM " .
						"cursos " .
						"INNER JOIN grupos ON cursos.grupos_id = grupos.id " .
						"INNER JOIN materias ON cursos.materias_id  = materias.id " .
						"INNER JOIN profesores ON cursos.profesores_id  = profesores.id ".
					"WHERE " .
						"grupos_id = '" . $grupo->id . "' " .
					"ORDER " .
						"BY cursos.inicio,grupos.turno, grupos.grado, grupos.letra, materias.nombre "
						);
						$nCursos = count($cursos);

						if($nCursos > 0){
							// acl
							$usr_login = Session :: get_data('usr.login');
							$this->acl = array();
							$acl = new gacl_extra();
							$acos_arr = array(
						'grupos' => array(
							'curso'
							)

							);
							$this->acl = $acl->acl_check_multiple($acos_arr, $usr_login);


							$dias=new Dias();
							$this->dias=$dias->find("id!='7' ORDER BY id");

							$cc=array();
							$this->inicio=2400;
							$this->fin=00;
							foreach($cursos as $curso){
								$cc[$curso->id]["c"]=$curso;

								$horarios=$curso->horarios();
								foreach($horarios as $ho){
									$fecha=substr($ho->inicio,0,2).substr($ho->inicio,3,2);
									if($fecha<$this->inicio){
										$this->inicio=$fecha;
									}

									$fecha=substr($ho->fin,0,2).substr($ho->fin,3,2);
									if($fecha>$this->fin){
										$this->fin=$fecha;
									}
								}
								$cc[$curso->id]["h"]=$horarios;

							}


							$this->inicio=substr($this->inicio,0,2);
							$this->fin=substr($this->fin,0,2);

							$this->option = 'vista';
							$this->nCursos = $nCursos;
							$this->cursos = $cc;
						}else{
							$this->option = 'alert';
							$this->error = 'No hay cursos asignados a este grupo.';
						}
			}else{
				$this->option = 'error';
				$this->error = 'No ha sido asignado al grupo.';
			}
		}else{
			$this->option = 'error';
			$this->error = 'El id de grupo no es v&aacute;lido';
		}
	}

	public function imprimir( $id = '' ){
		if( $id != '' ){
			$this->option = 'selector';
			$grupo = new Grupos();
			$grupo = $grupo->find($id);
			if( $grupo->id != '' ){
				if( $grupo->asignado() ){
					$this->alumnos = $grupo->alumnos();
					if(count($this->alumnos) > 0){
						$this->grupo = $grupo;
						$cs=$this->grupo->cursos();
						$this->cursos=array();
						foreach($cs as $c){
							if($c->aprobado())
							$this->cursos[]=$c;
						}
					}else{
						$this->option = 'alert';
						$this->error = 'No hay alumnos inscritos a este grupo.';
					}
				}else{
					$this->option = 'error';
					$this->error = 'El grupo est&aacute; fuera de su asignaci&oacute;n.';
				}
			}else{
				$this->option = 'error';
				$this->error = 'El id del grupo no es valido.';
			}
		}else{
			$grupo = new Grupos();
			$grupo = $grupo->find( $this->post('grupos_id') );
			$alumnos = $this->post('alumnos');

			if( $grupo->id != '' ){
				if( $grupo->asignado() ){
					if( count($alumnos) > 0){
						if( count( array_diff($alumnos, $grupo->alumnos_ids()) ) == 0 ){
							switch( $this->post('reporte') ){
								case 'boletas':
									require('app/reportes/xls.boleta.php');
									ob_end_clean();
									$this->set_response("view");
									$reporte = new XLSBoleta( $grupo->id, $alumnos );
									$reporte->generar();
									break;
								case 'finales':
									require('app/reportes/xls.finales.php');
									ob_end_clean();
									$this->set_response("view");
									$reporte = new XLSFinales( $grupo->id, $alumnos );
									$reporte->generar();
									break;
								case 'resumen':
									require('app/reportes/xls.resumen.php');
									ob_end_clean();
									$this->set_response("view");
									$reporte = new XLSResumen( $grupo->id );
									$reporte->generar();
									break;

								case 'alu':
									require('app/reportes/xls.alumnospromedio.php');
									ob_end_clean();
									$this->set_response("view");
									$reporte = new XLSalumnospromedio( $grupo->id );
									$reporte->generar();
									break;

								case 'lista':
									$curso_id=$this->post('cursos_select');
									$grupo_id=$this->post('grupos_id');
									$curso=new Cursos();
									if($curso->exists("id=".$curso_id." AND grupos_id=".$grupo_id)){
										//$curso=$curso->find("id=".$curso_id." AND grupos_id=".$grupo_id);
										require('app/reportes/xls.lista.php');
										ob_end_clean();
										$this->set_response("view");
										$reporte = new XLSLista( $curso_id );
										$reporte->generar();
									}else{
										$this->option = 'error';
										$this->error = 'El curso no pertenece al grupo.';

									}

									break;
									
								
								case 'evaluaciones':
									$curso_id=$this->post('cursos_select');
									$grupo_id=$this->post('grupos_id');
									$curso=new Cursos();
									if($curso->exists("id=".$curso_id." AND grupos_id=".$grupo_id)){
										//$curso=$curso->find("id=".$curso_id." AND grupos_id=".$grupo_id);
										require('app/reportes/xls.evaluaciones.php');
										ob_end_clean();
										$this->set_response("view");
										$reporte = new XLSEvaluaciones( $curso_id );
										$reporte->generar();
									}else{
										$this->option = 'error';
										$this->error = 'El curso no pertenece al grupo.';

									}

									break;	

								case 'inscritos':
									$cursos=$this->post('cursos_chk');
									$grupo_id=$this->post('grupos_id');
									$curso=new Cursos();
									if(is_array($cursos)){
										//$curso=$curso->find("id=".$curso_id." AND grupos_id=".$grupo_id);
										require('app/reportes/xls.inscritos.php');
										ob_end_clean();
										$this->set_response("view");
										$reporte = new XLSInscritos($cursos);
										$reporte->generar();
									}else{
										$this->option = 'error';
										$this->error = 'Ha ocurrido un error.';

									}

									break;

								case 'ast':
									ini_set ('max_execution_time', '0' );
									$curso_id=$this->post('cursos_select');
									$grupo_id=$this->post('grupos_id');
									$curso=new Cursos();
									if($curso->exists("id=".$curso_id." AND grupos_id=".$grupo_id)){
										//$curso=$curso->find("id=".$curso_id." AND grupos_id=".$grupo_id);
										require('app/reportes/xls.asistencias.php');
										ob_end_clean();
										$this->set_response("view");
										$reporte = new XLSAsistencias( $curso_id );
										$reporte->generar();
									}else{
										$this->option = 'error';
										$this->error = 'El curso no pertenece al grupo.';

									}

									break;


								case 'cal':
									$cursos=$this->post('cursos_chk');
									$grupo_id=$this->post('grupos_id');
									$curso=new Cursos();
									if(is_array($cursos)){
										//$curso=$curso->find("id=".$curso_id." AND grupos_id=".$grupo_id);
										require('app/reportes/xls.calificaciones.php');
										ob_end_clean();
										$this->set_response("view");
										$reporte = new XLSCalificaciones($cursos);
										$reporte->generar();
									}else{
										$this->option = 'error';
										$this->error = 'Ha ocurrido un error.';

									}

									break;


								case "reg":
									require('app/reportes/xls.registro.php');
									ob_end_clean();
									$this->set_response("view");
									$reporte = new XLSResumen( $grupo->id );
									$reporte->generar();
									break;

								default:
									$this->option = 'error';
									$this->error = 'El tipo de reporte seleccionado no es v&aacute;lido.';
									break;

							}
						}else{
							$this->option = 'error';
							$this->error = 'El alumno est&aacute; fuera de su asignaci&oacute;n.';
							exit;
						}
					}else{
						$this->option = 'alert';
						$this->error = 'No se seleccionaron alumnos.';
					}
				}else{
					$this->option = 'error';
					$this->error = 'El grupo est&aacute; fuera de su asignaci&oacute;n.';
				}
			}else{
				$this->option = 'error';
				$this->error = 'El id del grupo no es valido.';
			}

		}
	}

	public function index($pag = ''){
		$Grupos = new Grupos();
		$ciclo_id = Session :: get_data('ciclo.id');

		$controlador = $this->controlador;
		$accion = $this->accion;
		$path = $this->path = KUMBIA_PATH;

		$this->ofertas=new Oferta();
		$this->ofertas=$this->ofertas->find();

		$usr_login = Session :: get_data('usr.login');

		// acl
		$this->acl = array();
		$acl = new gacl_extra();
		$acos_arr = array(
			'grupos' => array(
				'agregar', 'asignar', 'editar', 'eliminar', 'ver','exportaramonestaciones','amonestaciones'
				),
			'cursos' => array(
			'grupo'
			),
			);
			$this->acl = $acl->acl_check_multiple($acos_arr, $usr_login);

			// busqueda
			$b = new Busqueda($controlador, $accion);
			$b->campos();

			// genera las condiciones
			$c = $b->condicion(array('oferta_id'));
			$c .= ($c == '' ? '' : 'AND ') . "ciclos_id = '" . $ciclo_id . "'";
			// verifica si el usuario solo puede revisar grupos por asignacion
			$asignaciones = $Grupos->asignados();
			if(count($asignaciones)>0){
				if(!in_array('ALL', $asignaciones)){
					$asg = '';
					foreach($asignaciones as $grupos_id){
						$asg .= ($asg == '' ? '' : 'OR ') . " grupos.id = '" . $grupos_id . "' ";
					}
					$c .= ($c == '' ? '' : ' AND ') . '(' . $asg . ')';
				}

				$this->busqueda = $b;
				// cuenta todos los registros
				$this->registros = $Grupos->count(($c == '' ? '' : $c));
				if($this->registros==0){
					$this->option="error";
					$this->error="No cuenta con ningun grupo asignado.";
				}else{

					// paginacion
					$paginador = new Paginador($controlador, $accion);
					if($pag != ''){
						$paginador->guardarPagina($pag);
					}
					$paginador->estableceRegistros($this->registros);
					$paginador->generar();
					$this->paginador = $paginador;

					// ejecuta la consulta
					$this->grupos = $Grupos->find(
							'conditions: ' . ($c == "" ? "1" : $c),
							'order: turno, grado, letra',
							'limit: ' . ($paginador->pagina() * $paginador->rpp()) . ', '
							. $paginador->rpp()
							);

							$this->option="vista";
				}
			}else{
				$this->option="error";
				$this->error="No cuenta con ningun grupo asignado.";
			}
			$Ciclos = new Ciclos();
			$this->ciclo = $Ciclos->find($ciclo_id);
			$Ciclos = $Ciclos->find("columns: id, numero", "conditions: abierto = '1' ", "order: numero DESC");
			$this->ciclos = $Ciclos;

	}

	public function ver($id = ''){
		$grupo = new Grupos();
		$grupo = $grupo->find($id);
		if($grupo->id != ''){
			if($grupo->asignado()){

				$ciclo   = $grupo->ciclo();
				$cursos  = $grupo->cursos();
				$alumnos = $grupo->alumnos();
				$this->ciclo     =   $ciclo;
				$this->grupo     =   $grupo;
				if(count($alumnos) > 0){
					$this->cursos    =   $cursos;
					$this->option    =   'vista';
					$this->alumnos   =   $alumnos;
					$this->asistencias = $grupo->asistencias($cursos);
					$this->calificaciones = $grupo->calificaciones();

					$usr_login = Session :: get_data('usr.login');

					$this->acl_grupos = array();
					$acl = new gacl_extra();
					$acos_arr=array(
					'grupos' => array(
						'imprimir', 'horario', 'curso'
						)
						);

						$this->acl_grupos=$acl->acl_check_multiple($acos_arr,$usr_login);
						$this->acl_grupos=$this->acl_grupos['grupos'];
				}else{
					$this->option = 'alert';
					$this->alert = ' No hay alumnos inscritos en el grupo.';
				}
			}else{
				$this->option = 'error';
				$this->error = ' No esta asignado al grupo.';
			}
		}else{
			$this->option = 'error';
			$this->error = ' No se especific&oacute; un id de grupo v&aacute;lido.';
		}
	}

	public function exportaramonestaciones(){
		$this->set_response('view');
		require ('app/reportes/xls.gruposamonestaciones.php');
		$ciclo_id = Session :: get_data('ciclo.id');
		$reporte = new XLSGruposamonestaciones($ciclo_id);
		$reporte->generar();
	}

	public function amonestaciones($id = '', $pag = ''){
		$ciclo_id = Session :: get_data('ciclo.id');
		$controlador = $this->controlador;
		$accion = $this->accion;
		$this->id = $id;
		$estado = new Aestado();
		$estado = $estado->pornombre('Aprobada');
		$b = new Busqueda($controlador, $accion);
		$b->establecerCondicion('fecha', "fecha = '" . Utils::convierteFechaMySql($b->campo('fecha')) . "' ");
		$grupos = new Grupos();
		$this->grupo = $grupos->find($id);
		$oferta = new Oferta();
		$this->oferta= $oferta->find($this->grupo->oferta_id);
		$this->reglamento = new Reglamentos();
		$this->articulo = new Articulo();
		$this->categoria = new Acategoria();

		$b->campos();
		// genera las condiciones
		$c = $b->condicion();
		$amonestaciones = new Amonestacion();

		$this->registros=$amonestaciones->count_by_sql(
		"SELECT count(amonestados.id) FROM
								amonestados
								INNER JOIN amonestacion ON amonestados.amonestacion_id = amonestacion.id
								INNER JOIN alumnos ON amonestados.alumnos_id = alumnos.id
								INNER JOIN alumnosgrupo on alumnos.id = alumnosgrupo.alumnos_id
								INNER JOIN grupos on alumnosgrupo.grupos_id = grupos.id 
								WHERE ".
		($c == "" ? "amonestacion.ciclos_id='".$ciclo_id."'" : "amonestacion.ciclos_id='".$ciclo_id."' AND ".$c).' AND grupos.id = '.$id.' AND amonestacion.aestado_id = '.$estado->id);
		// paginacion
		$paginador = new Paginador($controlador, $accion);
		if($pag != ''){
			$paginador->guardarPagina($pag);
		}
		$paginador->estableceRegistros($this->registros);
		$paginador->establecePath($controlador . '/' . $accion . '/' . $id );
		$paginador->generar();


		$this->busqueda = $b;

		$this->amonestaciones=$amonestaciones->find_all_by_sql(
							"SELECT amonestacion.*, alumnos.id as alumno_id FROM
								amonestados
								INNER JOIN amonestacion ON amonestados.amonestacion_id = amonestacion.id
								INNER JOIN alumnos ON amonestados.alumnos_id = alumnos.id
								INNER JOIN alumnosgrupo on alumnos.id = alumnosgrupo.alumnos_id
								INNER JOIN grupos on alumnosgrupo.grupos_id = grupos.id
								WHERE ".
		($c == "" ? "amonestacion.ciclos_id='".$ciclo_id."'" : "amonestacion.ciclos_id='".$ciclo_id."' AND ".$c).' AND grupos.id = '.$id.' '.'AND amonestacion.aestado_id = '.$estado->id." ORDER BY fecha DESC ".
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

							// acl
							$usr_login = Session :: get_data('usr.login');
							$this->acl = array ();
							$acl = new gacl_extra();
							$acos_arr = array (
								'amonestaciones' => array (
									'ver'
									)
									);
									$this->acl = $acl->acl_check_multiple($acos_arr, $usr_login);
	}

}
?>