<?php
Kumbia :: import('lib.kumbia.utils');

class Alumnos5 extends ActiveRecord{
	protected $grado;
	protected $grupo;
	protected $grupos_id;
	protected $letra;
	protected $turno;
	protected $oferta_id;
	protected $situacion;
	public $tipo_entidad;
	public $curso_articulo;
	public $tarjeta;
	protected $periodosalumnos_id;
	protected $bloquesalumnos_id;

	public function admision(){
		if($this->admision != ''){
			$f = str_replace('-', '/', Utils :: fecha_convertir($this->admision));
			if(strcmp($f, '00/00/0000') == 0){
				return '';
			}else{
				return $f;
			}
		}
		return '-';
	}

	public function asignado(){
		$ciclo_id = Session :: get_data('ciclo.id');
		$alugrp = new Alumnosgrupo();
		//$alugrp = $alugrp->find_first("conditions: alumnos_id = '" . $this->id . "' ");
		$alugrp = $alugrp->find_by_sql(
					" SELECT alumnosgrupo.* " .
					" FROM " .
					" alumnosgrupo " .
					" INNER JOIN grupos ON alumnosgrupo.grupos_id= grupos.id " .
					" WHERE grupos.ciclos_id='".$ciclo_id."' AND alumnosgrupo.alumnos_id='" . $this->id . "' "
					);


		if($alugrp['id'] != ''){
			$grupo = new Grupos();
			$asignados = $grupo->asignados();

			if( in_array($alugrp['grupos_id'], $asignados) ||
			    in_array('ALL', $asignados) )
			{
				return true;
			}
		}

		return false;
	}

	public function bachillerato(){
		$ciclo_id = Session :: get_data('ciclo.id');
		$g=$this->obtenerGrupoPorCiclo($ciclo_id);
		if($g->id!="")
		return $g->oferta_id;
		else
		return -1;
	}

	public function calificacion($curso_id, $oportunidad){
		$calificacion = new Calificaciones();

		$calificacion->find_by_sql(
			"SELECT " .
				"calificaciones.id, " .
				"calificaciones.valor " .
			"FROM " .
				"calificaciones Inner Join oportunidades " .
				"ON calificaciones.oportunidades_id = oportunidades.id " .
			"WHERE " .
				"cursos_id = '" . $curso_id . "' " .
				"AND alumnos_id = '" . $this->id . "' " .
				"AND oportunidades.clave  = '" . $oportunidad . "' "
		);

		return $calificacion;
	}

	public function calificaciones($curso_id){
		$calificaciones = new Calificaciones();

		$calificaciones = $calificaciones->find_all_by_sql(
			"SELECT " .
				"calificaciones.id, " .
				"calificaciones.valor, " .
				"calificaciones.alumnos_id, " .
				"calificaciones.oportunidades_id, " .
				"calificaciones.cursos_id, " .
				"oportunidades.clave AS clave " .
			"FROM " .
				"calificaciones Inner Join oportunidades " .
				"ON calificaciones.oportunidades_id = oportunidades.id " .
			"WHERE " .
				"cursos_id = '" . $curso_id . "' " .
				"AND alumnos_id = '" . $this->id . "' "
		);

		$cals = array();
		foreach($calificaciones as $cal){
			$cals[$cal->clave] = $cal;
		}

		return $cals;
	}

	function cursos($ciclo_id){
			$cursos=new Cursos();
			$cursos=$cursos->find_all_by_sql(
					"SELECT cursos.*,materias.id AS materia_id,materias.nombre AS materia, materias.semestre AS materia_semestre, materias.tipo AS materiaTipo
							FROM
							alumnoscursos
							INNER JOIN cursos ON alumnoscursos.cursos_id=cursos.id
							INNER JOIN materias ON cursos.materias_id=materias.id
							INNER JOIN grupos ON grupos.id=cursos.grupos_id
							WHERE
							alumnoscursos.alumnos_id='".$this->id."' AND grupos.ciclos_id='".$ciclo_id."'"
			);
			return $cursos;
	}


	public function fnacimiento(){
		if($this->fnacimiento != ''){
			$f = str_replace('-', '/', Utils :: fecha_convertir($this->fnacimiento));
			if(strcmp($f, '00/00/0000') == 0){
				return '';
			}else{
				return $f;
			}
		}
		return '-';
	}

	public function nombre($modo = 'normal'){
		$n = '';
		if($modo = 'reversa'){
			$n = $this->ap . ' ' . $this-> am . ', ' . $this->nombre;
		}else{
			$n = $this->nombre . ' ' . $this->ap . ' ' . $this-> am;
		}
		return $n;
	}

