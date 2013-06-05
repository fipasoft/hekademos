<?php
class Calificaciones extends ActiveRecord{
    protected $clave;
    protected $curso;
    protected $materia;
    protected $tipo;

    public function completas($ciclo_id){
        $myLog = new Logger('cierreCalificacionesCompletas.log');
        $myLog->log("Revisando calificaciones completas", Logger::DEBUG);
        $cursos=new Cursos();
        $cursos=$cursos->delCiclo($ciclo_id);

        $cond='';
        $calificaciones=array();
        foreach($cursos as $c){
            $cals=new Calificaciones();
            $cals=$cals->find_all_by_sql(
                        "SELECT " .
                        "calificaciones.*, " .
                        "materias.nombre AS materia, " .
                        "materias.tipo AS tipo " .
                        "FROM " .
                        "calificaciones " .
                        "INNER JOIN cursos ON calificaciones.cursos_id=cursos.id " .
                        "INNER JOIN materias ON cursos.materias_id=materias.id " .
                        "WHERE ".
                        "cursos_id='".$c->id."'");
            if(count($cals)>0){
                $calificaciones=array_merge($calificaciones,$cals);
            }
            $cond.="cursos_id=".$c->id." OR ";
        }

        $cond=substr($cond,0,strlen($cond)-3);

        //obtener alumnos-cursos del ciclo
        $alumCursos=new Alumnoscursos();
        $alumCursos=$alumCursos->find($cond);

        if(count($alumCursos)<=0)return false;

        /*$cond='';
        foreach($alumCursos as $ac){
            $cond.="( cursos_id=".$ac->cursos_id." AND alumnos_id=".$ac->alumnos_id.") OR ";
        }

        $cond=substr($cond,0,strlen($cond)-3);
        */
/*
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

        if(count($calificaciones)<=0)return false;
*/

        $datos=array();
        foreach($calificaciones as $cal){
            $datos[$cal->alumnos_id][$cal->cursos_id][$cal->oportunidades_id]=$cal;
        }

        $ordinario=new Oportunidades();
        $ordinario=$ordinario->ordinario();

        $extra=new Oportunidades();
        $extra=$extra->extraordinario();


        //revisar que esten todas las calificaciones de los alumnos-cursos
        $faltantes=array();
        foreach($alumCursos as $ac){
        $dato=$datos[$ac->alumnos_id][$ac->cursos_id][$ordinario->id];
        if($dato==null){
                    $faltantes[$ac->id]["ac"]=$ac;
                    $faltantes[$ac->id]["t"]="ORDINARIO";
                    $myLog->log("Falta calificación en curso ".$ac->cursos_id." de tipo ORDINARIO para el alumno ".$ac->alumnos_id, Logger::ERROR);
                    $myLog->close();
                    return false;
        }else{

            if(strToUpper($dato->tipo)=="TLR" || strToUpper($dato->tipo)=="PRO"){
                 if(strToUpper($dato->valor)!="SD" && strToUpper($dato->valor)!="A" && strToUpper($dato->valor)!="NA"){
                    $faltantes[$ac->id]["ac"]=$ac;
                    $faltantes[$ac->id]["t"]="ORDINARIO";
                    $myLog->log("Error en la calificación de un curso de tipo taller o programa en ".$ac->cursos_id." para el alumno ".$ac->alumnos_id, Logger::ERROR);
                    $myLog->close();
                    return false;
                }

            }else{
                 if($dato->valor<60 || strToUpper($dato->valor)=="SD"){
                    $dato=$datos[$ac->alumnos_id][$ac->cursos_id][$extra->id];
                    if($dato==null){
                        $myLog->log("Falta calificación en curso ".$ac->cursos_id." de tipo EXTRAORDINARIO para el alumno ".$ac->alumnos_id, Logger::ERROR);
                        $myLog->close();
                        $faltantes[$ac->id]["ac"]=$ac;
                        $faltantes[$ac->id]["t"]="EXTRAORDINARIO";
                        return false;
                    }
                }
            }

        }

        }


        if(count($faltantes)>0){
        $myLog->close();
        return false;
        }

        $myLog->log("Calificaciones Completas", Logger::DEBUG);
        $myLog->close();

        return true;
    }

