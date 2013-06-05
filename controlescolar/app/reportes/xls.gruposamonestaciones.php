<?php
class XLSGruposamonestaciones extends Reporte{
	private $condicion;
	private $ciclo;
	private $categorias;
	private $amon;
	private $proc;
	private $suspension;
	private $grupal;
	private $total;
	

	public function XLSGruposamonestaciones($ciclo_id){
		$controlador='grupos';
		$accion='index';
		$ciclo=new Ciclos();
		$this->ciclo=$ciclo=$ciclo->find_first($ciclo_id);
		//var_dump($this->ciclo);exit;
		$cat = new Acategoria();
		$this->categorias = $cat->find();
		$b = new Busqueda($controlador, $accion);
		$c = $b->condicion(array('oferta_id'));
		// genera las condiciones
		//$c1 = $c = $b->condicion();
		$c .= ($c == '' ? '' : 'AND ') . "ciclos_id = '" . $ciclo_id . "'";
		$grupos = new Grupos();
		$grupos = $grupos->find(
							'conditions: ' . ($c == "" ? "1" : $c),
							'order: turno, grado, letra'
						  );
		$this->Reporte('Amonestaciones grupales' . '  ' . ($c != '' ? ' FILTRADO' : '' ). '.xls');
		if(count($grupos) > 0){
			foreach($grupos as $grupo){
				$this->hoja_ciclo($grupo);
				$this->hoja_general($grupo);
			}
		}else{
			$this->hoja_vacia();
		}
		
		

	}

	public function hoja_ciclo($grupo){
		$oferta = new Oferta();
		$oferta = $oferta->find($grupo->oferta_id);
		$nombre = $grupo->grado.$grupo->letra.' '.$grupo->turno.' '.$this->ciclo->numero.' '.$oferta->clave;
		$hojas = $this->getHojas();
		if(array_key_exists($nombre, $hojas)){
			$h = $hojas[$nombre];
		}else{
			$cols = array( 15, 50, 20, 20, 20, 20
			);
			$h = $this->agregar_hoja($nombre, null, $cols);
			$h->cc_max = 16;
		}
		$this->contenido_ciclo($h, $grupo);
		$this->propiedades($h);
	}
	
	public function hoja_general($grupo){
		$oferta = new Oferta();
		$oferta = $oferta->find($grupo->oferta_id);
		$nombre = $grupo->grado.$grupo->letra.' '.$grupo->turno.' '.$oferta->clave;
		$hojas = $this->getHojas();
		if(array_key_exists($nombre, $hojas)){
			$h = $hojas[$nombre];
		}else{
			$cols = array(15, 50, 20, 20, 20, 20
			);
			$h = $this->agregar_hoja($nombre, null, $cols);
			$h->cc_max = 16;
		}
		$this->contenido_general($h, $grupo);
		$this->propiedades($h);
	}

	public function hoja_vacia(){
		$nombre = 'HEKADEMOS';
		$h = $this->agregar_hoja($nombre);
		$h->xls->write(0, 0, "No hay registros que coincidan con esas condiciones");
	}

