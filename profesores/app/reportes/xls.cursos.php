<?php
class XLSCursos extends Reporte {
    private $condicion;
    private $ciclo;

    public function XLSCursos($ciclo_id, $grupo_id = '') {
        $grupos = new Grupos();
        $ciclos = new Ciclos();
        $ciclo = $ciclos->find($ciclo_id);
        $this->ciclo = $ciclo;

        if ($grupo_id != '') {
            $this->condicion = "";
            $grupo = $grupos->find($grupo_id);
            $glabel = $grupo->grado . $grupo->letra . $grupo->turno;
            $this->Reporte('Cursos ' . $glabel . ' ' . $ciclo->numero . '.xls');
            $this->hoja($grupo);

        } else {
            $grupos = $this->datos_grupos();
            $login = $usr_login = Session :: get_data('prof.usr.login');
            $usr_grupos = Session :: get_data('prof.usr.grupos');
            $controlador = 'cursos';
            $accion = 'index';
            $b = new Busqueda($controlador, $accion);
            $b->establecerCondicion('profesor', "CONCAT(profesores.nombre, ' ', profesores.ap, ' ', profesores.am) " .
            "LIKE '%" . $b->campo('profesor') . "%' ");


            // genera las condiciones
            $c = $b->condicion(array (
                'estado_id' , 'oferta_id'
            ));

            $this->condicion = $c;
            $this->Reporte('Cursos ' . $ciclo->numero . ($c != '' ? ' FILTRADO' : '') . '.xls');


                    // cuenta todos los registros
                    $from = "cursos " .
                    "INNER JOIN grupos ON cursos.grupos_id = grupos.id " .
                    "INNER JOIN materias ON cursos.materias_id  = materias.id " .
                    "INNER JOIN profesores ON cursos.profesores_id  = profesores.id ";
                $cursos=new Cursos();
                $registros = $cursos->count_by_sql(
                "SELECT COUNT(*) " .
                "FROM " . $from .
                "WHERE grupos.ciclos_id = '" . $ciclo_id . "' " .
                ($c == ''? '' : 'AND ' . $c)
        );
            if($registros>0){
            if (count($grupos) > 0) {
                foreach ($grupos as $grupo) {
                    $cursos = $this->datos_curso($grupo);
                    if(count($cursos)>0)
                    $this->hoja($grupo);
                }
            } else {
                $this->hoja_vacia();
            }
            } else {
                $this->hoja_vacia_cursos();
            }
        }
    }

    public function hoja($grupo = '') {
        $nombre = $grupo->grado . $grupo->turno;
        $hojas = $this->getHojas();
        if (array_key_exists($nombre, $hojas)) {
            $h = $hojas[$nombre];
            $encabezado=false;
        } else {
            $cols = array (
                0.1,
                36,
                10,
                10,
                10,
                10,
                10,
                10,
                28
            );
            $h = $this->agregar_hoja($nombre, null, $cols);
            $h->cc_max = 8;
            $encabezado=true;
        }

        $this->contenido($h, $grupo,$encabezado);
        $this->propiedades($h);
    }

    public function hoja_vacia() {
        $nombre = 'SP5';
        $h = $this->agregar_hoja($nombre);
        $h->xls->write(0, 0, "No hay registros que coincidan con esas condiciones");
    }

    public function hoja_vacia_cursos() {
        $nombre = 'SP5';
        $h = $this->agregar_hoja($nombre);
        $h->xls->write(0, 0, "No existen cursos para el ciclo.");
    }

