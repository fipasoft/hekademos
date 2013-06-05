<?php
// Hekademos, Creado el 11/10/2008
/**
 * Horarios
 *
 * @package    Hekademos
 * @author     mimeks <mimex@fipasoft.com.mx>
 * @copyright  2008 FiPa Software (contacto at fipasoft.com.mx)
 * @license    http://www.gnu.org/licenses/gpl.html  GNU General Public License (GPL)
 *
 */
 class Horarios extends ActiveRecord{
	public function aula(){
		$aula = new Aulas();
		$aula = $aula->find($this->aulas_id);
		return $aula;
	}
	public function aulaClave(){
		$aula = new Aulas();
		$aula = $aula->find($this->aulas_id);
		return $aula->clave;
	}
	public function aulaNombre(){
		$aula = new Aulas();
		$aula = $aula->find($this->aulas_id);
		return $aula->nombre;
	}

	public function valida($curso_id,$grupos_id,$profesores_id,$materias_id,$dia,$entrada,$salida,$aula,$inicio){
		$conflictos = array();
		$disponible = false;

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

			if($materiaoferta!=2){
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
					$conflictos[] = $reg['grado'] . '&deg;' . $reg['letra'] . ' ' . $reg['turno'] . ', ' .
										  $reg['materia'] . ', ' .
										  $reg['prof_n'] . ' ' . $reg['prof_ap'] . ' ' .$reg['prof_am'] . ', ' .
										  $reg['dia'] . ' ' . $reg['inicio'] . '-' . $reg['fin'] . ', ' .
										  'Aula ' . $reg['aula'];
				}
			}else{
				$disponible = true;
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

						$conflictos[] = 'Inicio: '.Utils::fecha_espanol($cursos->inicio).',Duracion: '.$cursos->materia()->duracion.' semanas, Fin: '.$fin.', '.$reg['grado'] . '&deg;' . $reg['letra'] . ' ' . $reg['turno'] . ', ' .
											  $reg['materia'] . ', ' .
											  $reg['prof_n'] . ' ' . $reg['prof_ap'] . ' ' .$reg['prof_am'] . ', ' .
											  $reg['dia'] . ' ' . $reg['inicio'] . '-' . $reg['fin'] . ', ' .
											  'Aula ' . $reg['aula'];
						}

				}else{
					$fin=Utils::fecha_espanol(date('Y-m-d',$fin->format('U')));

					$conflictos[] = 'Inicio: '.Utils::fecha_espanol($cursos->inicio).',Duracion: '.$cursos->materia()->duracion.' semanas, Fin: '.$fin.', '.$reg['grado'] . '&deg;' . $reg['letra'] . ' ' . $reg['turno'] . ', ' .
										  $reg['materia'] . ', ' .
										  $reg['prof_n'] . ' ' . $reg['prof_ap'] . ' ' .$reg['prof_am'] . ', ' .
										  $reg['dia'] . ' ' . $reg['inicio'] . '-' . $reg['fin'] . ', ' .
										  'Aula ' . $reg['aula'];
				}
				}else{
					$conflictos[] = $reg['grado'] . '&deg;' . $reg['letra'] . ' ' . $reg['turno'] . ', ' .
										  $reg['materia'] . ', ' .
										  $reg['prof_n'] . ' ' . $reg['prof_ap'] . ' ' .$reg['prof_am'] . ', ' .
										  $reg['dia'] . ' ' . $reg['inicio'] . '-' . $reg['fin'] . ', ' .
										  'Aula ' . $reg['aula'];
				}
				}
				if(count($conflictos)==0)
				$disponible = true;
			}else{
				$disponible = true;
			}

			}
		}

		return array($disponible,$conflictos);

	}
 }
?>
