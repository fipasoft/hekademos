<?php
class XLSPlantilla extends Reporte {
	private $departamento;
	private $ciclo;

	public function XLSPlantilla($c_id,$departamento) {
		$ciclo = new Ciclos();
		$ciclo = $ciclo->find($c_id);
		if ($ciclo->id != '' && $departamento->id!='') {
			$this->ciclo = $ciclo;
			$this->departamento = $departamento;
			$this->Reporte('Plantilla '.utf8_decode($departamento->nombre).'.xls');
			$materias = $departamento->materias();
			if(count($materias)>0){
				foreach($materias as $materia){
					$this->hoja($materia);
				}
			}else{
				$this->Reporte('Plantilla.xls');
				$this->hoja_vacia();
			}
		} else {
			$this->Reporte('Plantilla.xls');
			$this->hoja_vacia();
		}
	}

	public function hoja($materia) {
		$nombre = $materia->semestre.utf8_decode(strToUpper($materia->nombre));
		$profesores = $materia->profesores();
		if(count($profesores)>0){
		$hojas = $this->getHojas();
		if(strlen($nombre)>31){
			$nombre = substr($nombre,0,30);
		}
		
		if (array_key_exists($nombre, $hojas)) {
			$h = $hojas[$nombre];
		} else {
			$cols = array (
			15,
			30,
			15,
			15,
			15,
			15,
			15,
			15,
			40
			);
			$h = $this->agregar_hoja($nombre, $rows, $cols);
			$h->cc_max = 8;
		}

		$this->contenido($h, $materia, $profesores);
		$this->propiedades($h);
		}
	}

	public function hoja_vacia() {
		$nombre = 'HEKADEMOS';
		$h = $this->agregar_hoja($nombre);
		$h->xls->write(0, 0, "No hay registros que coincidan con esas condiciones");
	}