    public function contenido(&$h, $grupo,$encabezado) {
        if ($encabezado) {
            $this->encabezado($h,$grupo);
        }
        $st = $this->getEstilos();
        $cursos = $this->datos_curso($grupo);
        $h->cc++;
        $tutor='';
        if($grupo->estutor()){
        $tutor=' {Tutor}';
        }
        $h->xls->write($h->rr, $h->cc, $grupo->verInfo().$tutor, $st['H2.Left']);
        $h->nueva_linea();
        $h->xls->write($h->rr, $h->cc, 'ID');
        $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'Materia', $st['TD.BGOrange']);
        $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'L', $st['TD.BGYellow']);
        $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'M', $st['TD.BGYellow']);
        $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'I', $st['TD.BGYellow']);
        $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'J', $st['TD.BGYellow']);
        $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'V', $st['TD.BGYellow']);
        $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'S', $st['TD.BGYellow']);
        $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'Profesor', $st['TD.BGOrange']);
        $h->cc++;
        $h->nueva_linea();
        $n = 0;
        foreach ($cursos as $curso) {
            $n++;
            $td = ($n % 2 == 0 ? 'Par' : '');
            $horarios = $curso->horarios();
            $h->xls->write($h->rr, $h->cc, $curso->id);
            $h->cc++;
            $h->xls->write($h->rr, $h->cc, utf8_decode($curso->verMateriaNombre()), $st['TD' . $td . '.Normal']);
            $h->cc++;
            for ($i = 1; $i < 7; $i++) {
                $h->xls->write($h->rr, $h->cc, $horarios[$i]->inicio . '-' . $horarios[$i]->fin, $st['TD' . $td . '.Normal']);
                $h->cc++;
            }
            $h->xls->write($h->rr, $h->cc, utf8_decode($curso->verProfesor()), $st['TD' . $td . '.Normal']);
            $h->cc++;
            $h->nueva_linea();
        }
        if (count($cursos) <= 0) {
            $h->xls->writeBlank(0, 0, $st['TD']);
        }
        $h->nueva_linea();
        $h->rr_max = $h->rr;
    }

    public function datos_curso($grupo) {
        $cursos = new Cursos();
        $c = $this->condicion;
        $usr_id = Session :: get_data('prof.usr.id');

        /*$from = "cursos " .
        "INNER JOIN grupos ON cursos.grupos_id = grupos.id " .
        "INNER JOIN materias ON cursos.materias_id  = materias.id " .
        "INNER JOIN profesores ON cursos.profesores_id  = profesores.id ";
        $cursos = $cursos->find_all_by_sql("SELECT " .
        "cursos.id, " .
        "cursos.grupos_id, " .
        "cursos.materias_id," .
        "cursos.profesores_id " .
        "FROM " . $from .
        "WHERE cursos.grupos_id = '" . $grupo->id . "' " .
         ($c == "" ? "" : "AND " . $c . " "));*/
        $from = "cursos " .
        "INNER JOIN grupos ON cursos.grupos_id = grupos.id " .
        "INNER JOIN materias ON cursos.materias_id  = materias.id " .
        "INNER JOIN profesores ON cursos.profesores_id  = profesores.id ";

        $condicion="cursos.grupos_id = '" . $grupo->id . "' AND profesores_id='".$usr_id."' AND cursos.estado_id='3' ";
        if($grupo->estutor()){
        $condicion="cursos.grupos_id = '" . $grupo->id . "' AND cursos.estado_id='3' ";
        }

        $cursos = new Cursos();
        $cursos = $cursos->find_all_by_sql("SELECT " .
        "materias.nombre as materia, " .
        "cursos.id, cursos.grupos_id, " .
        "cursos.materias_id," .
        "cursos.profesores_id, " .
        "cursos.estado_id, " .
        "cursos.observaciones " .
        "FROM " . $from .
        "WHERE " .$condicion.
         ($c == "" ? "" : "AND " . $c . " ") .
        "ORDER BY grupos.turno, grupos.grado, grupos.letra, materias.nombre ");

        return $cursos;
    }

    public function datos_grupos() {
        $b = new Busqueda('cursos', 'index');
        $c = $b->cargar();
        $ciclo = $this->ciclo;
        $grupos = new Grupos();
        $profesor_id=Session :: get_data('prof.usr.id');

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

        $from = "cursos " .
        "INNER JOIN grupos ON cursos.grupos_id = grupos.id " .
        "INNER JOIN materias ON cursos.materias_id  = materias.id " .
        "INNER JOIN profesores ON cursos.profesores_id  = profesores.id ";

        $grupos = $grupos->find_all_by_sql("SELECT " .
        "grupos.id, " .
        "grupos.grado, " .
        "grupos.letra, " .
        "grupos.turno " .
        "FROM " . $from .
        "WHERE grupos.ciclos_id = '" . $ciclo->id . "' ".($c == "" ? "1" : " AND ".$c) .
        "GROUP BY grupos.id " .
        "ORDER BY grupos.turno, grupos.grado, grupos.letra, materias.nombre ");
        $this->condicion = $c;
        return $grupos;
    }

    public function encabezado($h,$grupo) {
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
        $h->xls->write($h->rr, $h->cc, 'CURSOS DE '.$grupo->grado.' '.$turno, $st['H4']);
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc + 6);
        $h->nueva_linea();
        $h->xls->write($h->rr, $h->cc,'' , $st['H4']);
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc + 6);
        $h->nueva_linea();
        $h->nueva_linea();
        $h->nueva_linea();
        $h->xls->repeatRows(0,7);
        $h->xls->freezePanes(array(7, 0));
    }

    public function propiedades(& $h) {
        $h->xls->centerHorizontally();
        $h->xls->hideGridlines();
        $h->xls->printArea(0, 1, $h->rr_max, $h->cc_max);
        $h->xls->setFooter("SP5 " . date("j/n/Y H:i"), 0);
        $h->xls->setPortrait();
        $h->xls->setMargins_LR(0.2);
        $h->xls->setMargins_TB(0.27);
        $h->xls->setPaper(3);
        $h->xls->setPrintScale(85);
        $h->xls->setZoom(80);
    }
}
?>