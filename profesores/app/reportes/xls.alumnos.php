<?php
class XLSAlumnos extends Reporte{
    private $condicion;
    private $ciclo;

    public function XLSAlumnos($ciclo_id){
        $controlador='alumnos';
        $accion='index';
        $usr_grupos = Session :: get_data('prof.usr.grupos');
        $profesor_id=Session :: get_data('prof.usr.id');
        $alumnos = new Alumnos();
        $b = new Busqueda($controlador, $accion);
        $b->establecerCondicion('nombre', "CONCAT(TRIM(alumnos.nombre), ' ', TRIM(ap), ' ', TRIM(am)) LIKE '%" . $b->campo('nombre') . "%' ");
        $b->establecerCondicion('situacion', "situaciones.id  = '" . $b->campo('situacion') . "' ");
        $b->establecerCondicion('oferta_id', "grupos.oferta_id  = '" . $b->campo('oferta_id') . "' ");
        // genera las condiciones
        $this->condicion =$c = $b->condicion();

                $Grupos = new Grupos();
        $asignaciones = $Grupos->asignados();

        if(count($asignaciones)>0){
        if(!in_array('ALL', $asignaciones)){
            $asg = '';
            foreach($asignaciones as $grupos_id){
                $asg .= ($asg == '' ? '' : 'OR ') . " grupos.id = '" . $grupos_id . "' ";
            }
            $asg = " AND ($asg) ";
        }
        }

        /*    // cuenta todos los registros
        if(in_array("root",$usr_grupos) || in_array("administradores",$usr_grupos)){
        $registros = $alumnos->count_by_sql("SELECT " .
        "COUNT(*) " .
        "FROM " .
        "(SELECT " .
        "alumnos.id " .
        "FROM " .
        "ciclos " .
        "Inner Join grupos ON ciclos.id = grupos.ciclos_id " .
        "Inner Join alumnosgrupo ON grupos.id = alumnosgrupo.grupos_id " .
        "Inner Join alumnos ON alumnosgrupo.alumnos_id = alumnos.id " .
        "Inner Join situaciones ON alumnos.situaciones_id = situaciones.id " .
        "WHERE " .
        "ciclos.id = '" . $ciclo_id . "'" .
         ($c == "" ? "" : "AND " . $c) . " ) " .
        "AS t1 ");
        }else{
            $registros = $alumnos->count_by_sql(
                "SELECT count(*)  FROM (
                ((
                    SELECT
                    DISTINCT(alumnos.id), alumnos.codigo, alumnos.nombre, alumnos.am, alumnos.ap,  alumnos.foto, situaciones.nombre AS situacion
                    FROM
                    grupos
                    INNER JOIN alumnosgrupo ON grupos.id=alumnosgrupo.grupos_id
                    INNER JOIN alumnos ON alumnosgrupo.alumnos_id=alumnos.id
                    Inner Join situaciones ON alumnos.situaciones_id = situaciones.id
                    WHERE
                    (grupos.ciclos_id='" . $ciclo_id . "' ".$asg." ". ($c == "" ? "" : "AND " . $c) .")
                    UNION
                    (
                    SELECT
                    DISTINCT(alumnos.id), alumnos.codigo, alumnos.nombre, alumnos.am, alumnos.ap,  alumnos.foto, situaciones.nombre AS situacion
                    FROM
                    cursos
                    INNER JOIN grupos ON cursos.grupos_id=grupos.id
                    INNER JOIN alumnoscursos ON cursos.id=alumnoscursos.cursos_id
                    INNER JOIN alumnos ON alumnoscursos.alumnos_id=alumnos.id
                    INNER JOIN situaciones ON alumnos.situaciones_id = situaciones.id
                    WHERE " .
                    "(grupos.ciclos_id = '" . $ciclo_id . "' AND cursos.profesores_id=".$profesor_id." ".
                    ($c == "" ? "" : "AND " . $c) . " ) ORDER BY grupos.turno, grupos.grado, grupos.letra, alumnos.ap, alumnos.am, alumnos.nombre ) )
                    ) AS t1
                    )");

        }*/

        if(in_array("root",$usr_grupos) || in_array("administradores",$usr_grupos)){
        // ejecuta la consulta
        $alos = $alumnos->find_all_by_sql("SELECT " .
        "alumnos.*, " .
        "grupos.id AS grupos_id, " .
        "grupos.grado, " .
        "grupos.letra," .
        "grupos.turno, " .
        "situaciones.nombre AS situacion " .
        "FROM " .
        "ciclos " .
        "Inner Join grupos ON ciclos.id = grupos.ciclos_id " .
        "Inner Join alumnosgrupo ON grupos.id = alumnosgrupo.grupos_id " .
        "Inner Join alumnos ON alumnosgrupo.alumnos_id = alumnos.id " .
        "Inner Join situaciones ON alumnos.situaciones_id = situaciones.id " .
        "WHERE " .
        "ciclos.id = '" . $ciclo_id . "'" .
         ($c == "" ? "" : "AND " . $c) . " " .
        "ORDER BY " .
        "grupos.turno, grupos.grado, grupos.letra, alumnos.ap, alumnos.am, alumnos.nombre " );
        }else{

                // ejecuta la consulta
                $alos = $alumnos->find_all_by_sql(
                    "SELECT * FROM (
                    (
                    (
                    (
                    SELECT
                    DISTINCT(alumnos.id), alumnos.codigo, alumnos.nombre, alumnos.am, alumnos.ap,  alumnos.foto, alumnos.domicilio, alumnos.tel , alumnos.cel, alumnos.mail, alumnos.curp, alumnos.fnacimiento, alumnos.sexo, alumnos.situaciones_id, alumnos.admision, alumnos.promedio,
                     situaciones.nombre AS situacion
                    FROM
                    grupos
                    INNER JOIN alumnosgrupo ON grupos.id=alumnosgrupo.grupos_id
                    INNER JOIN alumnos ON alumnosgrupo.alumnos_id=alumnos.id
                    Inner Join situaciones ON alumnos.situaciones_id = situaciones.id
                    WHERE     (grupos.ciclos_id='" . $ciclo_id . "' ".$asg." ". ($c == "" ? "" : "AND " . $c) .")".
                    ")
                    UNION
                    (
                    SELECT
                    DISTINCT(alumnos.id), alumnos.codigo, alumnos.nombre, alumnos.am, alumnos.ap,  alumnos.foto, alumnos.domicilio, alumnos.tel , alumnos.cel, alumnos.mail, alumnos.curp, alumnos.fnacimiento, alumnos.sexo, alumnos.situaciones_id, alumnos.admision, alumnos.promedio ,
                     situaciones.nombre AS situacion
                    FROM
                    cursos
                    INNER JOIN alumnoscursos ON cursos.id=alumnoscursos.cursos_id
                    INNER JOIN alumnos ON alumnoscursos.alumnos_id=alumnos.id
                    INNER JOIN alumnosgrupo ON alumnos.id=alumnosgrupo.alumnos_id
                    INNER JOIN grupos ON alumnosgrupo.grupos_id=grupos.id
                    INNER JOIN situaciones ON alumnos.situaciones_id = situaciones.id
                    WHERE
                    (grupos.ciclos_id = '" . $ciclo_id . "' AND cursos.profesores_id=".$profesor_id." ".
                    ($c == "" ? "" : "AND " . $c) . " ) " .
                    ")
                    )ORDER BY  ap,am,nombre)AS t1
                    )");

        }
        $datos = array ();
        foreach($alos as $a){
            $grupo=$a->obtenerGrupoPorCiclo($ciclo_id);
            $datos[$grupo->id][]=$a;
        }

        $this->Reporte('Alumnos ' . $ciclo->numero . '  ' . ($c != '' ? ' FILTRADO' : '' ). '.xls');

        if(count($datos)>0){
            foreach($datos as $id=>$als){
                $this->hoja($id,$als);
            }

        }else{
            $this->hoja_vacia();
        }

/*
        $ciclo=new Ciclos();
        $this->ciclo=$ciclo=$ciclo->find_first($ciclo_id);
        $this->Reporte('Alumnos ' . $ciclo->numero . '  ' . ($c != '' ? ' FILTRADO' : '' ). '.xls');

            $grupos = $this->datos_grupos();

            if(count($grupos) > 0){
                foreach($grupos as $grupo){
                    if($this->cuenta_alumnos($grupo)>0)
                        $this->hoja($grupo);
                }
            }else{
                $this->hoja_vacia();
            }

            $hojas = $this->getHojas();
            if(count($hojas)==0)
                $this->hoja_vacia();
*/

    }

    public function hoja($id,$als){
        $grupo=new Grupos();
        $grupo=$grupo->find($id);
        $nombre = $grupo->grado. $grupo->letra . $grupo->turno ;
        $hojas = $this->getHojas();
        if(array_key_exists($nombre, $hojas)){
            $h = $hojas[$nombre];
        }else{
            if($grupo->estutor()){
            $cols = array(  10, 30, 20, 20,
                           35, 10, 15, 35, 23, 12, 5, 15, 15, 15, 15
                    );
            }else{
            $cols = array(  10, 30, 20, 20,
                            35, 23, 12, 5, 15, 15, 15, 15
                    );
            }
            $h = $this->agregar_hoja($nombre, null, $cols);
            $h->cc_max = 16;
        }
        $this->contenido($h, $grupo,$als);
        $this->propiedades($h);
    }

    public function hoja_vacia(){
        $nombre = 'SP5';
        $h = $this->agregar_hoja($nombre);
        $h->xls->write(0, 0, "No hay registros que coincidan con esas condiciones");
    }

    public function contenido(&$h, $grupo,$alumnos){
        $this->encabezado($h,$grupo);
        $st = $this->getEstilos();
        $h->xls->write($h->rr, $h->cc, 'Codigo', $st['TD.BGYellow']); $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'Nombre', $st['TD.BGYellow']); $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'ap', $st['TD.BGYellow']); $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'am', $st['TD.BGYellow']); $h->cc++;
        if($grupo->estutor()){
        $h->xls->write($h->rr, $h->cc, 'domicilio', $st['TD.BGYellow']); $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'tel', $st['TD.BGYellow']); $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'cel', $st['TD.BGYellow']); $h->cc++;
        }
        $h->xls->write($h->rr, $h->cc, 'mail', $st['TD.BGYellow']); $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'curp', $st['TD.BGYellow']); $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'fnacimiento', $st['TD.BGYellow']); $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'sexo', $st['TD.BGYellow']); $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'situacion', $st['TD.BGYellow']); $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'admision', $st['TD.BGYellow']); $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'promedio', $st['TD.BGYellow']); $h->cc++;
        $h->nueva_linea();
        $n = 0;
        foreach($alumnos as $alumno){
            $n++;
            $td = ($n%2 == 0 ? 'Par' : '');
            $tutor='';
            $h->xls->writeString($h->rr, $h->cc,$alumno->codigo, $st['TD' . $td . '.Normal']); $h->cc++;
            $h->xls->write($h->rr, $h->cc, utf8_decode($alumno->nombre), $st['TD' . $td . '.Normal']);$h->cc++;
            $h->xls->write($h->rr, $h->cc, utf8_decode($alumno->ap), $st['TD' . $td . '.Normal']);$h->cc++;
            $h->xls->write($h->rr, $h->cc, utf8_decode($alumno->am), $st['TD' . $td . '.Normal']);$h->cc++;
            if($alumno->estutor()){
            $h->xls->write($h->rr, $h->cc, utf8_decode($alumno->domicilio), $st['TD' . $td . '.Normal']);$h->cc++;
            $h->xls->writeString($h->rr, $h->cc, $alumno->tel, $st['TD' . $td . '.Normal']);$h->cc++;
            $h->xls->writeString($h->rr, $h->cc, $alumno->cel, $st['TD' . $td . '.Normal']);$h->cc++;
            }
            $h->xls->write($h->rr, $h->cc, utf8_decode($alumno->mail), $st['TD' . $td . '.Normal']);$h->cc++;
            $h->xls->write($h->rr, $h->cc, utf8_decode($alumno->curp), $st['TD' . $td . '.Normal']);$h->cc++;
            $h->xls->write($h->rr, $h->cc, $alumno->fnacimiento, $st['TD' . $td . '.Normal']);$h->cc++;
            $h->xls->write($h->rr, $h->cc, $alumno->sexo, $st['TD' . $td . '.Normal']);$h->cc++;
            $h->xls->write($h->rr, $h->cc, $alumno->situacion, $st['TD' . $td . '.Normal']);$h->cc++;
            $h->xls->write($h->rr, $h->cc, $alumno->admision, $st['TD' . $td . '.Normal']);$h->cc++;
            $h->xls->write($h->rr, $h->cc, $alumno->promedio, $st['TD' . $td . '.Normal']);$h->cc++;
            $h->cc++;
            $h->nueva_linea();
        }

        $h->nueva_linea();
        $h->rr_max = $h->rr;
    }

    public function encabezado(&$h,$grupo){
        $st = $this->getEstilos();
        $h->nueva_linea();
        $h->xls->insertBitmap($h->rr, $h->cc, getcwd() . '/public/img/sp5/udg.bmp', 0, 15, 1, 1);
        $h->cc += 6;
        $h->xls->insertBitmap($h->rr, $h->cc, getcwd() . '/public/img/sp5/lp5.bmp', 160, 15, 1, 1);
        $h->nueva_linea();
        $h->xls->write($h->rr, $h->cc, 'HEKADEMOS', $st['H3']);
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc + 6);
        $h->nueva_linea();
        $h->xls->write($h->rr, $h->cc, 'HEKADEMOS', $st['H4']);
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc + 6);
        $h->nueva_linea();
        $turno='';
        switch($grupo->turno){
            case 'M': $turno="MATUTINO"; break;
            case 'V': $turno="VESPERTINO"; break;
            case 'N': $turno="NOCTURNO"; break;
        }
        $tutor='';
        if($grupo->estutor()){
        $tutor=' {Tutor}';
        }
        $h->xls->write($h->rr, $h->cc, 'ALUMNOS DEL '.$grupo->grado.$grupo->letra.' '.$turno.$tutor, $st['H4']);
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc + 6);
        $h->nueva_linea();
        $h->xls->write($h->rr, $h->cc, strToUpper($grupo->verOferta()), $st['H4']);
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc + 6);
        $h->nueva_linea();
        $h->nueva_linea();
        $h->nueva_linea();
        $h->xls->repeatRows(0,8);
        $h->xls->freezePanes(array(9, 0));
    }

    public function propiedades(&$h){
        $h->xls->centerHorizontally();
        $h->xls->hideGridlines();
        $h->xls->printArea(0, 1, $h->rr_max, $h->cc_max);
        $h->xls->setFooter("SP5 ".date("j/n/Y H:i"), 0);
        $h->xls->setLandscape();
        $h->xls->setMargins_LR(0.2);
        $h->xls->setMargins_TB(0.27);
        $h->xls->setPaper(3);
        $h->xls->setPrintScale(80);
        $h->xls->setZoom(80);
    }

    /*public function datos_grupos(){
        $profesor_id=Session :: get_data('prof.usr.id');
        $ciclo = $this->ciclo;
        $grupos = db::raw_connect();

        $Grupos = new Grupos();
        $asignaciones = $Grupos->asignados();
        if(count($asignaciones)>0){
        if(!in_array('ALL', $asignaciones)){
            $asg = '';
            foreach($asignaciones as $grupos_id){
                $asg .= ($asg == '' ? '' : 'OR ') . " grupos.id = '" . $grupos_id . "' ";
            }
            $c .= ($c == '' ? '' : ' AND ') . '(' . $asg . ')';
        }
        }


        $from = "grupos " .
                "INNER JOIN cursos ON cursos.grupos_id=grupos.id " .
                "INNER JOIN oferta ON oferta.id = grupos.oferta_id ";

        $grupos = $grupos->in_query(
                            "SELECT " .
                                "grupos.id, " .
                                "grupos.grado, " .
                                "grupos.letra, " .
                                "grupos.turno, " .
                                "oferta.nombre, ".
                                "oferta.clave ".
                            "FROM " . $from .
                            "WHERE grupos.ciclos_id = '" . $ciclo->id . "' ".($c == "" ? "1" : " AND ".$c) .
                            "GROUP BY grupos.id " .
                            "ORDER BY grupos.turno, grupos.grado, grupos.letra, oferta.nombre "
                   );
        return $grupos;
    }

    public function cuenta_alumnos($grupo){
        return count($this->datos_alumnos($grupo));

    }*/

    /*public function datos_alumnos($grupo){
        $profesor_id=Session :: get_data('prof.usr.id');
        $c = $this->condicion;
        $ciclo = $this->ciclo;
        $alumnos = db::raw_connect();

            $from = "ciclos " .
                "Inner Join grupos ON ciclos.id = grupos.ciclos_id " .
                "INNER JOIN cursos ON grupos.id=cursos.grupos_id " .
                "Inner Join alumnoscursos ON cursos.id = alumnoscursos.cursos_id " .
                "Inner Join alumnos ON alumnoscursos.alumnos_id = alumnos.id " .
                "Inner Join situaciones ON alumnos.situaciones_id = situaciones.id ";
        $condicion="grupos.id = '" . $grupo['id'] . "' AND cursos.profesores_id=".$profesor_id."  AND cursos.estado_id='3' ";

        $Grupo=new Grupos();
        if($Grupo->estutorporid($grupo['id'])){
            $condicion="grupos.id = '" . $grupo['id'] . "' ";
        }

        $alumnos = $alumnos->in_query(
                            "SELECT " .
                                "distinct(alumnos.id),alumnos.*,situaciones.nombre as situacion ".
                            "FROM " . $from .
                            "WHERE ".$condicion .
                            ($c == "" ? "" : "AND " . $c . " ")  .
                            "ORDER BY alumnos.ap, alumnos.am, alumnos.nombre "
                   );
        $this->condicion = $c;
        return $alumnos;
    }*/

}
?>