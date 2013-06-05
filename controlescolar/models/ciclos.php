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
        ini_set("memory_limit","512M");
        set_time_limit(0);
        $siguiente=new Ciclos();
        $siguiente=$siguiente->find_first("numero='".$this->siguiente()."'");

        if($siguiente==null)return 'No existe el ciclo siguiente.<br/> Primero agregue el ciclo con el numero <span style="font-weight:bold;">'.$this->siguiente().'</span>';

        $myLog = new Logger('GruposCierre.log');
        if($this->avance==0 && $this->activo==1){
        $myLog->log("Inicio cierre ciclo ".$this->id, Logger::ERROR);
        //verificar calificaciones
        $calificaciones=new Calificaciones();
        //$faltantes=$calificaciones->faltantes($this->id);
        //var_dump($faltantes);exit;
        if(true || $calificaciones->completas($this->id)){

        //fechas de inicios de cursos
        $agenda=new Agenda();
        $evento = new Eventos();//
        $rol = new Roles();

        $evento = $evento->find_first(
            "conditions: clave = 'CRS-INT-INI'"
        );
        $rol = $rol->find_first(
            "conditions: eventos_id = '" . $evento->id . "' "
        );
        $periodo = $agenda->find_first(
            "conditions: " .
                "ciclos_id = '" . $siguiente->id . "' " .
                "AND roles_id = '" . $rol->id . "' "
        );

        $crs_ini=$periodo;

        $agenda=new Agenda();
        $evento = new Eventos();
        $rol = new Roles();

        $evento = $evento->find_first(
            "conditions: clave = 'CRS-PER-INI'"
        );
        $rol = $rol->find_first(
            "conditions: eventos_id = '" . $evento->id . "' "
        );
        $periodo = $agenda->find_first(
            "conditions: " .
                "ciclos_id = '" . $siguiente->id . "' " .
                "AND roles_id = '" . $rol->id . "' "
        );

        $crs_ini_2=$periodo;

        $agenda=new Agenda();
        $evento = new Eventos();
        $rol = new Roles();

        $evento = $evento->find_first(
            "conditions: clave = 'CRS-PER'"
        );
        $rol = $rol->find_first(
            "conditions: eventos_id = '" . $evento->id . "' "
        );
        $periodo = $agenda->find_first(
            "conditions: " .
                "ciclos_id = '" . $siguiente->id . "' " .
                "AND roles_id = '" . $rol->id . "' "
        );
        $crs=$periodo;

        //crear plantilla
        $grupos=new Grupos();
        $grupos=$grupos->todosporCiclo($this->id);
        $profesor=new Profesores();
        $profesor=$profesor->staff();
        $faltantes=array();
        foreach($grupos as $grupo){   //copea los grupos del ciclo anterior
            $nuevo=new Grupos();
            if(!$nuevo->exists("ciclos_id=".$siguiente->id." " .
                " AND grado=".$grupo->grado." " .
                " AND letra='".$grupo->letra."' " .
                " AND turno='".$grupo->turno."' " .
                " AND oferta_id=".$grupo->oferta_id)){

                $nuevo->ciclos_id=$siguiente->id;
                $nuevo->grado=$grupo->grado;
                $nuevo->letra=$grupo->letra;
                $nuevo->turno=$grupo->turno;
                $nuevo->oferta_id=$grupo->oferta_id;
                $nuevo->save();

                $myLog->log('Se agrego el grupo '.$nuevo->verInfo().', '.$nuevo->verOferta(), Logger::ERROR);
                            
                
            }else{
                $nuevo=$nuevo->find_first("ciclos_id=".$siguiente->id." " .
                        " AND grado=".$grupo->grado." " .
                        " AND letra='".$grupo->letra."' " .
                        " AND turno='".$grupo->turno."' " .
                        " AND oferta_id=".$grupo->oferta_id);
            $myLog->log('Ya existe el grupo '.$nuevo->verInfo().', '.$nuevo->verOferta(), Logger::ERROR);
            }

            $faltante=new Grupos();
            if($grupo->grado<6
                &&
                 !$faltante->exists("ciclos_id=".$this->id." AND " .
                                     " grado=".($grupo->grado+1)." " .
                                     " AND letra='".$grupo->letra."' " .
                                     " AND turno='".$grupo->turno."' " .
                                     " AND oferta_id=".$grupo->oferta_id)
                             &&
                 !$faltante->exists("ciclos_id=".$siguiente->id." " .
                                     " AND grado=".($grupo->grado+1)." " .
                                     " AND letra='".$grupo->letra."' " .
                                     " AND turno='".$grupo->turno."' " .
                                     " AND oferta_id=".$grupo->oferta_id)
                 ){
                $faltante=new Grupos();
                $faltante->ciclos_id=$siguiente->id;
                $faltante->grado=$grupo->grado+1;
                $faltante->letra=$grupo->letra;
                $faltante->turno=$grupo->turno;
                $faltante->oferta_id=$grupo->oferta_id;
                //$faltante->save();
                $faltantes[]=$faltante;
            }

        if($nuevo->id!=""){
            $cursos=$grupo->cursosInfo();

            foreach($cursos as $curso){
                $materiaTipo=$curso->materiaTipo();
                if($materiaTipo!="OPT"){
                $nv=new Cursos();
                if(!$nv->exists("grupos_id='".$nuevo->id."' AND materias_id='".$curso->materias_id."' ")){
                $nv->grupos_id=$nuevo->id;
                $nv->materias_id=$curso->materias_id;
                $nv->profesores_id=$curso->profesores_id;
                $nv->estado_id=1;

                $materia=$curso->materia();
                $oferta=new Ofertasmaterias();
                $oferta=$oferta->find_first("materias_id=".$materia->id);
                if($oferta->oferta_id==2){
                    if($materia->duracion==7){
                    $nv->inicio=substr($crs_ini->inicio,0,10);
                    }elseif($materia->duracion==12){
                    $nv->inicio=substr($crs_ini_2->inicio,0,10);
                    }else{
                    $nv->inicio=substr($crs->inicio,0,10);
                    }
                }else{
                $nv->inicio=substr($crs->inicio,0,10);
                }
                $nv->save();
                $myLog->log('Se agrego el curso '.$nv->verGrupo()." ".$nv->verMateria()." ".$materiaTipo, Logger::ERROR);


                }
                }
            }
        }
        }


        $ant=new Ciclos();
        $anterior=$ant->find_first("numero='".$this->anterior()."'");
        $cid=$anterior->id;
        if($anterior->id=='')
                $cid=$this->id;

        foreach($faltantes as $f){
        $myLog->log('Grupo faltante '.$f->verInfo().', '.$f->verOferta(), Logger::ERROR);

            $grupo=new Grupos();
            $grupo=$grupo->find_first("ciclos_id=".$cid." AND grado=".$f->grado." AND letra='A' AND turno='".$f->turno."' AND oferta_id=".$f->oferta_id);

            if($grupo->id!=''){
            $cursos=$grupo->cursosInfo();

            if(is_array($cursos)){
            foreach($cursos as $curso){ //copea los cursos del grupo con el profesor staff
                $materia=$curso->materia();
                if($materia!="OPT"){
                    $nv=new Cursos();
                    if(!$nv->exists("grupos_id='".$f->id."' AND materias_id='".$curso->materias_id."' ")){
                    $nv->grupos_id=$f->id;
                    $nv->materias_id=$curso->materias_id;
                    $nv->profesores_id=$profesor->id;
                    $nv->estado_id=1;

                    $materia=$curso->materia();
                    $oferta=new Ofertasmaterias();
                    $oferta=$oferta->find_first("materias_id=".$materia->id);
                        if($oferta->oferta_id==2){
                            if($materia->duracion==7){
                            $nv->inicio=substr($crs_ini->inicio,0,10);
                            }elseif($materia->duracion==12){
                            $nv->inicio=substr($crs_ini_2->inicio,0,10);
                            }else{
                            $nv->inicio=substr($crs->inicio,0,10);
                            }
                        }else{
                        $nv->inicio=substr($crs->inicio,0,10);
                        }
                        $nv->save();
                        $myLog->log('Se agrego el curso '.$nv->verGrupo()." ".$nv->verMateria(), Logger::ERROR);

                }
                }
            }
            }

            }
        }


        //realizar avance de alumnos
        $myLog->close();

        Kumbia :: import('app.scripts.Avance');
        $avance=new Avance($this);
        $avance->procesar();

        $myLog = new Logger('cierre.log');

        //guardar cambios en los ciclos

        $this->avance=1;
        $this->activo=1;
        $this->save();

        $siguiente->abierto=1;
        $siguiente->avance=0;
        $siguiente->activo=0;
        $siguiente->save();
        $myLog->log("Fin del cierre de ciclo ".$this->id, Logger::DEBUG);
        $myLog->close();
        return '';
    }else{
         $myLog->log("Faltan calificaciones Finales ".$this->id, Logger::ERROR);

         $myLog->log("Fin del cierre de ciclo ".$this->id, Logger::DEBUG);
         $myLog->close();

         return "Faltan calificaciones Finales";
        }
        }else{
        $myLog->log("Ya se llevo a cabo el cierre del ciclo ".$this->numero, Logger::DEBUG);
        $myLog->close();

        }
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

    public function fechaValida($f){
        $fecha=new DateTime($f);
        $fecha=$fecha->format('U');

        $ini_p  =  new DateTime( substr($this->inicio, 0, 10) );
        $ini_p =  $ini_p->format('U');


        $fin_p = new DateTime( substr($this->fin, 0, 10) );
        $fin_p = $fin_p->format('U');

        if($ini_p<=$fecha && $fecha<=$fin_p)
        return true;
        else
        return false;


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
