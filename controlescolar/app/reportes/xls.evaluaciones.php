<?php
Kumbia :: import('lib.excel.main');

class XLSEvaluaciones extends Reporte{
	private $curso;
	public function XLSEvaluaciones($curso_id = ''){
		$this->Reporte('Evaluaciones.xls');
		if($curso_id != ''){
			$curso_id = intval($curso_id, 10);
			$curso=new Cursos();
			$this->curso=$curso=$curso->find($curso_id);
			if(!$curso->id == ''){
				$this->curso = $curso;
				if($curso->asignado()){
					if($curso->aprobado()){
						$this->hoja();
							
						}else{
							$this->hoja_vacia('El curso no tiene registradas asistencias.');
						}
					}else{
						$this->hoja_vacia('El curso no esta aprobado.');
					}
				}else{
					$this->hoja_vacia('El curso no esta dentro de su asignacion.');
				}
			}else{
				$this->hoja_vacia('El curso no existe.');
			}
	}



	public function hoja(){
		$materia = $this->curso->materia();
		$nombre = substr($materia->nombre,0,30);
		$hojas = $this->getHojas();
		if(array_key_exists(utf8_decode($nombre), $hojas)){
			$h = $hojas[$nombre];
		}else{
			$cols = array (5,10,20,70,5,5,5,5,5,5,5,5,5,12,12);

			$rows[8] = 40;
				
			$h = $this->agregar_hoja($nombre, $rows, $cols);

		}
		$this->contenido($h);
		$this->propiedades($h);
	}

	public function hoja_vacia($msj){
		$nombre = 'HEKADEMOS';
		$h = $this->agregar_hoja($nombre);
		$h->xls->write(0, 0, $msj);
	}


	public function contenido(&$h){
		$alumnos		=	$this->curso->alumnosInscritos();
		$parciales		=	$this->curso->parciales();
		$calificaciones	=	$this->curso->calificaciones();
		$faltas			=	$this->curso->faltas();
		
		$this->encabezado($h);
		$st = $this->getEstilos();
		
		$h->nueva_linea();
		$h->cc += 1;
		$h->xls->write($h->rr, $h->cc, "No.", $st['TH.BGDarkgrayCenter']);
		$h->xls->write($h->rr + 1, $h->cc, "", $st['TH.BGDarkgrayCenter']);
		$h->xls->mergeCells($h->rr + 1, $h->cc, $h->rr, $h->cc);
		
		$h->cc++;
		$h->xls->write($h->rr, $h->cc, "No. de control", $st['TH.BGDarkgrayCenter']);
		$h->xls->write($h->rr + 1, $h->cc, "", $st['TH.BGDarkgrayCenter']);
		$h->xls->mergeCells($h->rr + 1, $h->cc, $h->rr, $h->cc);
		
		$h->cc++;
		$h->xls->write($h->rr, $h->cc, "Nombre", $st['TH.BGDarkgrayCenter']);
		$h->xls->write($h->rr + 1, $h->cc, "", $st['TH.BGDarkgrayCenter']);
		$h->xls->mergeCells($h->rr + 1, $h->cc, $h->rr, $h->cc);
		$h->cc++;
		
		$h->xls->write($h->rr, $h->cc, utf8_decode("Calificación"), $st['TH.BGDarkgrayCenter']);
		$h->xls->write($h->rr, $h->cc + 1, "", $st['TH.BGDarkgrayCenter']);
		$h->xls->write($h->rr, $h->cc + 2, "", $st['TH.BGDarkgrayCenter']);
		$h->xls->write($h->rr, $h->cc + 3, "", $st['TH.BGDarkgrayCenter']);
		$h->xls->write($h->rr, $h->cc + 4, "", $st['TH.BGDarkgrayCenter']);
		$h->xls->write($h->rr, $h->cc + 5, "", $st['TH.BGDarkgrayCenter']);
		$h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc + 5);
		
		$h->xls->writeString($h->rr + 1, $h->cc, "1", $st['TH.BGDarkgrayCenter']);
		$h->xls->writeString($h->rr + 1, $h->cc + 1, "2", $st['TH.BGDarkgrayCenter']);
		$h->xls->writeString($h->rr + 1, $h->cc + 2, "3", $st['TH.BGDarkgrayCenter']);
		$h->xls->write($h->rr + 1, $h->cc + 3, "R1", $st['TH.BGDarkgrayCenter']);
		$h->xls->write($h->rr + 1, $h->cc + 4, "R2", $st['TH.BGDarkgrayCenter']);
		$h->xls->write($h->rr + 1, $h->cc + 5, "R3", $st['TH.BGDarkgrayCenter']);
		