	public function contenido_general(&$h, $grupo){
		$this->encabezado($h,$grupo);
		$st = $this->getEstilos();
		$h->xls->write($h->rr, $h->cc, 'Codigo', $st['TD.BGYellow']); $h->cc++;
		$h->xls->write($h->rr, $h->cc, 'Nombre', $st['TD.BGYellow']); $h->cc++;
		foreach($this->categorias as $categoria){
			$h->xls->write($h->rr, $h->cc, utf8_decode($categoria->nombre), $st['TD.BGYellow']); $h->cc++;			
		}
		$h->xls->write($h->rr, $h->cc, 'Total', $st['TD.BGYellow']); $h->cc++;
		$h->nueva_linea();
		$n = 0;
		$salto = '
';
		$this->amon = 0;
		$this->proc = 0;
		$this->suspension = 0;
		$this->grupal = 0;
		$this->total = 0;
		$alumnosgrupo = new Alumnosgrupo();
		$alumnosgrupo = $alumnosgrupo->find('grupos_id = '.$grupo->id);
		$alumno = new Alumnos();
		$amonestacion = new Amonestacion();
		foreach($alumnosgrupo as $alumnogrupo){
			$total_alumno = 0;
			$alumno = $alumno->find($alumnogrupo->alumnos_id);
			$n++;
			$td = ($n%2 == 0 ? 'Par' : '');
			$h->xls->write($h->rr, $h->cc,utf8_decode($alumno->codigo), $st['TD' . $td . '.Normal']); $h->cc++;
			$h->xls->write($h->rr, $h->cc,utf8_decode($alumno->nombre.' '.$alumno->ap.' '.$alumno->am), $st['TD' . $td . '.Normal']); $h->cc++;
			foreach($this->categorias as $categoria){
				$num = $amonestacion->count_by_sql('SELECT count(distinct(amonestados.id)) FROM
								amonestados
								INNER JOIN amonestacion ON amonestados.amonestacion_id = amonestacion.id
								WHERE  amonestacion.acategoria_id = '.$categoria->id.' AND alumnos_id = '.$alumno->id);
				$total_alumno += $num;
				$h->xls->write($h->rr, $h->cc,($num > 0 ? $num : '-'), $st['TD' . $td . '.Normal']); $h->cc++;
				if($num > 0){
					$cat = new Acategoria();
					$cat = $cat->find();
					foreach($cat as $c){
						if($categoria->id == $c->id){
							if($c->nombre == 'Amonestación'){
								$this->amon += $num;
								$this->total += $num;
							}
							if($c->nombre == 'Procedimiento'){
								$this->proc += $num;
								$this->total += $num;
							}
							if($c->nombre == 'Suspensión'){	
								$this->suspension += $num;
								$this->total += $num;
							}
							if($c->nombre == 'Grupal'){
								$this->grupal += $num;
								$this->total += $num;
							}
							
						}
					}
				}
			}
			$h->xls->writeString($h->rr, $h->cc, ($total_alumno == 0 ? '-' : $total_alumno), $st['TD.BGYellow']); $h->cc++;
			$h->nueva_linea();
		}
		$h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc + 1);
		$h->xls->write($h->rr, $h->cc, 'Total', $st['TD.BGYellow']); $h->cc++;$h->cc++;
		$h->xls->writeString($h->rr, $h->cc, ($this->amon == 0 ? '-' : $this->amon), $st['TD.BGYellow']); $h->cc++;
		$h->xls->writeString($h->rr, $h->cc, ($this->proc == 0 ? '-' : $this->proc), $st['TD.BGYellow']); $h->cc++;
		$h->xls->writeString($h->rr, $h->cc, ($this->suspension == 0 ? '-' : $this->suspension), $st['TD.BGYellow']); $h->cc++;
		$h->xls->writeString($h->rr, $h->cc, ($this->grupal == 0 ? '-' : $this->grupal), $st['TD.BGYellow']); $h->cc++;
		$h->xls->writeString($h->rr, $h->cc, ($this->total == 0 ? '-' : $this->total), $st['TD.BGYellow']); $h->cc++;
		$h->nueva_linea();
		$h->rr_max = $h->rr;
	}
	
