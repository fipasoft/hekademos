<?php
Kumbia :: import('lib.excel.main');
class XLSCierre extends Reporte{
	private $condicion;
	private $ciclo;
	private $anterior;
	private $siguiente;
	private $alumnos;
	private $calificaciones;
	private $campos;
	private $articulos;

	public function XLSCierre($ciclo,$g,$alumnos,$anterior,$siguiente,$cal,$sit,$articulos){
		$this->ciclo = $ciclo;

		$this->ciclosiguiente = new Ciclos();
		$this->ciclosiguiente = $this->ciclosiguiente->find_first("numero='".$this->ciclo->siguiente()."'");;
		$this->anterior=$anterior;
		$this->siguiente=$siguiente;
		$this->alumnos=$alumnos;
		$this->calificaciones=$cal;
		$this->campos=$sit;
		$this->articulos=$articulos;

		$this->Reporte('Cierre'.$g->grado.$g->letra.$g->turno.'['.$g->verOfertaClave().'].xls');
		foreach($alumnos as $alumno){
		$this->hoja($alumno);
		}

	}

	public function hoja($alumno){
		$nombre = $alumno->codigo;
		$hojas = $this->getHojas();
		if(array_key_exists($nombre, $hojas)){
			$h = $hojas[$nombre];
		}else{
			$cols = array( 10, 50, 10, 10);
			$h = $this->agregar_hoja($nombre, null, $cols);
			$h->cc_max = 16;
		}
		$this->contenido($h, $alumno);
		$this->propiedades($h);
	}

	public function hoja_vacia(){
		$nombre = 'HEKADEMOS';
		$h = $this->agregar_hoja($nombre);
		$h->xls->write(0, 0, "No hay registros que coincidan con esas condiciones");
	}

	public function contenido(&$h, $alumno){
		$this->encabezado($h,$alumno);
		$st = $this->getEstilos();
		$h->xls->write($h->rr, $h->cc, 'Codigo', $st['TD.BGYellow']); $h->cc++;
		$h->xls->write($h->rr, $h->cc, $alumno->codigo, $st['TD.Normal']);
		$h->nueva_linea();
		$h->xls->write($h->rr, $h->cc, 'Nombre', $st['TD.BGYellow']); $h->cc++;
		$h->xls->write($h->rr, $h->cc, utf8_decode($alumno->nombre()), $st['TD.Normal']);
		$h->nueva_linea();
		$ag=$alumno->obtenerGrupoPorCiclo($this->ciclo->id);
		$h->xls->write($h->rr, $h->cc, 'Grupo', $st['TD.BGYellow']); $h->cc++;
		$h->xls->write($h->rr, $h->cc, utf8_decode($ag->ver()), $st['TD.Normal']);

		$h->nueva_linea();
		$h->nueva_linea();
		$h->xls->write($h->rr, $h->cc, 'Aprobadas', $st['TD.BGYellow']); $h->cc++;
		$h->xls->write($h->rr, $h->cc, utf8_decode($this->campos[$alumno->id]['aprobadas']), $st['TD.Normal']);

		$h->nueva_linea();
		$h->xls->write($h->rr, $h->cc, 'Promedio', $st['TD.BGYellow']); $h->cc++;
		$h->xls->write($h->rr, $h->cc, utf8_decode($this->campos[$alumno->id]['promedio']), $st['TD.Normal']);

		$h->nueva_linea();
		$h->xls->write($h->rr, $h->cc, 'Situacion', $st['TD.BGYellow']); $h->cc++;
		$h->xls->write($h->rr, $h->cc, utf8_decode($this->campos[$alumno->id]['situacion']), $st['TD.Normal']);
		$h->nueva_linea();
		$h->nueva_linea();
		$h->xls->write($h->rr, $h->cc, 'Cursos del '.$this->ciclo->numero, $st['TD.BGYellow']);
		$h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc +3);
		$h->nueva_linea();
		$h->xls->write($h->rr, $h->cc, 'Grupo', $st['TD.BGYellow']); $h->cc++;
		$h->xls->write($h->rr, $h->cc, 'Curso', $st['TD.BGYellow']); $h->cc++;
		$h->xls->write($h->rr, $h->cc, 'Ordinario', $st['TD.BGYellow']); $h->cc++;
		$h->xls->write($h->rr, $h->cc, 'Ext', $st['TD.BGYellow']); $h->cc++;

		$cursos=$this->anterior[$alumno->id];
		$h->nueva_linea();
		foreach($cursos as $curso){
		$grupo=$curso->grupo();
		$h->xls->write($h->rr, $h->cc, utf8_decode($grupo->grado.$grupo->letra.$grupo->turno), $st['TD.NormalCenter']); $h->cc++;
		$h->xls->write($h->rr, $h->cc, utf8_decode($curso->materia), $st['TD.NormalCenter']); $h->cc++;
		$calificaciones=$this->calificaciones[$alumno->id][$curso->id];
		$ord='-';
		$ext='-';
		foreach($calificaciones as $cal){
			if($cal->oportunidades_id==1){
				$ord=$cal->valor;
			}else{
				$ext=$cal->valor;
			}
		}

		$estilo='NormalCenter';
		if(!($ord>=60 || $ord=='A' || $ord=='-'))
			$estilo='BGOrange';

		$h->xls->writeString($h->rr, $h->cc, $ord, $st['TD.'.$estilo]); $h->cc++;
		$estilo='NormalCenter';
		if(!($ext>=60 || $ext=='A' || $ext=='-'))
			$estilo='BGOrange';

		$h->xls->writeString($h->rr, $h->cc, $ext, $st['TD.'.$estilo]); $h->cc++;

		$h->nueva_linea();

		}

