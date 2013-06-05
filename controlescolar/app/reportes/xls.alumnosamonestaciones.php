<?php
class XLSAlumnosamonestaciones extends Reporte{
	private $condicion;
	private $ciclo;
	private $alum;
	private $general;

	public function XLSAlumnosamonestaciones($ciclo_id, $id){
		$controlador='alumnos';
		$accion='amonestaciones/'.$id;
		$ciclo_id = Session :: get_data('ciclo.id');
		$b = new Busqueda($controlador, $accion);
		$b->establecerCondicion('fecha', "fecha = '" . Utils::convierteFechaMySql($b->campo('fecha')) . "' ");
		$b->establecerCondicion('aestado_id', "aestado_id = '" . $b->campo('aestado_id') . "' ");
		$b->quitarCondicion('ciclo');
		$b->campos();
		// genera las condiciones
		$c = $b->condicion();
		/*
		var_dump("SELECT count(*) FROM
								amonestacion
								INNER JOIN alumnos ON amonestacion.alumnos_id = alumnos.id
								WHERE ".($c == "" ? " aestado_id='2' AND ciclos_id='".$ciclo_id."' AND alumnos_id='".$id."' " : " aestado_id='2' AND ciclos_id='".$ciclo_id."' AND alumnos_id='".$id."' AND ".$c));exit;
								*/
		$amonestaciones = new Amonestacion();
		$por_ciclo= $amonestaciones->count_by_sql(
						"SELECT count(DISTINCT(amonestacion.id)) FROM
							 amonestacion
							INNER JOIN amonestados ON amonestacion.id = amonestados.amonestacion_id
							INNER JOIN alumnos ON amonestados.alumnos_id = alumnos.id 
								WHERE ".
				($c == "" ? " amonestacion.aestado_id='2' AND amonestacion.ciclos_id='".$ciclo_id."' AND amonestados.alumnos_id='".$id."' " : " amonestacion.aestado_id='2' AND amonestacion.ciclos_id='".$ciclo_id."' AND amonestados.alumnos_id='".$id."' AND ".$c));
		
		$amonestaciones = new Amonestacion();
		$gen = $amonestaciones->count_by_sql("SELECT count(amonestados.id) FROM
								amonestados
								INNER JOIN alumnos ON amonestados.alumnos_id = alumnos.id
								INNER JOIN amonestacion ON amonestados.amonestacion_id = amonestacion_id
								WHERE  ".
		($c == "" ? " aestado_id='2' AND alumnos_id='".$id."' " : " aestado_id='2' AND alumnos_id='".$id."' AND ".$c)." ORDER BY fecha DESC ");
		//var_dump($gen);exit;
		$ciclo=new Ciclos();
		$this->ciclo=$ciclo=$ciclo->find_first($ciclo_id);
		$alumno = new Alumnos();
		$this->alum = $alumno = $alumno->find($id);
		$this->Reporte($alumno->nombre.' '.$alumno->ap.' '.$alumno->am. ' Amonestaciones' . '  ' . ($c != '' ? ' FILTRADO' : '' ). '.xls');
		
		if($por_ciclo > 0 || $gen > 0){
			if($por_ciclo  > 0){
				$amonestaciones = new Amonestacion();
				$amonestaciones = $amonestaciones->find_all_by_sql("SELECT amonestacion.*,alumnos.id as alumno_id FROM
								amonestados
								INNER JOIN amonestacion ON amonestados.amonestacion_id = amonestacion.id
								INNER JOIN alumnos ON amonestados.alumnos_id = alumnos.id
								WHERE ".
				($c == "" ? " aestado_id='2' AND ciclos_id='".$ciclo_id."' AND alumnos_id='".$id."' " : " aestado_id='2' AND ciclos_id='".$ciclo_id."' AND alumnos_id='".$id."' AND ".$c)." ORDER BY fecha DESC ");
				//var_dump($amonestaciones);exit;
				$this->hoja($amonestaciones);
			}else{
				$this->hoja_vacia();
			}
			
			if($gen > 0){
				$amonestaciones = new Amonestacion();
				$amonestaciones = $amonestaciones->find_all_by_sql("SELECT amonestacion.*,alumnos.id as alumno_id FROM
								amonestados
								INNER JOIN amonestacion ON amonestados.amonestacion_id = amonestacion.id
								INNER JOIN alumnos ON amonestados.alumnos_id = alumnos.id
								WHERE ".
				($c == "" ? " aestado_id='2' AND alumnos_id='".$id."' " : " aestado_id='2' AND alumnos_id='".$id."' AND ".$c)." ORDER BY fecha DESC ");

				$this->general = true;
				
				$this->hoja($amonestaciones);
				
			}else{
				$this->hoja_vacia();
			}
			
		}else{
			$this->hoja_vacia();
		}
		
		$hojas = $this->getHojas();
		if(count($hojas)==0)
		$this->hoja_vacia();


	}

