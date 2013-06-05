<?php
Kumbia :: import('lib.kumbia.utils');

class Alumnoscursos extends ActiveRecord{
    protected $nombre;
    protected $semestre;

    public function alta($alumno,$curso){
    $c=new Cursos();
    $a=new Alumnos();
    if($c->exists($curso) && $a->exists($alumno)){
    $this->alumnos_id=$alumno;
    $this->cursos_id=$curso;
    $this->save();

    $c=$c->find_first($curso);
    $a=$a->find_first($alumno);
    $c->sincronizar($a);
    }
    }

    public function baja(){
        $asistencias=new Asistencias();
        $asistencias->delete('alumnos_id='.$this->alumnos_id.' AND cursos_id='.$this->cursos_id);

        $parciales=new Calificacionesparciales();
        $parciales->delete('alumnos_id='.$this->alumnos_id.' AND cursos_id='.$this->cursos_id);

        $calificaciones=new Calificaciones();
        $calificaciones->delete('alumnos_id='.$this->alumnos_id.' AND cursos_id='.$this->cursos_id);

        $faltas=new Faltas();
        $faltas->delete('alumnoscursos_id='.$this->id);

        $Alumnosconarticulo=new Alumnosconarticulo();
        $Alumnosconarticulo->delete('alumnoscursos_id='.$this->id);

        $this->delete();

    }

    public function parcial($n){
        $parciales = new Calificacionesparciales();
        $parcial = $parciales->find_first("columns: valor" ,
                                    "conditions: " .
                                    "alumnos_id = '" . $this->alumnos_id . "' " .
                                    "AND cursos_id = '" . $this->cursos_id . "' " .
                                    "AND periodo = '" . $n . "' ");
        return $parcial->valor;
    }

    public function faltas(){
        $faltas = new Faltas();
        $faltas = $faltas->find_first("conditions: alumnoscursos_id = '" . $this->id . "' ");
        return $faltas->cantidad;
    }
}
?>
