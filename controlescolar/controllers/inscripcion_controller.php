<?php
/*
 * Created on 11/12/2008
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 Kumbia :: import('app.componentes.*');
 Kumbia :: import('lib.kumbia.utils');

 class InscripcionController extends ApplicationController {
	public $template = "system";

	public function agregar($id){
		$this->exito='';
		$this->error='';
			if(isset($id) && trim($id)!=''){
			$this->curso=new Cursos();
			$this->curso=$this->curso->find_first($id);
			if($this->curso->id!=''){
			$this->grupo=$this->curso->grupo();
			$this->ciclo=$this->grupo->ciclo();
			if($this->ciclo->abierto()){
			if($this->curso->aprobado()){
			$agenda=new Agenda();
					if($agenda->eventoValido('ALU-CRS', $this->ciclo->id,$this->controlador,$this->accion,Session :: get_data('usr.login'))
							||
					   $agenda->eventoValido('ALU-CRS-ESP',$this->ciclo->id,$this->controlador,$this->accion,Session :: get_data('usr.login'))
						){
						$this->articulos=new Articulos();
						$this->articulos=$this->articulos->find();
						$this->option = 'captura';
						}else{
							$this->option = 'error';
							$this->error = ' La agenda no permite la inscripcion de alumnos.';
						}
			}else{
					$this->option = 'error';
					$this->error = ' El curso no esta aprobado aun.';
			}
			} else {
					$this->option = 'error';
					$this->error = ' El ciclo esta cerrado.';
			}
			} else {
					$this->option = 'error';
					$this->error = ' El curso no existe.';
			}

		}elseif($this->post("alumnos") && $this->post('articulos')){

			$curso=$this->post('curso_id');

			if(!isset($curso)) $curso="-1";

				$this->option = 'exito';

				$c=new Cursos();

				if($c->exists($curso)){
				$this->curso=$curso=$c->find_first($curso);
				$alumnos_ids=$this->post('alumnos');
				$alumnos_ids = array_unique($alumnos_ids);
				$this->materia=$materia=$curso->materia();
				$this->articulos=$this->post('articulos');
				$this->grupo=$grupo=$curso->grupo();
				$this->ciclo=$this->grupo->ciclo();
				if($this->ciclo->abierto()){
				if($this->curso->aprobado()){
				$agenda=new Agenda();
				if($agenda->eventoValido('ALU-CRS', $this->ciclo->id,$this->controlador,$this->accion,Session :: get_data('usr.login'))
							||
					   $agenda->eventoValido('ALU-CRS-ESP',$this->ciclo->id,$this->controlador,$this->accion,Session :: get_data('usr.login'))
				){

				if( is_array($alumnos_ids) && is_array($this->articulos) && count($alumnos_ids) == count($this->articulos) ){

					foreach($alumnos_ids as $llave=>$id){
						$alumno = new Alumnos();
						$alumno = $alumno->find_first("conditions: codigo = '" . $id . "'");
						if($alumno->id != ''){
							$db = db::raw_connect();
							//revisando que este inscrito en algun grupo
							$sql="SELECT grupos.*,alumnosgrupo.alumnos_id FROM " .
									" grupos " .
									" INNER JOIN alumnosgrupo ON alumnosgrupo.grupos_id=grupos.id " .
									" WHERE alumnosgrupo.alumnos_id=".$alumno->id." AND grupos.ciclos_id=".$grupo->ciclos_id;
							$enGrupo=$db->in_query($sql);
							if(count($enGrupo)>0){

							if($enGrupo[0]['grado']==$grupo->grado OR $enGrupo[0]['grado']-1==$this->grupo->grado){ //revisar que el curso sea del mismo semestre o uno anterior

							//revisar que no este inscrito en otro curso de la misma materia en el ciclo actual
							$sql='SELECT materias.nombre,' .
									'grupos.grado,' .
									'grupos.letra,' .
									'grupos.turno' .
									' FROM ' .
									' cursos ' .
									' Inner Join materias ON materias.id = cursos.materias_id ' .
									' Inner Join grupos ON grupos.id=cursos.grupos_id ' .
									' Inner Join alumnoscursos ON alumnoscursos.cursos_id=cursos.id ' .
									' Inner Join alumnos ON alumnos.id=alumnoscursos.alumnos_id ' .
									' WHERE ' .
									" materias.id='".$materia->id."' AND grupos.ciclos_id='".$grupo->ciclos_id."' AND alumnos.codigo='".$alumno->codigo."'";

							$registros=$db->in_query($sql);
							if( count($registros) == 0){

							//revisar que no haya cruce de horarios con los otros cursos del alumno
							$resp='';
							//$resp=$alumno->slotCursoDisponible($curso->id);
							$resp=1;
							if($resp==1){
								$alumnosCursos=new Alumnoscursos();
								//$alumnosCursos->cursos_id=$curso->id;
								//$alumnosCursos->alumnos_id=$alumno->id;
								//$alumnosCursos->save();
								$alumnosCursos->alta($alumno->id,$curso->id);
								$articulo=$this->articulos[$llave];
								$art=new Articulos();
								if($articulo!=''){
								$art=$art->find($articulo);
								if($art->id!=''){
									$alumnoArticulo = new Alumnosconarticulo();
									$alumnoArticulo->alumnoscursos_id=$alumnosCursos->id;
									$alumnoArticulo->articulos_id=$art->id;
									$alumnoArticulo->save();
									$at="con <strong>".$art->descripcion."</strong>";
									$this->exito .= '<br /> Se inscribio el alumno <strong>' . $alumno->codigo.'</strong> '.$at.'.';
								}else{
									$this->exito .= '<br /> Se inscribio el alumno <strong>' . $alumno->codigo.'</strong>.';
								}
								}else{
									$this->exito .= '<br /> Se inscribio el alumno <strong>' . $alumno->codigo.'</strong>.';
								}


								//$curso->sincronizar($alumno);



							}else{
								//$this->option = 'error';
								$this->error .= '<br />' . $alumno->codigo .' .'.$resp.'.';
							}
							}else{
								//$this->option = 'error';
								$this->error .= '<br />' . $alumno->codigo .' . Ya esta registrado en otro curso similar.';
							}

							}else{
								$this->error .= '<br /> ' . $alumno->codigo .'. Semestre actual del alumno es '.$enGrupo[0]['grado'].' .';
							}
							}else{
								$this->error .= '<br /> ' . $alumno->codigo .'. No esta inscrito a ningun grupo .';
							}
						}
					}
					$historial=new Historial();
								$historial->ciclos_id= Session :: get_data('ciclo.id');
								$historial->usuario=Session :: get_data('usr.login');
								$c=$this->curso->verMateriaNombre().' '.$this->grupo->grado.$this->grupo->letra.$this->grupo->turno.' '.$this->grupo->verOferta();
								$historial->descripcion='Se inscribieron alumnos al curso: '.$c.'.';
								$historial->controlador="inscripcion";
								$historial->accion="agregar";
								$historial->saved_at=date("Y-m-d H:i:s");//2009-01-20 14:28:29
								$historial->save();


				}else{
					$this->option = 'error';
					$this->error .= ' Los datos no son validos.';
				}
				}else{
							$this->option = 'error';
							$this->error = ' La agenda no permite la inscripcion de alumnos.';
						}
				}else{
					$this->option = 'error';
					$this->error = ' El curso no esta aprobado aun.';
					}
				} else {
					$this->option = 'error';
					$this->error = ' El ciclo esta cerrado.';
				}
				}else{
					$this->option = 'error';
					$this->error .= ' No existe el curso.';
				}

		}elseif($this->post('alumnos_id')!=''){
			$this->option="confirmar";
			$this->alumnos=$this->post('alumnos_id');
			$this->curso_id=$this->post('curso_id');
			$this->articulos=$this->post('articulo');
			if(is_array($this->alumnos))
				$this->alumnos=array_unique($this->alumnos);
				else
				$this->alumnos=array();
		}else{
				$this->option = 'error';
				$this->error = ' No tiene permiso para ver la pagina.';
		}

	}

	public function articulo($alumnocurso_id=''){
		$this->exito='';
		$this->error='';
			if(isset($alumnocurso_id) && trim($alumnocurso_id)!=''){
			$alumnocurso=new Alumnoscursos();
			$this->alumnocurso=$alumnocurso=$alumnocurso->find($alumnocurso_id);
			if($alumnocurso->id!=''){
			$this->curso=new Cursos();
			$this->curso=$this->curso->find_first($alumnocurso->cursos_id);

			$this->grupo=$this->curso->grupo();
			$this->ciclo=$this->grupo->ciclo();
			if($this->ciclo->abierto()){
			$agenda=new Agenda();
			if($agenda->eventoValido('ALU-CRS', $this->ciclo->id,$this->controlador,$this->accion,Session :: get_data('usr.login'))
							||
			   $agenda->eventoValido('ALU-CRS-ESP',$this->ciclo->id,$this->controlador,$this->accion,Session :: get_data('usr.login'))
			){


			if($this->curso->aprobado()){
			$this->articulos=new Articulos();
			$this->articulos=$this->articulos->find();
			$this->articulo_actual='-1';
			$alumnoArticulo = new Alumnosconarticulo();
			$alumnoArticulo=$alumnoArticulo->find_first("alumnoscursos_id=".$alumnocurso->id);
			if($alumnoArticulo->id!='')
				$this->articulo_actual=$alumnoArticulo->articulos_id;


			$this->option = 'captura';
			}else{
					$this->option = 'error';
					$this->error = ' El curso no esta aprobado aun.';
			}
			}else{
							$this->option = 'error';
							$this->error = ' La agenda no permite el cambio de articulo.';
			}
			} else {
					$this->option = 'error';
					$this->error = ' El ciclo esta cerrado.';
			}
			}else{
					$this->option = 'error';
					$this->error .= ' No existe la inscripcion.';

			}
			}elseif($this->post('alumnocurso_id')!=''){
				$this->option="confirmar";
				$alumnocurso_id=$this->post('alumnocurso_id');
				$alumnosCursos=new Alumnoscursos();
				$this->alumnosCursos=$alumnosCursos=$alumnosCursos->find($alumnocurso_id);
				if($alumnosCursos->id!=''){
					$this->alumno=new Alumnos();
					$this->alumno=$this->alumno->find($alumnosCursos->alumnos_id);

					$this->articulo=new Articulos();
					$this->articulo=$this->articulo->find($this->post('articulo'));
					$this->art=$this->articulo->descripcion;
					if($this->articulo->id=='')
					$this->art="Regular";


				}else{
					$this->option = 'error';
					$this->error .= ' No existe la inscripcion.';

				}
			}elseif($this->post('alumnoscursos_id')!='' && $this->post('articulo_id')!=''){
				$alumnocurso_id=$this->post('alumnoscursos_id');


				if(isset($alumnocurso_id) && trim($alumnocurso_id)!=''){
				$alumnosCursos=new Alumnoscursos();
				$alumnosCursos=$alumnosCursos->find($alumnocurso_id);
				if($alumnosCursos->id!=''){
					$curso=new Cursos();
					$curso=$curso->find($alumnosCursos->cursos_id);
					$agenda=new Agenda();
					$this->grupo=$this->curso->grupo();
					$this->ciclo=$this->grupo->ciclo();
					if($this->ciclo->abierto()){
					if($agenda->eventoValido('ALU-CRS', $this->ciclo->id,$this->controlador,$this->accion,Session :: get_data('usr.login'))
							||
			   			$agenda->eventoValido('ALU-CRS-ESP',$this->ciclo->id,$this->controlador,$this->accion,Session :: get_data('usr.login'))
						){
					if($curso->id!=''){
					if($curso->aprobado()){

					$articulo=trim($this->post('articulo_id'));

					if($articulo!=''){
						if($articulo=='-1'){
							$alumnoArticulo = new Alumnosconarticulo();
							$alumnoArticulo=$alumnoArticulo->find_first("alumnoscursos_id=".$alumnosCursos->id);
							if($alumnoArticulo->id!='')
							$alumnoArticulo->delete();

							$this->option = 'exito';
							$this->exito = ' El cambio de articulo se llevo a cabo con exito.';
						}else{
						$art=new Articulos();
						$art=$art->find($articulo);
						if($art->id!=''){
							$alumnoArticulo = new Alumnosconarticulo();
							$alumnoArticulo=$alumnoArticulo->find_first("alumnoscursos_id=".$alumnosCursos->id);
							if($alumnoArticulo->id!=''){
							$alumnoArticulo->alumnoscursos_id=$alumnosCursos->id;
							$alumnoArticulo->articulos_id=$art->id;
							$alumnoArticulo->update();
							}else{
							$alumnoArticulo = new Alumnosconarticulo();
							$alumnoArticulo->alumnoscursos_id=$alumnosCursos->id;
							$alumnoArticulo->articulos_id=$art->id;
							$alumnoArticulo->save();
							}
								$this->option = 'exito';
								$this->exito = ' El cambio de articulo se llevo a cabo con exito.';
							}else{
								$this->option = 'error';
								$this->error .= ' El articulo no existe.';

							}
						}
					}else{

						$this->option = 'error';
						$this->error .= ' No tiene permiso para ver la pagina.';
					}
					}else{
					$this->option = 'error';
					$this->error = ' El curso no esta aprobado aun.';
					}
					}else{
					$this->option = 'error';
					$this->error = ' El curso no existe.';
					}
					}else{
							$this->option = 'error';
							$this->error = ' La agenda no permite el cambio de articulo.';
					}
					} else {
					$this->option = 'error';
					$this->error = ' El ciclo esta cerrado.';
					}
					}else{
					$this->option = 'error';
					$this->error .= ' No existe la inscripcion.';

					}

				}else{
					$this->option = 'error';
					$this->error .= ' No tiene permiso para ver la pagina.';
				}
			}


	}

	public function confirmar(){
		$this->alumnos=$alumnos=$this->post('alumnos');
		$cursos_id=$this->post('cursos_id');
		if($alumnos && $cursos_id!=''){
		$curso=new Cursos();
			$this->curso=$curso=$curso->find($cursos_id);
			if( $curso->id != '' ){
			if($curso->aprobado()){
			$this->grupo=$this->curso->grupo();
			$this->ciclo=$this->grupo->ciclo();
			$this->option = 'confirm';

			$this->confirm = 'Se dispone a eliminar calificaciones y asistencias del curso '.$curso->verMateriaNombre().' de los siguientes alumnos. <br />';
			$this->alumnos_confirm='';
			foreach($alumnos as $alumno){
			$al=new Alumnos();
			$al=$al->find_first($alumno);
				$this->alumnos_confirm.=$al->codigo.' . '.$al->nombre('reversa').'<br/>';
			}
			}else{
					$this->option = 'error';
					$this->error = ' El curso no esta aprobado aun.';
			}

			}else{
			$this->option = 'error';
				$this->error = 'El id del curso no es valido.';

			}
		}else{
			$this->option = 'error';
				$this->error = 'No tiene permiso para ver la pagina.';
		}
	}


	public function eliminar($id){
			$alumnos=$this->post('alumnos');
			$cursos_id=$this->post('cursos_id');
			if($alumnos && $cursos_id!=''){
			$curso=new Cursos();
			$this->curso=$curso=$curso->find($cursos_id);
			if( $curso->id != '' ){
			$this->grupo=$this->curso->grupo();
			$this->ciclo=$this->grupo->ciclo();
			if($this->ciclo->abierto()){
			$agenda=new Agenda();
			if($agenda->eventoValido('ALU-CRS', $this->ciclo->id,$this->controlador,$this->accion,Session :: get_data('usr.login'))
							||
				$agenda->eventoValido('ALU-CRS-ESP',$this->ciclo->id,$this->controlador,$this->accion,Session :: get_data('usr.login'))
			){
			if($curso->aprobado()){
			$this->option='exito';
			$this->exito='';

			foreach($alumnos as $alumno){
				$alumnosCursos=new Alumnoscursos();
				if($alumnosCursos->exists("alumnos_id=".$alumno." AND cursos_id=".$cursos_id)){
				$alumnosCursos=$alumnosCursos->find_first("alumnos_id=".$alumno." AND cursos_id=".$cursos_id);


				$asistencias=new Asistencias();
				$asistencias->delete("cursos_id=".$cursos_id." AND alumnos_id=".$alumno);

				$calificaciones=new Calificaciones();
				$calificaciones->delete("cursos_id=".$cursos_id." AND alumnos_id=".$alumno);

				$parciales=new Calificacionesparciales ();
				$parciales->delete("cursos_id=".$cursos_id." AND alumnos_id=".$alumno);

				$faltas=new Faltas();
				$faltas->delete('alumnoscursos_id='.$alumnosCursos->id);

				$Alumnosconarticulo=new Alumnosconarticulo();
				$Alumnosconarticulo->delete('alumnoscursos_id='.$alumnosCursos->id);

				$alumnosCursos->delete();

				$al=new Alumnos();
				$al=$al->find_first($alumno);
				$this->exito.='<br/>Se dio de baja al alumno '.$al->codigo.' . '.$al->nombre('reversa');
				}
			}

			$historial=new Historial();
			$historial->ciclos_id= Session :: get_data('ciclo.id');
			$historial->usuario=Session :: get_data('usr.login');
			$c=$this->curso->verMateriaNombre().' '.$this->grupo->grado.$this->grupo->letra.$this->grupo->turno.' '.$this->grupo->verOferta();
			$historial->descripcion='Se dieron de baja alumnos del curso: '.$c.'.';
			$historial->controlador="inscripcion";
			$historial->accion="eliminar";
			$historial->saved_at=date("Y-m-d H:i:s");//2009-01-20 14:28:29
			$historial->save();
			}else{
					$this->option = 'error';
					$this->error = ' El curso no esta aprobado aun.';
			}
			}else{
							$this->option = 'error';
							$this->error = ' La agenda no permite el cambio de articulo.';
					}
			} else {
					$this->option = 'error';
					$this->error = ' El ciclo esta cerrado.';
			}
			}else{
				$this->option = 'error';
				$this->error = 'El id del curso no es valido.';
			}
			}else{
			if(isset($id) && trim($id)!=''){
			$curso=new Cursos();
			$this->curso=$curso=$curso->find($id);

			if( $curso->id != '' ){
			$this->grupo=$this->curso->grupo();
			$this->ciclo=$this->grupo->ciclo();
			if($this->ciclo->abierto()){
			$agenda=new Agenda();
			if($agenda->eventoValido('ALU-CRS', $this->ciclo->id,$this->controlador,$this->accion,Session :: get_data('usr.login'))
							||
				$agenda->eventoValido('ALU-CRS-ESP',$this->ciclo->id,$this->controlador,$this->accion,Session :: get_data('usr.login'))
			){

			if($curso->aprobado()){

				//if( $grupo->asignado() ){
				$alumnosCursos=new Alumnoscursos();
				$alumnosCursos=$alumnosCursos->find('cursos_id='.$curso->id);
				$cond='';
				foreach($alumnosCursos as $ac){
					$cond.=" id=".$ac->alumnos_id." OR ";
				}
				$cond=substr($cond,0,strlen($cond)-3);
				$this->alumnos=new Alumnos();
				$this->alumnos=$this->alumnos->find($cond);
				$this->option='captura';
						if( count($this->alumnos) == 0){

						$this->option = 'error';
						$this->error = 'No hay alumnos inscritos al curso.';
					}

			}else{
					$this->option = 'error';
					$this->error = ' El curso no esta aprobado aun.';
			}
			}else{
							$this->option = 'error';
							$this->error = ' La agenda no permite el cambio de articulo.';
			}
			} else {
					$this->option = 'error';
					$this->error = ' El ciclo esta cerrado.';
			}
			}else{
				$this->option = 'error';
				$this->error = 'El id del curso no es valido.';
			}
			}else{
				$this->option = 'error';
				$this->error = 'No tiene permiso para ver la pagina';
			}
			}

	}

	public function index(){
		$this->redirect('cursos/', 0);
	}

	public function inscribir(){
			$grupos=new Grupos();
			$grupos=$grupos->find("ciclos_id='3' ORDER BY turno,grado,letra");
			$this->grupos=$grupos;
	}


 }
?>
