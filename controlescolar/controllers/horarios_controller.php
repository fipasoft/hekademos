<?php
// Hekademos, Creado el 13/10/2008
/**
 * Controlador de horarios
 *
 * @package    Hekademos
 * @author     mimeks <mimex@fipasoft.com.mx>
 * @copyright  2008 FiPa Software (contacto at fipasoft.com.mx)
 * @license    http://www.gnu.org/licenses/gpl.html  GNU General Public License (GPL)
 *
 */
Kumbia :: import('app.componentes.*');
Kumbia :: import('lib.excel.main');

class HorariosController extends ApplicationController {
	public $template = "system";
	/*	public function validar(){
	 $this->set_response('view');
	 $curso_id = $this->curso_id = $valor = $this->post('curso_id');
	 $grupos_id = $this->grupos_id = $valor = $this->post('grupos_id');
		$profesores_id = $this->profesores_id = $valor = $this->post('profesores_id');
		$materias_id = $this->materias_id = $valor = $this->post('materias_id');
		$dia = $this->dia = $valor = $this->post('dia');
		$entrada = $this->entrada = $valor = $this->post('entrada');
		$salida = $this->salida = $valor = $this->post('salida');
		$aula = $this->aula = $valor = $this->post('aula');
		$inicio = $this->inicio = $valor = $this->post('inicio');
		$this->n = $this->post('n');
		$this->conflictos = array();
		$this->disponible = false;

		$grupo = new Grupos();
		$grupo = $grupo->find($grupos_id);

		$db = db::raw_connect();
		$registros = null;

		if($grupo->id != ''){
		$materia=new Materias();
		$materia=$materia->find($materias_id);
		$materiaoferta=$materia->Oferta('id');
		$prf=new Profesores();
		if($prf->esStaff($profesores_id))
		$profesores_id='-1';  //para evitar colisiones por profesor staff

		if($materiaoferta==1){
		$ciclos_id = Session :: get_data('ciclo.id');
		// restriccion de grupo y aula
		$registros = $db->query(
		"SELECT
		horarios.inicio,
		horarios.fin,
		materias.nombre AS materia,
		grupos.grado,
		grupos.letra,
		grupos.turno,
		profesores.nombre AS prof_n,
		profesores.ap AS prof_ap,
		profesores.am AS prof_am,
		aulas.clave AS aula,
		dias.nombre AS dia
		FROM
		cursos
		Inner Join horarios ON horarios.cursos_id = cursos.id
		Inner Join materias ON cursos.materias_id = materias.id
		Inner Join grupos ON cursos.grupos_id = grupos.id
		Inner Join profesores ON cursos.profesores_id = profesores.id
		Inner Join aulas ON horarios.aulas_id = aulas.id
		Inner Join dias ON horarios.dias_id = dias.id
		WHERE" .
		"	(" .
		"	grupos.ciclos_id = '" . $ciclos_id . "' AND
		((horarios.aulas_id = '". $aula ."' )
		OR
		(profesores.id='".$profesores_id."')) AND " .
		"horarios.dias_id = '" . $dia . "' AND " .
		"((horarios.inicio >  '" . $entrada . "' AND  horarios.inicio < '" . $salida . "') OR " .
		"(horarios.fin >  '" . $entrada . "' AND  horarios.fin < '" . $salida . "') OR " .
		"(horarios.inicio <=  '" . $entrada . "' AND  horarios.fin >= '" . $salida . "')) " .
		($curso_id != "" ? "AND cursos.id != '" . $curso_id . "'" : "").
		")"
		);
		if($db->num_rows($registros) > 0){
		while($reg = $db->fetch_array($registros, db :: DB_ASSOC)){
		$this->conflictos[] = $reg['grado'] . '&deg;' . $reg['letra'] . ' ' . $reg['turno'] . ', ' .
		$reg['materia'] . ', ' .
		$reg['prof_n'] . ' ' . $reg['prof_ap'] . ' ' .$reg['prof_am'] . ', ' .
		$reg['dia'] . ' ' . $reg['inicio'] . '-' . $reg['fin'] . ', ' .
		'Aula ' . $reg['aula'];
		}
		}else{
		$this->disponible = true;
		}
		}elseif($materiaoferta==2){
		$ciclos_id = Session :: get_data('ciclo.id');
		// restriccion de grupo y aula
		$registros = $db->query(
		"SELECT
		cursos.id as cursos_id,
		cursos.inicio as curso_inicio,
		horarios.inicio,
		horarios.fin,
		materias.nombre AS materia,
		grupos.grado,
		grupos.letra,
		grupos.turno,
		grupos.oferta_id,
		profesores.nombre AS prof_n,
		profesores.ap AS prof_ap,
		profesores.am AS prof_am,
		aulas.clave AS aula,
		dias.nombre AS dia
		FROM
		cursos
		Inner Join horarios ON horarios.cursos_id = cursos.id
		Inner Join materias ON cursos.materias_id = materias.id
		Inner Join grupos ON cursos.grupos_id = grupos.id
		Inner Join profesores ON cursos.profesores_id = profesores.id
		Inner Join aulas ON horarios.aulas_id = aulas.id
		Inner Join dias ON horarios.dias_id = dias.id
		WHERE" .
		"	(" .
		"	grupos.ciclos_id = '" . $ciclos_id . "' AND
		((horarios.aulas_id = '". $aula ."' )
		OR
		(profesores.id='".$profesores_id."')) AND " .
		"horarios.dias_id = '" . $dia . "' AND " .
		"((horarios.inicio >  '" . $entrada . "' AND  horarios.inicio < '" . $salida . "') OR " .
		"(horarios.fin >  '" . $entrada . "' AND  horarios.fin < '" . $salida . "') OR " .
		"(horarios.inicio <=  '" . $entrada . "' AND  horarios.fin >= '" . $salida . "')) " .
		($curso_id != "" ? "AND cursos.id != '" . $curso_id . "'" : "").
		")"
		);

		if($db->num_rows($registros) > 0){
		while($reg = $db->fetch_array($registros, db :: DB_ASSOC)){
		if($reg['oferta_id']==2){
		$cursos=new Cursos();
		$cursos=$cursos->find($reg['cursos_id']);
		$materia2 = $cursos->materia();
		$fin=$cursos->fechaFin($cursos->inicio);
		if($materia->duracion < 19 && $materia2->duracion < 19){
		$fc=$fin->format('U');
		$ic=  new DateTime( $cursos->inicio );
		$ic=$ic->format('U');
		$ini  =  new DateTime( Utils::convierteFechaMySql($inicio) );
		$ini=$ini->format('U');
		//$fn=$cursos->fechaFin2(Utils::convierteFechaMySql($inicio),$materia);
		//$fn=$fn->format('U');

		if(
		!(
		($ic<$ini && $fc<$ini)
		||
		($ini<$ic && $ini<$fc)
		)
		){

		$fin=Utils::fecha_espanol(date('Y-m-d',$fin->format('U')));

		$this->conflictos[] = 'Inicio: '.Utils::fecha_espanol($cursos->inicio).',Duracion: '.$cursos->materia()->duracion.' semanas, Fin: '.$fin.', '.$reg['grado'] . '&deg;' . $reg['letra'] . ' ' . $reg['turno'] . ', ' .
		$reg['materia'] . ', ' .
		$reg['prof_n'] . ' ' . $reg['prof_ap'] . ' ' .$reg['prof_am'] . ', ' .
		$reg['dia'] . ' ' . $reg['inicio'] . '-' . $reg['fin'] . ', ' .
		'Aula ' . $reg['aula'];
		}

		}else{
		$fin=Utils::fecha_espanol(date('Y-m-d',$fin->format('U')));

		$this->conflictos[] = 'Inicio: '.Utils::fecha_espanol($cursos->inicio).',Duracion: '.$cursos->materia()->duracion.' semanas, Fin: '.$fin.', '.$reg['grado'] . '&deg;' . $reg['letra'] . ' ' . $reg['turno'] . ', ' .
		$reg['materia'] . ', ' .
		$reg['prof_n'] . ' ' . $reg['prof_ap'] . ' ' .$reg['prof_am'] . ', ' .
		$reg['dia'] . ' ' . $reg['inicio'] . '-' . $reg['fin'] . ', ' .
		'Aula ' . $reg['aula'];
		}
		}else{
		$this->conflictos[] = $reg['grado'] . '&deg;' . $reg['letra'] . ' ' . $reg['turno'] . ', ' .
		$reg['materia'] . ', ' .
		$reg['prof_n'] . ' ' . $reg['prof_ap'] . ' ' .$reg['prof_am'] . ', ' .
		$reg['dia'] . ' ' . $reg['inicio'] . '-' . $reg['fin'] . ', ' .
		'Aula ' . $reg['aula'];
		}
		}
		if(count($this->conflictos)==0)
		$this->disponible = true;
		}else{
		$this->disponible = true;
		}

		}
		}
		}
		*/
	public function validar(){
		$this->set_response('view');
		$curso_id = $this->curso_id = $valor = $this->post('curso_id');
		$grupos_id = $this->grupos_id = $valor = $this->post('grupos_id');
		$profesores_id = $this->profesores_id = $valor = $this->post('profesores_id');
		$materias_id = $this->materias_id = $valor = $this->post('materias_id');
		$dia = $this->dia = $valor = $this->post('dia');
		$entrada = $this->entrada = $valor = $this->post('entrada');
		$salida = $this->salida = $valor = $this->post('salida');
		$aula = $this->aula = $valor = $this->post('aula');
		$inicio = $this->inicio = $valor = $this->post('inicio');
		$this->n = $this->post('n');

		$horarios = new Horarios();
		$validacion = $horarios->valida($curso_id,$grupos_id,$profesores_id,$materias_id,$dia,$entrada,$salida,$aula,$inicio);
		$this->conflictos = $validacion[1];
		$this->disponible = $validacion[0];


	}

