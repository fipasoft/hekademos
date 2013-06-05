<?php

class XLSPasswords extends Reporte{
	private $ciclo_id;
	private $tipo;
	private $modo;
	public function XLSPasswords($ciclo_id,$tipo,$modo){
		$this->tipo=$tipo;
		$this->ciclo_id=$ciclo_id;
		$this->modo=$modo;
		$this->Reporte('accesos '.$this->tipo.$this->modo.'.xls');
		if(strToLower($this->tipo)=='p' || strToLower($this->tipo)=='a' || strToLower($this->tipo)=='f'){
			if(strToLower($this->modo)=='g' || strToLower($this->modo)=='n'){
			if(strToLower($this->modo)=='g'){
			$grupos = $this->datos_grupos();

			if(count($grupos) > 0){
				foreach($grupos as $grupo){
					if(strToLower($this->tipo)=='p'){
					$tutores=$this->datos_tutores($grupo);
					if(count($tutores)>0)
					$this->hoja($grupo,$tutores);
					}elseif(strToLower($this->tipo)=='a'){
					$alumnos=$this->datos_alumnos($grupo);
					if(count($alumnos)>0)
					$this->hoja($grupo,$alumnos);
					}

				}
				if(count($this->getHojas())==0)
				$this->hoja_vacia();

			}else{
				$this->hoja_vacia();
			}
			}else{
				if(strToLower($this->tipo)=='a'){
				$alfa=$this->datos_alumnos_alfa();
					foreach($alfa as $letra => $a){
						$this->hojaL($letra,$a);
						}
				}elseif(strToLower($this->tipo)=='p'){
				$alfa=$this->datos_tutores_alfa();
					foreach($alfa as $letra => $a){
						$this->hojaL($letra,$a);
				}

				}elseif(strToLower($this->tipo)=='f'){
				$alfa=$this->datos_profesores_alfa();
					foreach($alfa as $letra => $a){
						$this->hojaL($letra,$a);
				}

				}

				if(count($this->getHojas())==0)
				$this->hoja_vacia();

			}
			}else{
				$this->hoja_vacia();
		}
		}else{
				$this->hoja_vacia();
		}

	}

		public function datos_tutores($grupo){
		$ciclo = $this->ciclo;
		$tutores = db::raw_connect();
		// ejecuta la consulta
		$tutores = $tutores->in_query(
			"SELECT " .
				"tutores.*, " .
				"alumnos.codigo, " .
				"tutorespassword.pass ".
			"FROM " .
				"tutores " .
				"Left Join (tutoria Inner Join alumnos ON alumnos.id = tutoria.alumnos_id " .
							"Inner Join alumnosgrupo ON alumnosgrupo.alumnos_id = alumnos.id " .
							"Inner Join grupos ON alumnosgrupo.grupos_id = grupos.id )" .
				"ON tutoria.tutores_id = tutores.id " .
				" INNER JOIN tutorespassword ON tutores.id=tutorespassword.tutores_id " .
				"WHERE grupos.id = '" . $grupo['id'] . "' AND grupos.ciclos_id='".$this->ciclo_id."' " .
				"ORDER BY tutores.ap, tutores.am, tutores.nombre "

				);

		return $tutores;
	}



		public function datos_tutores_alfa(){
		$ciclo = $this->ciclo;
		$tutores = db::raw_connect();
		// ejecuta la consulta
		$tutores = $tutores->in_query(
			"SELECT " .
				"tutores.*, " .
				"alumnos.codigo, " .
				"tutorespassword.pass ".
			"FROM " .
				"tutores " .
				"Left Join (tutoria Inner Join alumnos ON alumnos.id = tutoria.alumnos_id " .
							")" .
				"ON tutoria.tutores_id = tutores.id " .
				" INNER JOIN tutorespassword ON tutores.id=tutorespassword.tutores_id " .
				"ORDER BY tutores.ap, tutores.am, tutores.nombre "

				);
				$alfa=array();

		foreach($tutores as $t){
		$alfa[strToUpper(substr(trim($t['ap']),0,1))][]=$t;
		}

		ksort($alfa);

		return $alfa;

	}

	public function regresaPassword($ele){
		$sincifrar=substr(sha1($ele['id']),0,6);
		$ps=sha1($sincifrar);
		if($ps==$ele['pass'])
		$p=$sincifrar;
		else
		$p='No es posible obtener el password.';

		return $p;
	}


	public function hoja($grupo,$datos){
		$nombre = $grupo['grado']. $grupo['letra'] . $grupo['turno'].' '.$grupo['clave'] ;
		$hojas = $this->getHojas();
		if(array_key_exists($nombre, $hojas)){
			$h = $hojas[$nombre];
		}else{
			$cols = array(14, 14, 4, 14, 14, 4, 14, 14, 4, 14, 14, 4) ;
			$h = $this->agregar_hoja($nombre, null, $cols);
			$h->cc_max = 16;
		}
		$this->contenido($h, $grupo,$datos);
		$this->propiedades($h);
	}

	public function hojaL($l,$datos){
		$nombre = $l ;
		$hojas = $this->getHojas();
		if(array_key_exists($nombre, $hojas)){
			$h = $hojas[$nombre];
		}else{
			$cols = array(14, 14, 4, 14, 14, 4, 14, 14, 4, 14, 14, 4) ;
			$h = $this->agregar_hoja($nombre, null, $cols);
			$h->cc_max = 16;
		}
		$this->contenido($h, $grupo,$datos);
		$this->propiedades($h);
	}

