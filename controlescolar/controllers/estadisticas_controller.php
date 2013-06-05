<?php
/*
 * Created on 31/03/2009
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

 class EstadisticasController extends ApplicationController {
    public $template = "system";

  public function aprobacion(){
    $this->ciclo=new Ciclos();
    $this->ciclo=$this->ciclo->find(Session::get_data("ciclo.id"));
     $grupos=new Grupos();
    $grupos=$grupos->find("ciclos_id=".Session::get_data("ciclo.id")." AND turno!='N'  ORDER BY turno,grado,letra");
    $this->countGps=count($grupos);

    $cursos=new Cursos();
    $cursos=$cursos->delCiclo(Session::get_data("ciclo.id")," ORDER BY grupos.turno,grupos.grado,grupos.letra");
    $this->countCrs=count($cursos);

    $datos=array();
    $datos['cursos']=array();

    $estadistica=array();
    $materiaTurno=array();
    $info=array();
    $alumnos = new Alumnos();
    $this->inscritosM=$alumnos->count_by_sql(
                        " SELECT COUNT(alumnos.id) FROM alumnos " .
                        " INNER JOIN alumnosgrupo ON alumnos.id = alumnosgrupo.alumnos_id " .
                        " INNER JOIN grupos ON alumnosgrupo.grupos_id = grupos.id" .
                        " WHERE grupos.ciclos_id='".Session::get_data("ciclo.id")."' AND grupos.turno ='M'"
    );

    $this->inscritosV=$alumnos->count_by_sql(
                        " SELECT COUNT(alumnos.id) FROM alumnos " .
                        " INNER JOIN alumnosgrupo ON alumnos.id = alumnosgrupo.alumnos_id " .
                        " INNER JOIN grupos ON alumnosgrupo.grupos_id = grupos.id" .
                        " WHERE grupos.ciclos_id='".Session::get_data("ciclo.id")."' AND grupos.turno ='V'"
    );

    foreach($cursos as $curso){
        $materia=$curso->materia();
        $grupo=$curso->grupo();
        $datos['cursos'][$materia->semestre][$materia->id][$grupo->turno][]=$curso;
        $calificacion=new Calificaciones();
        $es=$calificacion->infoestadistica($curso->id);

        $e=array();//var_dump($es);exit;
        $e[1]['a']=$es[1]['a']." [".$es[1]['pa']."]%";
        $e[1]['r']=$es[1]['r']." [".$es[1]['pr']."]%";
        $e['ap']=$es['ap'];
        $e['rp']=$es['rp'];

        if($es[2]['a']=="-" && $es[2]['a']!="0"){
        $e[2]['a']="-";

        $e[2]['r']="-";
        }else{
        $e[2]['a']=$es[2]['a']." [".$es[2]['pa']."]%";
        $e[2]['r']=$es[2]['r']." [".$es[2]['pr']."]%";
        }

        $e['pm'] = $es['pm'];
        $e['pmt'] = $es['pmt'];

        $estadistica[$curso->id]=$e;
        $info[$grupo->turno][$grupo->grado]['a']+=($e[1]['a']+$e[2]['a']);
        $info[$grupo->turno][$grupo->grado]['r']+=($e[1]['a']+$e[1]['r'])-($e[1]['a']+$e[2]['a']);
        $info[$grupo->turno][$grupo->grado]['t']+=($e[1]['a']+$e[1]['r']);

        $materiaTurno[$grupo->turno][$curso->materias_id][1]['a']+=$es[1]['a'];
        $materiaTurno[$grupo->turno][$curso->materias_id][1]['r']+=$es[1]['r'];
        $materiaTurno[$grupo->turno][$curso->materias_id][2]['a']+=$es[2]['a'];
        $materiaTurno[$grupo->turno][$curso->materias_id][2]['r']+=$es[2]['r'];
        $materiaTurno[$grupo->turno][$curso->materias_id]['pmt'] += $es['pmt'];

        //$materiaTurno[$grupo->turno][$curso->materias_id]['ap']+=$es['ap'];
        //$materiaTurno[$grupo->turno][$curso->materias_id]['rp']+=$es['rp'];

        $total1=$es[1]['a']+$es[1]['r'];
        $total2=$es[2]['a']+$es[2]['r'];

        $materiaTurno[$grupo->turno][$curso->materias_id][1]['t']=$total1;
        $materiaTurno[$grupo->turno][$curso->materias_id][1]['t']=$total2;

        //$materiaTurno[$grupo->turno][$curso->materias_id][1]['pa']+=$es[1]['pa'];
        //$materiaTurno[$grupo->turno][$curso->materias_id][1]['pr']+=$es[1]['pr'];
        //$materiaTurno[$grupo->turno][$curso->materias_id][2]['pa']+=$es[2]['pa'];
        //$materiaTurno[$grupo->turno][$curso->materias_id][2]['pr']+=$es[2]['pr'];
        //$materiaTurno[$grupo->turno][$curso->materias_id]['cursos']+=1;


        if(!($es[2]["a"]=="-" && $es[2]["r"]=="-")){
            $materiaTurno[$grupo->turno][$curso->materias_id]['cursosReprobados']+=1;
        }

    }


    $materiaTotales=array();
    $mtt=array();

    foreach($materiaTurno as $t=>$turno){
        foreach($turno as $c=>$curso){
            $materia=new Materias();
            $materia=$materia->find($c);

            //$materiaTurno[$t][$c][1]['pa']=round($curso[1]['pa']/$curso['cursos'],2);
            $materiaTurno[$t][$c][1]['pa']=round(($materiaTurno[$t][$c][1]['a']/($materiaTurno[$t][$c][1]['a']+$materiaTurno[$t][$c][1]['r']))*100,2);
            $materiaTurno[$t][$c][1]['pr']=round(100-$materiaTurno[$t][$c][1]['pa'],2);

            if($curso['cursosReprobados']>0){
            //$materiaTurno[$t][$c][2]['pa']=round($curso[2]['pa']/$curso['cursosReprobados'],2);
            //$materiaTurno[$t][$c][2]['pr']=round($curso[2]['pr']/$curso['cursosReprobados'],2);
            $materiaTurno[$t][$c][2]['pa']=round(($materiaTurno[$t][$c][2]['a']/($materiaTurno[$t][$c][2]['a']+$materiaTurno[$t][$c][2]['r']))*100,2);
            $materiaTurno[$t][$c][2]['pr']=round(100-$materiaTurno[$t][$c][2]['pa'],2);

            }else{
            $materiaTurno[$t][$c][2]['pa']=0;
            $materiaTurno[$t][$c][2]['pr']=0;
            }

            $materiaTurno[$t][$c]['ap']=round((($materiaTurno[$t][$c][1]['a']+$materiaTurno[$t][$c][2]['a'])/($materiaTurno[$t][$c][1]['a']+$materiaTurno[$t][$c][1]['r']))*100,2);
            $materiaTurno[$t][$c]['rp']=100-$materiaTurno[$t][$c]['ap'];
            $materiaTurno[$t][$c]['pm'] = round($materiaTurno[$t][$c]['pmt']/($materiaTurno[$t][$c][1]['a']+$materiaTurno[$t][$c][1]['r']),2);

            $materiaTotales[$c][1]['a']+=$curso[1]['a'];
            $materiaTotales[$c][1]['r']+=$curso[1]['r'];
            $materiaTotales[$c][2]['a']+=$curso[2]['a'];
            $materiaTotales[$c][2]['r']+=$curso[2]['r'];

            $materiaTotales[$c]['pmt']+=$materiaTurno[$t][$c]['pmt'];

            /*$materiaTotales[$c][1]['pa']+=$materiaTurno[$t][$c][1]['pa'];
            $materiaTotales[$c][1]['pr']+=$materiaTurno[$t][$c][1]['pr'];
            $materiaTotales[$c][2]['pa']+=$materiaTurno[$t][$c][2]['pa'];
            $materiaTotales[$c][2]['pr']+=$materiaTurno[$t][$c][2]['pr'];
            $materiaTotales[$c]['turnos']+=1;
            */
            $mtt[$t][$materia->semestre][$c][1]['a']+=$curso[1]['a'];
            $mtt[$t][$materia->semestre][$c][1]['r']+=$curso[1]['r'];
            $mtt[$t][$materia->semestre][$c][2]['a']+=$curso[2]['a'];
            $mtt[$t][$materia->semestre][$c][2]['r']+=$curso[2]['r'];

            $mtt[$t][$materia->semestre][$c][1]['pa']+=$materiaTurno[$t][$c][1]['pa'];
            $mtt[$t][$materia->semestre][$c][1]['pr']+=$materiaTurno[$t][$c][1]['pr'];
            $mtt[$t][$materia->semestre][$c][2]['pa']+=$materiaTurno[$t][$c][2]['pa'];
            $mtt[$t][$materia->semestre][$c][2]['pr']+=$materiaTurno[$t][$c][2]['pr'];
        }
    }

    $totales=array();
    foreach($mtt as $t=>$turno){
        foreach($turno as $s=>$semestre){
            foreach($semestre as $c=>$materia){
            if(!$materiaTotales[$c]['calculado']){
            $materiaTotales[$c][1]['pa']=round(($materiaTotales[$c][1]['a']/($materiaTotales[$c][1]['a']+$materiaTotales[$c][1]['r']))*100,2);
            $materiaTotales[$c][1]['pr']=100-$materiaTotales[$c][1]['pa'];
            $materiaTotales[$c][2]['pa']=round(($materiaTotales[$c][2]['a']/($materiaTotales[$c][2]['a']+$materiaTotales[$c][2]['r']))*100,2);
            $materiaTotales[$c][2]['pr']=100-$materiaTotales[$c][2]['pa'];
            $materiaTotales[$c]['calculado']=true;
            }

            $total=$materiaTotales[$c][1]['a']+$materiaTotales[$c][1]['r'];
            $totales[$c]['pa']=round((($materiaTotales[$c][1]['a']+$materiaTotales[$c][2]['a'])/$total)*100,2);
            $totales[$c]['pr']=100-$totales[$c]['pa'];

            $totales[$c]['pm']=round($materiaTotales[$c]['pmt']/$total,2);
            $totales[$c]['total'] = $total;
            $totales[$c]['pmt'] = $materiaTotales[$c]['pmt'];

            $general['a']+=$materiaTotales[$c][1]['a']+$materiaTotales[$c][2]['a'];
            $general['r']+=$total-($materiaTotales[$c][1]['a']+$materiaTotales[$c][2]['a']);
            $general['t']+=$total;

            }
        }
    }


    $this->grupos=array();
    $this->tabs=count($datos['cursos'])+1;

    $this->datos=$datos;
    $this->estadistica=$estadistica;
    $this->materiaTurno=$materiaTurno;
    $this->materiaTotales=$materiaTotales;
    $this->totales=$totales;
    $this->mtt=$mtt;
    $this->informacion=$info;
    /*$this->grupos=array();
    $this->tabs=0;
    foreach($grupos as $g){
        $this->grupos[$g->turno][$g->grado][$g->letra]=$g;
    }

    foreach($this->grupos as $turnos){
        foreach($turnos as $letras){
            $this->tabs++;
        }
    }
    $this->tabs++;


    $this->datos=array();
    $db = db::raw_connect();
    $datos=$db->in_query("SELECT grupos.*,alumnos.id AS alumnos_id,
                alumnos.aprobadas
                FROM alumnos
                INNER JOIN alumnosgrupo ON alumnos.id=alumnosgrupo.alumnos_id
                INNER JOIN grupos ON alumnosgrupo.grupos_id=grupos.id
                WHERE grupos.ciclos_id=3
                ORDER BY grupos.turno,grupos.grado,grupos.letra
                ");

    foreach($datos as $fila){
            $this->datos[$fila['turno']][$fila['grado']][$fila['letra']][$fila['alumnos_id']]=$fila;
     }
     */

 }

  public function aprobadas(){
    $this->ciclo=new Ciclos();
    $this->ciclo=$this->ciclo->find(3);
     $grupos=new Grupos();
    $grupos=$grupos->find("ciclos_id=".Session::get_data("ciclo.id")." AND turno!='N'  ORDER BY turno,grado,letra");
    $this->countGps=count($grupos);

    $this->grupos=array();
    $this->tabs=0;
    foreach($grupos as $g){
        $this->grupos[$g->turno][$g->grado][$g->letra.$g->id]=$g;
    }

    foreach($this->grupos as $turnos){
        foreach($turnos as $letras){
            $this->tabs++;
        }
    }
    $this->tabs++;


    $this->datos=array();
    $db = db::raw_connect();
    $datos=$db->in_query("SELECT grupos.*,alumnos.id AS alumnos_id,
                alumnos.aprobadas
                FROM alumnos
                INNER JOIN alumnosgrupo ON alumnos.id=alumnosgrupo.alumnos_id
                INNER JOIN grupos ON alumnosgrupo.grupos_id=grupos.id
                WHERE grupos.ciclos_id=3
                ORDER BY grupos.turno,grupos.grado,grupos.letra
                ");

    foreach($datos as $fila){
            $this->datos[$fila['turno']][$fila['grado']][$fila['letra']][$fila['alumnos_id']]=$fila;
     }
 }

 public function asistencias(){
    $this->ciclo=new Ciclos();
    $this->ciclo=$this->ciclo->find(Session::get_data("ciclo.id"));
     $grupos=new Grupos();
    $grupos=$grupos->find("ciclos_id=".Session::get_data("ciclo.id")." AND turno!='N'  ORDER BY turno,grado,letra");
    $this->countGps=count($grupos);

    $this->grupos=array();
    $this->tabs=0;
    foreach($grupos as $g){
        $this->grupos[$g->turno][$g->grado][$g->letra.$g->id]=$g;
    }

    foreach($this->grupos as $turnos){
        foreach($turnos as $letras){
            $this->tabs++;
        }
    }
    $this->tabs++;


    $this->datos=array();
    $db = db::raw_connect();
    $datos=$db->in_query("SELECT
                DISTINCT(materias.nombre),
                grupos.id as grupos_id,
                grupos.grado,
                grupos.letra,
                grupos.turno,
                cursos.id as cursos_id,
                MIN(asistencias.dia) AS inicio,
                MAX(asistencias.dia) AS fin,
                count(DISTINCT(asistencias.dia)) AS capturadas
                FROM asistencias
                INNER JOIN cursos ON asistencias.cursos_id=cursos.id
                INNER JOIN grupos ON cursos.grupos_id=grupos.id
                INNER JOIN materias ON cursos.materias_id=materias.id
                INNER JOIN horarios ON horarios.cursos_id=cursos.id
                WHERE grupos.ciclos_id=".$this->ciclo->id."
                GROUP BY asistencias.cursos_id
                ORDER BY grupos.turno,grupos.grado,grupos.letra,materias.nombre;
                ");

    foreach($datos as $fila){
            $this->datos[$fila['turno']][$fila['grado']][$fila['letra']][$fila['cursos_id']]=$fila;
     }
 }

 public function calificaciones(){
    $this->ciclo=new Ciclos();
    $this->ciclo=$this->ciclo->find(Session::get_data("ciclo.id"));
     $grupos=new Grupos();
    $grupos=$grupos->find("ciclos_id=".Session::get_data("ciclo.id")." AND turno!='N' ORDER BY turno,grado,letra");
    $this->countGps=count($grupos);

    $this->grupos=array();
    $this->tabs=0;
    $grados2=array();
    foreach($grupos as $g){
        $this->grupos[$g->turno][$g->grado][$g->letra.$g->id]=$g;
        $grados2[$g->turno.$g->grado][]=$g->grado;
    }

    $this->tabs=count($grados2)+1;

    $agenda = new Agenda();
    $eventos = new Eventos();
    $eventos = $eventos->find("conditions: clave LIKE 'CAL-%' OR clave LIKE 'PRC-%'",
                                  "order: SUBSTRING(clave, 1, 3) DESC, clave ");
    $events=array();
    $this->datos=array();

    foreach($eventos as $e){
        if(!Utils:: endsWith(trim($e->clave),'-ESP')){
        $roles = new Roles();
            $especial=new Eventos();

            $rol = $roles->find_first(
                "conditions: (eventos_id = '" . $e->id . "' )" .
                        " AND aco_section='calificaciones'" .
                        " AND aco='agregar'"
            );

            if($rol->id!=""){

            $periodo = $agenda->find_first(
                "conditions: " .
                "ciclos_id = '" . $this->ciclo->id . "' " .
                "AND roles_id = '" . $rol->id . "' " .
                " AND activo='1'"
            );

            if($periodo->id != ''){
                $ini =  new DateTime( $periodo->inicio );
                $ini = mktime(00,00,01,$ini->format("n")  ,$ini->format("j")  , $ini->format("Y") );//$ini->format('U');

                $hoy=time();
                $hoy=mktime(date("H"),date("i"), date("s")  ,date("n",$hoy)  ,date("j",$hoy)  , date("Y",$hoy) );

                if( $ini <= $hoy ){

                    $clave=trim($e->clave);
                    $p=substr($clave,strlen($clave)-1);


                    $db = db::raw_connect();
                    if(is_numeric($p)){
                    $events[]=$p;
                    $datos=$db->in_query(
                    "SELECT
                    DISTINCT(materias.nombre),
                    grupos.id as grupos_id,
                    grupos.grado,
                    grupos.letra,
                    grupos.turno,
                    cursos.id as cursos_id,
                    count(DISTINCT(calificacionesparciales.id)) AS capturadas
                    FROM calificacionesparciales
                    INNER JOIN cursos ON calificacionesparciales.cursos_id=cursos.id
                    INNER JOIN grupos ON cursos.grupos_id=grupos.id
                    INNER JOIN materias ON cursos.materias_id=materias.id
                    WHERE grupos.ciclos_id=".$this->ciclo->id." AND calificacionesparciales.periodo='".$p."'
                    GROUP BY calificacionesparciales.cursos_id
                    ORDER BY grupos.turno,grupos.grado,grupos.letra,materias.nombre");

                    foreach($datos as $fila){
                            $this->datos[$p][$fila['turno']][$fila['grado']][$fila['letra']][$fila['cursos_id']]=$fila;

                     }
                     }else{

                        $opo=split("-",$e->clave);
                        $oportunidad=new Oportunidades();
                        $oportunidad=$oportunidad->find_first("clave='".$opo[1]."'");
                        if($oportunidad->id!=""){
                        $events[]=$p.$oportunidad->id.$e->clave;
                        $datos=$db->in_query(
                        "SELECT
                        DISTINCT(materias.nombre),
                        grupos.id as grupos_id,
                        grupos.grado,
                        grupos.letra,
                        grupos.turno,
                        cursos.id as cursos_id,
                        count(DISTINCT(calificaciones.id)) AS capturadas,
                        calificaciones.oportunidades_id AS oportunidad
                        FROM calificaciones
                        INNER JOIN cursos ON calificaciones.cursos_id=cursos.id
                        INNER JOIN grupos ON cursos.grupos_id=grupos.id
                        INNER JOIN materias ON cursos.materias_id=materias.id
                        WHERE grupos.ciclos_id=".$this->ciclo->id." AND calificaciones.oportunidades_id='".$oportunidad->id."'
                        GROUP BY calificaciones.cursos_id
                        ORDER BY grupos.turno,grupos.grado,grupos.letra,materias.nombre");

                        foreach($datos as $fila){
                                $this->datos[$p.$oportunidad->id.$e->clave][$fila['turno']][$fila['grado']][$fila['letra']][$fila['cursos_id']]=$fila;

                         }
                        }
                    }
                }
            }
            }


        }
    }
    sort($events);
    $this->events=$events;

 }

  public function promedios(){
    $this->ciclo=new Ciclos();
    $this->ciclo=$this->ciclo->find(3);
     $grupos=new Grupos();
    $grupos=$grupos->find("ciclos_id=".Session::get_data("ciclo.id")." AND turno!='N'  ORDER BY turno,grado,letra");
    $this->countGps=count($grupos);

    $this->grupos=array();
    $this->tabs=0;
    foreach($grupos as $g){
        $this->grupos[$g->turno][$g->grado][$g->letra.$g->id]=$g;
    }

    foreach($this->grupos as $turnos){
        foreach($turnos as $letras){
            $this->tabs++;
        }
    }
    $this->tabs++;


    $this->datos=array();
    $db = db::raw_connect();
    $datos=$db->in_query("SELECT grupos.*,alumnos.id AS alumnos_id,
                alumnos.promedio
                FROM alumnos
                INNER JOIN alumnosgrupo ON alumnos.id=alumnosgrupo.alumnos_id
                INNER JOIN grupos ON alumnosgrupo.grupos_id=grupos.id
                WHERE grupos.ciclos_id=3
                ORDER BY grupos.turno,grupos.grado,grupos.letra
                ");

    foreach($datos as $fila){
            $this->datos[$fila['turno']][$fila['grado']][$fila['letra']][$fila['alumnos_id']]=$fila;
     }
 }


 public function index(){
        $ciclo_id = Session :: get_data('ciclo.id');
        $ciclos = new Ciclos();
        $this->ciclo = $ciclos->find($ciclo_id);
        $this->ciclos = $ciclos->find("columns: id, numero", "conditions: abierto = '1' ", "order: numero DESC");
 }


 }
?>
