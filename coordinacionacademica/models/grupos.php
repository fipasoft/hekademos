<?php
class Grupos extends ActiveRecord{

    public function accesiblesParaAlumno($alumno_id,$niveles){
        if(in_array('director',$niveles) || in_array('secretario',$niveles) ){
            $ciclo_id = Session :: get_data('ciclo.id');
            return $this->find('conditions: ciclos_id = "' . $ciclo_id .'" AND id!='.$this->id.'', 'order: turno, grado, letra');
        }else if(in_array('oficial',$niveles)){
            return $this->similares();
        }else return array();

    }

    public function alumnos(){
        $alumnos = new Alumnos();
        $alumnos = $alumnos->find_all_by_sql(
            "SELECT " .
                "alumnos.id, alumnos.codigo, alumnos.nombre, alumnos.ap, " .
                "alumnos.am,alumnos.promedio,alumnos.aprobadas, grupos.grado, grupos.letra, grupos.turno, " .
                "situaciones.clave AS situacion " .
            "FROM " .
                "(alumnos " .
                "Inner Join alumnosgrupo ON alumnos.id = alumnosgrupo.alumnos_id " .
                "Inner Join grupos ON alumnosgrupo.grupos_id = grupos.id " .
                "Inner Join situaciones ON alumnos.situaciones_id = situaciones.id) " .
                "Left Join alumnoscursos ON alumnos.id = alumnoscursos.alumnos_id " .
            "WHERE " .
                "alumnosgrupo.grupos_id = '" . $this->id . "' " .
            "GROUP BY " .
                "alumnos.id " .
            "ORDER BY " .
                "alumnos.ap, alumnos.am, alumnos.nombre "
        );
        return $alumnos;
    }

    public function alumnos_inscritos_a_cursos(){
        $alumnos = new Alumnos();
        $alumnos = $alumnos->find_all_by_sql(
            "SELECT alumnos.*  FROM
                grupos
                INNER JOIN cursos ON grupos.id=cursos.grupos_id
                INNER JOIN alumnoscursos ON cursos.id=alumnoscursos.cursos_id
                INNER JOIN alumnos ON alumnoscursos.alumnos_id=alumnos.id " .
            "WHERE " .
                "grupos.id = '" . $this->id . "'  AND cursos.estado_id=3 " .
            "GROUP BY " .
                "alumnos.id " .
            "ORDER BY alumnos.ap, alumnos.am, alumnos.nombre "
        );

        return $alumnos;

    }

    public function alumnos_ids(){
        $alumnos = new Alumnos();
        $alumnos = $alumnos->find_all_by_sql(
            "SELECT " .
                "alumnos.id " .
            "FROM " .
                "(alumnos " .
                "Inner Join alumnosgrupo ON alumnos.id = alumnosgrupo.alumnos_id " .
                "Inner Join grupos ON alumnosgrupo.grupos_id = grupos.id) " .
                "Left Join alumnoscursos ON alumnos.id = alumnoscursos.alumnos_id " .
            "WHERE " .
                "alumnosgrupo.grupos_id = '" . $this->id . "' " .
            "GROUP BY " .
                "alumnos.id "
        );
        $alos = array();
        foreach($alumnos as $alumno){
            $alos[] = $alumno->id;
        }
        return $alos;
    }

    public function asignado(){
        $asignados = $this->asignados();
        if( in_array($this->id, $asignados) ||
            in_array('ALL', $asignados))
        {
            return true;
        }
        return false;
    }

    public function asignados(){
        $asignados = Session :: get_data('grp.asignados');
        if( !is_array($asignados) ){
            $usr_id = Session :: get_data('usr.id');
            $usr_grupos = Session :: get_data('usr.grupos');
            $asignados = array();
            // verifica si el usuario solo puede revisar grupos por asignacion
            if( in_array('direccion',  $usr_grupos) ||
                in_array('director',  $usr_grupos) ||
                in_array('secretario',  $usr_grupos) ||
                in_array('oficial',    $usr_grupos) ||
                in_array('plantilla',  $usr_grupos) ||
                in_array('administradores',  $usr_grupos)
                   )
             {
                 $asignados[] = 'ALL';
             }else{
                $asignaciones = new Asignar();
                $asignaciones = $asignaciones->find("conditions: usuarios_id = '" . $usr_id . "'");
                if(count($asignaciones) > 0){
                    foreach($asignaciones as $asignacion){
                        $asignados[] = $asignacion->grupos_id;
                    }
                }
            }
            Session :: set_data('grp.asignados', $asignados);
        }
        return $asignados;
    }

