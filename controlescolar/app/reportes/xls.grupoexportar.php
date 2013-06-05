<?php
class XLSGrupoexportar extends Reporte {
    private $condicion;
    private $ciclo;
    private $profesor;

    public function XLSGrupoexportar($grupo_id) {
            $grupo_id=intval($grupo_id,10);
            $grupo=new Grupos();
            $grupo=$grupo->find($grupo_id);

        if ($grupo->id != '') {
            $this->condicion = "";

            $this->Reporte('Horario cursos grupo '.utf8_decode($grupo->ver('html')).'.xls');
            $this->hoja($grupo);
            } else {
            $this->Reporte('Horario.xls');
            $this->hoja_vacia();
        }
    }

    public function semestreletras($s){
        switch($s){
            case "1":return "PRIMER";
            case "2":return "SEGUNDO";
            case "3":return "TERCER";
            case "4":return "CUARTO";
            case "5":return "QUINTO";
            case "6":return "SEXTO";
            default:return "";
        }
    }

    public function hoja($grupo) {
        $nombre = utf8_decode(strToUpper($grupo->verReducido()));
        $hojas = $this->getHojas();
        if (array_key_exists($nombre, $hojas)) {
            $h = $hojas[$nombre];
        } else {
            $cols = array (
                15,
                30,
                15,
                15,
                15,
                15,
                15,
                15,
                40

            );

            $rows =array(
                15,
                15,
                15,
                40,
                40,
                40,
                40,
                40,
                40,
                40,
                40,
                50,
                40,
                50,
                40,
                50,
                40,
                50,
                40,
                50,
                40,
                50,
                40,
                50,
                40,
                50,
                40,
                50,
                40,
                50,
                40
            );
            $h = $this->agregar_hoja($nombre, $rows, $cols);
            $h->cc_max = 8;
        }

        $this->contenido($h, $grupo);
        $this->propiedades($h);
    }

    public function hoja_vacia() {
        $nombre = 'HEKADEMOS';
        $h = $this->agregar_hoja($nombre);
        $h->xls->write(0, 0, "No hay registros que coincidan con esas condiciones");
    }

    public function contenido(&$h, $grupo) {
        $this->encabezado($h,$grupo);
        $st = $this->getEstilos();
        $salto='   ' .
                '   ';
        $cursos = new Cursos();

        $cursos = new Cursos();
        $cursos = $cursos->find_all_by_sql(
        "SELECT " .
                        "cursos.id, " .
                        "cursos.grupos_id, " .
                        "cursos.materias_id," .
                        "cursos.profesores_id, " .
                        "cursos.estado_id, ".
                        "cursos.inicio,  " .
                        "materias.nombre AS materia," .
                        "materias.tipo AS materiaTipo," .
                        "CONCAT(profesores.ap, ' ', profesores.am, ', ', profesores.nombre) AS profesor " .
                    "FROM " .
                        "cursos " .
                        "INNER JOIN grupos ON cursos.grupos_id = grupos.id " .
                        "INNER JOIN materias ON cursos.materias_id  = materias.id " .
                        "INNER JOIN profesores ON cursos.profesores_id  = profesores.id ".
                    "WHERE " .
                        "grupos_id = '" . $grupo->id . "' " .
                    "ORDER " .
                        "BY cursos.inicio,grupos.turno, grupos.grado, grupos.letra, materias.nombre "
        );
        $cc=array();
        $inicio=2400;
        $fin=00;
        foreach($cursos as $curso){
            $cc[$curso->id]["c"]=$curso;
            $horarios=$curso->horarios();
                        foreach($horarios as $ho){
                                $fecha=substr($ho->inicio,0,2).substr($ho->inicio,3,2);
                                if($fecha<$inicio){
                                    $inicio=$fecha;
                                }

                                $fecha=substr($ho->fin,0,2).substr($ho->fin,3,2);
                                if($fecha>$fin){
                                    $fin=$fecha;
                                }
                        }
                        $cc[$curso->id]["h"]=$horarios;
        }
        $cursos=$cc;

        $dias=new Dias();
        $dias=$dias->find("id!='7' ORDER BY id");

        $h->nueva_linea();
        $h->cc++;
        $h->xls->write($h->rr, $h->cc,utf8_decode($this->semestreletras($grupo->grado)." SEMESTRE"), $st['H2.BGYellowBorder']);
        $h->xls->write($h->rr, $h->cc+1, '', $st['H2.BGYellowBorder']);
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc+1);
        $h->cc++;
        $h->cc++;
        $h->cc++;

