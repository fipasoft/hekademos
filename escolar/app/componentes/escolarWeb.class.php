<?php

class escolarWeb{
    public $total_cursos;
    public $promedio;

    public function escolarWeb(){     }

    public function periodoalumnoID($id,$periodo_id){
        $db = new db("localhost", "hekademos", "hekademos", "hekademos");
        $consulta="SELECT * FROM periodosalumnos WHERE alumnos_id='$id' AND periodo_id='$periodo_id' ";
           $alumno=$db->fetch_one($consulta);
           return $alumno["id"];

    }

    public function alumnotienetrayectoria($alumno_id){
        $db = new db("localhost", "hekademos", "hekademos", "hekademos");
        $consulta="SELECT * FROM alumnotrayectoria WHERE alumnos_id='$alumno_id'";
           $alumno=$db->fetch_one($consulta);
           return !($alumno["id"]=="");

    }

    public function ofertancursos($periodo_id){
        $consulta = "SELECT count(*) AS total
                    FROM
                    periodo
                    INNER JOIN periodoscursos ON periodo.id = periodoscursos.periodo_id
                    WHERE periodo_id='".$periodo_id."'";
        $db = new db("localhost", "hekademos", "hekademos", "hekademos");
        $reg=$db->in_query($consulta);
           $db->close();
        return ($reg[0]["total"] > 0);
    }

    public function ofertantrayectorias($periodo_id){
        $consulta = "SELECT count(*) AS total
                    FROM
                    periodo
                    INNER JOIN periodotrayectoria ON periodo.id = periodotrayectoria.periodo_id
                    WHERE periodo_id='".$periodo_id."'";
        $db = new db("localhost", "hekademos", "hekademos", "hekademos");
        $reg=$db->in_query($consulta);
           $db->close();
        return ($reg[0]["total"] > 0);
    }

    public function agendaActiva($inicio,$fin){
        $inicio=new DateTime($inicio);
        $fin=new DateTime($fin);
        $hoy=new DateTime();

        if(($inicio->format('U')<=$hoy->format('U')) && ($hoy->format('U')<=$fin->format('U')) )
        return true;
        else
        return false;
    }

    public function alumnocursos($periodo,$alumno){
        $db = new db("localhost", "hekademos", "hekademos", "hekademos");
        $consulta="SELECT periodoscursos.*,materias.nombre,grupos.grado,grupos.letra,grupos.turno,materias.tipo FROM inscripcion " .
                    " INNER JOIN periodoscursos ON inscripcion.periodoscursos_id=periodoscursos.id " .
                    " INNER JOIN cursos ON periodoscursos.cursos_id=cursos.id " .
                    " INNER JOIN grupos ON cursos.grupos_id=grupos.id " .
                    "INNER JOIN materias ON cursos.materias_id=materias.id" .
                    " WHERE inscripcion.periodosalumnos_id='$alumno' AND periodoscursos.periodo_id='$periodo' ";
           $cursos=$db->in_query($consulta);
           $db->close();
           if(!is_array($cursos))
               return array();

           return $cursos;
    }

    public function alumnotrayectoria($periodo,$alumno){
        $db = new db("localhost", "hekademos", "hekademos", "hekademos");
        $consulta="SELECT periodotrayectoria.*
                FROM
                periodotrayectoria
                INNER JOIN periodotrayectoriaalumno ON periodotrayectoria.id = periodotrayectoriaalumno.periodotrayectoria_id
                WHERE periodotrayectoriaalumno.periodosalumnos_id ='$alumno' AND periodotrayectoria.periodo_id = '$periodo'
                LIMIT 1";

           $trayectorias=$db->in_query($consulta);
           $db->close();
           if(!is_array($trayectorias))
               return array();

           return $trayectorias;
    }

    public function alumnotrayectorianombre($periodo,$alumno){
        $trayectorias = $this->alumnotrayectoria($periodo,$alumno);
        $t=$trayectorias[0]["trayectoriaespecializante_id"];
        $consulta = "SELECT nombre FROM trayectoriaespecializante WHERE id='".$t."'";
         $db = new db("localhost", "hekademos", "hekademos", "hekademos");
        $trayectoria=$db->in_query($consulta);
           $db->close();
           return $trayectoria[0]["nombre"];

    }

    public function alumnogrupo($alumno_id,$ciclo_id){
         $db = new db("localhost", "hekademos", "hekademos", "hekademos");
        $consulta="SELECT grupos.* FROM " .
                " alumnosgrupo " .
                " INNER JOIN grupos ON alumnosgrupo.grupos_id=grupos.id " .
                " WHERE alumnosgrupo.alumnos_id='".$alumno_id."' AND grupos.ciclos_id='".$ciclo_id."' ".
                " LIMIT 0,1";

           $alumno=$db->fetch_one($consulta);
        $db->close();
        return $alumno;
    }

    public function cursosPeriodo($periodo_id,$alumno_id){
         $db = new db("localhost", "hekademos", "hekademos", "hekademos");
        $consulta="SELECT * FROM periodo WHERE id='".$periodo_id."'";
           $periodo=$db->fetch_one($consulta);
           $db->close();
        $alumno=$this->alumnogrupo($alumno_id,$periodo["ciclos_id"]);

         $db = new db("localhost", "hekademos", "hekademos", "hekademos");

        $consulta="SELECT
                    periodoscursos.id AS  periodoscursos_id,
                    cursos.id AS curso_id,cursos.profesores_id AS curso_profesorid,cursos.estado_id,
                    periodoscursos.cupos,materias.nombre AS materia_nombre,materias.tipo AS materia_tipo,(periodoscursos.cupos-periodoscursos.inscritos) AS total,
                    periodoscursos.tipos_id,grupos.*,oferta.nombre AS grupo_oferta
                     FROM
                    periodo
                    INNER JOIN periodoscursos ON periodoscursos.periodo_id=periodo.id
                    INNER JOIN cursos ON periodoscursos.cursos_id=cursos.id
                    INNER JOIN materias ON cursos.materias_id=materias.id
                    INNER JOIN grupos ON cursos.grupos_id=grupos.id
                    INNER JOIN oferta ON grupos.oferta_id=oferta.id
                    WHERE periodo.id='".$periodo_id."' AND grupos.oferta_id='".$alumno["oferta_id"]."'  AND grupos.grado='".($alumno["grado"]+1)."' AND grupos.turno='".$alumno["turno"]."'";
        $cursos=$db->in_query($consulta);
        $cc=array();
        foreach($cursos as $c){
        $cc[$c['materia_tipo']][]=$c;
        }
           $db->close();
           return $cc;
    }


