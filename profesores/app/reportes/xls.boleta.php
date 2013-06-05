<?php
Kumbia :: import('lib.excel.main');

class XLSBoleta extends Reporte{
    private $condicion;
    private $ciclo;
    private $grupo;
    private $nombre;

    public function XLSBoleta($grupo_id = '', $alumnos = array()){
        $grupos = new Grupos();
        $ciclos = new Ciclos();
        $grupo = $grupos->find($grupo_id);
        $this->grupo = $grupo;
        $ciclo = $ciclos->find($grupo->ciclos_id);
        $this->ciclo = $ciclo;
        $glabel = $grupo->grado . $grupo->letra . $grupo->turno;
        $this->nombre = $nombre = 'Boletas ' . $glabel . ' ' . $ciclo->numero . '.xls';
        $this->Reporte($nombre);

        $alumnos = $this->alumnos($grupo->id, $alumnos);
        if(count($alumnos) > 0){
            $i = 1;
            $alu = array();
            foreach($alumnos as $alumno){
                $alu[] = $alumno;
                if($i % 2 == 0){
                    $this->hoja($alu);
                    $alu = array();
                }
                $i ++;
            }
            if($i % 2 == 0){
                $this->hoja($alu);
            }
        }else{
            $this->hoja_vacia();
        }
    }

    public function hoja($alumnos){
        $nombre = '';
        foreach($alumnos as $alumno){
            $nombre .= ($nombre == '' ? '' : ' - ') . $alumno->ap . ' ' . $alumno->am;
        }
        $nombre = utf8_decode($nombre);
        $nombre = ereg_replace('[/\\]', ' ', $nombre);
        $nombre = substr($nombre, 0, 31);
        $hojas = $this->getHojas();
        if(array_key_exists($nombre, $hojas)){
            $h = $hojas[$nombre];
        }else{
            $cols = array(
                        10.71, 10.71, 10.71, 10.71,
                        17.14, 17.14, 33
                    );
            $rows = array();
            for($i = 0; $i < 60; $i++){
                $rows[] = 18;
            }
            $h = $this->agregar_hoja($nombre, $rows, $cols);
            $h->cc_max = 6;
        }
        $this->contenido($h, $alumnos);
        $this->propiedades($h);
    }

    public function hoja_vacia(){
        $nombre = 'SP5';
        $h = $this->agregar_hoja($nombre);
        $h->xls->write(0, 0, "No hay registros que coincidan con esas condiciones");
    }

    public function alumnos($grupo_id, $alos){
        $alumnos = new Alumnos();
        $c = '';
        foreach($alos as $alo){
            $c .= ($c == '' ? '' : 'OR ') . "alumnos_id = '" . $alo . "' " ;
        }

        $alumnos = $alumnos->find_all_by_sql(
                    "SELECT " .
                        "alumnos.id, " .
                        "alumnos.codigo, " .
                        "alumnos.ap, " .
                        "alumnos.am, " .
                        "alumnos.nombre " .
                    "FROM " .
                        "alumnos " .
                            "INNER JOIN " .
                        "alumnosgrupo " .
                            "ON alumnos.id = alumnosgrupo.alumnos_id " .
                    "WHERE alumnosgrupo.grupos_id = '" . $grupo_id . "' " .
                            ($c != '' ? ' AND(' . $c . ')' : '') .
                    "ORDER BY ap, am, nombre "
            );
        return $alumnos;
    }

