<?php
class XLSProfesoreshorario extends Reporte {
    private $condicion;
    private $ciclo;
    private $profesor;

    public function XLSProfesoreshorario($ciclo_id, $profesor_id) {
        $grupos = new Grupos();
        $ciclos = new Ciclos();
        $ciclo = $ciclos->find(intval($ciclo_id,10));
        $this->ciclo = $ciclo;

        $profesor_id=intval($profesor_id,10);
        if ($profesor_id != '') {
            $this->condicion = "";
            $this->profesor=new Profesores();
            $this->profesor=$this->profesor->find($profesor_id);

            if($this->profesor->id!=""){
            $this->Reporte('Horario ' .utf8_decode($this->profesor->nombre()) .', '.$this->ciclo->numero. '.xls');
            $this->hoja($profesor_id);
            }else{
            $this->Reporte('Horario.xls');
            $this->hoja_vacia();
            }
        } else {
            $this->Reporte('Horario.xls');
            $this->hoja_vacia();
        }
    }

    public function hoja($profesor_id = '') {
        $nombre = strToUpper($this->profesor->codigo);
        $hojas = $this->getHojas();
        if (array_key_exists($nombre, $hojas)) {
            $h = $hojas[$nombre];
        } else {
            $cols = array (
                10,
                25,
                25,
                25,
                25,
                25,
                25,
                25
            );
            $h = $this->agregar_hoja($nombre, null, $cols);
            $h->cc_max = 8;
        }

        $this->contenido($h, $profesor_id);
        $this->propiedades($h);
    }

    public function hoja_vacia() {
        $nombre = 'SP5';
        $h = $this->agregar_hoja($nombre);
        $h->xls->write(0, 0, "No hay registros que coincidan con esas condiciones");
    }

    public function contenido(&$h, $profesor_id) {
        $this->encabezado($h,$profesor_id);
        $st = $this->getEstilos();
        $salto='
                ';
        $cursos = new Cursos();
        $from = "cursos " .
        "INNER JOIN grupos ON cursos.grupos_id = grupos.id " .
        "INNER JOIN materias ON cursos.materias_id  = materias.id " .
        "INNER JOIN profesores ON cursos.profesores_id  = profesores.id ";
        $cursos = new Cursos();
        $cursos = $cursos->find_all_by_sql("SELECT " .
        "materias.nombre as materia, " .
        "cursos.id, cursos.grupos_id, " .
        "cursos.materias_id," .
        "cursos.profesores_id, " .
        "cursos.estado_id, " .
        "cursos.observaciones," .
        "cursos.inicio  " .
        "FROM " . $from .
        "WHERE cursos.profesores_id = '" . $profesor_id . "' AND grupos.ciclos_id='".$this->ciclo->id."' AND cursos.estado_id='3' " .
        "ORDER BY cursos.inicio,materias.nombre ");
        $cc=array();
        foreach($cursos as $curso){
            $cc[$curso->id]["c"]=$curso;
            $cc[$curso->id]["h"]=$curso->horarios();
        }
        $cursos=$cc;

        $dias=new Dias();
        $dias=$dias->find("id!='7' ORDER BY id");

        $h->nueva_linea();
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc+7);
        $h->xls->write($h->rr, $h->cc, 'Turno Matutino', $st['H2.Left']); $h->cc++;

        $h->nueva_linea();
        $h->xls->write($h->rr, $h->cc, 'Hora', $st['TD.BGYellow']); $h->cc++;
        foreach($dias as $d){
        $h->xls->write($h->rr, $h->cc, utf8_decode($d->nombre), $st['TD.BGYellow']); $h->cc++;
        }
        $h->nueva_linea();

            $inicio=7;
            $fin=13;
            for($ini=$inicio;$ini<$fin;$ini++){
            $h->xls->write($h->rr, $h->cc,$ini.":00-".($ini+1).":00", $st['TD.BGYellow']); $h->cc++;
                foreach($dias as $d){
                $b=false;
                $crs="";
                foreach($cursos as $c){
                $horarios=$c['h'];
                foreach($horarios as $ho){
                    $i=substr($ho->inicio,0,2);
                    $f=substr($ho->fin,0,2);
                    if($ho->dias_id==$d->id
                    &&
                    (
                    ($i<=$ini)
                    &&
                    ($f>=($ini+1))
                    )
                    ){
                    $curso=$c['c'];
                    $grupo=$curso->grupo();
                    $b=true;
                    $materia=$curso->materia();
                    $crs.= utf8_decode(Utils::convierteFecha($curso->inicio).$salto.$grupo->grado.$grupo->letra. ' ' . $grupo->verTurno() .' '.$grupo->verOfertaClave() .$salto.$materia->nombre.$salto);
                    }


                }

                }
                if(!$b){
                $h->xls->write($h->rr, $h->cc,'-------', $st['TD.NormalCenter']); $h->cc++;
                }else{

                $h->xls->write($h->rr, $h->cc,$crs, $st['TDPar.NormalCenter']); $h->cc++;

                }
                }

                $h->nueva_linea();
            }

