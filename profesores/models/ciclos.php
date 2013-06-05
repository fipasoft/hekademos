<?php
class Ciclos extends ActiveRecord{

    public function abierto(){
        if($this->id=='')return false;

        if($this->abierto==1)return true;
        else return false;
    }

    public function abiertos(){
    $abiertos=$this->find("abierto='1'");

    if(!is_array($abiertos))return array();
    else
    return $abiertos;
    }

    public function activo(){
    $activo=$this->find_first("activo='1'");
    return $activo;
    }


    public function anterior(){
        $tmp = explode('-', $this->numero);
        $numero = $tmp[0];
        $letra = $tmp[1];
        if($letra == 'B'){
            return $numero . '-A';
        }else{
            return ($numero - 1) . '-B';
        }
    }

        public function calificacionesTodas(){
        $cursos=new Cursos();
        $cursos=$cursos->delCiclo($this->id);

        $cond='';
        foreach($cursos as $c){
            $cond.="cursos_id=".$c->id." OR ";
        }

        $cond=substr($cond,0,strlen($cond)-3);

        //obtener alumnos-cursos del ciclo
        $alumCursos=new Alumnoscursos();
        $alumCursos=$alumCursos->find($cond);

        if(count($alumCursos)<=0)return false;

        $cond='';
        foreach($alumCursos as $ac){
            $cond.="( cursos_id=".$ac->cursos_id." AND alumnos_id=".$ac->alumnos_id.") OR ";
        }

        $cond=substr($cond,0,strlen($cond)-3);

        $calificaciones=db::raw_connect();
        $calificaciones=$calificaciones->in_query(
        "SELECT " .
        "calificaciones.*, " .
        "materias.nombre AS materia, " .
        "materias.tipo AS tipo " .
        "FROM " .
        "calificaciones " .
        "INNER JOIN cursos ON calificaciones.cursos_id=cursos.id " .
        "INNER JOIN materias ON cursos.materias_id=materias.id " .
        "WHERE ".
        $cond);
        return $calificaciones;
    }



    public function cierre(){
        Kumbia :: import('app.scripts.Avance');
        ini_set("memory_limit","128M");
        set_time_limit(0);
        $siguiente=new Ciclos();
        $siguiente=$siguiente->find_first("numero='".$this->siguiente()."'");

        if($siguiente==null)return 'No existe el ciclo siguiente.<br/> Primero agregue el ciclo con el numero <span style="font-weight:bold;">'.$this->siguiente().'</span>';

        $avance=new Avance($this);
        $avance=$avance->avanceAlumno(); return;
        //verificar calificaciones
        $calificaciones=new Calificaciones();
        if($calificaciones->completas($this->id) || true){

        //crear plantilla
        $grupos=new Grupos();
        $grupos=$grupos->todosporCiclo($this->id);
        $profesor=new Profesores();
        $profesor=$profesor->staff();
        foreach($grupos as $grupo){   //copea los grupos del ciclo anterior
            $nuevo=new Grupos();
            if(!$nuevo->exists("ciclos_id=".$siguiente->id." AND grado=".$grupo->grado." AND letra='".$grupo->letra."'"." AND turno='".$grupo->turno."' AND oferta_id=".$grupo->oferta_id)){
            $nuevo->ciclos_id=$siguiente->id;
            $nuevo->grado=$grupo->grado;
            $nuevo->letra=$grupo->letra;
            $nuevo->turno=$grupo->turno;
            $nuevo->oferta_id=$grupo->oferta_id;
            $nuevo->save();
            }else{
                $nuevo=$nuevo->find_first("ciclos_id=".$siguiente->id." AND grado=".$grupo->grado." AND letra='".$grupo->letra."'"." AND turno='".$grupo->turno."' AND oferta_id=".$grupo->oferta_id);
            }

            $cursos=$grupo->cursosInfo();

            foreach($cursos as $curso){ //copea los cursos del grupo con el profesor staff
                $nv=new Cursos();
                $nv->grupos_id=$nuevo->id;
                $nv->materias_id=$curso->materias_id;
                $nv->profesores_id=$profesor->id;
                $nv->estado_id=1;
                $nv->save();

            }
        }


        //realizar avance de alumnos

         //$this->avanceAlumnos();

        //guardar cambios en los ciclos

        /*$this->avance=1;
        $this->activo=0;
        $this->abierto=0;
        $this->save();

        $siguiente->abierto=1;
        $siguiente->avance=0;
        $siguiente->activo=0;
        $siguiente->save();*/
        return '';
    }else return "Faltan calificaciones Finales";
}

