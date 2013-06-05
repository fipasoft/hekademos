<?php
/*
 * Created on 23/09/2009
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 class Tutoresgrupos extends ActiveRecord{

	public function nombre(){
		$profesores = new Profesores();
		$profesores = $profesores->find($this->profesores_id);
		return $profesores->nombre . ' ' . $profesores->ap.' '.$profesores->am;
	}

 }

?>
