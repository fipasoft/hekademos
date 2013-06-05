<?php
class XLSConcentrado extends Reporte{
	private $condicion;
	private $ciclo;
	private $grupo;
	private $nombre;
	
	public function XLSConcentrado($grupo_id = ''){
		$grupos = new Grupos();
		$ciclos = new Ciclos();
		$grupo = $grupos->find($grupo_id);
		$this->grupo = $grupo;
		$ciclo = $ciclos->find($grupo->ciclos_id);
		$this->ciclo = $ciclo;
		$glabel = $grupo->grado . $grupo->letra . $grupo->turno;
		$this->nombre = $nombre = 'Concentrado ' . $glabel . ' ' . $ciclo->numero . '.xls';
		$this->Reporte($nombre);
		
		$cursos = $this->datos_curso($grupo->id);
		if(count($cursos) > 0){
			foreach($cursos as $curso){
				$this->hoja($curso);
			}
		}else{
			$this->hoja_vacia();
		}
	}

	public function hoja($curso){
		$grupo = $this->grupo;
		$nombre = $curso->verMateriaNombre();
		$nombre = utf8_decode($nombre);
		$nombre = str_replace('/', ' ', $nombre);
		$nombre = str_replace('\\', ' ', $nombre);
		$nombre = substr($nombre, 0, 31);
		$hojas = $this->getHojas();
		if(array_key_exists($nombre, $hojas)){
			$h = $hojas[$nombre];
		}else{
			$cols = array(
						36, 13, 13, 13
					);
			$rows = array( 0.1 );
			$h = $this->agregar_hoja($nombre, $rows, $cols);	
			$h->cc_max = 8;
		}
		$this->contenido($h, $curso);
		$this->propiedades($h);
	}
	
	public function hoja_vacia(){
		$nombre = 'HEKADEMOS';
		$h = $this->agregar_hoja($nombre);	
		$h->xls->write(0, 0, "No hay registros que coincidan con esas condiciones");
	}
	
	public function alumnos($curso_id){
		$alumnos = new Alumnos();
		
		$alumnos = $alumnos->find_all_by_sql(
					"SELECT " .
						"alumnos.id, " .
						"alumnos.codigo, " .
						"alumnos.ap, " .
						"alumnos.am, " .
						"alumnos.nombre " .
					"FROM " . 
						"alumnos " .
							"INNER JOIN " . 
						"alumnoscursos " . 
							"ON alumnos.id = alumnoscursos.alumnos_id " .
					"WHERE alumnoscursos.cursos_id = '" . $curso_id . "' "  .
					"ORDER BY ap, am, nombre "
			);
		return $alumnos;
	}

	public function contenido(&$h, $curso){
		$grupo = $this->grupo;
		$st = $this->getEstilos();
		$alumnos = $this->alumnos($curso->id);
		$h->cc++;
		$h->xls->write($h->rr, $h->cc, 'curso_id', $st['TD.BGYellow']); $h->cc++;
		$h->xls->write($h->rr, $h->cc, $curso->id, $st['TD.Normal']);
		$h->nueva_linea(); $h->cc++;
		$h->xls->write($h->rr, $h->cc, 'Grado', $st['TD.BGYellow']); $h->cc++;
		$h->xls->write($h->rr, $h->cc, $grupo->grado, $st['TD.Normal']);
		$h->nueva_linea(); $h->cc++;
		$h->xls->write($h->rr, $h->cc, 'Grupo', $st['TD.BGYellow']); $h->cc++;
		$h->xls->write($h->rr, $h->cc, $grupo->letra, $st['TD.Normal']);
		$h->nueva_linea(); $h->cc++;
		$h->xls->write($h->rr, $h->cc, 'Turno', $st['TD.BGYellow']); $h->cc++;
		$h->xls->write($h->rr, $h->cc, $grupo->turno, $st['TD.Normal']);
		$h->nueva_linea(); $h->cc++;
		$h->nueva_linea();

		$h->xls->write($h->rr, $h->cc, 'Nombre', $st['TD.BGOrange']); $h->cc++;
		$h->xls->write($h->rr, $h->cc, 'Codigo', $st['TD.BGOrange']); $h->cc++;
		$h->xls->write($h->rr, $h->cc, 'Faltas', $st['TD.BGOrange']); $h->cc++;
		$h->xls->write($h->rr, $h->cc, 'Calificacion', $st['TD.BGOrange']); $h->cc++;
		$h->nueva_linea(); 
		$n = 0;
		if(count($alumnos) > 0){
			foreach($alumnos as $alumno){
				$n++;
				$td = ($n%2 == 0 ? 'Par' : '');
				$nombre = $alumno->ap . ' ' . $alumno->am . ', '. $alumno->nombre;
				$h->xls->write($h->rr, $h->cc, utf8_decode($nombre), $st['TD' . $td . '.Normal']); $h->cc++;
				$h->xls->write($h->rr, $h->cc, utf8_decode($alumno->codigo), $st['TD' . $td . '.Normal']); $h->cc++;
				$h->nueva_linea(); 
			}
		}else{
			$h->xls->writeBlank(0, 0, $st['TD']);
		}
		$h->nueva_linea();
		$h->rr_max = $h->rr;
	}

	public function datos_curso($grupo_id){
		$cursos = new Cursos();
		$c = $this->condicion;
		
		$from = "cursos " .
				"INNER JOIN grupos ON cursos.grupos_id = grupos.id " .
				"INNER JOIN materias ON cursos.materias_id  = materias.id " .
				"INNER JOIN profesores ON cursos.profesores_id  = profesores.id ";
		$cursos = $cursos->find_all_by_sql(
					"SELECT " .
						"cursos.id, " .
						"cursos.grupos_id, " .
						"cursos.materias_id," .
						"cursos.profesores_id " .
					"FROM " . $from .
					"WHERE cursos.grupos_id = '" . $grupo_id . "' " .
					($c == "" ? "" : "AND " . $c . " ") .
					"ORDER BY materias.nombre "
			);
		return $cursos;
	}

	public function encabezado(){
		
	}
	
	public function getNombre(){
		return $this->nombre;
	}
	
	public function propiedades(&$h){
		$h->xls->centerHorizontally();
		$h->xls->hideGridlines();
		$h->xls->printArea(0, 0, $h->rr_max, $h->cc_max);
		$h->xls->setFooter("HEKADEMOS ".date("j/n/Y H:i"), 0);
		$h->xls->setPortrait();
		$h->xls->setMargins_LR(0.2);
		$h->xls->setMargins_TB(0.27);
		$h->xls->setPaper(3);
		$h->xls->setPrintScale(85);	
		$h->xls->setZoom(80);
	}	
}
?>