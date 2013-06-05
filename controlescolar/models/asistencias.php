<?php
Kumbia :: import('lib.kumbia.utils');

class Asistencias extends ActiveRecord{
	protected $valor;
	protected $asistencias;
	protected $faltas;

	public function dia(){
		$fecha = explode('-', $this->dia);
		return intval($fecha[2]);
	}
	public function diaMes(){
		$fecha = explode('-', $this->dia);
		return intval($fecha[2]) . ' ' . strtolower(substr(Utils :: mes_espanol($fecha[1]), 0, 3));
	}
	public function diaSemana(){
		$fecha = explode('-', $this->dia);
		$dia = date_create($this->dia);
		return Utils :: dia_espanol($dia->format('w')) . ' ' . intval($fecha[2]);
	}
	public function mes(){
		$fecha = explode('-', $this->dia);
		return substr(Utils :: mes_espanol($fecha[1]), 0, 3);
	}
	public function mesA(){
		$fecha = explode('-', $this->dia);
		return Utils :: mes_espanol($fecha[1]) . ' de ' . $fecha[0];
	}
}
?>