	public function hoja_vacia(){
		$nombre = 'HEKADEMOS';
		$h = $this->agregar_hoja($nombre);
		$h->xls->write(0, 0, "No hay registros que coincidan con esas condiciones");
	}

	public function contenido(&$h, $grupo,$lista){
		//$this->encabezado($h,$grupo);
		$st = $this->getEstilos();
		// alumnos
			$i = 1;
			$n = 4;
			foreach($lista as $ele){
				$h->cc = (($i-1) % $n) * 3;
				if($grupo==null){
				$h->xls->write($h->rr, $h->cc, $i.'] '.utf8_decode($ele['ap'].' '.$ele['am'].' '.$ele['nombre']), $st['TD.Normal']); $h->cc++;
				}else{
				$h->xls->write($h->rr, $h->cc, $i.'] '.utf8_decode($ele['ap'].' '.$ele['am'].' '.$ele['nombre']).' ['.$grupo['grado'].$grupo['letra'].$grupo['turno'].']', $st['TD.Normal']); $h->cc++;
				}
				$h->xls->writeBlank($h->rr, $h->cc, $st['TD.Normal']);

				$h->xls->mergeCells($h->rr, $h->cc-1, $h->rr, $h->cc);
				$h->xls->setRow($h->rr, 30.75);
				$h->cc = (($i-1) % $n) * 3;
				$h->xls->write($h->rr+1, $h->cc, $ele['codigo'], $st['TD.Normal']); $h->cc++;
				$h->xls->write($h->rr+1, $h->cc, 'NIP: '.utf8_decode($this->regresaPassword($ele)), $st['TD.Normal']); $h->cc++;
				if($i % $n == 0){
					$h->nueva_linea();
					$h->nueva_linea();
					$h->nueva_linea();
				}
				$i++;
			}
			$h->cc_max = $n*3-2;
			$h->rr_max = $h->rr+1;


		$h->nueva_linea();
		$h->rr_max = $h->rr;
	}


public function datos_grupos(){
		$grupos = db::raw_connect();

		$from = "grupos " .
				"INNER JOIN oferta ON oferta.id = grupos.oferta_id ";

		$grupos = $grupos->in_query(
							"SELECT " .
								"grupos.id, " .
								"grupos.grado, " .
								"grupos.letra, " .
								"grupos.turno, " .
								"oferta.nombre, ".
								"oferta.clave ".
							"FROM " . $from .
							"WHERE grupos.ciclos_id = '" . $this->ciclo_id . "' " .
							"GROUP BY grupos.id " .
							"ORDER BY grupos.turno, grupos.grado, grupos.letra, oferta.nombre "
				   );
		return $grupos;
	}


	public function datos_alumnos($grupo){
		$alumnos = db::raw_connect();

			$from = "alumnos " .
				"INNER JOIN alumnosgrupo ON alumnosgrupo.alumnos_id=alumnos.id " .
				"INNER JOIN grupos ON grupos.id=alumnosgrupo.grupos_id " .
				" INNER JOIN alumnospassword ON alumnos.id=alumnospassword.alumnos_id ";

		$alumnos = $alumnos->in_query(
							"SELECT " .
								"alumnos.*,alumnospassword.pass ".
							"FROM " . $from .
							"WHERE grupos.id = '" . $grupo['id'] . "' " .
							"ORDER BY alumnos.ap, alumnos.am, alumnos.nombre "
				   );
		return $alumnos;
	}

	public function datos_alumnos_alfa(){
		$alumnos = db::raw_connect();

			$from = "alumnos " .
				" INNER JOIN alumnospassword ON alumnos.id=alumnospassword.alumnos_id ";

		$alumnos = $alumnos->in_query(
							"SELECT " .
								"alumnos.*,alumnospassword.pass ".
							"FROM " . $from .
							"ORDER BY alumnos.ap, alumnos.am, alumnos.nombre "
				   );

		$alfa=array();

		foreach($alumnos as $alumno){
		$alfa[strToUpper(substr(trim($alumno['ap']),0,1))][]=$alumno;
		}

		ksort($alfa);

		return $alfa;
	}

	public function datos_profesores_alfa(){
		$profesores = db::raw_connect();

			$from = "profesores " .
				" INNER JOIN profesorespassword ON profesores.id=profesorespassword.profesores_id ";

		$profesores = $profesores->in_query(
							"SELECT " .
								"profesores.*,profesorespassword.pass ".
							"FROM " . $from .
							"ORDER BY profesores.ap, profesores.am, profesores.nombre "
				   );

		$alfa=array();

		foreach($profesores as $profesor){
		$alfa[strToUpper(substr(trim($profesor['ap']),0,1))][]=$profesor;
		}

		ksort($alfa);

		return $alfa;
	}

	private function propiedades(&$h){
		$h->xls->centerHorizontally();
		$h->xls->hideGridlines();
		$h->xls->printArea(0, 0, $h->rr_max, $h->cc_max);
		$h->xls->setLandscape(1);
		$h->xls->setMargins_LR(0.1);
		$h->xls->setMargins_TB(0.1);
		$h->xls->setPaper(3);
		$h->xls->setPrintScale(100);
		$h->xls->setZoom(75);
	}


	}

?>