	public function obtenerGrupo(){

		$ciclo_activo=new Ciclos();
		$ciclo_activo=$ciclo_activo->activo();
		$grupo = new Grupos();
		$grupo = $grupo->find_by_sql(
			"SELECT " .
				"grupos.id, " .
				"grupos.grado, " .
				"grupos.letra, " .
				"grupos.turno " .
			"FROM " .
				"grupos Inner Join alumnosgrupo On grupos.id = alumnosgrupo.grupos_id " .
			"WHERE " .
				"alumnosgrupo.alumnos_id = '" . $this->id . "' AND grupos.ciclos_id=".$ciclo_activo->id
		);

		if($grupo!=null){
		$gru=new Grupos();
		$gru=$gru->find_first($grupo['id']);
		return $gru;
		}else return $grupo;
	}

	public function obtenerGrupoPorCiclo($ciclo_id){

		$ciclo_activo=new Ciclos();
		$ciclo_activo=$ciclo_activo->activo();
		$grupo = new Grupos();
		$grupo = $grupo->find_by_sql(
			"SELECT " .
				"grupos.id, " .
				"grupos.grado, " .
				"grupos.letra, " .
				"grupos.turno " .
			"FROM " .
				"grupos Inner Join alumnosgrupo On grupos.id = alumnosgrupo.grupos_id " .
			"WHERE " .
				"alumnosgrupo.alumnos_id = '" . $this->id . "' AND grupos.ciclos_id=".$ciclo_id
		);

		if($grupo!=null){
		$gru=new Grupos();
		$gru=$gru->find_first($grupo['id']);
		return $gru;
		}else return $grupo;
	}

	public function obtenerCursos($ciclo_id){
		$cursos = db::raw_connect();
		$cursos = $cursos->in_query(
			" SELECT
				alumnos.id,
				alumnos.codigo,
				grupos.*,
				cursos.*
				FROM
				alumnos
				INNER JOIN alumnoscursos ON alumnos.id=alumnoscursos.alumnos_id
				INNER JOIN cursos ON cursos.id=alumnoscursos.cursos_id
				INNER JOIN grupos ON grupos.id=cursos.grupos_id
 				WHERE alumnos.id=".$this->id."  AND grupos.ciclos_id=".$ciclo_id."  "
		);

		if($cursos!=null){
		return $cursos;
		}else return array();
	}

	public function sexo(){
		if($this->sexo != ''){
			if($this->sexo == 'H'){
				return 'Hombre';
			}else if($this->sexo == 'M'){
				return 'Mujer';
			}
		}
		return '-';
	}

	public function situacion(){
		$situaciones = new Situaciones();
		if($this->situaciones_id!='')
		$situacion = $situaciones->find($this->situaciones_id);
		else
		$situacion = $situaciones->find_first("clave='".$this->situacion."'");

		return $situacion->nombre;
	}

	public function tutores(){
		$tutores = new Tutores();
		$tutores = $tutores->find_all_by_sql(
			"SELECT " .
				"tutores.id, " .
				"tutores.nombre, " .
				"tutores.ap, " .
				"tutores.am " .
			"FROM " .
				"tutores Inner Join tutoria ON tutores.id = tutoria.tutores_id " .
			"WHERE " .
				"tutoria.alumnos_id = '" . $this->id . "' " .
			"ORDER BY " .
				"tutores.ap, tutores.am, tutores.nombre "
		);
		return $tutores;
	}

