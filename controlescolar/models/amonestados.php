<?php
class Amonestados extends ActiveRecord{
	
public function alumno(){
		$alumno = new Alumnos();
		$alumno = $alumno->find($this->alumnos_id);
		return $alumno;
	}
	
public function grupo(){
	$grupo_alumno = new Alumnosgrupo();
	$grupo_alumno = $grupo_alumno->find_first('alumnos_id = '.$this->alumnos_id);
	$grupo = new Grupos();
	$grupo = $grupo->find($grupo_alumno->grupos_id);
	return $grupo;
}

}
?>
