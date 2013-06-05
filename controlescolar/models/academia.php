<?php
class Academia extends ActiveRecord{

	public function materias(){
		$materias = new Materias();
		$materias = $materias->find_all_by_sql(
				"SELECT materias.* FROM 
				materias
				INNER JOIN academiamateria ON materias.id = academiamateria.materias_id
				WHERE academiamateria.academia_id='".$this->id."'");
		return $materias;
	}

}
?>
