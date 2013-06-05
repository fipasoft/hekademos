<?php
class Distribucion extends ActiveRecord{
	
	public function aula(){
		$aula = new Aulas();
		$aula = $aula->find($this->aulas_id);
		return $aula;
	}
}
?>