    public function faltantes($ciclo_id, $rdatos = false){
        $nombre = 'faltantes_'.time().".log";
        $myLog = new Logger($nombre);
        $myLog->log("Revisando calificaciones completas", Logger::DEBUG);
        $cursos=new Cursos();
        $cursos=$cursos->delCiclo($ciclo_id);

        $cond='';
        $calificaciones=array();
        foreach($cursos as $c){
            $cals=new Calificaciones();
            $cals=$cals->find_all_by_sql(
                        "SELECT " .
                        "calificaciones.*, " .
                        "materias.nombre AS materia, " .
                        "materias.tipo AS tipo " .
                        "FROM " .
                        "calificaciones " .
                        "INNER JOIN cursos ON calificaciones.cursos_id=cursos.id " .
                        "INNER JOIN materias ON cursos.materias_id=materias.id " .
                        "WHERE ".
                        "cursos_id='".$c->id."'");
            if(count($cals)>0){
                $calificaciones=array_merge($calificaciones,$cals);
            }
            $cond.="cursos_id=".$c->id." OR ";
        }

        $cond=substr($cond,0,strlen($cond)-3);

        //obtener alumnos-cursos del ciclo
        $alumCursos=new Alumnoscursos();
        $alumCursos=$alumCursos->find($cond);

        if(count($alumCursos)<=0){
            if($rdatos){
                return array();
            }else{
                $myLog->log("No se han realizado las inscripciones", Logger::DEBUG);
                $myLog->close();
                return $nombre;
            }
        }

        $datos=array();
        foreach($calificaciones as $cal){
            $datos[$cal->alumnos_id][$cal->cursos_id][$cal->oportunidades_id]=$cal;
        }

        $ordinario=new Oportunidades();
        $ordinario=$ordinario->ordinario();

        $extra=new Oportunidades();
        $extra=$extra->extraordinario();


        //revisar que esten todas las calificaciones de los alumnos-cursos
        $faltantes=array();
        foreach($alumCursos as $ac){
        $dato=$datos[$ac->alumnos_id][$ac->cursos_id][$ordinario->id];
        if($dato==null){
                    $faltantes[$ac->id]["ac"]=$ac;
                    $faltantes[$ac->id]["t"]="ORDINARIO";
                    //$myLog->log("Falta calificación en curso ".$ac->cursos_id." de tipo ORDINARIO para el alumno ".$ac->alumnos_id, Logger::ERROR);
                    //$myLog->close();
                    //return false;
        }else{

            if(strToUpper($dato->tipo)=="TLR" || strToUpper($dato->tipo)=="PRO"){
                 if(strToUpper($dato->valor)!="SD" && strToUpper($dato->valor)!="A" && strToUpper($dato->valor)!="NA"){
                    $faltantes[$ac->id]["ac"]=$ac;
                    $faltantes[$ac->id]["t"]="ORDINARIO";
                    //$myLog->log("Error en la calificación de un curso de tipo taller o programa en ".$ac->cursos_id." para el alumno ".$ac->alumnos_id, Logger::ERROR);
                    //$myLog->close();
                    //return false;
                }

            }else{
                 if($dato->valor<60 || strToUpper($dato->valor)=="SD"){
                    $dato=$datos[$ac->alumnos_id][$ac->cursos_id][$extra->id];
                    if($dato==null){
                        //$myLog->log("Falta calificación en curso ".$ac->cursos_id." de tipo EXTRAORDINARIO para el alumno ".$ac->alumnos_id, Logger::ERROR);
                        //$myLog->close();
                        $faltantes[$ac->id]["ac"]=$ac;
                        $faltantes[$ac->id]["t"]="EXTRAORDINARIO";
                        //return false;
                    }
                }
            }

        }

        }


        $myLog->log("---------------------------------------------------------------------- ", Logger::ERROR);
        $info = array();
        foreach($faltantes as $ff){
            $f=$ff["ac"];
            $t=$ff["t"];
            $curso=new Cursos();
            $curso=$curso->find($f->cursos_id);
            $grupo=$curso->grupo();
            $info[$grupo->id][$f->cursos_id][] = $ff;
        }

        foreach($info as $grupo_id => $cursos){

            $grupo=new Grupos();
            $grupo=$grupo->find($grupo_id);

            $myLog->log("Faltan calificaciones en el grupo ".$grupo->ver(), Logger::ERROR);
            foreach($cursos as $curso_id => $datos){
                $curso=new Cursos();
                $curso=$curso->find($curso_id);
                $materia = $curso->materia();
                $myLog->log("        Curso ".$materia->nombre, Logger::ERROR);

            }
        }

        $myLog->log("---------------------------------------------------------------------- ", Logger::ERROR);
        $myLog->log("Detalle de calificaciones faltantes. ", Logger::ERROR);

        foreach($info as $grupo_id => $cursos){

            $grupo=new Grupos();
            $grupo=$grupo->find($grupo_id);

            $myLog->log("Faltan calificaciones en el grupo ".$grupo->ver(), Logger::ERROR);
            foreach($cursos as $curso_id => $datos){
                $curso=new Cursos();
                $curso=$curso->find($curso_id);
                $materia = $curso->materia();
                $myLog->log("        Curso ".$materia->nombre, Logger::ERROR);
                foreach($datos as $ff){
                $f=$ff["ac"];
                $t=$ff["t"];
                $alumno=new Alumnos();
                $alumno=$alumno->find($f->alumnos_id);
                $myLog->log("                Falta calificación de tipo ".$t." para el alumno ".$alumno->codigo." ".$alumno->nombre(), Logger::ERROR);
                }
            }
        }

        /*foreach($faltantes as $ff){
            $f=$ff["ac"];
            $t=$ff["t"];
            $curso=new Cursos();
            $curso=$curso->find($f->cursos_id);
            $grupo=$curso->grupo();
            $alumno=new Alumnos();
            $alumno=$alumno->find($f->alumnos_id);

            $myLog->log("Falta calificación de tipo ".$t." en el curso " .
                        $grupo->ver()." ".$curso->verMateriaNombre()." para el alumno ".$alumno->codigo." ".$alumno->nombre(), Logger::ERROR);

        }*/
        $myLog->close();
        if($rdatos)
        return $faltantes;
        else
        return $nombre;
    }