    public function contenido(&$h, $alumnos){
        $grupo = $this->grupo;
        $st = $this->getEstilos();

        $i = 1;
        foreach($alumnos as $alumno){
            $this->encabezado($h);
            $nombre = $alumno->ap . ' ' . $alumno->am . ', '. $alumno->nombre;
            $h->nueva_linea();
            $h->xls->write($h->rr, $h->cc, 'NOMBRE:', $st['TD.NoBorderBold']); $h->cc++;
            $h->xls->write($h->rr, $h->cc, utf8_decode($nombre), $st['TD.NoBorder']);
            $h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc + 5);
            $h->nueva_linea();
            $h->xls->write($h->rr, $h->cc, 'GRUPO:', $st['TD.NoBorderBold']); $h->cc++;
            $h->xls->write($h->rr, $h->cc, utf8_decode($grupo->ver()), $st['TD.NoBorder']);
            $h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc + 3);
            $h->nueva_linea();
            $h->nueva_linea();
            $h->xls->setRow($h->rr, 22.50);
            $h->xls->write($h->rr, $h->cc, 'MATERIA', $st['TH.BGGray']); $h->cc++;
            $h->xls->writeBlank($h->rr, $h->cc, $st['TH.BGGray']); $h->cc++;
            $h->xls->writeBlank($h->rr, $h->cc, $st['TH.BGGray']); $h->cc++;
            $h->xls->writeBlank($h->rr, $h->cc, $st['TH.BGGray']);
            $h->xls->mergeCells($h->rr, $h->cc-3, $h->rr, $h->cc); $h->cc++;
            $h->xls->write($h->rr, $h->cc, 'FALTAS', $st['TH.BGGray']); $h->cc++;
            $h->xls->write($h->rr, $h->cc, 'CALIFICACION', $st['TH.BGGray']); $h->cc++;
            $h->xls->write($h->rr, $h->cc, 'OBSERVACIONES', $st['TH.BGGray']); $h->cc++;
            $h->nueva_linea();
            $n = 0;
            $cursos = $this->cursos($alumno->id);
            if(count($cursos) > 0){
                $asistenciasTotal=$this->obtenTotalAsistencias($cursos);
                foreach($cursos as $curso){
                    $nAsistencias=$asistenciasTotal[$curso->cursos_id];
                    $c=new Cursos();
                    $c=$c->find($curso->cursos_id);
                    $asistencias=$c->asistenciasAlumno($curso->alumnos_id);
                    $faltas = $nAsistencias - $asistencias['AST'];
                    $calificacion = $curso->parcial(1);
                    $observaciones = (
                            $curso->semestre != $grupo->grado ?
                                'Materia de ' . Utils :: ordinales($curso->semestre) . ' semestre':
                                ''
                    );
                    $n++;
                    $h->cc = 0;
                    $h->xls->write($h->rr, $h->cc, utf8_decode($curso->nombre), $st['TD.Normal']); $h->cc++;
                    $h->xls->writeBlank($h->rr, $h->cc, $st['TD.Normal']); $h->cc++;
                    $h->xls->writeBlank($h->rr, $h->cc, $st['TD.Normal']); $h->cc++;
                    $h->xls->writeBlank($h->rr, $h->cc, $st['TD.Normal']);
                    $h->xls->mergeCells($h->rr, $h->cc-3, $h->rr, $h->cc); $h->cc++;
                    if($faltas==0)$faltas='';
                    $h->xls->write($h->rr, $h->cc, $faltas, $st['TD.NormalCenter']); $h->cc++;
                    $h->xls->write($h->rr, $h->cc, $calificacion, $st['TD.NormalCenter']); $h->cc++;
                    $h->xls->write($h->rr, $h->cc, $observaciones, $st['TD.Normal']); $h->cc++;
                    $h->nueva_linea();
                }
            }else{
                $h->xls->write($h->rr, $h->cc, 'No hay registros que mostrar', $st['TD.NoBorder']);
                $h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc+6);
                $h->nueva_linea();
                $n++;
            }
            $this->pieDePagina($h);
            for($n; $n < 11; $n++){
                $h->nueva_linea();
            }
            if($i == 1){
                $this->separador($h);
            }
            $i++;
        }

        $h->rr_max = $h->rr;
    }

    public function cursos($alumno_id){
        $cursos = new Alumnoscursos();
        $c = $this->condicion;

        $from = "alumnoscursos " .
                "Inner Join cursos ON cursos.id = alumnoscursos.cursos_id " .
                "Inner Join materias ON cursos.materias_id = materias.id  " .
                "Inner Join grupos ON grupos.id=cursos.grupos_id ";
        $cursos = $cursos->find_all_by_sql(
                    "SELECT " .
                        "alumnoscursos.id, " .
                        "alumnoscursos.alumnos_id, " .
                        "alumnoscursos.cursos_id, " .
                        "materias.semestre AS semestre, " .
                        "materias.nombre AS nombre " .
                    "FROM " . $from .
                    "WHERE alumnoscursos.alumnos_id = '" . $alumno_id . "' AND grupos.ciclos_id=".$this->ciclo->id ." ".
                    ($c == "" ? "" : "AND " . $c . " ") .
                    "ORDER BY materias.semestre DESC, materias.nombre "
            );
        return $cursos;
    }

    public function obtenTotalAsistencias($cursos){
        $asistencias=array();
        foreach($cursos as $curso){
            $c=new Cursos();
            $c=$c->find($curso->cursos_id);
            $hdrAsistencias  =  $c->asistenciasHdr();
            $nAsistencias    =  count($hdrAsistencias);
            $asistencias[$curso->cursos_id]=$nAsistencias;
        }
        return $asistencias;
    }

    public function encabezado(&$h){
        $st = $this->getEstilos();
        $h->nueva_linea();
        $h->xls->insertBitmap($h->rr, $h->cc, getcwd() . '/public/img/sp5/udg.bmp', 0, 15, 1, 1);
        $h->cc += 6;
        $h->xls->insertBitmap($h->rr, $h->cc, getcwd() . '/public/img/sp5/lp5.bmp', 160, 22, 1, 1);
        $h->nueva_linea();
        $h->xls->write($h->rr, $h->cc, 'HEKADEMOS', $st['H3']);
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc + 6);
        $h->nueva_linea();
        $h->xls->write($h->rr, $h->cc, 'HEKADEMOS', $st['H4']);
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc + 6);
        $h->nueva_linea();
        $h->xls->write($h->rr, $h->cc, 'BOLETA DE CALIFICACIONES PARCIALES', $st['H4']);
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc + 6);
        $h->nueva_linea();
        $h->nueva_linea();
    }

    public function pieDePagina(&$h){
        $st = $this->getEstilos();
        $h->nueva_linea();
        $h->xls->write(
                $h->rr,
                $h->cc,
                'ESTAS CALIFICACIONES SON PARCIALES Y NO TIENEN VALIDEZ OFICIAL.',
                $st['TD.NoBorder']
        );
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc + 6);
        $h->nueva_linea();
        $h->xls->write(
                $h->rr,
                $h->cc,
                utf8_decode('Para Cualquier aclaración, favor de acudir a la Dirección o a la ' .
                'Oficialía Mayor de esta Escuela. Teléfono 38114149 ext. 106'),
                $st['TD.NoBorderSmall']
        );
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc + 6);
        $h->nueva_linea();
        $h->nueva_linea();
        $h->nueva_linea();
    }

    public function separador(&$h){
        $st = $this->getEstilos();
        $h->nueva_linea();
        $h->nueva_linea();
        $h->xls->setRow($h->rr, 11.25);
        for($h->cc = 0; $h->cc <= 6; $h->cc++){
            $h->xls->writeBlank($h->rr, $h->cc,    $st['TH.BGGrayNoBorder']);
        }
        $h->nueva_linea();
        $h->nueva_linea();
    }

    public function getNombre(){
        return $this->nombre;
    }

    public function propiedades(&$h){
        $h->xls->centerHorizontally();
        $h->xls->hideGridlines();
        $h->xls->printArea(0, 0, $h->rr_max, $h->cc_max);
        $h->xls->setPortrait();
        $h->xls->setMargins_LR(0.8);
        $h->xls->setMargins_TB(0.4);
        $h->xls->setHeader('', 0);
        $h->xls->setFooter('', 0);
        $h->xls->setPaper(3);
        $h->xls->setPrintScale(70);
        $h->xls->setZoom(50);
    }
}
?>