	public function validarCalificacionesFinales($curso_id, $opr_ast, $opr_ids){
		$cal = $this->calificaciones($curso_id);
		$ord = $cal['ORD'];
		$ext = $cal['EXT'];

		$respuesta['CODIGO']   =  $this->codigo;
		$respuesta['EXITO']   =  '';
		$respuesta['ERROR']  =  '';

		switch($opr_ast){
			case 'ORD':
					if($ord->valor == 'SD'){
						// segun el porcentaje de asistencias el periodo ordinario no puede ser sin-derecho
						$ord->valor = '50';
						if($ord->save()){
							$respuesta['EXITO']  .= 'Se cambi&oacute; ORDINARIO de SD a 50. ';
						}else{
							$respuesta['ERR'] .= 'No se pudo cambiar ORDINARIO de SD a 50. ';
						}
					}
					if($ord->valor >= 60){
						if($ext->id != ''){
							// si el ordinario es aprobatorio no debe haber calificacion en extraordinario
							try{
								$ext->delete();
								$respuesta['EXITO']  .= 'Se elimin&oacute; EXTRAORDINARIO. ';
							}catch(dbException $e){
								$respuesta['ERR'] .= 'No se pudo eliminar EXTRAORDINARIO. ';
							}
						}
					}else if($ext->id == '' || $ext->valor == 'SD'){
						// si el periodo ordinario no es aprobatorio debe existir calificacion en extraordinario
						if($ext->id == ''){
							$ext = new Calificaciones();
							$ext->cursos_id = $curso_id;
							$ext->alumnos_id = $this->id;
							$ext->oportunidades_id = $opr_ids['EXT'];
						}
						$ext->valor = '50';
						if($ext->save()){
							$respuesta['EXITO']  .= 'Se agreg&oacute; EXTRAORDINARIO con valor de 50. ';
						}else{
							$respuesta['ERR'] .= 'No se pudo agregar EXTRAORDINARIO con valor de 50. ';
						}
					}
					break;

			case 'EXT':
					if($ord->valor != 'SD'){
						// segun el porcentaje de asistencias no se tiene derecho a ordinario
						$ord->valor = 'SD';
						if($ord->save()){
							$respuesta['EXITO']  .= 'Se cambi&oacute; ORDINARIO de ' . $ord->valor . ' a 50. ';
						}else{
							$respuesta['ERR'] .= 'No se pudo cambiar ORDINARIO de ' . $ord->valor . ' a 50. ';
						}
					}
					if($ext->valor == 'SD' || $ext->id == ''){
						// segun el porcentaje de asistencias el extraordinario no puede estar sin-derecho o vacio
						if($ext->id == ''){
							$ext = new Calificaciones();
							$ext->cursos_id = $curso_id;
							$ext->alumnos_id = $this->id;
							$ext->oportunidades_id = $opr_ids['EXT'];
						}
						$ext->valor = '50';
						if($ext->save()){
							$respuesta['EXITO']  .= 'Se agreg&oacute; EXTRAORDINARIO con valor de 50. ';
						}else{
							$respuesta['ERR'] .= 'No se pudo agregar EXTRAORDINARIO con valor de 50. ';
						}
					}
					break;

			case 'SD':
					if($ord->valor != 'SD'){
						// segun el porcentaje de asistencias no se tiene derecho a ordinario
						$ord->valor = 'SD';
						if($ord->save()){
							$respuesta['EXITO']  .= 'Se cambi&oacute; ORDINARIO de ' . $ord->valor . ' a SD. ';
						}else{
							$respuesta['ERR'] .= 'No se pudo cambiar ORDINARIO de ' . $ord->valor . ' a SD. ';
						}
					}
					if($ext->valor != 'SD'){
						// segun el porcentaje de asistencias no se tiene derecho a extraordinario
						if($ext->id == ''){
							$ext = new Calificaciones();
							$ext->cursos_id = $curso_id;
							$ext->alumnos_id = $this->id;
							$ext->oportunidades_id = $opr_ids['EXT'];
						}
						$ext->valor = 'SD';
						if($ext->save()){
							$respuesta['EXITO']  .= 'Se cambi&oacute; EXTRAORDINARIO de ' . $ext->valor . ' a SD. ';
						}else{
							$respuesta['ERR'] .= 'No se pudo cambiar EXTRAORDINARIO de ' . $ext->valor . ' a SD. ';
						}
					}
					break;

		}
		return $respuesta;
	}

	public function verGrupo(){
		$ciclo_id = Session :: get_data('ciclo.id');
		$grupo = new Grupos();
		$grupo = $grupo->find_by_sql(
			"SELECT " .
				"grupos.id, " .
				"grupos.grado, " .
				"grupos.letra, " .
				"grupos.turno " .
			"FROM " .
				"grupos Inner Join alumnosgrupo On grupos.id = alumnosgrupo.grupos_id " .
			"WHERE " .
				"alumnosgrupo.alumnos_id = '" . $this->id . "' AND grupos.ciclos_id='$ciclo_id' "
		);
		return $grupo['grado'] . '&deg;' . $grupo['letra'] . ' ' . $grupo['turno'];
	}

	public function slotCursoDisponible($curso_id){
		$curso=new Cursos();
		$curso=$curso->find_first($curso_id);
		$horarios=new Horarios();
		$horarios=$horarios->find('cursos_id='.$curso_id);
		foreach($horarios as $horario){

		$sql="SELECT * FROM
								cursos
								Inner Join alumnoscursos ON alumnoscursos.cursos_id=cursos.id
								Inner Join horarios ON horarios.cursos_id = cursos.id
								WHERE alumnoscursos.alumnos_id=".$this->id."
								 AND horarios.dias_id = '".$horario->dias_id."' AND
								((horarios.inicio >  '".$horario->inicio."' AND  horarios.inicio < '".$horario->fin."') OR
								(horarios.fin >  '".$horario->inicio."' AND  horarios.fin < '".$horario->fin."') OR
								(horarios.inicio <=  '".$horario->inicio."' AND  horarios.fin >= '".$horario->fin."')) AND
								cursos.id !='".$curso_id."'";
		$cursos=$this->find_by_sql($sql);

		if($cursos!=null){
			$materia=new Materias();
			$materia=$materia->find_first($cursos['materias_id']);
			return "Conflicto de horario con ".$materia->nombre;
		}
		}

		return 1;

	}
}
?>
