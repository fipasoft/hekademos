<?php
/*
 * Created on 08/03/2009
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

 class Avance{
    private $ciclo;
    private $IRREGULAR=4;
    private $EGRESADO=3;
    private $BAJA=2;
    private $REGULAR=1;
    private $SIGUIENTE;
    private $ARTICULOS;
     function Avance($ciclo){
         $this->ciclo=$ciclo;

         $situaciones=new Situaciones();
         $situaciones=$situaciones->find_first("clave='IRR'");
        $this->IRREGULAR=$situaciones->id;

        $situaciones=new Situaciones();
         $situaciones=$situaciones->find_first("clave='EGR'");
        $this->EGRESADO=$situaciones->id;

        $situaciones=new Situaciones();
         $situaciones=$situaciones->find_first("clave='BJA'");
        $this->BAJA=$situaciones->id;

        $situaciones=new Situaciones();
         $situaciones=$situaciones->find_first("clave='REG'");
        $this->REGULAR=$situaciones->id;

        $sig=new Ciclos();
        $this->SIGUIENTE=$sig->find_first("numero='".$this->ciclo->siguiente()."'");

        $this->cargaArticulos();
     }


     private function cargaArticulos(){
         $articulos=new Articulos();
         $articulos =$articulos->find();

         foreach($articulos as $articulo){
             $this->ARTICULOS[$articulo->clave]=$articulo;
         }

         $a=new Articulos();
         $a->clave='baja';

         $this->ARTICULOS['baja']=$a;


     }


     public function procesar(){
         $grupos=new Grupos();
        $grupos=$grupos->find("ciclos_id='".$this->ciclo->id."' ORDER BY grado,letra,turno");
        foreach($grupos as $grupo){
                $alumnos=$grupo->alumnos();

                foreach($alumnos as $alumno){

                        $cursos=$alumno->cursos($grupo->ciclos_id);
                        $aprobados=array();
                        $reprobados=array();
                        $articulos=array();
                        foreach($cursos as $curso){

                            if(!$this->aprobo($alumno->id,$curso->id)){
                                $reprobados[]=$curso;
                                $alumcurso=new Alumnoscursos();
                                $alumcurso=$alumcurso->find_first("cursos_id='".$curso->id."' AND alumnos_id='".$alumno->id."'");
                                if($alumcurso->id!=""){
                                    $articulos[$curso->id]=$this->obtenArticulo($alumcurso);
                                }
                            }else{
                                $aprobados[]=$curso;
                            }

                        }

                        $proximos=$this->proximosCursos($alumno->id,$reprobados,$grupo);

                        if(count($reprobados)>0){
                            //cambiar status de alumno a irregular
                            $alumno->situaciones_id=$this->IRREGULAR;

                        }

                        if(count($reprobados)>=3){
                            $this->inscripcionGrupo($alumno->id,$grupo,true);
                        }else{
                            if($grupo->grado==6){
                            //cambiar status de alumno a egresado
                            $alumno->situaciones_id=$this->EGRESADO;
                            }else{
                            $this->inscripcionGrupo($alumno->id,$grupo);
                            }

                        }



                        foreach($proximos as $proximo){
                            $this->inscripcionCurso($alumno->id,$proximo->id,$articulos[$proximo->id]);
                        }

                        $alumno->save();
                }
        }
     }


    private function obtenArticulo($alumcurso){
        $articulo=new Alumnosconarticulo();
        $articulo=$articulo->articuloCurso($alumcurso->id);
        if($articulo->id!=''){
            switch($articulo->clave){
                case 'rep':
                    return $this->ARTICULOS['art33'];
                break;

                case 'art33':
                    return $this->ARTICULOS['art34'];
                break;

                case 'art34':
                    return $this->ARTICULOS['art35'];
                break;

                case 'art35':
                    return $this->ARTICULOS['baja'];
                break;

                default:
                    return null;
                    break;

            }
        }else{
            return $this->ARTICULOS['rep'];
        }
    }


     private function aprobo($alumno_id,$curso_id){
             $calificaciones=new Calificaciones();
             $calificaciones=$calificaciones->find("cursos_id='".$curso_id."' AND alumnos_id='".$alumno_id."' ORDER BY oportunidades_id");
             foreach($calificaciones as $calificacion){
                 if($calificacion->valor > 59 || $calificacion->valor == 'A'){
                             return true;
                 }

             }

             return false;
     }


    //Obtiene los proximos cursos del alumno.
     private function proximosCursos($alumno_id,$reprobados,$grupo){
         $proximos=array();
         $reprob=array_merge(array(),$reprobados);
        if(count($reprob)<3){
        $grupo_siguiente=new Grupos();
        $grupo_siguiente=$grupo_siguiente->find_first(
                            "ciclos_id='".$this->SIGUIENTE->id."' AND
                             grado='".($grupo->grado+1)."' AND
                             letra='".$grupo->letra."' AND
                             turno='".$grupo->turno."' "
        );


        $cc=$grupo_siguiente->cursos();

        $cursos=array();
        foreach($cc as $c){
            $cursos[$c->materia_id]=$c;
        }

        $materias=new Materias();
        $materias=$materias->find("semestre='".($grupo->grado+1)."'");
        foreach($materias as $m){
                if($m->tipo=="OBL" || $m->tipo=="TLR"){
                    if(array_key_exists($m->id,$cursos)){
                        $proximos[]=$cursos[$m->id];
                    }

                }


        }


        }

        $grupo_siguiente=new Grupos();
        $grupo_siguiente=$grupo_siguiente->find_first(
                            "ciclos_id='".$this->SIGUIENTE->id."' AND
                             grado='".$grupo->grado."' AND
                             letra='".$grupo->letra."' AND
                             turno='".$grupo->turno."' "
        );
        $cc=$grupo_siguiente->cursos();

        $cursos=array();
        foreach($cc as $c){
            $cursos[$c->materia_id]=$c;
        }

        foreach($reprob as $k=>$reprobado){
                if(array_key_exists($reprobado->materia_id,$cursos)){
                        $proximos[]=$cursos[$reprobado->materia_id];
                        unset($reprob[$k]);
                    }
        }


        foreach($reprob as $reprobado){
            $curso=$this->buscaCurso($reprobado->materia_id,$grupo);
            if($curso->id==''){
                            //Posible error
            }else{
                $proximos[]=$curso;
            }
        }



         return $proximos;

     }

     public function avanceAlumno(){
         $myLog = new Logger('cierre.log');
        $myLog->begin();
         $alumno=new Alumnos();
         $alumno=$alumno->find('2701');
         $myLog->log("**************Inicio******************");
         $myLog->log("Alumno: ".$alumno->id, Logger::DEBUG);
         $grupo=$alumno->obtenerGrupo();
         $myLog->log("Alumno: ".$grupo->verInfo(), Logger::DEBUG);

                        $cursos=$alumno->cursos($grupo->ciclos_id);
                        $aprobados=array();
                        $reprobados=array();
                        $articulos=array();
                        $baja=false;
                        foreach($cursos as $curso){

                            if(!$this->aprobo($alumno->id,$curso->id)){
                                $reprobados[]=$curso;
                                $alumcurso=new Alumnoscursos();
                                $alumcurso=$alumcurso->find_first("cursos_id='".$curso->id."' AND alumnos_id='".$alumno->id."'");
                                if($alumcurso->id!=""){
                                    $myLog->log("alumcurso ".$alumcurso->id, Logger::DEBUG);
                                    $art=$this->obtenArticulo($alumcurso);
                                    $myLog->log($curso->materia_id." clave ".$art->clave, Logger::DEBUG);
                                    $articulos[$curso->materia_id]=$art;

                                    if($art->clave=='baja'){
                                        $baja=true;
                                    }




                                }
                            }else{
                                $aprobados[]=$curso;
                            }

                        }


                        if(count($reprobados)>0){
                            if($baja){
                                $alumno->situaciones_id=$this->BAJA;
                                $myLog->log( "Alumno de baja", Logger::DEBUG);
                                }else{
                                    //cambiar status de alumno a irregular
                                    $alumno->situaciones_id=$this->IRREGULAR;
                                    $myLog->log("situaciones: Irregular ".count($reprobados), Logger::DEBUG);
                                }
                        }


                        if(count($reprobados)>=3){
                            $g=$this->inscripcionGrupo($alumno->id,$grupo,true);
                            $myLog->log("Grupo nuevo: ".$g->verInfo(), Logger::DEBUG);
                            $myLog->log("Alumno: retenido", Logger::DEBUG);

                            $proximos=$this->proximosCursos($alumno->id,$reprobados,$grupo);
                        }else{
                            if($grupo->grado==6){
                            //cambiar status de alumno a egresado
                            $alumno->situaciones_id=$this->EGRESADO;
                            $myLog->log("situaciones: Egresado", Logger::DEBUG);
                            }else{
                            $g=$this->inscripcionGrupo($alumno->id,$grupo);
                            $myLog->log("Grupo nuevo: ".$g->verInfo(), Logger::DEBUG);
                            $myLog->log("Alumno: ".$alumno->id, Logger::DEBUG);

                            $proximos=$this->proximosCursos($alumno->id,$reprobados,$grupo);
                            }

                        }


                        if(count($reprobados)==0){
                            $alumno->situaciones_id=$this->REGULAR;
                            $myLog->log("Alumno Regular ", Logger::DEBUG);
                        }


                        foreach($proximos as $proximo){
                            //$this->inscripcionCurso($alumno->id,$proximo->id,$articulos[$proximo->id]);
                            $myLog->log($proximo->materia_id." inscripcionCurso: ".$proximo->id." articulo: ".$articulos[$proximo->materia_id]->clave, Logger::DEBUG);
                        }

                        //$alumno->save();
                        $myLog->log("**************Fin******************");
                        $myLog->commit();
                        $myLog->close();

     }

    //Inscribe al alumno al curso indicado
     private function inscripcionCurso($alumno_id,$curso_id,$articulo_id=null){
         $inscripcion=new Alumnoscursos();
        $inscripcion->alumnos_id=$alumno->id;
        $inscripcion->cursos_id=$proximo->id;
        $inscripcion->save();

        if($articulo_id!=null){
            $articulo=new Alumnosconarticulo();
            $articulo->articulos_id=$articulo_id;
            $articulo->alumnoscursos_id=$inscripcion->id;
        }
     }


     private function inscripcionGrupo($alumno_id,$grupo,$rep=false){

        $grupo_siguiente=new Grupos();
         if($rep){
        $grupo_siguiente=$grupo_siguiente->find_first(
                            "ciclos_id='".$this->SIGUIENTE->id."' AND
                             grado='".$grupo->grado."' AND
                             letra='".$grupo->letra."' AND
                             turno='".$grupo->turno."' "
                             );
         }else{
        $grupo_siguiente=$grupo_siguiente->find_first(
                            "ciclos_id='".$this->SIGUIENTE->id."' AND
                             grado='".($grupo->grado+1)."' AND
                             letra='".$grupo->letra."' AND
                             turno='".$grupo->turno."' "
        );

         }
            $grupo=new Alumnosgrupo();
            if(!$grupo->exists("alumnos_id=".$alumno_id." AND grupos_id=".$grupo_siguiente->id)){
            $grupo->alumnos_id=$alumno_id;
            $grupo->grupos_id=$grupo_siguiente->id;
            $grupo->save();
            }

            return $grupo_siguiente;
     }


     private function buscaCurso($materia_id,$grupo){
         $curso=new Cursos();
         $curso=$curso->find_by_sql(
                     "SELECT materias.nombre,grupos.*,cursos.* FROM
                            cursos
                            INNER JOIN materias ON cursos.materias_id=materias.id
                            INNER JOIN grupos ON cursos.grupos_id=grupos.id
                            WHERE grupos.ciclos_id='".$this->SIGUIENTE->id."' AND materias.id='".$materia_id."' AND grupos.turno='".$grupo->turno."'"
         );

         if($curso->id!=''){
         $curso=new Cursos();
         $curso=$curso->find_by_sql(
                     "SELECT materias.nombre,grupos.*,cursos.* FROM
                            cursos
                            INNER JOIN materias ON cursos.materias_id=materias.id
                            INNER JOIN grupos ON cursos.grupos_id=grupos.id
                            WHERE grupos.ciclos_id='".$this->SIGUIENTE->id."' AND materias.id='".$materia_id."'"
         );
         }

         return $curso;

     }

 }
?>