    public function trayectoriasPeriodo($periodo_id,$alumno_id){
         $db = new db("localhost", "hekademos", "hekademos", "hekademos");
        $consulta="SELECT * FROM periodo WHERE id='".$periodo_id."'";
           $periodo=$db->fetch_one($consulta);
           $db->close();
        $alumno=$this->alumnogrupo($alumno_id,$periodo["ciclos_id"]);

         $db = new db("localhost", "hekademos", "hekademos", "hekademos");

        $consulta="SELECT trayectoriaespecializante.nombre , periodotrayectoria.*,
                    (periodotrayectoria.cupos - periodotrayectoria.inscritos) AS total
                    FROM
                    trayectoriaespecializante
                    INNER JOIN periodotrayectoria ON trayectoriaespecializante.id = periodotrayectoria.trayectoriaespecializante_id
                    WHERE periodotrayectoria.periodo_id='".$periodo_id."' AND periodotrayectoria.turno='".$alumno["turno"]."'";
        $trayectorias=$db->in_query($consulta);
        return $trayectorias;
    }

    public function optativasConfiguracion($periodo_id){
        $config=array();
             $db = new db("localhost", "hekademos", "hekademos", "hekademos");
            $consulta=" SELECT * FROM periodoconfiguracion WHERE periodo_id='".$periodo_id."' " .
                                                " ORDER BY turno,grado,tipo";

            $configuracion=$db->in_query($consulta);

            foreach($configuracion as $cn){
                $config[$cn["oferta_id"]][$cn["turno"]][$cn["grado"]][$cn["tipo"]]=$cn;
            }

            return $config;

    }


    public function periodo($id,$alumno_id){
         $db = new db("localhost", "hekademos", "hekademos", "hekademos");
           $consulta="    SELECT periodo.id AS periodo_id,
                    periodo.inicio AS periodo_inicio,
                    periodo.fin AS periodo_fin,
                    periodo.activo AS periodo_activo,
                    periodo.ciclos_id,
                    bloque.id AS bloque_id,
                    bloque.inicio AS bloque_inicio,
                    bloque.fin AS bloque_fin FROM periodo " .
                     " INNER JOIN periodosalumnos ON periodo.id=periodosalumnos.periodo_id".
                     " INNER JOIN bloquesalumnos ON periodosalumnos.id=bloquesalumnos.periodosalumnos_id " .
                     " INNER JOIN bloque ON bloquesalumnos.bloque_id=bloque.id " .
                     " WHERE periodo.id='".$id."' AND periodosalumnos.id='".$alumno_id."'";
           $periodo=$db->fetch_one($consulta);
           $db->close();
           return $periodo;
           }


    public function agenda($id,$ciclo_id){
         $db = new db("localhost", "hekademos", "hekademos", "hekademos");
           $consulta="    SELECT
                    periodo.id AS periodo_id,
                    periodo.inicio AS periodo_inicio,
                    periodo.fin AS periodo_fin,
                    periodo.activo AS periodo_activo,
                    bloque.id AS bloque_id,
                    bloque.inicio AS bloque_inicio,
                    bloque.fin AS bloque_fin
                    FROM
                     periodo
                    INNER JOIN periodosalumnos ON periodo.id=periodosalumnos.periodo_id
                    INNER JOIN bloquesalumnos ON periodosalumnos.id=bloquesalumnos.periodosalumnos_id
                    INNER JOIN bloque ON bloquesalumnos.bloque_id=bloque.id
                       WHERE periodosalumnos.alumnos_id='".$id."' AND periodo.ciclos_id='".$ciclo_id."' AND periodo.activo='1'";
           $periodoalumno=$db->in_query($consulta);
           $db->close();
           return $periodoalumno;
           }

    public function accesos($codigo,$dia){
    $accesos=array();
    if($dia=="")
        $dia=date("Y-m-d",time());

        $accesos["date"]=$dia;


    $afxuser=new AFxUser();
    $afxuser=$afxuser->find_first("UserInfo3='".$codigo."'");
    if($afxuser->CardNumber==""){
        $accesos["valido"]=false;
        $accesos["error"]="El alumno no cuenta con informacion de su tarjeta.";
    }else{

        $accesos["valido"]=true;
        $accesos["tarjeta"]=$afxuser->CardNumber;
        $f=split("-",$dia);
        if(checkdate($f[1],$f[2],$f[0])){
                try{
                $date=new DateTime($dia);
                $accesos["fecha"]=$date->format("j")." de ". mes_espanol($date->format("m"))." de ".$date->format("Y");
                $eventos=new ViewEvents();
                //se resta 2.041666667 por que la fecha esta adelantada con 2 dias y 1, .041666667 igual a 1 hora en el formato
                // de fecha de SQL Server
                $q="SELECT ViewUser.UserInfo3,ViewEvents.UniqueID,CAST((ViewEvents.PanelLocalDT - 2.041666667) AS datetime) AS fecha,ViewEvents.DoorNumberText,
                                ViewEvents.CardNumber,ViewEvents.UserNumberText,ViewEvents.AreaNumberText,ViewDoor.DoorNumber,ViewDoor.PodDoorIndex
                      FROM [Director].[dbo].[ViewUser]
                          INNER JOIN [Director].[dbo].[ViewEvents] ON ViewUser.CardNumber=ViewEvents.CardNumber
                        INNER JOIN [Director].[dbo].[ViewDoor] ON ViewEvents.DoorNumber=ViewDoor.DoorNumber
                          WHERE PanelLocalDT > CAST(CAST('".$dia." 00:00:01' AS datetime) AS float)+2.041666667 AND
                      PanelLocalDT < CAST(CAST('".$dia." 23:59:59' AS datetime) AS float)+2.041666667 AND (ViewUser.CardNumber='".$afxuser->CardNumber."')";
                $accesos["eventos"]=$eventos->find_all_by_sql($q);
                $entradas=0;
                $salidas=0;
                foreach($accesos["eventos"] as $e){
                    if($e->esEntrada()==true)
                        $entradas++;
                        elseif($e->esSalida()==true)
                        $salidas++;
                }
                $accesos["entradas"]=$entradas;
                $accesos["salidas"]=$salidas;

        }catch(ActiveRecordException $e){
                $accesos["valido"]=false;
                $accesos["error"]="Ocurrio un error en la base de datos.<br/>".$q;


                }catch(Exception $e){
                $accesos["valido"]=false;
                $accesos["error"]="La fecha no es valida.";

                }
        }



        }

        return $accesos;
    }

