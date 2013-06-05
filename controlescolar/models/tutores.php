<?php
Kumbia :: import('lib.kumbia.utils');

class Tutores extends ActiveRecord{
	
	public function nombre(){
		if( $this->ap != '' &&
		    $this->am != '' &&
		    $this->nombre != '' )
		{
			return $this->ap . ' ' . $this->am . ', ' . $this->nombre;
		}
		return '-';
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
	
	public function tutorados(){
		$t = new Tutoria();
		$t = $t->find("conditions: tutores_id = '" . $this->id . "'");
		$a = new Alumnos();
		$alumnos = array();
		foreach($t as $tutoria){
			$alumnos[] = $a->find($tutoria->alumnos_id);
		}
		return $alumnos;
	}
}
?>
