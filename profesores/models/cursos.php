<?php
// Hekademos, Creado el 30/09/2008
/**
 * Cursos
 *
 * @package    Hekademos
 * @author     mimeks <mimex@fipasoft.com.mx>
 * @copyright  2008 FiPa Software (contacto at fipasoft.com.mx)
 * @license    http://www.gnu.org/licenses/gpl.html  GNU General Public License (GPL)
 *
 */
class Cursos extends ActiveRecord{
    private $calCapturadas;
    protected $materiaTipo;
    protected $materia;
    protected $profesor;
    protected $curso_articulo;
    protected $materia_id;
    public $materia_semestre;

    public function alumnos(){
        $alumnos = new Alumnos();
        $alumnos = $alumnos->find_all_by_sql(
            "SELECT " .
                "alumnos.id, alumnos.codigo, alumnos.nombre, alumnos.ap, " .
                "alumnos.am, grupos.grado, grupos.letra, grupos.turno, " .
                "situaciones.nombre AS situacion " .
            "FROM
                alumnos
                Inner Join alumnosgrupo ON alumnos.id = alumnosgrupo.alumnos_id
                Inner Join grupos ON alumnosgrupo.grupos_id = grupos.id
                Inner Join alumnoscursos ON alumnos.id = alumnoscursos.alumnos_id
                Inner Join situaciones ON alumnos.situaciones_id = situaciones.id " .
            "WHERE " .
                "alumnoscursos.cursos_id = '" . $this->id . "' ".
            "ORDER BY " .
                "alumnos.ap, alumnos.am, alumnos.nombre "
        );
        return $alumnos;
    }


    public function alumnosgrupo(){
        $alumnos = new Alumnos();
        $grupo=$this->grupo();
        $alumnos = $alumnos->find_all_by_sql(
            "SELECT " .
                "alumnos.id, alumnos.codigo, alumnos.nombre, alumnos.ap, " .
                "alumnos.am, grupos.grado, grupos.letra, grupos.turno, " .
                "situaciones.nombre AS situacion " .
            "FROM
                alumnos
                Inner Join alumnosgrupo ON alumnos.id = alumnosgrupo.alumnos_id
                Inner Join grupos ON alumnosgrupo.grupos_id = grupos.id
                Inner Join alumnoscursos ON alumnos.id = alumnoscursos.alumnos_id
                Inner Join situaciones ON alumnos.situaciones_id = situaciones.id " .
            "WHERE " .
                "alumnoscursos.cursos_id = '" . $this->id . "' AND grupos.ciclos_id=".$grupo->ciclos_id." ".
            "ORDER BY " .
                "alumnos.ap, alumnos.am, alumnos.nombre "
        );

        foreach($alumnos as $alumno){
        $articulo=new Articulos();
        $articulo=$articulo->find_by_sql(
            "SELECT articulos.* " .
            " FROM " .
            " alumnoscursos" .
            " INNER JOIN alumnosconarticulo ON alumnoscursos.id=alumnosconarticulo.alumnoscursos_id" .
            " INNER JOIN articulos ON alumnosconarticulo.articulos_id=articulos.id" .
            " WHERE alumnoscursos.alumnos_id='".$alumno->id."' AND alumnoscursos.cursos_id='".$this->id."'"
        )    ;

        if($articulo['clave']==null)
        $alumno->curso_articulo='';
        else
        $alumno->curso_articulo=$articulo['clave'];

        }

        return $alumnos;
    }

    public function alumnosInscritos(){
        $alumnos = new Alumnos();
        $alumnos = $alumnos->find_all_by_sql(
            "SELECT " .
                "alumnos.*" .
            "FROM
                alumnos    Inner Join alumnoscursos ON alumnos.id = alumnoscursos.alumnos_id " .
            "WHERE " .
                "alumnoscursos.cursos_id = '" . $this->id . "' " .
            "ORDER BY " .
                "alumnos.ap, alumnos.am, alumnos.nombre "
        );
    return $alumnos;
    }

    public function alumnosIds(){
        $alumnos = new Alumnos();
        $alumnos = $alumnos->find_all_by_sql(
            "SELECT " .
                "alumnos.id " .
            "FROM
                alumnos    Inner Join alumnoscursos ON alumnos.id = alumnoscursos.alumnos_id " .
            "WHERE " .
                "alumnoscursos.cursos_id = '" . $this->id . "' " .
            "ORDER BY " .
                "alumnos.ap, alumnos.am, alumnos.nombre "
        );
        $ids = array();
        foreach($alumnos as $alumno){
            $ids[] = $alumno->id;
        }
        return $ids;
    }

    public function aprobado(){
        if($this->estado_id==3)
            return true;
            else return false;
    }

    public function enProceso(){
        if($this->estado_id==1)
            return true;
            else return false;
    }

    public function enRevision(){
        if($this->estado_id==2)
            return true;
            else return false;
    }

    public function asistenciasAlumno($alumno_id){
        $asistencias = new Asistencias();
        $asistencias = $asistencias->find_all_by_sql(
            "SELECT " .
                "asistencias.id, " .
                "asistencias.dia, " .
                "asistenciasvalor.clave AS valor " .
            "FROM asistencias Inner Join asistenciasvalor " .
            "ON asistencias.asistenciasvalor_id = asistenciasvalor.id " .
            "WHERE cursos_id = '" . $this->id . "' " .
                "AND alumnos_id = '" . $alumno_id . "' " .
            "ORDER BY dia "
        );
        $ast = array();
        foreach($asistencias as $asistencia){
            $ast[str_replace('-','',$asistencia->dia)] = $asistencia->valor;
            $ast['id-' . str_replace('-','',$asistencia->dia)] = $asistencia->id;
            $ast[$asistencia->valor]++;
        }
        return $ast;
    }

        public function asistenciasAlumnoInfo($alumno_id){
        $asistencias = new Asistencias();
        $asistencias = $asistencias->find_all_by_sql(
            "SELECT " .
                "asistencias.id, " .
                "asistencias.dia, " .
                "asistenciasvalor.clave AS valor " .
            "FROM asistencias Inner Join asistenciasvalor " .
            "ON asistencias.asistenciasvalor_id = asistenciasvalor.id " .
            "WHERE cursos_id = '" . $this->id . "' " .
                "AND alumnos_id = '" . $alumno_id . "' " .
            "ORDER BY dia "
        );
        return $asistencias;
    }

