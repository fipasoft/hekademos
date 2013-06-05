<?php
class Personal extends ActiveRecord{
	public $tarjeta;
	public $tipo_entidad;

	public function nombre(){
		return trim($this->ap) . ' ' . trim($this->am) . ', ' . trim($this->nombre);
	}

	public function tipo(){
		$tipo=new Tipopersonal();
		$tipo=$tipo->find_first($this->tipopersonal_id);
		return $tipo->nombre;
	}
}
?>