    public function abierto(){
        return true;
        $consulta="SELECT * FROM wp5contenido WHERE id=27";
        $db = new db("localhost", "hekademos", "hekademos", "hekademos");
           $contenido=$db->in_query($consulta);
           $consulta="SELECT * FROM wp5modulo WHERE id=6";
           $modulo=$db->in_query($consulta);
           if($modulo[0]['mod_status']!=1 || $contenido[0]['con_status']!=1){
           return false;
           }
           return true;

    }

    public function verificaPassword($id,$tipo,$password,$id_tutor){
        if($tipo=="tutores"){
            if($id_tutor==null)return false;

            $tutor=$this->getTutor($id_tutor);
            $consulta="SELECT * FROM tutorespassword WHERE tutores_id='".$tutor["id"]."'";
              $db = new db("localhost", "hekademos", "hekademos", "hekademos");
               $pass=$db->fetch_one($consulta);
               $db->close();
               if($pass!=null && $pass["pass"]==sha1($password))
                   return true;
                   else return false;

        }else if($tipo=="alumnos"){
            $consulta="SELECT * FROM alumnospassword WHERE alumnos_id='".$id."'";
              $db = new db("localhost", "hekademos", "hekademos", "hekademos");
               $pass=$db->fetch_one($consulta);
               $db->close();
               if($pass!=null && $pass["pass"]==sha1($password))
                   return true;
                   else return false;

        }else return false;
    }

    function cambiaPassword($id,$tipo,$password,$id_tutor){
        if($tipo=="tutores"){
            if($id_tutor==null)return false;

            $tutor=$this->getTutor($id_tutor);
             $db = new db("localhost", "hekademos", "hekademos", "hekademos");
            $consulta="SELECT tutoria.alumnos_id,tutoria.tutores_id,tutorespassword.pass FROM tutoria,tutorespassword WHERE tutoria.alumnos_id='$id'  " .
                    "AND tutorespassword.tutores_id=tutoria.tutores_id AND tutorespassword.pass='".sha1($password)."'";
            $tutores=$db->in_query($consulta);
            if($db->num_rows()==0){
            $consulta="UPDATE tutorespassword SET pass='".sha1($password)."' WHERE tutores_id='".$tutor["id"]."'";
               $pass=$db->query($consulta);

            return true;
            }else{
                return false;
            }
            $db->close();

        }else if($tipo=="alumnos"){
            $consulta="UPDATE alumnospassword SET pass='".sha1($password)."' WHERE alumnos_id='".$id."'";
              $db = new db("localhost", "hekademos", "hekademos", "hekademos");
               $pass=$db->query($consulta);
               $db->close();
            return true;

        }else return false;
    }


     public function getAlumno($id){
     $consulta="SELECT  alumnos.*, situaciones.nombre AS situacion FROM  alumnos, situaciones WHERE  alumnos.situaciones_id= situaciones.id AND   alumnos.id='".$id."'";
      $db = new db("localhost", "hekademos", "hekademos", "hekademos");
       $alumno=$db->in_query($consulta);
       $db->close();
       return $alumno[0];
     }

     public function getAlumnoGrupo($id){
     $ultimo_ciclo=$this->cicloActivo();
     $ultimo_ciclo=$ultimo_ciclo["id"];
         $consulta="SELECT  situaciones.nombre AS situacion, alumnos.id AS alumnos_id,alumnos.codigo,alumnos.ap,alumnos.am,alumnos.nombre,alumnos.domicilio,alumnos.tel,alumnos.cel,alumnos.mail,alumnos.foto,alumnos.curp,alumnos.fnacimiento,alumnos.sexo,alumnos.admision,alumnos.promedio,alumnos.aprobadas,grupos.id AS grupo_id,grupos.grado,grupos.letra,grupos.turno,grupos.oferta_id" .
                " FROM  situaciones,alumnos,alumnosgrupo,grupos WHERE  alumnos.situaciones_id= situaciones.id AND alumnosgrupo.grupos_id=grupos.id AND alumnosgrupo.alumnos_id='".$id."' AND alumnos.id='".$id."' AND grupos.ciclos_id='".$ultimo_ciclo."'";
      $db = new db("localhost", "hekademos", "hekademos", "hekademos");
       $alumno=$db->in_query($consulta);
       $db->close();
       return $alumno[0];
     }


    public function getTutoresDeAlumno($id){
     $consulta="SELECT tutores.* FROM tutores,alumnos,tutoria WHERE alumnos.id=tutoria.alumnos_id AND tutores.id=tutoria.tutores_id AND alumnos.id='".$id."'";
      $db = new db("localhost", "hekademos", "hekademos", "hekademos");
       $tutores=$db->in_query($consulta);
       $db->close();
       return $tutores;

     }

     public function getTutorDeAlumno($id){
     $consulta="SELECT tutores.* FROM tutores,alumnos,tutoria WHERE alumnos.id=tutoria.alumnos_id AND alumnos.id='".$id."'";
      $db = new db("localhost", "hekademos", "hekademos", "hekademos");
       $tutor=$db->in_query($consulta);
       $db->close();
       return $tutor[0];

     }

    public function getTutor($id){
     $consulta="SELECT * FROM tutores WHERE id='".$id."'";
      $db = new db("localhost", "hekademos", "hekademos", "hekademos");
       $tutor=$db->in_query($consulta);
       $db->close();
       return $tutor[0];
     }