    public function asignado($cache=true){
        // solo ciclos activos
        // return true;

        $asignados = $this->asignados($cache);
        if( in_array($this->id, $asignados) ||
            in_array('ALL', $asignados))
        {
            return true;
        }
        return false;

    }

        public function asignados($cache=true){
        //$myLog = new Logger('prueba.txt');
        //$myLog->begin();

        if($cache)
        $asignados = Session :: get_data('prof.crs.asignados');
        else
        $asignados=null;

        if(!is_array($asignados)){
        $usr_id = Session :: get_data('prof.usr.id');
        $asignados=array();
        $usr_grupos = Session :: get_data('prof.usr.grupos');
        if( in_array('direccion',  $usr_grupos) ||
        in_array('director',  $usr_grupos) ||
        in_array('secretario',  $usr_grupos)){ //acceso a todos
            $asignados[]='ALL';
        }elseif(in_array('plantilla',  $usr_grupos)){//acceso a los ciclos abiertos
        $abiertos=new Ciclos();
        $abiertos=$abiertos->abiertos();
        $cond='';
        foreach($abiertos as $a){
            $cond.="ciclos_id='".$a->id."' OR ";
        }

        $cond=substr($cond,0,strlen($cond)-3);
        $grupos=new Grupos();
        $grupos=$grupos->find($cond);
        foreach($grupos as $grupo){
                    $cursos=$grupo->cursos();
                    foreach($cursos as $curso){
                        $asignados[]=$curso->id;
                    }
            }
        //$myLog->log("plantilla: ".count($asignados), Logger::WARNING);
        }elseif(in_array('oficial',    $usr_grupos) ||
                in_array('secretarias',  $usr_grupos)){ //acceso solo al ciclo activo
        $abiertos=new Ciclos();
        $abiertos=$abiertos->abiertos();
        $cond='';
        foreach($abiertos as $a){
            $cond.="ciclos_id='".$a->id."' OR ";
        }

        $cond=substr($cond,0,strlen($cond)-3);
        $grupos=new Grupos();
        $grps=$grupos->find($cond);


            $gs=array();

            foreach($grps as $g){
                $gs[]=$g->id;
            }
            $grupo=new Grupos();
            $grupos=$grupo->asignados();
            foreach($grupos as $grupoAsignado){
                if(in_array($grupoAsignado,$gs)){
                    $grupo=new Grupos();
                    $grupo=$grupo->find($grupoAsignado);
                    $cursos=$grupo->cursos();
                    foreach($cursos as $curso){
                        $asignados[]=$curso->id;
                    }
                }
            }
        }elseif(in_array('profesores',$usr_grupos)){
        $cursos=new Cursos();
        $asignaciones=$cursos->find("profesores_id='".$usr_id."'");
        if(count($asignaciones) > 0){
                    foreach($asignaciones as $asignacion){
                        $asignados[] = $asignacion->id;
                    }
                }
        }

        Session :: set_data('prof.crs.asignados', $asignados);

        }//else $myLog->log("guardado: ".count($asignados), Logger::WARNING);
        //$myLog->commit();
        //$myLog->commit();
        return $asignados;
    }

    public function asistenciasFechas(){
        $myLog = new Logger('asistencias');
        $horarios = new Horarios();
        $horarios = $horarios->find("conditions: cursos_id = '" . $this->id . "'");
        $dias = array();
        $fechas = array();
        $excluir = $this->asistenciasFechasExcluir();
        if(count($horarios) > 0){
            foreach($horarios as $horario){
                $dias[] = $horario->dias_id;
            }
        }else{
            $dias = array(1, 2, 3, 4, 5, 6);
        }
        $agenda = new Agenda();
        $evento = new Eventos();
        $rol = new Roles();
        $ciclo =  $this->ciclo();

        $evento = $evento->find_first(
            "conditions: clave = 'CRS-PER'"
        );
        $rol = $rol->find_first(
            "conditions: eventos_id = '" . $evento->id . "' "
        );
        $periodo = $agenda->find_first(
            "conditions: " .
                "ciclos_id = '" . $ciclo->id . "' " .
                "AND roles_id = '" . $rol->id . "' " .
                "AND activo = '1' "
        );
        $materia=$this->materia();
        if($materia->oferta('id')==2){
        $vacaciones=$this->asistenciasFechasVacaciones();
        $ini_p  =  new DateTime( $periodo->inicio );
        $i    =    $ini  =  new DateTime( $this->inicio );

        $fin = new DateTime( $this->inicio );
        $myLog->log($ini->format('N'), Logger::DEBUG);
        $lim=($materia->duracion * 7)-($ini->format('N')-1);
        $myLog->log("lim: ".$lim, Logger::DEBUG);
        $cc=1;
        while($cc<=$lim){

        $fch = $fin->format('Y-m-d');

            if(
                !in_array($fch, $vacaciones)
            ){
                $cc++;
            }
            if($cc<=$lim)
            $fin->modify('+1 day');
        }
        }else{
        $i      =  $ini  =  new DateTime( substr($periodo->inicio, 0, 10) );
        $fin = new DateTime( substr($periodo->fin, 0, 10) );
        }

        $myLog->log("inicio: ".$ini->format('Y-m-d'), Logger::DEBUG);
        $myLog->log("fin: ".$fin->format('Y-m-d'), Logger::DEBUG);
        $i_time =  $ini->format('U');
        $fin =  $fin->format('U');

        $hoy = time();

        if($fin > $hoy){
            $fin = $hoy;
        }

        while($i_time <= $fin){
            $fecha = $i->format('Y-m-d');
            if(
                in_array($i->format('N'), $dias) &&
                !in_array($fecha, $excluir)
            ){
                    $a         =  new Asistencias();
                    $a->dia    =  $fecha;
                    $fechas[]  =  $a;
            }
            $i->modify('+1 day');
            $i_time = $i->format('U');
        }

        $myLog->close();
        return $fechas;
    }