    public function asistencias($cursos = ''){
        if( !is_array($cursos) ){
            $cursos = $this->cursos();
        }
        $asistencias = array();
        foreach($cursos as $curso){
            $asistencias[$curso->id] = $curso->asistenciasResumen();
        }
        return $asistencias;
    }


    public function asistenciasInfo($cursos = ''){
        if( !is_array($cursos) ){
            $cursos = $this->cursos();
        }
        $asistencias = array();
        foreach($cursos as $curso){
            $asistencias[$curso->id] = $curso->asistenciasInfo();
        }
        return $asistencias;
    }



    public function cambioValido($actual,$nuevo,$alumno_id,$niveles){
            if(in_array('director',$niveles) || in_array('secretario',$niveles)){
            $accesibles=$this->accesiblesParaAlumno($alumno_id,$niveles);
            $grupoNuevo=new Grupos();
            $grupoNuevo=$grupoNuevo->find($nuevo);
            foreach($accesibles as $accesible){
                if($accesible->id==$grupoNuevo->id){
                return true;
                }
            }
            return false;

            }else if(in_array('oficial',$niveles)){
            $accesibles=$this->accesiblesParaAlumno($alumno_id,$niveles);
            $grupoActual=new Grupos();
            $grupoActual=$grupoActual->find($actual);
            $grupoNuevo=new Grupos();
            $grupoNuevo=$grupoNuevo->find($nuevo);
            foreach($accesibles as $accesible){
                if($accesible->id==$grupoNuevo->id && $grupoActual->grado==$grupoNuevo->grado){
                return true;
                }
            }
            return false;

            }else return false;

    }

    public function calificaciones(){
        $calificaciones = new Calificaciones();
        $calificaciones = $calificaciones->find_all_by_sql(
            "SELECT " .
                "calificaciones.id, " .
                "calificaciones.valor, " .
                "calificaciones.alumnos_id, " .
                "oportunidades.clave as clave, ".
                "cursos.id as curso " .
            "FROM " .
                "grupos " .
                "Inner Join cursos ON cursos.grupos_id = grupos.id " .
                "Inner Join calificaciones ON calificaciones.cursos_id = cursos.id " .
                "Inner Join oportunidades ON calificaciones.oportunidades_id = oportunidades.id " .
            "WHERE " .
                "cursos.grupos_id = '" . $this->id . "' "
        );
        $cal = array();
        foreach($calificaciones as $calificacion){
            $cal[$calificacion->alumnos_id][$calificacion->curso][$calificacion->clave]['valor']  =  $calificacion->valor;
            $cal[$calificacion->alumnos_id][$calificacion->curso][$calificacion->clave]['status'] =  $calificacion->status();
        }
        return $cal;
    }

    public function ciclo(){
        $ciclo = new Ciclos();
        $ciclo = $ciclo->find($this->ciclos_id);
        return $ciclo;
    }

    public function cursos(){
        $cursos = new Cursos();
        $cursos = $cursos->find_all_by_sql(
            "SELECT " .
                "cursos.id, " .
                "estado_id, " .
                "materias.nombre AS materia, " .
                "materias.id AS materia_id, " .
                "materias.tipo AS materiaTipo, " .
                "materias.semestre AS materia_semestre " .
            "FROM " .
                "cursos Inner Join materias ON cursos.materias_id = materias.id " .
            "WHERE " .
                "cursos.grupos_id = '" . $this->id . "' " .
            "ORDER BY " .
                "materias.nombre "
        );
        return $cursos;
    }

    public function cursosInfo(){
        $cursos = new Cursos();
        $cursos = $cursos->find("cursos.grupos_id = '" . $this->id . "' ");
        return $cursos;
    }

        public function cursosInformacion(){
        $cursos = new Cursos();
        $cursos = $cursos->find_all_by_sql(
            "SELECT " .
                "cursos.* " .
                "estado_id, " .
                "materias.nombre AS materia, " .
                "materias.id AS materia_id, " .
                "materias.tipo AS materiaTipo, " .
                "materias.semestre AS materia_semestre " .
            "FROM " .
                "cursos Inner Join materias ON cursos.materias_id = materias.id " .
            "WHERE " .
                "cursos.grupos_id = '" . $this->id . "' AND cursos.estado_id=3 " .
            "ORDER BY " .
                "materias.nombre "
        );
        return $cursos;
    }


    public function cursosMaterias(){
        $cc = new Cursos();
        $cc = $cc->find_all_by_sql(
            "SELECT " .
                "cursos.*, " .
                "materias.id AS materia_id, " .
                "materias.nombre AS materia, " .
                "materias.tipo AS materiaTipo " .
            "FROM " .
                "cursos Inner Join materias ON cursos.materias_id = materias.id " .
            "WHERE " .
                "cursos.grupos_id = '" . $grupo_siguiente->id . "' " .
            "ORDER BY " .
                "materias.nombre "
        );

        return $cc;
    }