    public function cicloActivo(){
         $db = new db("localhost", "hekademos", "hekademos", "hekademos");
        $elementos=$db->in_query("SELECT * FROM ciclos WHERE activo='1'");
        $db->close();

        return $elementos[0];

    }

    public function ciclosAlumno($id){
        $consulta="SELECT  ciclos.* ";
        $consulta.=" FROM  alumnosgrupo, grupos, ciclos ";
        $consulta.=" WHERE  alumnosgrupo.alumnos_id='".$id."' AND   alumnosgrupo.grupos_id= grupos.id AND  ciclos.id= grupos.ciclos_id";
         $db = new db("localhost", "hekademos", "hekademos", "hekademos");
        $elementos=$db->in_query($consulta);
        $db->close();

        return $elementos;
    }

     public function kardex($alumno_id){
    $consulta=" SELECT  calificaciones.alumnos_id, calificaciones.valor, calificaciones.oportunidades_id,";
    $consulta.="  cursos.materias_id, cursos.profesores_id,";
    $consulta.="  grupos.grado, grupos.letra,";
    $consulta.="  grupos.turno,";
    $consulta.="  ciclos.id as ciclo_id, ciclos.numero, ciclos.inicio, ciclos.fin, ciclos.avance,";
    $consulta.="  materias.clave, materias.nombre AS nombre_materia,";
    $consulta.="  profesores.nombre AS nombre_profesor, profesores.ap AS ap_profesor, profesores.am AS am_profesor,";
    $consulta.="  oportunidades.nombre As oportunidad";
    $consulta.=" FROM  calificaciones, cursos, grupos, ciclos, materias, profesores, oportunidades";
    $consulta.=" WHERE  calificaciones.cursos_id= cursos.id AND  grupos.id= cursos.grupos_id AND  grupos.ciclos_id= ciclos.id AND  materias.id= cursos.materias_id AND  profesores.id= cursos.profesores_id AND  oportunidades.id= calificaciones.oportunidades_id AND  calificaciones.alumnos_id='".$alumno_id."'";
    $consulta.=" order by  ciclos.inicio, materias.nombre, calificaciones.oportunidades_id";
     $db = new db("localhost", "hekademos", "hekademos", "hekademos");
       $elementos=$db->in_query($consulta);
    $db->close();
    $ciclos=array();
    $index=0;
    $this->total_cursos=0;
    $puntajes=0;
    foreach($elementos as $ele){
    if($ele["avance"]==1){
    $ciclos[$ele["ciclo_id"]][$index]=$ele;
    $puntajes+=$ele["valor"];
    $index++;
    $this->total_cursos++;
    }
    }

    if($this->total_cursos>0)
    $this->promedio=($puntajes/$this->total_cursos);
    else
    $this->promedio=0;

       return $ciclos;

     }

    public function obtenCalificaciones($id,$id_ciclo){
        $xml="";

        $consulta="SELECT alumnos.id AS alumnos_id,alumnos.codigo,alumnos.ap,alumnos.am,alumnos.nombre,grupos.id AS grupo_id,grupos.grado,grupos.letra,grupos.turno" .
                " FROM alumnos,alumnosgrupo,grupos WHERE alumnosgrupo.grupos_id=grupos.id AND alumnosgrupo.alumnos_id='".$id."' AND alumnos.id='".$id."' AND grupos.ciclos_id='".$id_ciclo."'";

         $db = new db("localhost", "hekademos", "hekademos", "hekademos");
           $alumno_grupo=$db->fetch_one($consulta);

        $consulta="SELECT cursos.id,materias.id AS materia_id,materias.clave,materias.nombre AS materia_nombre,cursos.profesores_id FROM alumnoscursos,cursos,grupos,materias WHERE alumnoscursos.alumnos_id='".$id."' AND alumnoscursos.cursos_id=cursos.id AND
                    grupos.id=cursos.grupos_id AND grupos.ciclos_id='".$id_ciclo."' AND materias.id=cursos.materias_id";
        $cursos=$db->in_query($consulta);

        $datos_retorno=array();
        $datos_cursos=array();
        $perds=array();
           foreach($cursos as $curso){
                   $consulta="SELECT codigo,nombre,ap,am FROM profesores WHERE id=".$curso["profesores_id"];
                $profesor=$db->fetch_one($consulta);
                $profesor="".$profesor["ap"]." ".$profesor["am"]." ".$profesor["nombre"];
                $consulta="SELECT valor,oportunidades_id FROM calificaciones WHERE cursos_id=".$curso["id"]." AND alumnos_id=".$alumno_grupo["alumnos_id"]." order by oportunidades_id";
                $calificaciones=$db->in_query($consulta);


            $ordinario="";
            $extra="";
            $estado="";
            foreach($calificaciones as $cal){
                    if($cal["oportunidades_id"]==1){
                    $ordinario=$cal["valor"];
                    //$existe_ordinario=1;
                    if($ordinario>59 || $ordinario=='A'){
                        $estado="Aprobado";
                    }else $estado="Reprobado";

                    }else if($cal["oportunidades_id"]==2){
                    $extra=$cal["valor"];

                    if($extra>59){
                        $estado="Aprobado";
                    }else $estado="Reprobado";

                    }
                }


               $consulta="SELECT * FROM calificacionesparciales WHERE cursos_id=".$curso["id"]." AND alumnos_id=".$alumno_grupo["alumnos_id"];
               $parciales=$db->in_query($consulta);
               $datos_cursos[$curso["id"]]=array("id" => $curso["id"],"profesor" => $profesor,"materia_nombre" => $curso["materia_nombre"],
                                              "clave" => $curso["clave"],"materia_id" => $curso["materia_id"],
                                              "ordinario"=>$ordinario,"extra" =>$extra, "status" =>$estado);
               if(sizeof($parciales)>0){
               foreach($parciales as $parcial){
               $datos_retorno[$curso["id"]][$parcial["periodo"]]=array("alumno_id" => $alumno_grupo["alumnos_id"],
                                                                        "grupo_id" => $alumno_grupo["grupo_id"],
                                                                        "curso_id" => $curso["id"],
                                                                     "periodo" => $parcial["crp_periodo"], "valor" => $parcial["valor"]);
            $perds[$parcial["periodo"]]=$parcial["periodo"];
            }

               }else{
               $datos_retorno[$curso["id"]]["-1"]=array("alumno_id" => $alumno_grupo["alumnos_id"],
                                                        "grupo_id" => $alumno_grupo["grupo_id"],
                                                        "curso_id" => $curso["id"],
                                                        "periodo" => 0, "valor" => 0);
            $perds["-1"]="-1";
            }

           }

        $existeValor=0;
        $xml.='<?xml version="1.0" encoding="UTF-8" ?>';
         $xml.='<calificaciones_reporte>';

        $xml.='<tabla>';
                $llaves=array_keys($datos_retorno);
                //$perds=array_keys($datos_retorno[$llaves[0]]);
                $xml.='<periodos>';
                if(sizeof($perds)>0){
                foreach($perds as $p)
                if($p!="-1")
                $xml.='<periodo><valor>'.$p.'</valor></periodo>';

                }else{
                $xml.='<periodo><valor>-</valor></periodo>';

                }
                $xml.='</periodos>';


        foreach($datos_retorno as $curso){
            $xml.="<registro>";
            $llaves=array_keys($curso);

            $crs=$datos_cursos[$curso[$llaves[0]]["curso_id"]];

            $xml.="<materia>".$crs["materia_nombre"]."</materia>";
            foreach($perds as $p){

            if($curso[$p]!=null && $p!=-1 ){
            $periodo=$curso[$p];
            $existeValor+=$periodo["valor"];
            $xml.='<calificacion periodo="'.$periodo["periodo"].'"><valor>'.$periodo["valor"]."</valor></calificacion>";
            }else if($p!=-1 ){
            $xml.='<calificacion periodo="'.$p.'"><valor>-</valor></calificacion>';

            }else $periodo=$curso[$p];
            }

            if($crs["ordinario"]=='')
            $crs["ordinario"]='-';
            else
            $existeValor+=1;

            if($crs["extra"]=='')$crs["extra"]='-';


            if($crs["status"]=='')$crs["status"]='-';

            $xml.="<profesor>".$crs["profesor"]."</profesor>";
            $xml.="<ordinario>".$crs["ordinario"]."</ordinario>";
            $xml.="<extra>".$crs["extra"]."</extra>";
            $xml.="<status>".$crs["status"]."</status>";
            $xml.="</registro>";

        }



        $xml.="</tabla>";
           $xml.='</calificaciones_reporte>';
        if($existeValor==0)
        $xml='<?xml version="1.0" encoding="UTF-8" ?><calificaciones_reporte></calificaciones_reporte>';

           $db->close();

           return $xml;
}