        public function asistenciasFechasLista(){
        $myLog = new Logger('asistencias');
        $horarios = new Horarios();
        $horarios = $horarios->find("conditions: cursos_id = '" . $this->id . "'");
        $dias = array();
        $fechas = array();
        $excluir = $this->asistenciasFechasExcluirLista();
        if(count($horarios) > 0){
            foreach($horarios as $horario){
                $dias[] = $horario->dias_id;
            }
        }else{
            $dias = array(1, 2, 3, 4, 5, 6);
        }
        $agenda = new Agenda();
        $evento = new Eventos();
        $rol = new Roles();
        $ciclo =  $this->ciclo();

        $evento = $evento->find_first(
            "conditions: clave = 'CRS-PER'"
        );
        $rol = $rol->find_first(
            "conditions: eventos_id = '" . $evento->id . "' "
        );
        $periodo = $agenda->find_first(
            "conditions: " .
                "ciclos_id = '" . $ciclo->id . "' " .
                "AND roles_id = '" . $rol->id . "' " .
                "AND activo = '1' "
        );
        $materia=$this->materia();
        if($materia->oferta('id')==2){
        $vacaciones=$this->asistenciasFechasVacaciones();
        $ini_p  =  new DateTime( $periodo->inicio );
        $i    =    $ini  =  new DateTime( $this->inicio );

        $fin = new DateTime( $this->inicio );
        $myLog->log($ini->format('N'), Logger::DEBUG);
        $lim=($materia->duracion * 7)-($ini->format('N')-1);
        $myLog->log("lim: ".$lim, Logger::DEBUG);
        $cc=1;
        while($cc<=$lim){

        $fch = $fin->format('Y-m-d');

            if(
                !in_array($fch, $vacaciones)
            ){
                $cc++;
            }
            if($cc<$lim)
            $fin->modify('+1 day');
        }
        }else{
        $i      =  $ini  =  new DateTime( substr($periodo->inicio, 0, 10) );
        $fin = new DateTime( substr($periodo->fin, 0, 10) );
        }

        $myLog->log("inicio: ".$ini->format('Y-m-d'), Logger::DEBUG);
        $myLog->log("fin: ".$fin->format('Y-m-d'), Logger::DEBUG);
        $i_time =  $ini->format('U');
        $fin =  $fin->format('U');

        //$hoy = time();

        //if($fin > $hoy){
        //    $fin = $hoy;
        //}

        while($i_time <= $fin){
            $fecha = $i->format('Y-m-d');
            if(
                in_array($i->format('N'), $dias) &&
                !in_array($fecha, $excluir)
            ){
                    $a         =  new Asistencias();
                    $a->dia    =  $fecha;
                    $fechas[]  =  $a;
            }
            $i->modify('+1 day');
            $i_time = $i->format('U');
        }

        $myLog->close();
        return $fechas;
    }

    public function asistenciasFechasEdicion(){
        $fechas = $this->asistenciasHdr();
        return $fechas;
    }

        private function asistenciasFechasVacaciones(){
        $excluir = array();
        $ciclo =  $this->ciclo();
        $agenda = new Agenda();
        $eventos = new Eventos();

        $eventos = $eventos->find("conditions: clave ='DSC-VAC' ");
        foreach($eventos as $evento){
            $rol = new Roles();
            $rol = $rol->find_first(
                "conditions: eventos_id = '" . $evento->id . "' "
            );

            $periodos = $agenda->find(
                "conditions: " .
                "ciclos_id = '" . $ciclo->id . "' " .
                "AND roles_id = '" . $rol->id . "' " .
                "AND activo = '1' "
            );
            foreach($periodos as $periodo){
            $i      =  $ini  =  new DateTime( substr($periodo->inicio, 0, 10) );
            $i_time =  $ini->format('U');

            $fin = new DateTime( substr($periodo->fin, 0, 10) );
            $fin_time = $fin->format('U');

            while($i_time <= $fin_time){
                $excluir[] = $i->format('Y-m-d');
                $i->modify('+1 day');
                $i_time = $i->format('U');
            }
            }
        }

        return array_unique($excluir);
    }

    private function asistenciasFechasExcluir(){
        $excluir = array();
        $ciclo =  $this->ciclo();
        $agenda = new Agenda();
        $eventos = new Eventos();

        $eventos = $eventos->find("conditions: clave LIKE 'DSC-%' ");
        foreach($eventos as $evento){
            $rol = new Roles();
            $rol = $rol->find_first(
                "conditions: eventos_id = '" . $evento->id . "' "
            );

            $periodos = $agenda->find(
                "conditions: " .
                "ciclos_id = '" . $ciclo->id . "' " .
                "AND roles_id = '" . $rol->id . "' " .
                "AND activo = '1' "
            );
            foreach($periodos as $periodo){
            $i      =  $ini  =  new DateTime( substr($periodo->inicio, 0, 10) );
            $i_time =  $ini->format('U');

            $fin = new DateTime( substr($periodo->fin, 0, 10) );
            $fin_time = $fin->format('U');

            while($i_time <= $fin_time){
                $excluir[] = $i->format('Y-m-d');
                $i->modify('+1 day');
                $i_time = $i->format('U');
            }
            }
        }


        $capturadas = $this->asistenciasHdr();
        foreach($capturadas as $capturada){
            $excluir[] = $capturada->dia;
        }

        return array_unique($excluir);
    }
    private function asistenciasFechasExcluirLista(){
        $excluir = array();
        $ciclo =  $this->ciclo();
        $agenda = new Agenda();
        $eventos = new Eventos();

        $eventos = $eventos->find("conditions: clave LIKE 'DSC-%' ");
        foreach($eventos as $evento){
            $rol = new Roles();
            $rol = $rol->find_first(
                "conditions: eventos_id = '" . $evento->id . "' "
            );

            $periodos = $agenda->find(
                "conditions: " .
                "ciclos_id = '" . $ciclo->id . "' " .
                "AND roles_id = '" . $rol->id . "' " .
                "AND activo = '1' "
            );
            foreach($periodos as $periodo){
            $i      =  $ini  =  new DateTime( substr($periodo->inicio, 0, 10) );
            $i_time =  $ini->format('U');

            $fin = new DateTime( substr($periodo->fin, 0, 10) );
            $fin_time = $fin->format('U');

            while($i_time <= $fin_time){
                $excluir[] = $i->format('Y-m-d');
                $i->modify('+1 day');
                $i_time = $i->format('U');
            }
            }
        }

        return array_unique($excluir);
    }

    public function asistenciasHdr(){
        $asistencias = new Asistencias();
        $asistencias = $asistencias->find_all_by_sql(
                                "SELECT dia " .
                                "FROM asistencias " .
                                "WHERE cursos_id = '" . $this->id . "' " .
                                "GROUP BY dia " .
                                "ORDER BY dia "
                             );
        return $asistencias;
    }



