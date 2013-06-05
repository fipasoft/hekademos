<?php
class Calificacionesparciales extends ActiveRecord{
	
	public function deClaveAPeriodo($clave){
		$tmp = explode('-', $clave);
		$periodo = intval($tmp[1], 10);
		return $periodo;
	}

	public function editables($parciales, $disponibles){
		foreach($parciales as $modo => $claves){
			if($modo == 'AGR' || $modo == 'EDI'){
				foreach($claves as $clave => $valores){
					if(!in_array($clave, $disponibles)){
						return false;						
					}
				}
			}else{
				return false;
			}
		}
		return true;
	}	
	
	public function status($c = ''){
		$c = ($c == '' ? $this->valor : $c);
		if($c >= 60){
			return 'aprobado';
		}else{
			return 'reprobado';
		}
	}
	
	public function valido(){
		if($this->valor >= 0 && $this->valor <= 100){
			$this->valor = intval($this->valor, 10);
			return true;
		}else{
			return false;
		}
	}
	
}
?>