    public function obtenAsistencias($id,$id_ciclo){
        $consulta="SELECT alumnos.id AS alumnos_id,alumnos.codigo,alumnos.ap,alumnos.am,alumnos.nombre,grupos.id AS grupo_id,grupos.grado,grupos.letra,grupos.turno" .
                " FROM alumnos,alumnosgrupo,grupos WHERE alumnosgrupo.grupos_id=grupos.id AND alumnosgrupo.alumnos_id='".$id."' AND alumnos.id='".$id."' AND grupos.ciclos_id='".$id_ciclo."'";

         $db = new db("localhost", "hekademos", "hekademos", "hekademos");
           $alumno_grupo=$db->fetch_one($consulta);

        $consulta="SELECT cursos.id,materias.id AS materia_id,materias.clave,materias.nombre AS materia_nombre,cursos.profesores_id FROM alumnoscursos,cursos,grupos,materias WHERE alumnoscursos.alumnos_id=".$id." AND alumnoscursos.cursos_id=cursos.id AND
                    grupos.id=cursos.grupos_id AND grupos.ciclos_id='".$id_ciclo."' AND materias.id=cursos.materias_id";
        $cursos=$db->in_query($consulta);


        $xml="";
        $datos_retorno=array();
           $materias=array();
           $mesesFecha=array();
           foreach($cursos as $curso){
               $consulta="SELECT id AS asistencia_id,asistenciasvalor_id,dia FROM asistencias WHERE cursos_id=".$curso["id"]." AND alumnos_id=".$alumno_grupo["alumnos_id"];
               $asistencias=$db->in_query($consulta);

               $diasTotales=$db->in_query("SELECT * " .
                                "FROM  asistencias " .
                                "WHERE cursos_id = '" . $curso['id'] . "' " .
                                "GROUP BY dia " .
                                "ORDER BY dia ");
            $dmaterias=array();
            foreach($diasTotales as $dt){
            $dmaterias[substr($dt["dia"],5,2)][substr($dt["dia"],8,2)]=$dt;
            }

               foreach($asistencias as $asistencia){

               $datos_retorno[substr($asistencia["dia"],5,2)][substr($asistencia["dia"],8,2)][$curso["id"]]=array(
                                "alumno_id" => $alumno_grupo["alumnos_id"], "grupo_id" => $alumno_grupo["grupo_id"], "curso_id" => $curso["id"],
                                "materia_id" => $curso["materia_id"], "clave" => $curso["clave"],"materia_nombre" => $curso["materia_nombre"],
                                "asistencia_id" => $asistencia["asistencias_id"], "asistenciavalor_id" => $asistencia["asistenciasvalor_id"],
                                "dia" => $asistencia["dia"]);

            $materias[$curso["id"]]=$curso["materia_nombre"];
            $mesesFecha[substr($asistencia["dia"],5,2)]=substr($asistencia["dia"],0,4);
            }

            foreach($dmaterias as $days){
                foreach($days as $day){
                    $eva=$datos_retorno[substr($day["dia"],5,2)][substr($day["dia"],8,2)][$day["cursos_id"]];
                    if($eva==null){
                        $datos_retorno[substr($day["dia"],5,2)][substr($day["dia"],8,2)][$day["cursos_id"]]=array(
                                "alumno_id" => $alumno_grupo["alumnos_id"], "grupo_id" => $alumno_grupo["grupo_id"], "curso_id" => $curso["id"],
                                "materia_id" => $curso["materia_id"], "clave" => $curso["clave"],"materia_nombre" => $curso["materia_nombre"],
                                "asistencia_id" => "-1", "asistenciavalor_id" => 0,
                                "dia" => $day["dia"]);
                    }

                }
            }
           }

           $db->close();
        $xml='<?xml version="1.0" encoding="UTF-8" ?>';

        if(sizeof($materias)>0){

         $xml.='<asistencias_reporte>';

            $cont=0;
            $meses=array_keys($datos_retorno);
            $resumen=array();
        foreach($datos_retorno as $dia){
        $xml.='<tabla mes="'.mes_espanol($meses[$cont]).'" mes_numerico="'.$meses[$cont].'" mes_fecha="'.$mesesFecha[$meses[$cont]].$meses[$cont].'">';
            $cont++;
            $cont1=0;
                $dias=array_keys($dia);
                $xml.='<dias>';
                foreach($dias as $d)
                if(substr($d,0,1)=='0')
                $xml.='<dia><fecha>'.substr($d,1).'</fecha></dia>';
                else
                $xml.='<dia><fecha>'.$d.'</fecha></dia>';
                $xml.='</dias>';
                $mats=array();
            foreach($dia as $curso){


                    $xmlT='';
                    foreach($curso as $asistencia){

                    $mats[$asistencia['curso_id']][substr($asistencia["dia"],8,2)]=$asistencia;

                    }

            }


            //sort($materias);
            foreach ($materias as $k =>$v){
                $m=$mats[$k];

                $xml.='<asistencias materia="'.$v.'">';
                for($index=0;$index<sizeof($dias);$index++){
                    $dd=$dias[$index];
                    $valor=$m[$dd];
                    if($valor==null)
                    $xml.='<asistencia><dia_asistencia>'.$dd.'</dia_asistencia><valor></valor></asistencia>';
                    else
                    $xml.='<asistencia><dia_asistencia>'.$dd.'</dia_asistencia><valor>'.$valor["asistenciavalor_id"].' </valor></asistencia>';


                    $resumen[$k][$valor["asistenciavalor_id"]]++;


                }

                $xml.='</asistencias>';
                }
        $xml.='</tabla>';
        }

        $xml.="<resumen>";

        foreach ($materias as $k =>$v){
            $materia=$resumen[$k];

            $xml.="<registro>";
            $xml.="<materia>".$v."</materia>";
            if($materia["1"]==0)
            $xml.="<asistencias>0</asistencias>";
            else
            $xml.="<asistencias>".$materia["1"]."</asistencias>";
            if($materia["0"]==0)
            $xml.="<faltas>0</faltas>";
            else
            $xml.="<faltas>".$materia["0"]."</faltas>";

            $total=$materia["1"]+$materia["0"]+$materia["3"];
            if($total==0)
            $res=0;
            else
            $res=$materia["1"]/$total;
            $porcentaje=$res*100;

            $num=number_format($porcentaje, 2, '.', '');

            $cifra=explode('.',$num);

            if($cifra[1]==0)
            $num=$cifra[0];

            $xml.="<porcentaje>".$num."</porcentaje>";

            if($porcentaje>79){
                $status="Ordinario";
            }else if($porcentaje>59){
                $status="Extraordinario";
            }else{
                $status="Sin Derecho";

            }

            $xml.="<status>".$status."</status>";


            $xml.="</registro>";


        }
        $xml.="</resumen>";
        $xml.='</asistencias_reporte>';
        }else $xml='<?xml version="1.0" encoding="UTF-8" ?><asistencias_reporte></asistencias_reporte>';


         //Generamos la salida
         return $xml;
    }

