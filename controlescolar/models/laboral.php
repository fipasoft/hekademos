<?php
class Laboral extends ActiveRecord{
	
	public function lcategoria(){
		$lcategoria = new Lcategoria();
		$lcategoria = $lcategoria->find($this->lcategoria_id);
		return $lcategoria;
	}
}
?>
