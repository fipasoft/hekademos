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
                12,
                12,
                12,
                30,
                12,
                12,
                12
            );
            $h = $this->agregar_hoja($nombre, null, $cols);
            $h->cc_max = 8;
        }

        $this->contenido($h, $profesor_id);
        $this->propiedades($h);
    }

    public function hoja_vacia() {
        $nombre = 'HEKADEMOS';
        $h = $this->agregar_hoja($nombre);
        $h->xls->write(0, 0, "No hay registros que coincidan con esas condiciones");
    }

    public function timeToMinutes($time)
    {
    $horaSplit = explode(":", $time);
    if( count($horaSplit) < 3 ) {
    $horaSplit[2] = 0;
    }

    # Pasamos los elementos a segundos
    $horaSplit[0] = $horaSplit[0] * 60 * 60;
    $horaSplit[1] = $horaSplit[1] * 60;

    return (($horaSplit[0] + $horaSplit[1] + $horaSplit[2]) / 60);
    }

    public function minutesToHours($mins)
    {
    $hours = floor($mins / 60);
    $minutes = $mins - ($hours * 60);

    if (!$minutes) {
    $minutes = "00";
    }
    else if ($minutes <= 9) {
    $minutes = "0" . $minutes;
    }

    return ("{$hours}:{$minutes}");
    }

    public function contenido(&$h, $profesor_id) {
        $this->encabezado($h);
        $st = $this->getEstilos();
        $salto='   ' .
                '   ';

        $h->nueva_linea();
        $h->nueva_linea();
        $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'PROFR. '.utf8_decode(strToUpper($this->profesor->nombre())), $st['H2']);
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc+6);

        $h->nueva_linea();

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
        "WHERE cursos.profesores_id = '" . $profesor_id . "' AND grupos.ciclos_id='".$this->ciclo->id."' " . // AND cursos.estado_id='3'
        "ORDER BY cursos.inicio,materias.nombre ");
        $cc=array();
        $horas=array();
        foreach($cursos as $curso){
            $cc[$curso->id]["c"]=$curso;
            $cc[$curso->id]["h"]=$curso->horarios();
            $grupo=$curso->grupo();
            foreach($cc[$curso->id]["h"] as $horario){
                $inicio =$this->timeToMinutes($horario->inicio);
                $fin =$this->timeToMinutes($horario->fin);
                $horas[$grupo->turno]+=$fin-$inicio;
            }
        }
        $cursos=$cc;

        $dias=new Dias();
        $dias=$dias->find("id!='6' AND id!='7' ORDER BY id");

        $h->nueva_linea();
        $h->cc++;
        $h->xls->write($h->rr, $h->cc, '', $st['TD.NoBorderCenterBold']);
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc+6);
        $h->nueva_linea();
        $h->cc++;
        $h->xls->write($h->rr, $h->cc, '', $st['TD.NoBorderCenterBold']);
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc+6);
        $h->nueva_linea();
        $h->cc++;
        $h->xls->write($h->rr, $h->cc, '', $st['TD.NoBorderCenterBold']);
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc+6);

        $h->nueva_linea();
        $h->nueva_linea();
        $h->nueva_linea();
        $profesoresinfo = $this->getInfo($this->profesor->codigo);
        $laboral = $this->profesor->laboral();
        $lca = "No definido";
        
        if($laboral->id!=""){
            $lcategoria = $laboral->lcategoria();
            $lca = $lcategoria->nombre;
        }
        
        $h->cc+=2;
        $h->xls->write($h->rr, $h->cc, $lca, $st['TD.NoBorder']);
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc+1);
        $h->cc+=2;
        $h->xls->write($h->rr, $h->cc, '', $st['TD.NoBorder']);
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc+2);

        $horas = array();
        foreach($cursos as $r){
            $curso = $r['c'];
            $materia = $curso->materia();
            $grupo = $curso->grupo();
            $hcategoria = $curso->hcategoria();
            $materiahrs = new Materiahrs();
            $materiahrs = $materiahrs->find_first("materias_id = '".$materia->id."'");
            if($horas[$hcategoria->id] == null){
                $horas[$hcategoria->id] = $materiahrs->horas;
            }else{
                $horas[$hcategoria->id] += $materiahrs->horas;
            }
        }
        
        $hcategorias = new Hcategoria();
        $hcategorias = $hcategorias->find();
        
        foreach($hcategorias as $hc){
        $h->nueva_linea();
        $h->cc+=2;
        $h->xls->write($h->rr, $h->cc, utf8_decode($hc->nombre), $st['TD.NoBorder']);
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc+1);
        $h->cc+=2;
        $h->xls->write($h->rr, $h->cc, $horas[$hc->id], $st['TD.NoBorder']);
        
        }
        
            $h->nueva_linea();
            $h->nueva_linea();
            $h->cc++;
            $h->cc++;
            $h->xls->write($h->rr, $h->cc, 'Grupo', $st['TH.BGYellow']); $h->cc++;
            $h->xls->write($h->rr, $h->cc, 'Bachillerato', $st['TH.BGYellow']); $h->cc++;
            $h->xls->write($h->rr, $h->cc, 'Curso', $st['TH.BGYellow']); $h->cc++;
            $h->xls->write($h->rr, $h->cc, utf8_decode('Duración'), $st['TH.BGYellow']); $h->cc++;
            $h->xls->write($h->rr, $h->cc, utf8_decode('Asignación'), $st['TH.BGYellow']); $h->cc++;
            $h->nueva_linea();
            foreach($cursos as $r){
                $curso = $r['c'];
                $h->cc++;
                $h->cc++;
                $materia = $curso->materia();
                $grupo = $curso->grupo();
                $hcategoria = $curso->hcategoria();
                
                $asignacion = ($hcategoria->id == '' ? '-' : $hcategoria->clave );
                $duracion = ($materia->duracion == '' || $materia->duracion == '0' ? 19 : $materia->duracion );
                $h->xls->write($h->rr, $h->cc,$grupo->grado.$grupo->letra.$grupo->turno, $st['TD.NormalCenter']); $h->cc++;
                $oo = "";
                if($grupo->verOfertaClave() == "COM"){
                    $oo = "BGC";
                }elseif($grupo->verOfertaClave() == "GEN"){
                    $oo = "BG";
                }
                $h->xls->write($h->rr, $h->cc,$oo, $st['TD.NormalCenter']); $h->cc++;
                $h->xls->write($h->rr, $h->cc,utf8_decode($materia->nombre), $st['TD.NormalCenter']); $h->cc++;
                $h->xls->write($h->rr, $h->cc,$duracion, $st['TD.NormalCenter']); $h->cc++;
                $h->xls->write($h->rr, $h->cc,$asignacion, $st['TD.NormalCenter']); $h->cc++;
                $h->nueva_linea();
                
            }

        $h->nueva_linea();
        $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'Hora', $st['TH.BGYellow']); $h->cc++;
        foreach($dias as $d){
        $h->xls->write($h->rr, $h->cc, utf8_decode($d->nombre), $st['TH.BGYellow']); $h->cc++;
        }
        
        $h->nueva_linea();

        $h->xls->write($h->rr, $h->cc+1, 'Turno Matutino ', $st['H3']);
        $h->xls->mergeCells($h->rr, $h->cc+1, $h->rr, $h->cc+3);
        $h->nueva_linea();

            $inicio=7;
            $fin=13;
            for($ini=$inicio;$ini<$fin;$ini++){
            $h->cc++;
            $h->xls->write($h->rr, $h->cc,$ini.":00-".($ini+1).":00", $st['TH.BGYellow']); $h->cc++;
                foreach($dias as $d){
                $b=false;
                $crs="";
                $estilo = "NormalCenter";
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
                    if($materia->duracion == 7)
                        $estilo ='BGYellow';
                    $crs.= utf8_decode($grupo->grado.$grupo->letra. ' '.substr($materia->nombre,0,5)).$salto;
                    }


                }

                }
                if(!$b){
                $h->xls->write($h->rr, $h->cc,'', $st['TD.NormalCenter']); $h->cc++;
                }else{
                $crs=substr($crs,0,strlen($crs)-1);
                $h->xls->write($h->rr, $h->cc,$crs, $st['TD.'.$estilo]); $h->cc++;

                }
                }

                $h->nueva_linea();
            }

        $h->xls->write($h->rr, $h->cc+1, 'Turno Vespertino ', $st['H3']);
        $h->xls->mergeCells($h->rr, $h->cc+1, $h->rr, $h->cc+3);


        $h->nueva_linea();

            $inicio=13;
            $fin=21;
            for($ini=$inicio;$ini<$fin;$ini++){
                $h->cc++;
                $h->xls->write($h->rr, $h->cc,$ini.":00-".($ini+1).":00", $st['TH.BGYellow']); $h->cc++;
                foreach($dias as $d){
                $b=false;
                $crs="";
                $estilo = "NormalCenter";
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
                    if($materia->duracion == 7)
                        $estilo ='BGYellow';
                    $crs.= utf8_decode($grupo->grado.$grupo->letra.' '.substr($materia->nombre,0,5)).$salto;
                    }


                }

                }
                if(!$b){
                $h->xls->write($h->rr, $h->cc,'', $st['TD.NormalCenter']); $h->cc++;
                }else{
                $crs=substr($crs,0,strlen($crs)-1);

                $h->xls->write($h->rr, $h->cc,$crs, $st['TD.'.$estilo]); $h->cc++;

                }
                }
                $h->cc_max = $h->cc;
                $h->nueva_linea();

            }
            
                $h->nueva_linea();
                $h->nueva_linea();
                $h->cc++;    
                $nota = '';
                $h->xls->write($h->rr, $h->cc,"", $st['H3']); $h->cc++;
                $h->xls->write($h->rr, $h->cc,$nota, $st['H4']);    
                $h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc+4);
                
                
                $h->nueva_linea();
                
                $nota = '';
                $h->cc++;    
                $h->xls->write($h->rr, $h->cc,$nota, $st['H4']);
                $h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc+3);
                
                //$this->fondo($h);
            
                $h->rr_max = $h->rr;
        }

    public function encabezado($h) {
        $template=new Template();
        $st = $this->getEstilos();
        $h->nueva_linea();
        $h->cc++;
        $h->xls->insertBitmap($h->rr, $h->cc, getcwd() . '/public/img/sp5/udg.bmp', 0, 15, 1, 1);
        $h->cc += 6;
        $h->xls->insertBitmap($h->rr, $h->cc, getcwd() . '/public/img/sp5/lp5.bmp', 160, 15, 1, 1);
        $h->nueva_linea();
        $h->cc++;
        $h->xls->write($h->rr, $h->cc, $template->excel_escuela(), $st['H3']);
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc + 6);
        $h->nueva_linea();
        $h->cc++;
        $h->xls->write($h->rr, $h->cc, $template->excel_subtitulo(), $st['H4']);
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc + 6);
        $h->nueva_linea();
        $h->cc++;
        $h->xls->write($h->rr, $h->cc, "", $st['H4']);
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc + 6);
        $h->nueva_linea();
        $h->xls->repeatRows(0,5);
        $h->xls->freezePanes(array(5, 0));
    }

    public function fondo($h){
        $st = $this->getEstilos();
        for($i=0;$i<10;$i++)
            $h->nueva_linea();

        $h->cc++;
        $h->cc++;
        $h->xls->write($h->rr, $h->cc, '', $st['TD.NoBorderCenter']);
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc + 4);
        $h->nueva_linea();
        $h->cc++;
        $h->cc++;
        $h->xls->write($h->rr, $h->cc, '', $st['TD.NoBorderCenter']);
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc + 4);
        $h->nueva_linea();
        $h->cc++;
        $h->cc++;

        $hoy = new DateTime();

        $h->xls->write($h->rr, $h->cc, '"GUADALAJARA, JAL. '.$hoy->format('j').' DE '.strToUpper(Utils::mes_espanol($hoy->format('m'))).' DEL '.$hoy->format('Y').'"', $st['TD.NoBorderCenter']);
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc + 4);

        $h->nueva_linea();
        $h->nueva_linea();
        $h->nueva_linea();
        $h->nueva_linea();
        $h->nueva_linea();
        $h->nueva_linea();
        $h->nueva_linea();
        $h->nueva_linea();
        $h->nueva_linea();
        $h->xls->write($h->rr, $h->cc, '', $st['TD.NoBorderCenter']);
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc + 3);
        $h->cc+=4;
        $h->xls->write($h->rr, $h->cc, '', $st['TD.NoBorderCenter']);
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc + 3);
        $h->nueva_linea();
        $h->xls->write($h->rr, $h->cc, '', $st['TD.NoBorderCenter']);
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc + 3);
        $h->cc+=4;
        $h->xls->write($h->rr, $h->cc, '', $st['TD.NoBorderCenter']);
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc + 3);

        $h->nueva_linea();
        $h->nueva_linea();
        $h->nueva_linea();
        $h->cc+=2;
        $h->xls->write($h->rr, $h->cc, '', $st['TD.NoBorderCenter']);
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc + 4);
        $h->nueva_linea();
        $h->cc+=2;
        $h->xls->write($h->rr, $h->cc, '', $st['TD.NoBorderCenter']);
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc + 4);



    }

    public function getInfo($codigo){
        $profesoresinfo = new Profesoresinfo();
        $profesoresinfo = $profesoresinfo->find_first("codigo = '".$codigo."'");
        return $profesoresinfo;

    }

    public function propiedades(& $h) {
        $h->xls->centerHorizontally();
        $h->xls->hideGridlines();
        $h->xls->printArea(0, 0, $h->rr_max, $h->cc_max+1);
        $h->xls->setFooter("HEKADEMOS " . date("j/n/Y H:i"), 0);
        $h->xls->setPortrait();
        $h->xls->setMargins_LR(0.2);
        $h->xls->setMargins_TB(0.27);
        $h->xls->setPaper(3);
        $h->xls->setPrintScale(85);
        $h->xls->setZoom(80);
    }
}
?>