	public function contenido(&$h, $materia, $profesores) {
		$this->encabezado($h,$grupo);
		$st = $this->getEstilos();
		$salto='   ' .
		
		$h->nueva_linea();
		$h->xls->write($h->rr, $h->cc, 'Materia', $st['H1']); $h->cc++;
		$h->nueva_linea();
		$h->xls->write($h->rr, $h->cc, 'Departamento', $st['TD.BGYellow']); $h->cc++;
			$h->xls->write($h->rr, $h->cc, utf8_decode($this->departamento->nombre), $st['TD.Normal']); $h->cc++;
		
		$h->nueva_linea();		'   ';
		$h->xls->write($h->rr, $h->cc, 'Academia', $st['TD.BGYellow']); $h->cc++;
		$academia = new Academia();
		$academia = $academia->find_all_by_sql(
			"SELECT academia.* FROM 
				academia
				INNER JOIN academiamateria ON academia.id = academiamateria.academia_id
				WHERE academiamateria.materias_id ='".$materia->id."' LIMIT 0,1"
		);
		$academia = $academia[0];
			$h->xls->write($h->rr, $h->cc, utf8_decode($academia->nombre), $st['TD.Normal']); $h->cc++;
		$h->nueva_linea();
		$h->xls->write($h->rr, $h->cc, 'Semestre', $st['TD.BGYellow']); $h->cc++;
			$h->xls->write($h->rr, $h->cc, utf8_decode($materia->semestre), $st['TD.Normal']); $h->cc++;
		
		$h->nueva_linea();
		$h->xls->write($h->rr, $h->cc, 'Clave', $st['TD.BGYellow']); $h->cc++;
			$h->xls->write($h->rr, $h->cc, utf8_decode($materia->clave), $st['TD.Normal']); $h->cc++;
		$h->nueva_linea();
		$h->xls->write($h->rr, $h->cc, 'Nombre', $st['TD.BGYellow']); $h->cc++;
			$h->xls->write($h->rr, $h->cc, utf8_decode($materia->nombre), $st['TD.Normal']); $h->cc++;
		$h->nueva_linea();
		$h->xls->write($h->rr, $h->cc, 'Oferta', $st['TD.BGYellow']); $h->cc++;
		$h->xls->write($h->rr, $h->cc, utf8_decode($materia->Oferta('nombre')), $st['TD.Normal']); $h->cc++;
		$h->nueva_linea();
		$h->xls->write($h->rr, $h->cc, 'Horas por semana', $st['TD.BGYellow']); $h->cc++;
			$h->xls->write($h->rr, $h->cc, utf8_decode($materia->horasporsemana()), $st['TD.Normal']); $h->cc++;
		$h->nueva_linea();
		$h->xls->write($h->rr, $h->cc, 'Tipo', $st['TD.BGYellow']); $h->cc++;
			$h->xls->write($h->rr, $h->cc, $materia->tipo, $st['TD.Normal']); $h->cc++;
		
		
		$h->nueva_linea();
		$h->nueva_linea();
		$h->nueva_linea();
		$h->xls->write($h->rr, $h->cc, 'Profesores', $st['H1']); $h->cc++;
		$h->nueva_linea();
		
		$h->nueva_linea();
		$h->xls->write($h->rr, $h->cc, 'Codigo', $st['TD.BGYellow']); $h->cc++;
		$h->xls->write($h->rr, $h->cc, 'Nombre', $st['TD.BGYellow']); $h->cc++;
		$h->xls->write($h->rr, $h->cc, 'Horas asignadas', $st['TD.BGYellow']); $h->cc++;
		$h->xls->write($h->rr, $h->cc, 'H. Frente a grupo', $st['TD.BGYellow']); $h->cc++;
		$h->xls->write($h->rr, $h->cc, 'H. Descarga', $st['TD.BGYellow']); $h->cc++;
		$h->xls->write($h->rr, $h->cc, 'Asignatura', $st['TD.BGYellow']); $h->cc++;
		$h->xls->write($h->rr, $h->cc, 'Perfil', $st['TD.BGYellow']); $h->cc++;
		$h->xls->write($h->rr, $h->cc, 'Grado de estudios', $st['TD.BGYellow']); $h->cc++;
		$h->xls->write($h->rr, $h->cc, 'Nombramiento', $st['TD.BGYellow']); $h->cc++;
		$h->xls->write($h->rr, $h->cc, 'Turno', $st['TD.BGYellow']); $h->cc++;
		$h->nueva_linea();
		
		$n = 0;
		foreach($profesores as $profesor){
			$contrato = new Contratoinfo();
			$contrato = $contrato->find_first("profesores_id='".$profesor->id."'");
			$n++;
			$td = ($n%2 == 0 ? 'Par' : '');
			$h->xls->write($h->rr, $h->cc, $profesor->codigo, $st['TD' . $td . '.Normal']); $h->cc++;
			$h->xls->write($h->rr, $h->cc, utf8_decode($profesor->nombre()), $st['TD' . $td . '.Normal']); $h->cc++;	
			if($contrato->id!=""){
			$h->xls->write($h->rr, $h->cc, $contrato->hasignadas, $st['TD' . $td . '.Normal']); $h->cc++;
			$h->xls->write($h->rr, $h->cc, $contrato->hfgrupo, $st['TD' . $td . '.Normal']); $h->cc++;
			$h->xls->write($h->rr, $h->cc, $contrato->hdescarga, $st['TD' . $td . '.Normal']); $h->cc++;
			$h->xls->write($h->rr, $h->cc, $contrato->asignatura, $st['TD' . $td . '.Normal']); $h->cc++;
			$h->xls->write($h->rr, $h->cc, utf8_decode($contrato->perfil), $st['TD' . $td . '.Normal']); $h->cc++;
			$h->xls->write($h->rr, $h->cc, utf8_decode($contrato->gradoestudio), $st['TD' . $td . '.Normal']); $h->cc++;
			$h->xls->write($h->rr, $h->cc, utf8_decode($contrato->nombramiento), $st['TD' . $td . '.Normal']); $h->cc++;
			$h->xls->write($h->rr, $h->cc, $contrato->turno, $st['TD' . $td . '.Normal']); $h->cc++;	
			}else{
			$h->xls->write($h->rr, $h->cc, '_', $st['TD' . $td . '.Normal']); $h->cc++;
			$h->xls->write($h->rr, $h->cc, '_', $st['TD' . $td . '.Normal']); $h->cc++;
			$h->xls->write($h->rr, $h->cc, '_', $st['TD' . $td . '.Normal']); $h->cc++;
			$h->xls->write($h->rr, $h->cc, '_', $st['TD' . $td . '.Normal']); $h->cc++;
			$h->xls->write($h->rr, $h->cc, '_', $st['TD' . $td . '.Normal']); $h->cc++;
			$h->xls->write($h->rr, $h->cc, '_', $st['TD' . $td . '.Normal']); $h->cc++;
			$h->xls->write($h->rr, $h->cc, '_', $st['TD' . $td . '.Normal']); $h->cc++;
			$h->xls->write($h->rr, $h->cc, '_', $st['TD' . $td . '.Normal']); $h->cc++;	
				
			}

			$h->cc_max = $h->cc + 1;
			$h->nueva_linea();
		}
		

		$h->rr_max = $h->rr + 1;
	}

	public function encabezado($h,$grupo) {
		$template=new Template();
		$ciclo=$this->ciclo;
		$st = $this->getEstilos();

		$h->xls->insertBitmap($h->rr, $h->cc, getcwd() . '/public/img/sp5/udg.bmp', 0, 15, 1, 1);
		$h->cc += 6;
		$h->xls->insertBitmap($h->rr, $h->cc, getcwd() . '/public/img/sp5/lp5.bmp', 160, 15, 1, 1);
		$h->nueva_linea();
		$h->xls->write($h->rr, $h->cc, $template->excel_escuela(), $st['H3']);
		$h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc + 6);
		$h->nueva_linea();
		$h->xls->write($h->rr, $h->cc, $template->excel_subtitulo(), $st['H4']);
		$h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc + 6);

		$h->nueva_linea();
		$h->xls->repeatRows(0,3);
		$h->xls->freezePanes(array(3, 0));
	}

	public function propiedades(& $h) {
		$h->xls->centerHorizontally();
		$h->xls->hideGridlines();
		$h->xls->printArea(0, 0, $h->rr_max, $h->cc_max);
		$h->xls->setFooter("HEKADEMOS " . date("j/n/Y H:i"), 0);
		$h->xls->setLandscape();
		$h->xls->setMargins_LR(0.2);
		$h->xls->setMargins_TB(0.27);
		$h->xls->setPaper(3);
		$h->xls->setPrintScale(60);
		$h->xls->setZoom(80);
	}
}
?>