		$h->nueva_linea();
		$h->nueva_linea();
		$h->xls->write($h->rr, $h->cc, 'DESPUES DEL CIERRE', $st['TD.BGYellow']);
		$h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc +3);

		$h->nueva_linea();
		$h->nueva_linea();
		$h->xls->write($h->rr, $h->cc, 'Aprobadas', $st['TD.BGYellow']); $h->cc++;
		$h->xls->write($h->rr, $h->cc, $alumno->aprobadas, $st['TD.Normal']);

		$h->nueva_linea();
		$h->xls->write($h->rr, $h->cc, 'Promedio', $st['TD.BGYellow']); $h->cc++;
		$h->xls->write($h->rr, $h->cc, $alumno->promedio, $st['TD.Normal']);

		$h->nueva_linea();
		$h->xls->write($h->rr, $h->cc, 'Situacion', $st['TD.BGYellow']); $h->cc++;
		$situacion=$alumno->situacion();
		$estilo='Normal';
		if($situacion!='Regular')
		$estilo='BGOrange';

		$h->xls->write($h->rr, $h->cc, utf8_decode($situacion), $st['TD.'.$estilo]);

		$ag=$alumno->obtenerGrupoPorCiclo($this->ciclosiguiente->id);
		if($ag->id!=''){
		$h->nueva_linea();
		$h->xls->write($h->rr, $h->cc, 'Grupo', $st['TD.BGYellow']); $h->cc++;
		$h->xls->write($h->rr, $h->cc, utf8_decode($ag->ver()), $st['TD.Normal']);
		}
		$h->nueva_linea();
		$h->nueva_linea();

		$h->xls->write($h->rr, $h->cc, 'Cursos del '.$this->ciclosiguiente->numero, $st['TD.BGYellow']);
		$h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc +1);
		$h->nueva_linea();
		$h->xls->write($h->rr, $h->cc, 'Grupo', $st['TD.BGYellow']); $h->cc++;
		$h->xls->write($h->rr, $h->cc, 'Curso', $st['TD.BGYellow']); $h->cc++;
		$proximos=$this->siguiente[$alumno->id];
		$h->nueva_linea();

		if(is_array($proximos)){
		foreach($proximos as $curso){
		$ccc=new Cursos();
		$ccc->find($curso->id);

		$grupo=$ccc->grupo();
		$articulo=$this->articulos[$alumno->id][$curso->materia_id];

		$art='';
		$estilo='NormalCenter';
		if($articulo->id!=''){
			$art='['.$articulo->clave.']';
			$estilo='BGOrange';
		}

		$h->xls->write($h->rr, $h->cc, utf8_decode($grupo->grado.$grupo->letra.$grupo->turno), $st['TD.'.$estilo]); $h->cc++;
		$h->xls->write($h->rr, $h->cc, utf8_decode($curso->materia)." ".$art, $st['TD.'.$estilo]); $h->cc++;
		$h->nueva_linea();

		}
		}


		$h->cc_max=5;
		$h->rr_max = $h->rr;
	}

	public function encabezado(&$h,$grupo){
		$st = $this->getEstilos();
		$template=new Template();
		$h->nueva_linea();
		$h->xls->insertBitmap($h->rr, $h->cc, getcwd() . '/public/img/sp5/udg.bmp', 0, 15, 1, 1);
		$h->cc += 6;
		$h->xls->insertBitmap($h->rr, $h->cc, getcwd() . '/public/img/sp5/lp5.bmp', 160, 15, 1, 1);
		$h->nueva_linea();
		$h->xls->write($h->rr, $h->cc,  $template->excel_escuela(), $st['H3']);
		$h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc + 6);
		$h->nueva_linea();
		$h->xls->write($h->rr, $h->cc, $template->excel_subtitulo(), $st['H4']);
		$h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc + 6);
		$h->nueva_linea();
		$h->nueva_linea();
		$h->xls->repeatRows(0,8);
		$h->xls->freezePanes(array(9, 0));
	}

	public function propiedades(&$h){
		$h->xls->centerHorizontally();
		$h->xls->hideGridlines();
		$h->xls->printArea(0, 0, $h->rr_max, $h->cc_max);
		$h->xls->setFooter("HEKADEMOS ".date("j/n/Y H:i"), 0);
		$h->xls->setLandscape();
		$h->xls->setMargins_LR(0.2);
		$h->xls->setMargins_TB(0.27);
		$h->xls->setPaper(3);
		$h->xls->setPrintScale(80);
		$h->xls->setZoom(80);
	}


}
?>