    public function asistenciasResumen(){
        $asistencias = new Asistencias();
        $resumen = array();

        $n = count($this->asistenciasHdr());
        $resumen['_n_'] = $n;
        if($n > 0){
            $asistencias = $asistencias->find_all_by_sql(
                "SELECT " .
                    "alumnos_id, " .
                    "COUNT(asistencias.id) AS asistencias " .
                "FROM asistencias Inner Join asistenciasvalor " .
                "ON asistencias.asistenciasvalor_id = asistenciasvalor.id " .
                "WHERE " .
                    "cursos_id = '" . $this->id . "' " .
                    "AND alumnos_id IN " .
                        "(SELECT alumnoscursos.alumnos_id FROM alumnoscursos WHERE alumnoscursos.cursos_id = '" . $this->id . "') " .
                    "AND asistenciasvalor.clave = 'AST' " .
                "GROUP BY alumnos_id " .
                "ORDER BY dia "
            );

            foreach($asistencias as $ast){
                $prc = ($ast->asistencias * 100 / $n);
                $resumen[$ast->alumnos_id]['porcentaje'] = number_format($prc, 1, '.', ',');
                $resumen[$ast->alumnos_id]['oportunidad'] = $this->oportunidad($prc);
            }

        }else{
            foreach($this->alumnos() as $alumno){
                $resumen[$alumno->id]['porcentaje'] = '-';
                $resumen[$alumno->id]['oportunidad'] = 'ordinario';
            }
        }
        return $resumen;
    }

    public function asistenciasInfo(){
        $asistencias = new Asistencias();
        $resumen = array();

        $n = count($this->asistenciasHdr());
        $resumen['_n_'] = $n;
        if($n > 0){
            $asistencias = $asistencias->find_all_by_sql(
                "SELECT " .
                    "alumnos_id, " .
                    "COUNT(asistencias.id) AS asistencias " .
                "FROM asistencias Inner Join asistenciasvalor " .
                "ON asistencias.asistenciasvalor_id = asistenciasvalor.id " .
                "WHERE " .
                    "cursos_id = '" . $this->id . "' " .
                    "AND alumnos_id IN " .
                        "(SELECT alumnoscursos.alumnos_id FROM alumnoscursos WHERE alumnoscursos.cursos_id = '" . $this->id . "') " .
                    "AND asistenciasvalor.clave = 'AST' " .
                "GROUP BY alumnos_id " .
                "ORDER BY dia "
            );
            $faltas=new Asistencias();
            $faltas = $faltas->find_all_by_sql(
                "SELECT " .
                    "alumnos_id, " .
                    "COUNT(asistencias.id) AS faltas " .
                "FROM asistencias Inner Join asistenciasvalor " .
                "ON asistencias.asistenciasvalor_id = asistenciasvalor.id " .
                "WHERE " .
                    "cursos_id = '" . $this->id . "' " .
                    "AND alumnos_id IN " .
                        "(SELECT alumnoscursos.alumnos_id FROM alumnoscursos WHERE alumnoscursos.cursos_id = '" . $this->id . "') " .
                    "AND asistenciasvalor.clave = 'FTA' " .
                "GROUP BY alumnos_id " .
                "ORDER BY dia "
            );


            foreach($asistencias as $ast){
                $resumen[$ast->alumnos_id]['asistencias'] = $ast->asistencias;
                }

            foreach($faltas as $fta){
                $resumen[$fta->alumnos_id]['faltas'] = $fta->faltas;

                }

        }else{
            foreach($this->alumnos() as $alumno){
                $resumen[$alumno->id]['asistencias'] = '0';
                $resumen[$alumno->id]['faltas'] = '0';
            }
        }
        return $resumen;
    }


    public function asistenciasResumenAlumno($alumno_id){
        $asistencias = new Asistencias();
        $resumen = array();

        $n = count($this->asistenciasHdr());
        if($n > 0){
            $nAst = $asistencias->count_by_sql(
                "SELECT " .
                    "COUNT(asistencias.id) " .
                "FROM asistencias Inner Join asistenciasvalor " .
                "ON asistencias.asistenciasvalor_id = asistenciasvalor.id " .
                "WHERE cursos_id = '" . $this->id . "' " .
                    "AND alumnos_id = '" . $alumno_id . "' " .
                    "AND asistenciasvalor.clave = 'AST' " .
                "ORDER BY dia "
            );

            $resumen['_n_'] = $n;
            $prc = ($nAst * 100 / $n);
            $resumen['porcentaje'] = number_format($prc, 1, '.', ',');
            $resumen['oportunidad'] = $this->oportunidad($prc);
        }else{
            $resumen['oportunidad'] = 'ordinario';
        }
        return $resumen;
    }



        public function asistenciasFechasTotales(){
        $myLog = new Logger('asistencias');
        $horarios = new Horarios();
        $horarios = $horarios->find("conditions: cursos_id = '" . $this->id . "'");
        $dias = array();
        $fechas = array();
        $excluir = $this->asistenciasFechasExcluirLista();
        if(count($horarios) > 0){
            foreach($horarios as $horario){
                $dias[] = $horario->dias_id;
            }
        }else{
            $dias = array(1, 2, 3, 4, 5, 6);
        }
        $agenda = new Agenda();
        $evento = new Eventos();
        $rol = new Roles();
        $ciclo =  $this->ciclo();

        $evento = $evento->find_first(
            "conditions: clave = 'CRS-PER'"
        );
        $rol = $rol->find_first(
            "conditions: eventos_id = '" . $evento->id . "' "
        );
        $periodo = $agenda->find_first(
            "conditions: " .
                "ciclos_id = '" . $ciclo->id . "' " .
                "AND roles_id = '" . $rol->id . "' " .
                "AND activo = '1' "
        );
        $materia=$this->materia();
        if($materia->oferta('id')==2){
        $vacaciones=$this->asistenciasFechasVacaciones();
        $ini_p  =  new DateTime( $periodo->inicio );
        $i    =    $ini  =  new DateTime( $this->inicio );

        $fin = new DateTime( $this->inicio );
        $myLog->log($ini->format('N'), Logger::DEBUG);
        $lim=($materia->duracion * 7)-($ini->format('N')-1);
        $myLog->log("lim: ".$lim, Logger::DEBUG);
        $cc=1;
        while($cc<=$lim){

        $fch = $fin->format('Y-m-d');

            if(
                !in_array($fch, $vacaciones)
            ){
                $cc++;
            }
            if($cc<$lim)
            $fin->modify('+1 day');
        }
        }else{
        $i      =  $ini  =  new DateTime( substr($periodo->inicio, 0, 10) );
        $fin = new DateTime( substr($periodo->fin, 0, 10) );
        }

        $myLog->log("inicio: ".$ini->format('Y-m-d'), Logger::DEBUG);
        $myLog->log("fin: ".$fin->format('Y-m-d'), Logger::DEBUG);
        $i_time =  $ini->format('U');
        $fin =  $fin->format('U');

        $hoy = time();

        if($fin > $hoy){
            $fin = $hoy;
        }

        while($i_time <= $fin){
            $fecha = $i->format('Y-m-d');
            if(
                in_array($i->format('N'), $dias) &&
                !in_array($fecha, $excluir)
            ){
                    $a         =  new Asistencias();
                    $a->dia    =  $fecha;
                    $fechas[]  =  $a;
            }
            $i->modify('+1 day');
            $i_time = $i->format('U');
        }

        $myLog->close();
        return $fechas;
    }