	public function hoja($amonestaciones){
		if(!$this->general)
		$nombre = $this->ciclo->numero;
		else
		$nombre = 'General';
		$hojas = $this->getHojas();
		if(array_key_exists($nombre, $hojas)){
			$h = $hojas[$nombre];
		}else{
				if(!$this->general)
					$cols = array( 20, 15, 50, 50);
				else
					$cols = array( 10, 20, 15, 50, 50);
			$h = $this->agregar_hoja($nombre, null, $cols);
			$h->cc_max = 16;
		}
		$this->contenido($h, $amonestaciones);
		$this->propiedades($h);
	}

	public function hoja_vacia(){
		$nombre = 'HEKADEMOS';
		$h = $this->agregar_hoja($nombre);
		$h->xls->write(0, 0, "No hay registros que coincidan con esas condiciones");
	}

	public function contenido(&$h, $amonestaciones){
		$this->encabezado($h,$grupo);
		$st = $this->getEstilos();
		if($this->general){
			$h->xls->write($h->rr, $h->cc, 'Ciclo', $st['TD.BGYellow']); 
			$h->cc++;
		}
		$h->xls->write($h->rr, $h->cc, 'Tipo', $st['TD.BGYellow']); $h->cc++;
		$h->xls->write($h->rr, $h->cc, 'Fecha', $st['TD.BGYellow']); $h->cc++;
		$h->xls->write($h->rr, $h->cc, utf8_decode('Descripción'), $st['TD.BGYellow']); $h->cc++;
		$h->xls->write($h->rr, $h->cc, utf8_decode('Infracción'), $st['TD.BGYellow']); $h->cc++;
		$h->nueva_linea();
		$n = 0;
		$categorias = new Acategoria();
		$salto = '
';
		foreach($amonestaciones as $amonestacion){
			$infracciones_str = '';
			$categorias = $categorias->find($amonestacion->acategoria_id);
			$infracciones = new Reglamento();
			$infracciones = $infracciones->find_all_by_sql('SELECT reglamento.* FROM '.
				'amonestacion INNER JOIN infringe ON amonestacion.id = infringe.amonestacion_id '.
				'INNER JOIN reglamento on infringe.reglamento_id = reglamento.id '.
				'WHERE amonestacion.id = '.$amonestacion->id);
			if(count($infracciones) > 0){
			foreach($infracciones as $infraccion){
				if($infraccion->id != ''){
					$reglamento = new Reglamentos();
					$articulo = new Articulo();
					$reglamento = $reglamento->find($infraccion->reglamentos_id);
					$articulo = $articulo->find($infraccion->articulo_id);
					$infracciones_str .= 'Articulo '.$articulo->numero.' del reglamento '.$reglamento->nombre.$salto;
				}
			}
			}else{
				$infracciones_str = 'No asignado.';
			}
			$n++;
			$td = ($n%2 == 0 ? 'Par' : '');
			if($this->general){
				$cicl = new Ciclos();
				$ciclo = $cicl->find($amonestacion->ciclos_id);
				$h->xls->writeString($h->rr, $h->cc,utf8_decode($cicl->numero), $st['TD' . $td . '.Normal']); $h->cc++;
			}
			$h->xls->writeString($h->rr, $h->cc,utf8_decode($categorias->nombre), $st['TD' . $td . '.Normal']); $h->cc++;
			$h->xls->write($h->rr, $h->cc, utf8_decode(Utils :: fecha_mix($amonestacion->fecha)), $st['TD' . $td . '.Normal']);$h->cc++;
			$h->xls->write($h->rr, $h->cc, utf8_decode($amonestacion->descripcion), $st['TD' . $td . '.Normal']);$h->cc++;
			$h->xls->write($h->rr, $h->cc, utf8_decode($infracciones_str != '' ? $infracciones_str : 'No asignado.'), $st['TD' . $td . '.Normal']);$h->cc++;
			$h->cc++;
			$h->nueva_linea();
		}

		$h->nueva_linea();
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
		$h->xls->write($h->rr, $h->cc, $alumno->nombre.' '.$alumno->ap.' '.$alumno->am. ' Amonestaciones', $st['H4']);
		$h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc + 6);
		$h->nueva_linea();
		if(!$this->general){
			$h->xls->write($h->rr, $h->cc, " Ciclo ". strToUpper($this->ciclo->numero), $st['H4']);
			$h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc + 6);
			$h->nueva_linea();
		}else{
			$h->xls->write($h->rr, $h->cc, 'General.', $st['H4']);
			$h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc + 6);
			$h->nueva_linea();
		}
		$h->nueva_linea();
		$h->nueva_linea();
		$h->xls->repeatRows(0,8);
		$h->xls->freezePanes(array(9, 0));
	}

	public function propiedades(&$h){
		$h->xls->centerHorizontally();
		$h->xls->hideGridlines();
		$h->xls->printArea(0, 1, $h->rr_max, $h->cc_max);
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