	public function contenido_ciclo(&$h, $grupo){
		$this->encabezado_ciclo($h,$grupo);
		$st = $this->getEstilos();
		$h->xls->write($h->rr, $h->cc, 'Codigo', $st['TD.BGYellow']); $h->cc++;
		$h->xls->write($h->rr, $h->cc, 'Nombre', $st['TD.BGYellow']); $h->cc++;
		foreach($this->categorias as $categoria){
			$h->xls->write($h->rr, $h->cc, utf8_decode($categoria->nombre), $st['TD.BGYellow']); $h->cc++;			
		}
		$h->xls->write($h->rr, $h->cc, 'Total', $st['TD.BGYellow']); $h->cc++;
		$h->nueva_linea();
		$n = 0;
		$salto = '
';
		$this->amon = 0;
		$this->proc = 0;
		$this->suspension = 0;
		$this->grupal = 0;
		$this->total = 0;
		$alumnosgrupo = new Alumnosgrupo();
		$alumnosgrupo = $alumnosgrupo->find('grupos_id = '.$grupo->id);
		$alumno = new Alumnos();
		$amonestacion = new Amonestacion();
		foreach($alumnosgrupo as $alumnogrupo){
			$total_alumno = 0;
			$alumno = $alumno->find($alumnogrupo->alumnos_id);
			$n++;
			$td = ($n%2 == 0 ? 'Par' : '');
			$h->xls->write($h->rr, $h->cc,utf8_decode($alumno->codigo), $st['TD' . $td . '.Normal']); $h->cc++;
			$h->xls->write($h->rr, $h->cc,utf8_decode($alumno->nombre.' '.$alumno->ap.' '.$alumno->am), $st['TD' . $td . '.Normal']); $h->cc++;
			foreach($this->categorias as $categoria){
				//$num = $amonestacion->count_by_sql('Select COUNT(*) FROM amonestacion WHERE acategoria_id = '.$categoria->id.' AND alumnos_id = '.$alumno->id.' AND ciclos_id = '.$this->ciclo->id);
				$num = $amonestacion->count_by_sql('SELECT count(distinct(amonestados.id)) FROM
								amonestados
								INNER JOIN amonestacion ON amonestados.amonestacion_id = amonestacion.id
								WHERE  amonestacion.acategoria_id = '.$categoria->id.' AND amonestados.alumnos_id = '.$alumno->id.' AND ciclos_id = '.$this->ciclo->id);
				//var_dump($num);exit;
				$total_alumno += $num;
				$h->xls->write($h->rr, $h->cc,($num > 0 ? $num : '-'), $st['TD' . $td . '.Normal']); $h->cc++;
				if($num > 0){
					$cat = new Acategoria();
					$cat = $cat->find();
					foreach($cat as $c){
						if($categoria->id == $c->id){
							if($c->nombre == 'Amonestación'){
								$this->amon += $num;
								$this->total += $num;
							}
							if($c->nombre == 'Procedimiento'){
								$this->proc += $num;
								$this->total += $num;
							}
							if($c->nombre == 'Suspensión'){	
								$this->suspension += $num;
								$this->total += $num;
							}
							if($c->nombre == 'Grupal'){
								$this->grupal += $num;
								$this->total += $num;
							}
							
						}
					}
				}
			}
			$h->xls->writeString($h->rr, $h->cc, ($total_alumno == 0 ? '-' : $total_alumno), $st['TD.BGYellow']); $h->cc++;
			$h->nueva_linea();
		}
		$h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc + 1);
		$h->xls->write($h->rr, $h->cc, 'Total', $st['TD.BGYellow']); $h->cc++;$h->cc++;
		$h->xls->writeString($h->rr, $h->cc, ($this->amon == 0 ? '-' : $this->amon), $st['TD.BGYellow']); $h->cc++;
		$h->xls->writeString($h->rr, $h->cc, ($this->proc == 0 ? '-' : $this->proc), $st['TD.BGYellow']); $h->cc++;
		$h->xls->writeString($h->rr, $h->cc, ($this->suspension == 0 ? '-' : $this->suspension), $st['TD.BGYellow']); $h->cc++;
		$h->xls->writeString($h->rr, $h->cc, ($this->grupal == 0 ? '-' : $this->grupal), $st['TD.BGYellow']); $h->cc++;
		$h->xls->writeString($h->rr, $h->cc, ($this->total == 0 ? '-' : $this->total), $st['TD.BGYellow']); $h->cc++;
		$h->nueva_linea();
		$h->rr_max = $h->rr;
	}
	
	
	public function encabezado(&$h,$grupo){
		$oferta = new Oferta();
		$oferta = $oferta->find($grupo->oferta_id);
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
		$h->xls->write($h->rr, $h->cc,'Amonestaciones '.$grupo->grado.$grupo->letra.$grupo->turno, $st['H4']);
		$h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc + 6);
		$h->nueva_linea();
		$h->xls->write($h->rr, $h->cc, $oferta->nombre, $st['H4']);
		$h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc + 6);
		$h->nueva_linea();
		$h->nueva_linea();
		$h->nueva_linea();
		$h->xls->repeatRows(0,8);
		$h->xls->freezePanes(array(9, 0));
	}
	
	public function encabezado_ciclo(&$h,$grupo){
		$oferta = new Oferta();
		$oferta = $oferta->find($grupo->oferta_id);
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
		$h->xls->write($h->rr, $h->cc,'Amonestaciones '.$grupo->grado.$grupo->letra.$grupo->turno, $st['H4']);
		$h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc + 6);
		$h->nueva_linea();
		$h->xls->write($h->rr, $h->cc, $oferta->nombre, $st['H4']);
		$h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc + 6);
		$h->nueva_linea();
		$h->xls->write($h->rr, $h->cc, 'Ciclo '.$this->ciclo->numero, $st['H4']);
		$h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc + 6);
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