    public function calificacionAlumno($alumnos_id, $oportunidad){
        $calificaciones = new Calificaciones();

        $calificacion = $calificaciones->find_by_sql(
            "SELECT " .
                "calificaciones.valor " .
            "FROM " .
                "calificaciones Inner Join oportunidades " .
                "ON calificaciones.oportunidades_id = oportunidades.id " .
            "WHERE " .
                "cursos_id = '" . $this->id . "' " .
                "AND alumnos_id = '" . $alumnos_id . "' " .
                "AND oportunidades.clave  = '" . $oportunidad . "' "
        );

        return $calificacion['valor'];
    }

    public function calificacionAprobatoria($alumnos_id, $oportunidad){
        $calificacion = $this->calificacionAlumno($alumnos_id, $oportunidad);

        if($this->materiaTipo() == 'TLR'){
            if($calificacion == 'A'){
                return true;
            }
        }else{
            if($calificacion >= 60){
                return true;
            }
        }
        return false;
    }

    public function calificaciones(){
        $calificaciones = new Calificaciones();
        $calificaciones = $calificaciones->find_all_by_sql(
            "SELECT " .
                "calificaciones.id, " .
                "calificaciones.valor, " .
                "calificaciones.alumnos_id, " .
                "oportunidades.clave as clave ".
            "FROM " .
                "calificaciones Inner Join oportunidades " .
                "ON calificaciones.oportunidades_id = oportunidades.id " .
            "WHERE " .
                "cursos_id = '" . $this->id . "' "
        );
        $cal = array();
        foreach($calificaciones as $calificacion){
            $cal[$calificacion->alumnos_id][$calificacion->clave]['valor']  =  $calificacion->valor;
            $cal[$calificacion->alumnos_id][$calificacion->clave]['id']     =  $calificacion->id;
            $cal[$calificacion->alumnos_id][$calificacion->clave]['status'] =  $calificacion->status();
        }
        return $cal;
    }

    public function calificacionesCaptura($accion){
        $aro=Session :: get_data('prof.usr.login');
        $agenda = new Agenda();
        $materia = $this->materia();
        $ciclo =  $this->ciclo();
        $eventos = new Eventos();
        if($materia->duracion==7){
        $eventos = $eventos->find("conditions: clave LIKE 'CCA-%' OR clave LIKE 'CPC-%'",
                                  "order: SUBSTRING(clave, 1, 3) DESC, clave ");
        }else{
        $eventos = $eventos->find("conditions: clave LIKE 'CAL-%' OR clave LIKE 'PRC-%'",
                                  "order: SUBSTRING(clave, 1, 3) DESC, clave ");
        }

        $calificaciones = array();
        $capturado = array_merge($this->calificacionesCapturadas(), $this->parcialesCapturados());
        $usr_grupos = Session :: get_data('prof.usr.grupos');
        $grupos='';
        foreach($usr_grupos as $g){
            $grupos.=" OR aro='".$g."'";
        }


        foreach($eventos as $evento){
            $roles = new Roles();
            $especial=new Eventos();

            $roles = $roles->find(
                "conditions: (eventos_id = '" . $evento->id . "'  OR  eventos_id = '" . $especial->eventoEspecial($evento) . "' )" .
                        " AND aco_section='calificaciones'" .
                        " AND aco='".$accion."'".
                " AND (aro='".$aro."' ".$grupos." OR aro='".Session :: get_data( 'prof.usr.codigo')."')"
            );

            foreach($roles as $rol){

            $periodo = $agenda->find_first(
                "conditions: " .
                "ciclos_id = '" . $ciclo->id . "' " .
                "AND roles_id = '" . $rol->id . "' " .
                "AND activo = '1' "
            );

            if($periodo->id != ''){
                $ini =  new DateTime( $periodo->inicio );
                $ini = mktime(00,00,01,$ini->format("n")  ,$ini->format("j")  , $ini->format("Y") );//$ini->format('U');


                $fin = new DateTime( $periodo->fin );
                $fin = mktime(23,59,59,$fin->format("n")  ,$fin->format("j")  , $fin->format("Y") );//$fin->format('U');


                $hoy=time();
                $hoy=mktime(date("H"),date("i"), date("s")  ,date("n",$hoy)  ,date("j",$hoy)  , date("Y",$hoy) );


                if(Utils:: endsWith($evento->clave,"-ESP"))
                $evento->clave=substr($evento->clave,0,strlen($evento->clave)-4);


                if( ($ini <= $hoy &&
                    $hoy <= $fin
                    )
                     &&
                    (  ( $accion == 'agregar' && !in_array($evento->clave, $capturado) ) ||
                     ( ($accion == 'editar' || $accion == 'eliminar') && in_array($evento->clave, $capturado))
                    )
                ){

                    $periodo->nombre = $evento->nombre;
                    $periodo->clave = $evento->clave;
                    $n = explode('-', $evento->clave);
                    $clave = $n[0];
                    if($clave == 'PRC' || $clave=='CPC'){
                        $n = intval($n[1], 0);
                    }else{
                        $n = $n[1];
                    }
                    $calificaciones[$clave][$n] = $periodo;

                }
            }
            }
        }

        if($materia->duracion==7){
                // para bloquear los extraordinarios hasta que se capturen los ordinarios
        if($accion == 'agregar'){
            if(!in_array('CCA-ORD', $capturado) && isset($calificaciones['CCA']['EXT'])){
                unset($calificaciones['CCA']['EXT']);
                if(count($calificaciones['CCA']) == 0){
                    unset($calificaciones['CCA']);
                }
            }
        }
        if($accion == 'eliminar'){
            if(isset($calificaciones['CCA']['EXT'])){
                unset($calificaciones['CCA']['ORD']);
            }
        }

        // las materias del tipo taller y programa ext y dif cultural no pueden ser evaluadas en periodo extraordinario
        if($materia->tipo == 'TLR' || $materia->tipo == 'PRO'){
            //unset($calificaciones['PRC']);
            if($calificaciones['CCA']['EXT'] != ''){
                unset($calificaciones['CCA']['EXT']);
            }
        }

        // limpia el arreglo
        $calificaciones['CCA'] = array_reverse($calificaciones['CCA']);
        if(count($calificaciones['CCA']) == 0){
            unset($calificaciones['CCA']);
        }

        }else{
        // para bloquear los extraordinarios hasta que se capturen los ordinarios
        if($accion == 'agregar'){
            if(!in_array('CAL-ORD', $capturado) && isset($calificaciones['CAL']['EXT'])){
                unset($calificaciones['CAL']['EXT']);
                if(count($calificaciones['CAL']) == 0){
                    unset($calificaciones['CAL']);
                }
            }
        }
        if($accion == 'eliminar'){
            if(isset($calificaciones['CAL']['EXT'])){
                unset($calificaciones['CAL']['ORD']);
            }
        }

        // las materias del tipo taller y programa ext y dif cultural no pueden ser evaluadas en periodo extraordinario
        if($materia->tipo == 'TLR' || $materia->tipo == 'PRO'){
            //unset($calificaciones['PRC']);
            if($calificaciones['CAL']['EXT'] != ''){
                unset($calificaciones['CAL']['EXT']);
            }
        }

        // limpia el arreglo
        $calificaciones['CAL'] = array_reverse($calificaciones['CAL']);
        if(count($calificaciones['CAL']) == 0){
            unset($calificaciones['CAL']);
        }

        }
        return $calificaciones;
    }

