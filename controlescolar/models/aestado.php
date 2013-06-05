<?php
class Aestado extends ActiveRecord{

	public function pornombre($nombre){
		$estado = new Aestado();
		$estado = $estado->find_first("nombre='".$nombre."'");
		return $estado;
	}
	
}