    /**
     * @method array Retorna un arreglo de objetos de la clase Grupos de acuerdo a los privilegios ACL establecidos en la agenda del SP5
     * @return array Arreglo de objetos ActiveRecord de la clase Grupos
     */
    public function disponiblesParaAgregarAlumno(){
        $asignados = $this->asignados();
        $disponibles = array();
        $ciclo_id = Session :: get_data('ciclo.id');
        if( in_array('ALL', $asignados) ){
            $disponibles = $this->todos();
        }else{
            $c = '';
            foreach($asignados as $gid){
                $c .= ($c == '' ? '' : ' OR ') . "id = '" . $gid  . "'";
            }
            $disponibles = $this->find(
                "conditions: grado = '1' AND ciclos_id = '" . $ciclo_id . "' " .
                            ($c != '' ? ' AND (' . $c . ')' : '')
            );
        }
        return $disponibles;
    }

        /**
     * @method array Retorna un arreglo de objetos de la clase Grupos de acuerdo a los privilegios ACL establecidos en la agenda del SP5
     * @return array Arreglo de objetos ActiveRecord de la clase Grupos
     */
    public function disponiblesParaAgregarCurso(){
        $grupos=$this->todos();
        $grps=array();
        foreach($grupos as $grupo){
        // materias asignadas a otros cursos del mismo grupo
                    $cursos = new Cursos();
                    $cursos = $cursos->find("conditions: grupos_id = '" . $grupo->id . "'",
                                            "fields: materias_id"
                                       );

                    $c = '';
                    foreach($cursos as $curso){
                        $c .= "AND materias.id != '" . $curso->materias_id . "' ";
                    }
                    // materias
                    $materias = new Materias();

                    $materias=$materias->porOferta($grupo->oferta_id,$grupo->grado,$c);
                    if(count($materias) > 0){
                    $grps[]=$grupo;
                    }
        }
        return $grps;
        }

    public function encargados(){
        $asignaciones = new Asignar();
        return $asignaciones->find("conditions: grupos_id = '" . $this->id . "'");
    }

    public function tutores(){
        $asignaciones = new Tutoresgrupos();
        return $asignaciones->find("conditions: grupos_id = '" . $this->id . "'");
    }


    public function materiasAsignadas(){
        // materias asignadas a otros cursos del mismo grupo
        $crs = new Cursos();
        $crs = $crs->find(  "conditions: grupos_id = '" . $this->id . "'",
                            "columns: materias_id"
                           );
        $cM = '';
        foreach($crs as $cr){
            $cM .= "AND id != '" . $cr->materias_id . "' ";
        }
        // materias
        $mat = new Materias();
        $mat = $mat->find("conditions: semestre = '" . $this->grado . "' " . $cM);
        return $mat;
    }

    public function todos(){
        $grupos = new Grupos();
        $ciclo_id = Session :: get_data('ciclo.id');
        return $grupos->find('conditions: ciclos_id = "' . $ciclo_id .'"', 'order: turno, grado, letra');
    }

    public function todosporCiclo($ciclo_id){
        $grupos = new Grupos();
        return $grupos->find('conditions: ciclos_id = "' . $ciclo_id .'"', 'order: turno, grado, letra');
    }

    function similares(){
        return $this->find("ciclos_id=".$this->ciclos_id.' AND grado='.$this->grado.' AND id!='.$this->id );
    }

    public function ver($modo = ''){
        return $this->grado . ($modo == 'html' ? '&deg;' : '°') .$this->letra . ' ' . $this->verTurno() . ' '.$this->verOferta() ;
    }

    public function verReducido($modo = ''){
        return $this->grado . ($modo == 'html' ? '&deg;' : '°') .$this->letra . ' ' . $this->verTurno() . ' '.$this->verOfertaClave() ;
    }


    public function nCursos(){
        return count($this->materiasAsignadas());
    }

    public function verInfo(){
        return $this->grado . $this->letra . $this->turno;
    }

    public function verTurno(){
        switch($this->turno){
            case 'M': return 'Matutino';
            case 'V': return 'Vespertino';
            case 'N': return 'Nocturno';
        }
    }

    public function verOferta(){
        $oferta=new Oferta();
        $oferta=$oferta->find_first($this->oferta_id);
        return $oferta->nombre;
    }

    public function verOfertaClave(){
        $oferta=new Oferta();
        $oferta=$oferta->find_first($this->oferta_id);
        return $oferta->clave;
    }


}
?>