    public function inicio($id_alumno){
        $datos=array();
        $datos["alumno"]=$this->getAlumnoGrupo($id_alumno);
        if($datos["alumno"]==null){
        $datos["alumno"]=$this->getAlumno($id_alumno);
        }
        $ciclos=$this->ciclosAlumno($id_alumno);
        if(sizeof($ciclos)>0){
        $datos["cicloActivo"]=$this->cicloActivo();
        $datos["resumen"]=$this->resumen($id_alumno,$datos["cicloActivo"]["id"]);

        }


        return $datos;
    }

    private function resumen($id,$id_ciclo){
        $consulta="SELECT alumnos.id AS alumnos_id,alumnos.codigo,alumnos.ap,alumnos.am,alumnos.nombre,grupos.id AS grupo_id,grupos.grado,grupos.letra,grupos.turno" .
                " FROM alumnos,alumnosgrupo,grupos WHERE alumnosgrupo.grupos_id=grupos.id AND alumnosgrupo.alumnos_id='".$id."' AND alumnos.id='".$id."'  AND grupos.ciclos_id='".$id_ciclo."'" ;

         $db = new db("localhost", "hekademos", "hekademos", "hekademos");
           $alumno_grupo=$db->fetch_one($consulta);
        $val="";
        $consulta="SELECT cursos.id,materias.id AS materia_id,materias.clave,materias.nombre AS materia_nombre,cursos.profesores_id FROM alumnoscursos,cursos,grupos,materias WHERE alumnoscursos.alumnos_id=".$id." AND alumnoscursos.cursos_id=cursos.id AND
                    grupos.id=cursos.grupos_id AND grupos.ciclos_id='".$id_ciclo."' AND materias.id=cursos.materias_id ORDER BY materias.nombre";
        $cursos=$db->in_query($consulta);

        $datos_retorno=array();
        $perds=array();

           foreach($cursos as $curso){
               $existeValor=0;
               $consulta="SELECT * FROM calificacionesparciales WHERE cursos_id=".$curso["id"]." AND alumnos_id=".$alumno_grupo["alumnos_id"];
               $parciales=$db->in_query($consulta);
               $total=0;
               if(sizeof($parciales)>0){
               foreach($parciales as $parcial){

            $total+=$parcial["valor"];
            }

            $parciales=$total/sizeof($parciales);
               }else{
               $parciales="";
               }

               $existeValor+=$parciales;

            $consulta="SELECT valor,oportunidades_id FROM calificaciones WHERE cursos_id=".$curso["id"]." AND alumnos_id=".$alumno_grupo["alumnos_id"];
            $calificaciones=$db->in_query($consulta);

            $ordinario="";
            $extra="";
            $estado="ND";
            foreach($calificaciones as $cal){
                    if($cal["oportunidades_id"]==1){
                    $ordinario=$cal["valor"];

                    $existeValor+=$ordinario;

                    }else if($cal["oportunidades_id"]==2){
                    $extra=$cal["valor"];

                    $existeValor+=$extra;
                    }
                }

            $consulta="SELECT asistenciasvalor_id,count(asistenciasvalor_id) AS cantidad FROM asistencias WHERE cursos_id=".$curso["id"]." AND alumnos_id=".$alumno_grupo["alumnos_id"]." GROUP BY asistenciasvalor_id";
               $cantidades=$db->in_query($consulta);

            $asistencias=array();

            foreach($cantidades as $c){
            $asistencias[$c["asistenciasvalor_id"]]=$c["cantidad"];
            }
            $total=0;
            $total=$asistencias["0"]+$asistencias["1"];

            $r=$asistencias["1"]/$total;
            $porcentaje=$r*100;
            $existeValor+=$porcentaje;

            if($existeValor>0)$datos_retorno[$curso["id"]]=array("alumno_id" => $alumno_grupo["alumnos_id"],
                                                                        "grupo_id" => $alumno_grupo["grupo_id"],
                                                                        "curso_id" => $curso["id"],
                                                                        "materia_id" => $curso["materia_id"],
                                                                        "clave" => $curso["clave"],
                                                                        "materia_nombre" => $curso["materia_nombre"],
                                                                        "profesor_id" => $curso["profesores_id"],
                                                                        "parciales" => $parciales,
                                                                        "ordinario"=>$ordinario,
                                                                        "extra"=>$extra,
                                                                        "asistencias"=>$porcentaje);


           }

        $datos=array();
        $datos["datos"]=$datos_retorno;
        sort($perds);
        $datos["periodos"]=$perds;
        return $datos;

    }