	public function generar(){
		$ciclo_id = 6;
		$ciclo = new Ciclos();
		$ciclo = $ciclo->find($ciclo_id);
		$grupos = new Grupos();
		$grupos = $grupos->todosporCiclo($ciclo->id);
		mysql_query("BEGIN");
		$myLog = new Logger('GeneraHorario.log');
		$myLog->log("Generacion de horario ".$ciclo->numero, Logger::ERROR);
		$myLog->log(count($grupos)." grupos", Logger::ERROR);
		$myLog->close();
		$asig = 0;
		$ct =0;
		foreach($grupos as $grupo){
			$myLog = new Logger('GeneraHorario.log');
			$myLog->log($grupo->ver(), Logger::ERROR);
			$cursos = $grupo->cursos();
			$myLog->log(count($cursos)." cursos", Logger::ERROR);
			$myLog->close();
			foreach($cursos as $curso){
				
				$materia = $curso->materia();
				$htempo = new Horariotemporal();
				if($materia->semestre==1 ){
					$asig += $htempo->primero($grupo,$curso,$materia);
				}elseif($materia->semestre==2 ){
					$asig += $htempo->segundo($grupo,$curso,$materia);
				}elseif($materia->semestre==3 ){
					$asig += $htempo->tercero($grupo,$curso,$materia);
				}elseif($materia->semestre==4 ){
					$asig += $htempo->cuarto($grupo,$curso,$materia);
				}elseif($materia->semestre==5 ){
					$asig += $htempo->quinto($grupo,$curso,$materia);
				}elseif($materia->semestre==6 ){
					$asig += $htempo->sexto($grupo,$curso,$materia);
				}
			}
			
			$ct +=count($cursos);
		}

		$myLog = new Logger('GeneraHorario.log');
		$myLog->log("Asignados ".$asig." ".$ct, Logger::ERROR);
		$myLog->close();
		ob_clean();
		$this->set_response("view");
		
		require ('app/reportes/xls.horario.php');
		$ciclo_id = Session :: get_data('ciclo.id');
		$reporte = new XLSHorario($ciclo_id, $grp_id);
		$reporte->generar();
		mysql_query("ROLLBACK");


	}

}
?>