		$h->cc+=6;
		$h->xls->write($h->rr, $h->cc, "Faltas", $st['TH.BGDarkgrayCenter']);
		$h->xls->write($h->rr, $h->cc + 1, "", $st['TH.BGDarkgrayCenter']);
		$h->xls->write($h->rr, $h->cc + 2, "", $st['TH.BGDarkgrayCenter']);
		$h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc + 2);
		
		
		$h->xls->writeString($h->rr + 1, $h->cc, "1", $st['TH.BGDarkgrayCenter']);
		$h->xls->writeString($h->rr + 1, $h->cc + 1, "2", $st['TH.BGDarkgrayCenter']);
		$h->xls->writeString($h->rr + 1, $h->cc + 2, "3", $st['TH.BGDarkgrayCenter']);
		
		$h->cc+=3;
		$h->xls->write($h->rr, $h->cc, "Total faltas", $st['TH.BGDarkgrayCenter']);
		$h->xls->write($h->rr + 1, $h->cc, "", $st['TH.BGDarkgrayCenter']);
		$h->xls->mergeCells($h->rr + 1, $h->cc, $h->rr, $h->cc);
		
		$h->cc++;
		$h->xls->write($h->rr, $h->cc, "Prom final", $st['TH.BGDarkgrayCenter']);
		$h->xls->write($h->rr + 1, $h->cc, "", $st['TH.BGDarkgrayCenter']);
		$h->xls->mergeCells($h->rr + 1, $h->cc, $h->rr, $h->cc);
		
		$h->nueva_linea();
		$i = 1;
		foreach($alumnos as $alumno){
			$h->nueva_linea();
			$h->cc++;
			$h->xls->writeString($h->rr, $h->cc, $i, $st['TD.Normal']); $h->cc++;
			$h->xls->writeString($h->rr, $h->cc, $alumno->codigo, $st['TD.Normal']); $h->cc++;
			$h->xls->write($h->rr, $h->cc, utf8_decode($alumno->nombre()), $st['TD.Normal']); $h->cc++;
			
			$h->xls->write($h->rr, $h->cc, $parciales[$alumno->id][1], $st['TD.Normal']); $h->cc++;
			$h->xls->write($h->rr, $h->cc, $parciales[$alumno->id][2], $st['TD.Normal']); $h->cc++;
			$h->xls->write($h->rr, $h->cc, $parciales[$alumno->id][3], $st['TD.Normal']); $h->cc++;
			
			
			$h->xls->write($h->rr, $h->cc, "", $st['TD.Normal']); $h->cc++;
			$h->xls->write($h->rr, $h->cc, "", $st['TD.Normal']); $h->cc++;
			$h->xls->write($h->rr, $h->cc, "", $st['TD.Normal']); $h->cc++;
			$h->xls->write($h->rr, $h->cc, "", $st['TD.Normal']); $h->cc++;
			$h->xls->write($h->rr, $h->cc, "", $st['TD.Normal']); $h->cc++;
			$h->xls->write($h->rr, $h->cc, "", $st['TD.Normal']); $h->cc++;
			
			$ft = $faltas[$alumno->id];
			if($ft == 0)
				$ft = "-";
				
			$h->xls->write($h->rr, $h->cc, $ft, $st['TD.Normal']); $h->cc++;
			$h->xls->write($h->rr, $h->cc, $calificaciones[$alumno->id]["ORD"]["valor"], $st['TD.Normal']);
			
			$i++;
			
		}
		
		$this->fondo($h);
		
		$h->cc_max = $h->cc;
		$h->rr_max=$h->rr;
		
	}
	
	public function fondo(&$h){
		$st = $this->getEstilos();
		
		$h->nueva_linea();
		$h->nueva_linea();
		$h->nueva_linea();
		
		$h->cc++;
		$h->xls->write($h->rr, $h->cc, "ETAPA", $st['TH.BGDarkgrayCenter']);$h->cc++;
		$h->xls->write($h->rr, $h->cc, "", $st['TH.BGDarkgrayCenter']);
		$h->xls->mergeCells($h->rr, $h->cc - 1, $h->rr, $h->cc);
		$h->cc++;
		
		$h->xls->write($h->rr, $h->cc, "FECHA", $st['TH.BGDarkgrayCenter']);$h->cc++;
		$h->xls->write($h->rr, $h->cc, "FIRMA DEL PROFESOR", $st['TH.BGDarkgrayCenter']);
		for($i=0; $i<5; $i++){
			$h->cc++;
			$h->xls->write($h->rr, $h->cc, "", $st['TH.BGDarkgrayCenter']);
		}
		$h->xls->mergeCells($h->rr, $h->cc-5, $h->rr, $h->cc);
		
		$h->cc++;
		$h->xls->write($h->rr, $h->cc, "OBSERVACIONES", $st['TH.BGDarkgrayCenter']);
		for($i=0; $i<4; $i++){
			$h->cc++;
			$h->xls->write($h->rr, $h->cc, "", $st['TH.BGDarkgrayCenter']);
		}
		$h->xls->mergeCells($h->rr, $h->cc-4, $h->rr, $h->cc);
		
		$h->nueva_linea();
		
		$h->nueva_linea();
		
		$h->cc++;
		$h->xls->write($h->rr, $h->cc, "PRIMER PARCIAL", $st['TH.BGGrayBoldLeft']);$h->cc++;
		$h->xls->write($h->rr, $h->cc, "", $st['TH.BGGrayBoldLeft']);
		$h->xls->mergeCells($h->rr, $h->cc - 1, $h->rr, $h->cc);
		$h->cc++;
		
		$h->xls->write($h->rr, $h->cc, "", $st['TH.BGGrayBoldLeft']);$h->cc++;
		$h->xls->write($h->rr, $h->cc, "", $st['TH.BGGrayBoldLeft']);
		for($i=0; $i<5; $i++){
			$h->cc++;
			$h->xls->write($h->rr, $h->cc, "", $st['TH.BGGrayBoldLeft']);
		}
		$h->xls->mergeCells($h->rr, $h->cc-5, $h->rr, $h->cc);
		
		$h->cc++;
		$h->xls->write($h->rr, $h->cc, "", $st['TH.BGGrayBoldLeft']);
		for($i=0; $i<4; $i++){
			$h->cc++;
			$h->xls->write($h->rr, $h->cc, "", $st['TH.BGGrayBoldLeft']);
		}
		$h->xls->mergeCells($h->rr, $h->cc-4, $h->rr, $h->cc);
		
		$h->nueva_linea();
		
		$h->cc++;
		$h->xls->write($h->rr, $h->cc, "SEGUNDO PARCIAL", $st['TH.BGGrayBoldLeft']);$h->cc++;
		$h->xls->write($h->rr, $h->cc, "", $st['TH.BGGrayBoldLeft']);
		$h->xls->mergeCells($h->rr, $h->cc - 1, $h->rr, $h->cc);
		$h->cc++;
		
		$h->xls->write($h->rr, $h->cc, "", $st['TH.BGGrayBoldLeft']);$h->cc++;
		$h->xls->write($h->rr, $h->cc, "", $st['TH.BGGrayBoldLeft']);
		for($i=0; $i<5; $i++){
			$h->cc++;
			$h->xls->write($h->rr, $h->cc, "", $st['TH.BGGrayBoldLeft']);
		}
		$h->xls->mergeCells($h->rr, $h->cc-5, $h->rr, $h->cc);
		
		$h->cc++;
		$h->xls->write($h->rr, $h->cc, "", $st['TH.BGGrayBoldLeft']);
		for($i=0; $i<4; $i++){
			$h->cc++;
			$h->xls->write($h->rr, $h->cc, "", $st['TH.BGGrayBoldLeft']);
		}
		$h->xls->mergeCells($h->rr, $h->cc-4, $h->rr, $h->cc);
		
		$h->nueva_linea();
		
		$h->cc++;
		$h->xls->write($h->rr, $h->cc, "TERCER PARCIAL", $st['TH.BGGrayBoldLeft']);$h->cc++;
		$h->xls->write($h->rr, $h->cc, "", $st['TH.BGGrayBoldLeft']);
		$h->xls->mergeCells($h->rr, $h->cc - 1, $h->rr, $h->cc);
		$h->cc++;
		
		$h->xls->write($h->rr, $h->cc, "", $st['TH.BGGrayBoldLeft']);$h->cc++;
		$h->xls->write($h->rr, $h->cc, "", $st['TH.BGGrayBoldLeft']);
		for($i=0; $i<5; $i++){
			$h->cc++;
			$h->xls->write($h->rr, $h->cc, "", $st['TH.BGGrayBoldLeft']);
		}
		$h->xls->mergeCells($h->rr, $h->cc-5, $h->rr, $h->cc);
		
		$h->cc++;
		$h->xls->write($h->rr, $h->cc, "", $st['TH.BGGrayBoldLeft']);
		for($i=0; $i<4; $i++){
			$h->cc++;
			$h->xls->write($h->rr, $h->cc, "", $st['TH.BGGrayBoldLeft']);
		}
		$h->xls->mergeCells($h->rr, $h->cc-4, $h->rr, $h->cc);
		
		$h->nueva_linea();
		
		$h->cc++;
		$h->xls->write($h->rr, $h->cc, utf8_decode("RECUPERACIÓN 1"), $st['TH.BGGrayBoldLeft']);$h->cc++;
		$h->xls->write($h->rr, $h->cc, "", $st['TH.BGGrayBoldLeft']);
		$h->xls->mergeCells($h->rr, $h->cc - 1, $h->rr, $h->cc);
		$h->cc++;
		
		$h->xls->write($h->rr, $h->cc, "", $st['TH.BGGrayBoldLeft']);$h->cc++;
		$h->xls->write($h->rr, $h->cc, "", $st['TH.BGGrayBoldLeft']);
		for($i=0; $i<5; $i++){
			$h->cc++;
			$h->xls->write($h->rr, $h->cc, "", $st['TH.BGGrayBoldLeft']);
		}
		$h->xls->mergeCells($h->rr, $h->cc-5, $h->rr, $h->cc);
		
		$h->cc++;
		$h->xls->write($h->rr, $h->cc, "", $st['TH.BGGrayBoldLeft']);
		for($i=0; $i<4; $i++){
			$h->cc++;
			$h->xls->write($h->rr, $h->cc, "", $st['TH.BGGrayBoldLeft']);
		}
		$h->xls->mergeCells($h->rr, $h->cc-4, $h->rr, $h->cc);

		$h->nueva_linea();
		
		$h->cc++;
		$h->xls->write($h->rr, $h->cc, utf8_decode("RECUPERACIÓN 2"), $st['TH.BGGrayBoldLeft']);$h->cc++;
		$h->xls->write($h->rr, $h->cc, "", $st['TH.BGGrayBoldLeft']);
		$h->xls->mergeCells($h->rr, $h->cc - 1, $h->rr, $h->cc);
		$h->cc++;
		
		$h->xls->write($h->rr, $h->cc, "", $st['TH.BGGrayBoldLeft']);$h->cc++;
		$h->xls->write($h->rr, $h->cc, "", $st['TH.BGGrayBoldLeft']);
		for($i=0; $i<5; $i++){
			$h->cc++;
			$h->xls->write($h->rr, $h->cc, "", $st['TH.BGGrayBoldLeft']);
		}
		$h->xls->mergeCells($h->rr, $h->cc-5, $h->rr, $h->cc);
		
		$h->cc++;
		$h->xls->write($h->rr, $h->cc, "", $st['TH.BGGrayBoldLeft']);
		for($i=0; $i<4; $i++){
			$h->cc++;
			$h->xls->write($h->rr, $h->cc, "", $st['TH.BGGrayBoldLeft']);
		}
		$h->xls->mergeCells($h->rr, $h->cc-4, $h->rr, $h->cc);
		
		$h->nueva_linea();
		
		$h->cc++;
		$h->xls->write($h->rr, $h->cc, utf8_decode("RECUPERACIÓN 3"), $st['TH.BGGrayBoldLeft']);$h->cc++;
		$h->xls->write($h->rr, $h->cc, "", $st['TH.BGGrayBoldLeft']);
		$h->xls->mergeCells($h->rr, $h->cc - 1, $h->rr, $h->cc);
		$h->cc++;
		
		$h->xls->write($h->rr, $h->cc, "", $st['TH.BGGrayBoldLeft']);$h->cc++;
		$h->xls->write($h->rr, $h->cc, "", $st['TH.BGGrayBoldLeft']);
		for($i=0; $i<5; $i++){
			$h->cc++;
			$h->xls->write($h->rr, $h->cc, "", $st['TH.BGGrayBoldLeft']);
		}
		$h->xls->mergeCells($h->rr, $h->cc-5, $h->rr, $h->cc);
		
		$h->cc++;
		$h->xls->write($h->rr, $h->cc, "", $st['TH.BGGrayBoldLeft']);
		for($i=0; $i<4; $i++){
			$h->cc++;
			$h->xls->write($h->rr, $h->cc, "", $st['TH.BGGrayBoldLeft']);
		}
		$h->xls->mergeCells($h->rr, $h->cc-4, $h->rr, $h->cc);
		
	}


	public function encabezado(&$h) {
		$grupo    	   	=	$this->curso->grupo();
		$materia	   	=	$this->curso->materia();
		$profesor	 	=	$this->curso->profesor();
		$ciclo    	   	=	$grupo->ciclo();
		
		
		$st = $this->getEstilos();
		$template=new Template();
		$h->cc++;
		$h->xls->insertBitmap($h->rr, $h->cc, getcwd() . '/public/img/sp5/lista.bmp', 0, 0, 1.5, 1);
		$h->cc++;
		$h->xls->write($h->rr, $h->cc, "COLEGIO DE ESTUDIOS CIENTIFICOS Y ", $st['H1']);
		for($i=0; $i<9; $i++){
			$h->cc++;
			$h->xls->write($h->rr, $h->cc, "", $st['H1']);
		}
		$h->xls->mergeCells($h->rr, $h->cc-9, $h->rr, $h->cc);
		
		$h->nueva_linea();
		$h->xls->write($h->rr, $h->cc, "                                     TECNOLOGICOS DEL ESTADO DE JALISCO", $st['H1']);
		for($i=0; $i<9; $i++){
			$h->cc++;
			$h->xls->write($h->rr, $h->cc, "", $st['H1']);
		}
		$h->xls->mergeCells($h->rr, $h->cc-9, $h->rr, $h->cc);
		
		
		$h->cc++;
		$h->xls->write($h->rr, $h->cc, "SCA-F06M01-8.2", $st['H1']);
		for($i=0; $i<4; $i++){
			$h->cc++;
			$h->xls->write($h->rr, $h->cc, "", $st['H1']);
		}
		$h->xls->mergeCells($h->rr, $h->cc-4, $h->rr, $h->cc);
		
		
		$h->nueva_linea();
		$h->nueva_linea();
		$h->nueva_linea();
		$h->nueva_linea();
		
		$h->nueva_linea();
		$h->cc += 1;
		$h->xls->write($h->rr, $h->cc, "", $st['H3.BGGrayLeft']);
		$h->cc += 1;
		$h->xls->write($h->rr, $h->cc, "PLANTEL", $st['H3.BGGrayLeft']);$h->cc++;
		$h->xls->write($h->rr, $h->cc, "HEKADEMOS", $st['H3.BGGrayLeft']);
		
		$h->cc += 1;
		$h->xls->write($h->rr, $h->cc, "PERIODO ESCOLAR", $st['H3.BGGrayLeft']);
		for($i=0; $i<4; $i++){
			$h->cc++;
			$h->xls->write($h->rr, $h->cc, "", $st['H3.BGGrayLeft']);
		}
		$h->xls->mergeCells($h->rr, $h->cc-4, $h->rr, $h->cc);
		
		$h->cc++;
		$h->xls->write($h->rr, $h->cc, $ciclo->numero, $st['H3.BGGrayLeft']);
		for($i=0; $i<5; $i++){
			$h->cc++;
			$h->xls->write($h->rr, $h->cc, "", $st['H3.BGGrayLeft']);
		}
		$h->xls->mergeCells($h->rr, $h->cc-5, $h->rr, $h->cc);
		
		
		
		$h->nueva_linea();
		$h->cc += 1;
		$h->xls->write($h->rr, $h->cc, "", $st['H3.BGGrayLeft']);
		$h->cc += 1;
		$h->xls->write($h->rr, $h->cc, "CARRERA", $st['H3.BGGrayLeft']);$h->cc++;
		$h->xls->write($h->rr, $h->cc, $grupo->verOferta(), $st['H3.BGGrayLeft']);
		
		$h->cc += 1;
		$h->xls->write($h->rr, $h->cc, "GRADO", $st['H3.BGGrayLeft']);
		for($i=0; $i<4; $i++){
			$h->cc++;
			$h->xls->write($h->rr, $h->cc, "", $st['H3.BGGrayLeft']);
		}
		$h->xls->mergeCells($h->rr, $h->cc-4, $h->rr, $h->cc);
		
		$h->cc++;
		
		$h->xls->writeString($h->rr, $h->cc, $grupo->grado, $st['H3.BGGrayLeft']);
		for($i=0; $i<5; $i++){
			$h->cc++;
			$h->xls->write($h->rr, $h->cc, "", $st['H3.BGGrayLeft']);
		}
		$h->xls->mergeCells($h->rr, $h->cc-5, $h->rr, $h->cc);
		
		
		
		$h->nueva_linea();
		$h->cc += 1;
		$h->xls->write($h->rr, $h->cc, "", $st['H3.BGGrayLeft']);
		$h->cc += 1;
		$h->xls->write($h->rr, $h->cc, "ASIGNATURA", $st['H3.BGGrayLeft']);$h->cc++;
		$h->xls->write($h->rr, $h->cc, utf8_decode($this->curso->verMateriaNombre()), $st['H3.BGGrayLeft']);
		
		$h->cc += 1;
		$h->xls->write($h->rr, $h->cc, "GRUPO", $st['H3.BGGrayLeft']);
		for($i=0; $i<4; $i++){
			$h->cc++;
			$h->xls->write($h->rr, $h->cc, "", $st['H3.BGGrayLeft']);
		}
		$h->xls->mergeCells($h->rr, $h->cc-4, $h->rr, $h->cc);
		$h->cc++;
		$h->xls->write($h->rr, $h->cc, $grupo->letra, $st['H3.BGGrayLeft']);
		for($i=0; $i<5; $i++){
			$h->cc++;
			$h->xls->write($h->rr, $h->cc, "", $st['H3.BGGrayLeft']);
		}
		$h->xls->mergeCells($h->rr, $h->cc-5, $h->rr, $h->cc);
		
		
		$h->nueva_linea();
		$h->cc += 1;
		$h->xls->write($h->rr, $h->cc, "", $st['H3.BGGrayLeft']);
		$h->cc += 1;
		$h->xls->write($h->rr, $h->cc, "PROFESOR", $st['H3.BGGrayLeft']);$h->cc++;
		$h->xls->write($h->rr, $h->cc, utf8_decode($this->curso->verProfesor()), $st['H3.BGGrayLeft']);
		
				
		$h->cc += 1;
		$h->xls->write($h->rr, $h->cc, "TURNO", $st['H3.BGGrayLeft']);
		for($i=0; $i<4; $i++){
			$h->cc++;
			$h->xls->write($h->rr, $h->cc, "", $st['H3.BGGrayLeft']);
		}
		$h->xls->mergeCells($h->rr, $h->cc-4, $h->rr, $h->cc);
		$h->cc++;
		$h->xls->write($h->rr, $h->cc, $grupo->verTurno(), $st['H3.BGGrayLeft']);
		
		for($i=0; $i<5; $i++){
			$h->cc++;
			$h->xls->write($h->rr, $h->cc, "", $st['H3.BGGrayLeft']);
		}
		$h->xls->mergeCells($h->rr, $h->cc-5, $h->rr, $h->cc);
		
		
		$h->nueva_linea();
		$h->cc += 1;
		$h->xls->write($h->rr, $h->cc, "REPORTE DE EVALUACIONES PARCIALES ", $st['H1']);
		
		for($i=0; $i<13; $i++){
			$h->cc++;
			$h->xls->write($h->rr, $h->cc, "", $st['H1']);
			$h->xls->write($h->rr + 1, $h->cc, "", $st['H1']);
		}
		
		$h->xls->mergeCells($h->rr, $h->cc-13, $h->rr + 1, $h->cc);
		
		$h->nueva_linea();
		$h->xls->repeatRows(0,14);
		$h->xls->freezePanes(array(14, 0));
		
	}

	public function propiedades(&$h){
		$h->xls->centerHorizontally();
		$h->xls->hideGridlines();
		$h->xls->printArea(0, 0, $h->rr_max, $h->cc_max);
		$h->xls->setPortrait();
		$h->xls->setMargins_LR(0.8);
		$h->xls->setMargins_TB(0.4);
		$h->xls->setHeader('', 0);
		$h->xls->setFooter('', 0);
		$h->xls->setPaper(3);
		$h->xls->setPrintScale(65);
		$h->xls->setZoom(65);
	}
}
?>