    private function avanceAlumnos(){
        $avance=new Avance($this);
        $avance->procesar();

    }

    private function avanceSencillo(){
        $alumnos=new Alumnos();
        $alumnos=$alumnos->find_all_by_sql(
        "SELECT " .
        "alumnos.*, " .
        "grupos.grado, " .
        "grupos.letra, " .
        "grupos.turno, " .
        "grupos.oferta_id " .
        "FROM " .
        "alumnos " .
        "INNER JOIN alumnosgrupo ON alumnos.id=alumnosgrupo.alumnos_id " .
        "INNER JOIN grupos ON alumnosgrupo.grupos_id=grupos.id " .
        "WHERE grupos.ciclos_id=".$this->id
        );
        $cicloAvance = new Ciclos();
        $cicloAvance = $cicloAvance->find_first("numero='" . $this->siguiente() . "'");
        foreach($alumnos as $alumno){
            if($alumno->grado < 4){
                $grupoAvance = new Grupos();
                $grupoAvance=$grupoAvance->find_first("ciclos_id=" . $cicloAvance->id . " AND grado='" . ($alumno->grado + 1) . "' AND letra='" . $alumno->letra . "' AND turno='" . $alumno->turno . "' AND oferta_id=" . $alumno->oferta_id);
                if($grupoAvance->id==''){
                    $grupoAvance = new Grupos();
                    $grupoAvance->ciclos_id=$cicloAvance->id;
                    $grupoAvance->grado=$alumno->grado+1;
                    $grupoAvance->letra=$alumno->letra;
                    $grupoAvance->turno=$alumno->turno;
                    $grupoAvance->oferta_id=$alumno->oferta_id;
                    $grupoAvance->save();


                }
                $grp = new Alumnosgrupo();
                $grp->alta($alumno->id, $grupoAvance->id);
            }elseif($alumno->grado == 4 || $alumno->grado == 5 ){
                $grupoAvance = new Grupos();
                $grupoAvance = $grupoAvance->find_first("ciclos_id=" . $cicloAvance->id . " AND grado='" . ($alumno->grado + 1) . "' AND letra='" . $alumno->letra . "' AND turno='" . $alumno->turno . "' AND oferta_id=" . $alumno->oferta_id);
                if($grupoAvance->id==''){
                    $grupoAvance = new Grupos();
                    $grupoAvance->ciclos_id=$cicloAvance->id;
                    $grupoAvance->grado=$alumno->grado+1;
                    $grupoAvance->letra=$alumno->letra;
                    $grupoAvance->turno=$alumno->turno;
                    $grupoAvance->oferta_id=$alumno->oferta_id;
                    $grupoAvance->save();


                }
                $grp = new Alumnosgrupo();
                $grp->alta($alumno->id, $grupoAvance->id);
            }elseif($alumno->grado==6){

                $a=new Alumnos();
                $a=$a->find_first($alumno->id);
                $a->situaciones_id=3;
                $a->sexo=' ';
                $a->save();
            }
        }

    }



    public function meses(){
        $fecha=explode("-",$this->inicio);
        $inicio=$fecha[1];

        $fecha=explode("-",$this->fin);
        $fin=$fecha[1];

        $meses=array();


    }

    public function siguiente(){
        $tmp = explode('-', $this->numero);
        $numero = $tmp[0];
        $letra = $tmp[1];
        if($letra == 'B'){
            return ($numero + 1) . '-A';
        }else{
            return $numero . '-B';
        }
    }


}

?>