        $h->nueva_linea();
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc+7);
        $h->xls->write($h->rr, $h->cc, 'Turno Vespertino', $st['H2.Left']); $h->cc++;


        $h->nueva_linea();
        $h->xls->write($h->rr, $h->cc, 'Hora', $st['TD.BGYellow']); $h->cc++;
        foreach($dias as $d){
        $h->xls->write($h->rr, $h->cc, utf8_decode($d->nombre), $st['TD.BGYellow']); $h->cc++;
        }
        $h->nueva_linea();

            $inicio=13;
            $fin=21;
            for($ini=$inicio;$ini<$fin;$ini++){
            $h->xls->write($h->rr, $h->cc,$ini.":00-".($ini+1).":00", $st['TD.BGYellow']); $h->cc++;
                foreach($dias as $d){
                $b=false;
                $crs="";
                foreach($cursos as $c){
                $horarios=$c['h'];
                foreach($horarios as $ho){
                    $i=substr($ho->inicio,0,2);
                    $f=substr($ho->fin,0,2);
                    if($ho->dias_id==$d->id
                    &&
                    (
                    ($i<=$ini)
                    &&
                    ($f>=($ini+1))
                    )
                    ){
                    $curso=$c['c'];
                    $grupo=$curso->grupo();
                    $b=true;
                    $materia=$curso->materia();
                    $crs.= utf8_decode(Utils::convierteFecha($curso->inicio).$salto.$grupo->grado.$grupo->letra. ' ' . $grupo->verTurno() .' '.$grupo->verOfertaClave() .$salto.$materia->nombre.$salto);
                    }


                }

                }
                if(!$b){
                $h->xls->write($h->rr, $h->cc,'-------', $st['TD.NormalCenter']); $h->cc++;
                }else{

                $h->xls->write($h->rr, $h->cc,$crs, $st['TDPar.NormalCenter']); $h->cc++;

                }
                }
                $h->cc_max = $h->cc;
                $h->nueva_linea();

            }


                $h->rr_max = $h->rr;
        }

    public function encabezado($h,$profesor_id) {
        $template=new Template();
        $st = $this->getEstilos();
        $h->nueva_linea();
        $h->xls->insertBitmap($h->rr, $h->cc, getcwd() . '/public/img/sp5/udg.bmp', 0, 15, 1, 1);
        $h->cc += 6;
        $h->xls->insertBitmap($h->rr, $h->cc, getcwd() . '/public/img/sp5/lp5.bmp', 160, 15, 1, 1);
        $h->nueva_linea();
        $h->xls->write($h->rr, $h->cc, $template->excel_escuela(), $st['H3']);
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc + 6);
        $h->nueva_linea();
        $h->xls->write($h->rr, $h->cc, $template->excel_subtitulo(), $st['H4']);
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc + 6);
        $h->nueva_linea();
        $h->xls->write($h->rr, $h->cc, 'HORARIO DE  '.utf8_decode(strToUpper($this->profesor->nombre())), $st['H4']);
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc + 6);
        $h->nueva_linea();
        $h->xls->write($h->rr, $h->cc, 'CODIGO: '.strToUpper($this->profesor->codigo) , $st['H4']);
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc + 6);
        $h->nueva_linea();
        $h->xls->write($h->rr, $h->cc, 'CICLO '.$this->ciclo->numero, $st['H4']);
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
        $h->xls->printArea(0, 0, $h->rr_max, $h->cc_max);
        $h->xls->setFooter(utf8_decode("Coordinación academica ") . date("j/n/Y H:i"), 0);
        $h->xls->setPortrait();
        $h->xls->setMargins_LR(0.2);
        $h->xls->setMargins_TB(0.27);
        $h->xls->setPaper(3);
        $h->xls->setPrintScale(85);
        $h->xls->setZoom(80);
    }
}
?>