        $h->xls->write($h->rr, $h->cc, utf8_decode("TURNO"), $st['H2.BGYellowBorder']); $h->cc++;
        $h->xls->write($h->rr, $h->cc, utf8_decode($grupo->verTurno()), $st['H2.BGYellowBorder']); $h->cc++;$h->cc++;$h->cc++;

        $h->xls->write($h->rr, $h->cc, utf8_decode("TUTOR DEL GRUPO"), $st['H2.BGYellowBorder']);

        $h->nueva_linea();
        $h->cc++;
        $h->xls->write($h->rr, $h->cc, utf8_decode("GRUPO"), $st['H2.BGYellowBorder']); $h->cc++;
        $h->xls->write($h->rr, $h->cc, $grupo->letra, $st['H2.BGYellowBorder']);

        $h->cc+=2;
        if($curso!=null && $curso->id!=""){
            $aula=$curso->aula();
            $au=str_replace('AULA','',strToUpper($aula->nombre));
        }else{
            $au="";
        }

        $tutor=$grupo->tutor();

        if($tutor!=null && $tutor->id!=""){
            $tgrupo=$tutor->nombre();
        }else{
            $tgrupo="";
        }

        $h->xls->write($h->rr, $h->cc, utf8_decode("AULA No."), $st['H2.BGYellowBorder']); $h->cc++;
        $h->xls->write($h->rr, $h->cc, $au, $st['H2.BGYellowBorder']); $h->cc++; $h->cc++;$h->cc++;
        $h->xls->write($h->rr, $h->cc, utf8_decode($tgrupo), $st['H2.BGYellowBorder']);


        $h->nueva_linea();
        $h->nueva_linea();
        $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'Hora', $st['H2.BGYellowBorder']); $h->cc++;
        foreach($dias as $d){
        $h->xls->write($h->rr, $h->cc, utf8_decode($d->nombre), $st['H2.BGYellowBorder']); $h->cc++;
        }
        $h->xls->write($h->rr, $h->cc, "", $st['H2.BGYellowBorder']);
        $h->nueva_linea();
        $h->cc++;
        $inicio=substr($inicio,0,2);
        $fin=substr($fin,0,2);

            for($ini=$inicio;$ini<$fin;$ini++){
            $h->xls->write($h->rr, $h->cc,$ini.":00-".($ini+1).":00", $st['H2.Border']);
            $h->xls->write($h->rr+1, $h->cc,'', $st['H2.Border']);
            $h->xls->mergeCells($h->rr, $h->cc, $h->rr+1, $h->cc);
             $h->cc++;
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
                    $crs.= utf8_decode($materia->nombre.$salto);
                    }


                }
                }
                if(!$b){
                    $h->xls->write($h->rr, $h->cc,'', $st['TD.Normal12']);
                    $h->xls->write($h->rr+1, $h->cc,'', $st['TD.Normal12']);
                    $h->xls->mergeCells($h->rr, $h->cc, $h->rr+1, $h->cc);
                     $h->cc++;
                }else{
                    $crs=substr($crs,0,strlen($crs)-1);
                    $h->xls->write($h->rr, $h->cc,$crs, $st['TD.Normal12']);
                    $h->xls->write($h->rr+1, $h->cc,'', $st['TD.Normal12']);
                    $h->xls->mergeCells($h->rr, $h->cc, $h->rr+1, $h->cc);
                    $h->cc++;
                }
                }

                $h->nueva_linea();
                $h->nueva_linea();
                $h->cc++;
                //break;
            }

                $c=8;
                $r=9;
                foreach($cursos as $curso){
                    $h->xls->write($r, $c,utf8_decode($curso["c"]->materia)." - ".utf8_decode($curso["c"]->profesor) ,$st['TD.Normal12']); $h->cc++;
                    $r++;
                    //$h->xls->write($r, $c,utf8_decode($curso["c"]->profesor), $st['TD.Bold']); $h->cc++;
                    //$r++;
                }
                $h->rr_max = $r;
        }

    public function encabezado($h,$grupo) {
        $template=new Template();
        $ciclo=$grupo->ciclo();
        $st = $this->getEstilos();

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
        $h->xls->repeatRows(0,3);
        $h->xls->freezePanes(array(3, 0));
    }

    public function propiedades(& $h) {
        $h->xls->centerHorizontally();
        $h->xls->hideGridlines();
        $h->xls->printArea(0, 0, $h->rr_max, $h->cc_max);
        $h->xls->setFooter("HEKADEMOS " . date("j/n/Y H:i"), 0);
        $h->xls->setLandscape();
        $h->xls->setMargins_LR(0.2);
        $h->xls->setMargins_TB(0.27);
        $h->xls->setPaper(3);
        $h->xls->setPrintScale(60);
        $h->xls->setZoom(80);
    }
}
?>