    private function ultimasCalificaciones($id_alumno){
    $consulta="SELECT * FROM calificacionesparciales WHERE alumnos_id='".$id_alumno."' order by saved_at DESC LIMIT 0,5";

     $db = new db("localhost", "hekademos", "hekademos", "hekademos");
       $datos1=$db->in_query($consulta);

    $datos=array();

    $cont=0;
    foreach($datos1 as $dato){
        $consulta="SELECT distinct cursos.materias_id,cursos.id as curso_id, materias.nombre as nombre" .
                " FROM cursos,materias WHERE cursos.materias_id=materias.id AND cursos.id='".$dato["cursos_id"]."'";
        $tempo=$db->in_query($consulta);
        $datos["parciales"][$cont]=array("materia_nombre" => $tempo[0]["nombre"],"valor" => $dato["valor"],"fecha" => $this->convierteFecha($dato["save_at"]));
        $cont++;
    }

    $consulta="SELECT * FROM calificaciones WHERE alumnos_id='".$id_alumno."' order by saved_at DESC LIMIT 0,5";
    $datos1=$db->in_query($consulta);

    $cont=0;
    foreach($datos1 as $dato){
        $consulta="SELECT distinct cursos.materias_id,cursos.id as curso_id, materias.nombre as nombre" .
                " FROM cursos,materias WHERE cursos.materias_id=materias.id AND cursos.id='".$dato["cursos_id"]."'";
        $tempo=$db->in_query($consulta);
        $datos["finales"][$cont]=array("materia_nombre" => $tempo[0]["nombre"],"valor" => $dato["valor"],"fecha" => $this->convierteFecha($dato["save_at"]));
        $cont++;
    }

    return $datos;
    }

    private function ultimasAsistencias($id_alumno){
    $consulta="SELECT * FROM asistencias WHERE alumnos_id='".$id_alumno."' order by dia DESC LIMIT 0,5 ";
     $db = new db("localhost", "hekademos", "hekademos", "hekademos");
       $datos1=$db->in_query($consulta);

    $datos=array();
    $cont=0;
    foreach($datos1 as $dato){

        $consulta="SELECT distinct cursos.materias_id,cursos.id as curso_id, materias.nombre as nombre" .
                " FROM cursos,materias WHERE cursos.materias_id=materias.id AND cursos.id='".$dato["cursos_id"]."'";
        $tempo=$db->in_query($consulta);
        $datos[$cont]=array("materia_nombre" => $tempo[0]["nombre"],"valor" => $dato["asistenciasvalor_id"],"fecha" => $this->convierteFecha($dato["dia"]));
        $cont++;
    }
    return $datos;
    }

    private function convierteFecha($fecha){
        $fechas=split("-",$fecha);
        return $fechas[2]."/".substr(mes_espanol($fechas[1]),0,3)."/".substr($fechas[0],strlen($fechas[0])-2);
    }

        public function obtenCalificaciones1($id,$id_ciclo){
        $consulta="SELECT  alumnosgrupo.alumnos_id, grupos.id AS grupo_id, cursos.id AS curso_id, materias.id AS materia_id, materias.clave, materias.nombre AS materia_nombre";
        $consulta.=" FROM  alumnosgrupo, grupos, cursos, materias";
        $consulta.=" WHERE  grupos.ciclos_id='".$id_ciclo."' AND  alumnosgrupo.alumnos_id='".$id."'";
        $consulta.=" AND  alumnosgrupo.grupos_id= grupos.id AND  cursos.grupos_id= grupos.id AND  materias.id= cursos.materias_id ";
         $db = new db("localhost", "hekademos", "hekademos", "hekademos");
           $datos1=$db->in_query($consulta);

        $datos_retorno=array();
           foreach($datos1 as $datos){
               $consulta="SELECT id AS asistencia_id,asistenciavalor_id,dia FROM asistencia WHERE curso_id=".$datos["curso_id"]." AND alumno_id=".$datos["alumno_id"];
               $asistencias=$db->in_query($consulta);
               foreach($asistencias as $asistencia){
               $datos_retorno[substr($asistencia["dia"],5,2)][substr($asistencia["dia"],8,2)][$datos["curso_id"]]=array("alumno_id" => $datos["alumno_id"], "grupo_id" => $datos["grupo_id"], "curso_id" => $datos["curso_id"], "materia_id" => $datos["materia_id"], "clave" => $datos["clave"], "materia_nombre" => $datos["materia_nombre"], "asistencia_id" => $asistencia["asistencia_id"], "asistenciavalor_id" => $asistencia["asistenciavalor_id"], "dia" => $asistencia["dia"]);
            }
           }

           $db->close();
        $xml='<?xml version="1.0" encoding="ISO-8859-1" ?>';
         $xml.='<calificaciones>';


            $cont=0;
            $meses=array_keys($datos_retorno);
        foreach($datos_retorno as $dia){

        $xml.='<tabla>';

                $xml.='<mes value="'.$meses[$cont].'">';
                $cont++;
            $cont1=0;
            foreach($dia as $curso){


                $dias=array_keys($dia);
                $xml.='<dia value="'.$dias[$cont1].'">';
                $cont1++;
                    foreach($curso as $asistencia){
                    $xml.="<asistencia>";
                    $xml.='<materia>'.$asistencia["materia_nombre"].'</materia>';
                    $xml.='<valor>'.$asistencia["asistenciavalor_id"].'</valor>';
                    $xml.="</asistencia>";

                    }
                    $xml.='</dia>';

            }
        $xml.='</mes>';
        $xml.='</tabla>';
        }
        $xml.='</calificaciones>';

    //Generamos la salida
         return $xml;
    }