    public function deClaveAOportunidad($clave){
        $tmp = explode('-', $clave);
        $oportunidad = $tmp[1];
        return $oportunidad;
    }

    public function editables($calificaciones, $disponibles){
        foreach($calificaciones as $modo => $claves){
            if($modo == 'AGR' || $modo == 'EDI'){
                foreach($claves as $clave => $valores){
                    if(!in_array($clave, $disponibles)){
                        return false;
                    }
                }
            }else{
                return false;
            }
        }
        return true;
    }



    public function oportunidadClaves(){
        $claves = array();
        $oportunidades = new Oportunidades();
        $oportunidades = $oportunidades->find();

        foreach($oportunidades as $oportunidad){
            $claves[$oportunidad->clave] = $oportunidad->id;
        }

        return $claves;
    }

    public function status($c = ''){
        $c = ($c == '' ? $this->valor : $c);
        if($c >= 60 || $c == 'A'){
            return 'aprobado';
        }else{
            return 'reprobado';
        }
    }

    public function valida($tipo = ''){
        if($this->valor == 'SD'){
            return true;
        }else if($tipo == 'TLR' || $tipo == 'PRO'){
            if($this->valor == 'A' || $this->valor == 'NA'){
                return true;
            }
        }else if($this->valor >= 0 && $this->valor <= 100){
            $this->valor = intval($this->valor, 10);
            return true;
        }
        return false;
    }


    public function infoestadistica($curso_id){
        $calificaciones=new Calificaciones();
        $calificaciones=$calificaciones->find("cursos_id='".$curso_id."'");

        $cals=array();
        $datos=array();
        foreach($calificaciones as $calificacion){
            if($calificacion->valor>59 || $calificacion->valor=="A"){
            $datos[$calificacion->oportunidades_id]['a'][]=$calificacion;
            }else{
            $datos[$calificacion->oportunidades_id]['r'][]=$calificacion;
            }
            $cals[$calificacion->alumnos_id][$calificacion->oportunidades_id] = $calificacion->valor;
        }

        $curso = new Cursos();
        $curso = $curso->find($curso_id);
        $materia = $curso->materia();
        $tppromedio = 0;
        if(strToUpper($materia->tipo)!="TLR" && strToUpper($materia->tipo)!="PRO"){
            $ordinario=new Oportunidades();
            $ordinario=$ordinario->ordinario();

            $extra=new Oportunidades();
            $extra=$extra->extraordinario();
            foreach($cals as $ca){
                $ord = $ca[$ordinario->id];
                 $ext = $ca[$extra->id];
                 if($ord!=null && is_numeric($ord)){ //ordinario es numerico
                    if($ord>=60){
                        $tppromedio += $ord; //no aprobatoria
                    }else{ // revisa el extra
                        if($ext!=null && is_numeric($ext)){ //extraordinario es numerico
                            $tppromedio += $ext;
                         }else{ // no es numerico coloca el ord
                             $tppromedio += $ord;
                         }
                    }
                 }elseif($ext!=null && is_numeric($ext)){  //extraordinario es numerico
                    $tppromedio += $ext;
                 }else{ // no tiene derecho ni en ordinario ni en ext
                     $tppromedio += 0;
                 }
                }
        }



        $datos2=array();
        $datos2['pm'] = round($tppromedio/count($cals),2);
        $datos2['pmt'] = $tppromedio;
        foreach($datos as $d=>$dato){
            $datos2[$d]['a']=count($dato['a']);
            $datos2[$d]['r']=count($dato['r']);
            $datos2[$d]['t']=$datos2[$d]['r']+$datos2[$d]['a'];

            if(trim($datos2[$d]['a'])=="")
                    $datos2[$d]['a']=0;
            if(trim($datos2[$d]['r'])=="")
                    $datos2[$d]['r']=0;

            if($datos2[$d]['t']<=0){
            $datos2[$d]['pa']=0;
            $datos2[$d]['pr']=0;
            }else{

            $datos2[$d]['pa']=round(($datos2[$d]['a']/$datos2[$d]['t'])*100,2);
            $datos2[$d]['pr']=round(($datos2[$d]['r']/$datos2[$d]['t'])*100,2);

            }

        }

            $datos2['ap']=round((($datos2[1]['a']+$datos2[2]['a'])/($datos2[1]['a']+$datos2[1]['r']))*100,2);
            $datos2['rp']=100-$datos2['ap'];

        if($datos2[2]['r']=="" && $datos2[2]['a']==""){
            $datos2[2]['r']="-";
            $datos2[2]['a']="-";
            $datos2[2]['pa']="-";
            $datos2[2]['pr']="-";
        }

        return $datos2;
    }

}
?>