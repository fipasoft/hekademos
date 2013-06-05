<?php
class Amonestacion extends ActiveRecord{
    public $alumno_id;

    /*
    public function alumno(){
        $alumno = new Alumnos();
        $alumno = $alumno->find($this->alumnos_id);
        return $alumno;
    }
    */

    public function porAlumno($alumno_id){
        $amonestacion = new Amonestacion();
        $amonestaciones = $amonestacion->find("alumnos_id='".$alumno_id."'");
        return $amonestaciones;
    }
    
    public function porAlumnoCiclo($alumno_id,$ciclo_id){
        $amonestacion = new Amonestacion();
        $amonestaciones = $amonestacion->find("alumnos_id='".$alumno_id."' AND ciclos_id='".$ciclo_id."'");
        return $amonestaciones;
    }
    
    
    public function estado(){
        $estado = new Aestado();
        $estado = $estado->find($this->aestado_id);
        return $estado;
    }
    
    public function categoria(){
        $categoria = new Acategoria();
        $categoria = $categoria->find($this->acategoria_id);
        return $categoria->nombre;    
    }
    

}