    function horario($id,$id_ciclo){

         $db = new db("localhost", "hekademos", "hekademos", "hekademos");
        $consulta="SELECT * FROM dias ORDER BY id";
        $diasSemana=$db->in_query($consulta);

        $consulta="SELECT cursos.id,cursos.inicio AS fecha_inicio,materias.id AS materia_id,materias.clave,materias.nombre AS materia_nombre,cursos.profesores_id,profesores.ap,profesores.am,profesores.nombre as profe FROM alumnoscursos,cursos,grupos,materias,profesores WHERE alumnoscursos.alumnos_id=".$id." AND alumnoscursos.cursos_id=cursos.id AND
                    grupos.id=cursos.grupos_id AND grupos.ciclos_id='".$id_ciclo."' AND materias.id=cursos.materias_id AND profesores.id=cursos.profesores_id ORDER BY materias.nombre,cursos.inicio ";
        $cursos=$db->in_query($consulta);

        $materias=array();
        $profes=array();
        $cond="";
        $informacion=array();
        $datos=array();
        $horarios=array();
        $hrs=array();
        $ifm=array();
        $conds=array();
        if(count($cursos)>0){
        foreach($cursos as $curso){
            $ini  =  new DateTime( $curso["fecha_inicio"]);

            $cond=$conds[$ini->format('W')];
            if($cond==null)$cond='';

                $cond.=" horarios.cursos_id=".$curso["id"]." OR";
                $conds[$ini->format('W')]=$cond;

                $materias[$curso["id"]]=$curso["materia_nombre"];
                $profes[$curso["id"]]=$curso["ap"]." ".$curso["am"]." ".$curso["profe"];
                $ifm[$ini->format('W')][$curso["id"]]=array("curso_id"=>$curso["id"],
                                                 "fecha_inicio"=>$curso["fecha_inicio"],
                                                 "nombre"=>$curso["materia_nombre"],
                                                 "profe"=>$profes[$curso["id"]]);

                $informacion[$curso["id"]]=array("curso_id"=>$curso["id"],
                                                 "fecha_inicio"=>$curso["fecha_inicio"],
                                                 "nombre"=>$curso["materia_nombre"],
                                                 "profe"=>$profes[$curso["id"]]);
        }

        foreach($conds as $key=>$cond){
            $cond=substr($cond,0,strlen($cond)-2);

        $consulta="SELECT horarios.*,aulas.clave,aulas.nombre FROM horarios,aulas " .
                    "WHERE (".$cond.") AND aulas.id=horarios.aulas_id ORDER BY horarios.dias_id,horarios.inicio";
        $clases=$db->in_query($consulta);
        $horario=array();
        $bloques=array();

        foreach($clases as $clase){
        $ini  =  new DateTime( $clase["fecha_inicio"]);
        $info=$informacion[$clase["cursos_id"]];

        $horario[$clase["dias_id"]][]=array("cursos_id"=>$clase["cursos_id"],
                                                                "nombre"=>$materias[$clase["cursos_id"]],
                                                                "inicio"=>$clase["inicio"],
                                                                "fin"=>$clase["fin"],
                                                                "aula"=>$clase["nombre"],
                                                                "clave_aula"=>$clase["clave"],
                                                                "dia"=>$clase["dias_id"],
                                                                "profesor"=>$profes[$clase["cursos_id"]],
                                                                "fecha_inicio"=>$info["fecha_inicio"]
        );


        $bloques[$clase["dias_id"]]=count(array_keys($horario[$clase["dias_id"]]));

        }


    arsort($bloques);

    $llaves=array_keys($bloques);
    $mas=$llaves[0];

    $banderaSab=false;
    $banderaDom=false;
    $ids=array_keys($horario);
    foreach($ids as $id){
        if($id==6)$banderaSab=true;
        if($id==7){
            $banderaSab=true;
            $banderaDom=true;
        }
    }



    for($b=0;$b<$bloques[$mas];$b++){

    foreach($diasSemana as $d){

    if(($d["id"]==6 && $banderaSab) || ($d["id"]==7 && $banderaDom) || $d["id"]<6){
        $cl=$horario[$d["id"]][$b];
        $horarios[$key][$b][$d["id"]]=$cl;

    }

    }
    }

    }
    $db->close();
        }
        ksort($horarios);
    return array("horario"=>$horarios,"informacion"=>$ifm);
    }




public function regresaTurno($t){
    $t=strtoupper($t);
    switch($t){
        case "M": return "Matutino";
        case "V": return "Vespertino";
        case "N": return "Nocturno";
        default: return "No disponible";
    }
}

    public function materiaTipo($tipo){
        switch($tipo){
            case 'OBL': return 'Obligatoria';
            case 'OPT': return 'Optativa';
            case 'TLR': return 'Taller';
            case 'PRO': return 'Programa de extensi&oacute;n y difusi&oacute;n cultural';
        }
        return;
    }

}
?>