    public function calificacionesCapturadas(){
        $capturadas = array();
        $calificaciones = new Calificaciones();
        $materia = $this->materia();

        $calificaciones = $calificaciones->find_all_by_sql(
            "SELECT " .
                "oportunidades.clave AS clave " .
            "FROM " .
                "calificaciones Inner Join oportunidades " .
                "ON calificaciones.oportunidades_id = oportunidades.id " .
            "WHERE " .
                "cursos_id = '" . $this->id . "' " .
            "GROUP BY oportunidades.clave "
        );

        foreach($calificaciones as $calificacion){
            if($materia->duracion==7)
            $capturadas[] = 'CCA-' . $calificacion->clave;
            else
            $capturadas[] = 'CAL-' . $calificacion->clave;
        }
        $this->calCapturadas = $capturadas;
        return $capturadas;
    }

    public function ciclo(){
        $Grupos = new Grupos();
        $grupo = $Grupos->find($this->grupos_id);
        $ciclo = $grupo->ciclo();
        return $ciclo;
    }

    public function delCiclo($ciclo_id){
        $cursos=$this->find_all_by_sql(
                            "SELECT cursos.* FROM
                                    grupos
                                    INNER JOIN cursos ON cursos.grupos_id=grupos.id
                                    WHERE grupos.ciclos_id=".$ciclo_id);

                            return $cursos;
    }

    public function fechaInicioValido($inicio){
        $myLog = new Logger('inicio.log');
        $agenda = new Agenda();
        $evento = new Eventos();
        $rol = new Roles();
        $ciclo =  $this->ciclo();

        $evento = $evento->find_first(
            "conditions: clave = 'CRS-PER'"
        );
        $rol = $rol->find_first(
            "conditions: eventos_id = '" . $evento->id . "' "
        );
        $periodo = $agenda->find_first(
            "conditions: " .
                "ciclos_id = '" . $ciclo->id . "' " .
                "AND roles_id = '" . $rol->id . "' " .
                "AND activo = '1' "
        );
        $materia=$this->materia();
        if($materia->oferta('id')==2){
            $ini_p  =  new DateTime( substr($periodo->inicio, 0, 10) );
            $ini_p =  $ini_p->format('U');


            $fp=$fin_p = new DateTime( substr($periodo->fin, 0, 10) );
            $fin_p = $fin_p->format('U');

            $vacaciones=$this->asistenciasFechasVacaciones();
            $i    =    $ini  =  new DateTime( $inicio );
            //$myLog->log('-'.($ini->format('N')-1).' day '.$ini->format('N'), Logger::DEBUG);
            //$ini->modify('-'.($ini->format('N')-1).' day' );

            $fin = new DateTime( $inicio );
            //$myLog->log('-'.($fin->format('N')-1).' day '.$fin->format('N'), Logger::DEBUG);
            //$fin->modify('-'.($fin->format('N')-1).' day' );


            $myLog->log($ini->format('N'), Logger::DEBUG);
            $lim=($materia->duracion * 7)-($ini->format('N')-1);
            $myLog->log("lim: ".$lim, Logger::DEBUG);
            $cc=1;
            while($cc<=$lim){

            $fch = $fin->format('Y-m-d');


            if(
                !in_array($fch, $vacaciones)
            ){
                $cc++;
            }
            if($cc<$lim)
            $fin->modify('+1 day');
            }
            $des=$fin->format('N')-6;
            //if($des>0)
            //$fin->modify('-'.($des).' day');
            //else
            //$fin->modify('+'.(-1*$des).' day');
            }else{
            return true;
            }
            $myLog->log("inicio: ".$ini->format('Y-m-d'), Logger::DEBUG);
            $myLog->log("fin: ".$fin->format('Y-m-d'), Logger::DEBUG);
            $ini_c =  $ini->format('U');
            $fin_c =  $fin->format('U');

            $myLog->log("semanas: ".$fin->format('W').'<='.$fp->format('W'), Logger::DEBUG);
        if( (($ini_p<=$ini_c) && ($ini_c<=$fin_p))  &&
            ( ($ini_p<=$fin_c) && ( ($fin->format('Y')==$fp->format('Y')) && ($fin->format('W')<=$fp->format('W')) ) ) ||
            (($ini_p<=$fin_c) && ($fin_c<=$fin_p))
             ){
            $myLog->log("return: true", Logger::DEBUG);
            $myLog->commit();
            $myLog->close();
                return true;
                }else{
            $myLog->log("return: false", Logger::DEBUG);
            $myLog->commit();

//Cierra el Log
$myLog->close();
                return false;

            }

        }

    public function fechaFin($inicio){

        $agenda = new Agenda();
        $evento = new Eventos();
        $rol = new Roles();
        $ciclo =  $this->ciclo();

        $evento = $evento->find_first(
            "conditions: clave = 'CRS-PER'"
        );
        $rol = $rol->find_first(
            "conditions: eventos_id = '" . $evento->id . "' "
        );
        $periodo = $agenda->find_first(
            "conditions: " .
                "ciclos_id = '" . $ciclo->id . "' " .
                "AND roles_id = '" . $rol->id . "' " .
                "AND activo = '1' "
        );
        $materia=$this->materia();
        if($materia->oferta('id')==2){
            $ini_p  =  new DateTime( substr($periodo->inicio, 0, 10) );
            $ini_p =  $ini_p->format('U');


            $fp=$fin_p = new DateTime( substr($periodo->fin, 0, 10) );
            $fin_p = $fin_p->format('U');

            $vacaciones=$this->asistenciasFechasVacaciones();
            $i    =    $ini  =  new DateTime( $inicio );

            $fin = new DateTime( $inicio );



            $lim=($materia->duracion * 7)-($ini->format('N')-1);

            $cc=1;
            while($cc<=$lim){

            $fch = $fin->format('Y-m-d');


            if(
                !in_array($fch, $vacaciones)
            ){
                $cc++;
            }
            if($cc<$lim)
            $fin->modify('+1 day');
            }
            $des=$fin->format('N')-6;

            }else{
            return true;
            }

            $ini_c =  $ini->format('U');
            $fin_c =  $fin->format('U');

            return $fin;


        }

    public function fechaFin2($inicio,$materia){

        $agenda = new Agenda();
        $evento = new Eventos();
        $rol = new Roles();
        $ciclo =  $this->ciclo();

        $evento = $evento->find_first(
            "conditions: clave = 'CRS-PER'"
        );
        $rol = $rol->find_first(
            "conditions: eventos_id = '" . $evento->id . "' "
        );
        $periodo = $agenda->find_first(
            "conditions: " .
                "ciclos_id = '" . $ciclo->id . "' " .
                "AND roles_id = '" . $rol->id . "' " .
                "AND activo = '1' "
        );

        if($materia->oferta('id')==2){
            $ini_p  =  new DateTime( substr($periodo->inicio, 0, 10) );
            $ini_p =  $ini_p->format('U');


            $fp=$fin_p = new DateTime( substr($periodo->fin, 0, 10) );
            $fin_p = $fin_p->format('U');

            $vacaciones=$this->asistenciasFechasVacaciones();
            $i    =    $ini  =  new DateTime( $inicio );

            $fin = new DateTime( $inicio );



            $lim=($materia->duracion * 7)-($ini->format('N')-1);

            $cc=1;
            while($cc<=$lim){

            $fch = $fin->format('Y-m-d');


            if(
                !in_array($fch, $vacaciones)
            ){
                $cc++;
            }
            if($cc<$lim)
            $fin->modify('+1 day');
            }
            $des=$fin->format('N')-6;

            }else{
            return true;
            }

            $ini_c =  $ini->format('U');
            $fin_c =  $fin->format('U');

            return $fin;


        }


    public function grupo(){
        $Grupos = new Grupos();
        $grupo = $Grupos->find($this->grupos_id);
        return $grupo;
    }

    public function horario($d){
        $horario = new Horarios();
        $horario = $horario->find_first("conditions: cursos_id = '" . $this->id . "' AND " .
                                    "dias_id = '" . $d . "'"
                               );
        return $horario;
    }

    public function horarios(){
        $horarios = new Horarios();
        $horarios = $horarios->find("conditions: cursos_id = '" . $this->id . "'",
                                    "order: dias_id");
        $hs = array();
        foreach($horarios as $h){
            $h->inicio = substr($h->inicio, 0, 5);
            $h->fin = substr($h->fin, 0, 5);
            $hs[$h->dias_id] = $h;
        }
        return $hs;
    }

    public function horariosDias(){
        $horarios = new Horarios();
        $horarios = $horarios->find("conditions: cursos_id = '" . $this->id . "'",
                                    "order: dias_id");
        $hs = array();
        foreach($horarios as $h){
            $dia=new Dias();
            $dia=$dia->find_first($h->dias_id);
            $hs[$h->dias_id] = strtoLower(Utils::textoPlano($dia->nombre));
        }
        return $hs;
    }

    public function inscribirAlumnosdelGrupo(){
        $materia=$this->materia();
        if($materia->tipo=="OBL" || $materia->tipo=="TLR"){
        $grupo=$this->grupo();
        $alumnos=$grupo->alumnos_ids();
        foreach($alumnos as $alumno_id){
                $inscripcion=new Alumnoscursos();
                if(!$inscripcion->exists("alumnos_id=".$alumno_id." AND cursos_id=".$this->id)){
                    $inscripcion->alta($alumno_id,$this->id);
                }
        }
        }
    }

    public function materia(){
        $Materias = new Materias();
        $materia = $Materias->find($this->materias_id);
        return $materia;
    }

    public function materiaTipo(){
        if($this->materiaTipo == ''){
            $Materias = new Materias();
            $materia = $Materias->find($this->materias_id);
            $this->materiaTipo = $materia->tipo;
        }
        return $this->materiaTipo;
    }

    public function oportunidad($prc){
        if($prc >= 80){
            return 'ordinario';
        }else if($prc < 80 && $prc >= 60){
            if($this->materiaTipo() == 'TLR'){
                return 'sin-derecho';
            }else{
                return 'extraordinario';
            }
        }else{
            return 'sin-derecho';
        }
    }

    public function oportunidadAlumno($alumnos_id, $modo = ''){
        $ast = $this->asistenciasResumenAlumno($alumnos_id);
        $capturadas = $this->calCapturadas;

        switch($modo){

            case 'AST':
                    $oportunidad = $ast['oportunidad'];
                 break;

            case 'AST-ORD':
                    $oportunidad = $ast['oportunidad'];
                    if( $ast['oportunidad'] != 'sin-derecho' &&
                        $ast['oportunidad'] != 'extraordinario' )
                    {
                            if( in_array('CAL-ORD', $capturadas) || in_array('CCA-ORD', $capturadas) ){
                                if( !$this->calificacionAprobatoria($alumnos_id, 'ORD') ){
                                    $oportunidad = 'extraordinario';
                                }
                            }
                    }
                break;

            case 'AST-EXT':
            default:
                    $oportunidad = $ast['oportunidad'];
                    if( $ast['oportunidad'] != 'sin-derecho'){
                        if( $ast['oportunidad'] == 'extraordinario' ){
                            if( in_array('CAL-EXT', $capturadas) || in_array('CCA-EXT', $capturadas) ){
                                if( !$this->calificacionAprobatoria($alumnos_id, 'EXT') ){
                                    $oportunidad = 'sin-derecho';
                                }
                            }
                        }else{
                            if( in_array('CAL-ORD', $capturadas) || in_array('CCA-ORD', $capturadas) ){
                                if( !$this->calificacionAprobatoria($alumnos_id, 'ORD') ){
                                    $oportunidad = 'extraordinario';
                                    if( in_array('CAL-EXT', $capturadas) || in_array('CCA-EXT', $capturadas) ){
                                        if( !$this->calificacionAprobatoria($alumnos_id, 'EXT') ){
                                            $oportunidad = 'sin-derecho';
                                        }
                                    }
                                }
                            }
                        }
                    }
                break;

        }

        return $oportunidad;
    }

    public function oportunidadClaveAlumno($alumnos_id, $modo = ''){
        $oportunidad = $this->oportunidadAlumno($alumnos_id, $modo);
        $clave = substr($oportunidad, 0, 3);
        $clave = strtoupper($clave);

        return $clave;
    }

    public function parciales(){
        $parciales = new Calificacionesparciales();
        $parciales = $parciales->find("conditions: cursos_id = '" . $this->id . "' ");
        $prc = array();
        foreach($parciales as $parcial){
            $prc[$parcial->alumnos_id][$parcial->periodo] = $parcial->valor;
            $prc[$parcial->alumnos_id]['id_' . $parcial->periodo] = $parcial->id;
            $prc[$parcial->alumnos_id]['total'] += $parcial->valor;
        }
        return $prc;
    }

    private function parcialesCapturados(){
        $parciales = $this->parcialesHdr();
        $materia = $this->materia();
        $capturados = array();
        foreach($parciales as $parcial){
            if($materia->duracion==7)
            $capturados[] = 'CPC-' . str_pad($parcial->periodo, 3, "0", STR_PAD_LEFT);
            else
            $capturados[] = 'PRC-' . str_pad($parcial->periodo, 3, "0", STR_PAD_LEFT);
        }
        return $capturados;
    }

    public function parcialesHdr(){
        $parciales = new Calificacionesparciales();
        $parciales = $parciales->find_all_by_sql(
                        "SELECT periodo " .
                        "FROM calificacionesparciales " .
                        "WHERE cursos_id = '" . $this->id . "' " .
                        "GROUP BY periodo " .
                        "ORDER BY periodo "
                     );
        return $parciales;
    }

    public function parcialesResumen(){
        $p = new Calificacionesparciales();
        $parciales = $this->parciales();
        $n = count($this->parcialesHdr());
        $resumen = array();
        $resumen['_n_'] = $n;
        foreach($parciales as $alumno_id => $calificacion){
            $promedio = $calificacion['total'] / $n;
            $resumen[$alumno_id]['promedio'] = number_format($promedio, 1, '.', ',');
            $resumen[$alumno_id]['status'] = $p->status($promedio);
        }
        return $resumen;
    }

    public function profesor(){
        $Profesores = new Profesores();
        $profesor = $Profesores->find($this->profesores_id);
        return $profesor;
    }

    public function verEstado(){
        $status = new Estado();
        $status = $status->find($this->estado_id);
        return $status->nombre;
    }

    // Retorna los valores en formato de cadena
    public function verGrupo(){
        $Grupos = new Grupos();
        $grupo = $Grupos->find($this->grupos_id);
        return $grupo->grado . $grupo->letra . $grupo->turno;
    }


    public function verMateria(){
        $Materias = new Materias();
        $materia = $Materias->find($this->materias_id);
        return $materia->clave . '/' . $materia->nombre;
    }

    public function verMateriaNombre(){
        $Materias = new Materias();
        $materia = $Materias->find($this->materias_id);
        return $materia->nombre;
    }

    public function verMateriaTipo(){
        $Materias = new Materias();
        $materia = $Materias->find($this->materias_id);
        return $materia->tipo();
    }

    public function verProfesor(){
        $Profesores = new Profesores();
        $profesor = $Profesores->find($this->profesores_id);
        return $profesor->ap . ' ' . $profesor->am . ', ' . $profesor->nombre;
    }

    public function rechazado(){
        if($this->estado_id==4)
            return true;
            else return false;
    }


    public function sincronizar($alumno){

    $capturadas = $this->asistenciasHdr();
    $parciales = $this->parcialesHdr();

    $falta=0;
    foreach($capturadas as $capturada){
        $asistencias=new Asistencias();
        $asistencias->alumnos_id=$alumno->id;
        $asistencias->cursos_id=$this->id;
        $asistencias->asistenciasvalor_id=$falta;
        $asistencias->dia=$capturada->dia;
        $asistencias->saved_at=date("Y-m-d H:i:s");
        $asistencias->save();
    }

    $calificacion='50';
    foreach($parciales as $parcial){
        $p=new Calificacionesparciales();
        $p->alumnos_id=$alumno->id;
        $p->cursos_id=$this->id;
        $p->periodo=$parcial->periodo;
        $p->valor=$calificacion;
        $p->saved_at=date("Y-m-d H:i:s");
        $p->save();
    }



    }

    public function estutor(){
        $tutorias=Session :: get_data('prof.usr.tutorias');
        return (array_key_exists($this->grupos_id,$tutorias));


    }

    public function tutoradoencurso(){
            $alumnos=$this->alumnosInscritos();
            foreach($alumnos as $alumno){
                if($alumno->estutor()){
                    return true;
                }
            }

            return false;
    }

}
?>
