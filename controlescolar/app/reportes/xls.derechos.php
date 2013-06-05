<?php
Kumbia :: import('lib.excel.main');
class XLSDerechos extends Reporte {
    private $condicion;
    public function XLSDerechos($id = '') {

        if(is_array($id)){
            $this->Reporte('DERECHOS A EXAMEN.xls');
            $grupos=$id;
            foreach($grupos as $id){
            $this->creaHoja($id);
            }
        }else{
        $this->Reporte('DERECHOS A EXAMEN.xls');
        $this->creaHoja($id);
        }
    }

    public function creaHoja($id){
        $grupo = new Grupos();
        $grupo = $grupo->find($id);
        if($grupo->id != ''){
            if($grupo->asignado()){
                $alumnos = $grupo->alumnos();
                if(count($alumnos) > 0){

                    $this->hoja($grupo,$alumnos);
                }else{

                    $this->hoja_vacia(' No hay alumnos inscritos en el grupo.',$grupo);
                }
            }else{
                $this->hoja_vacia(' No esta asignado al grupo.',$grupo);
            }
        }else{
            $this->hoja_vacia(' No se especific&oacute; un id de grupo v&aacute;lido.',null,$id);
        }
    }

    public function hoja($grupo,$alumnos) {
        $nombre = $grupo->verOfertaClave() . $grupo->grado. $grupo->letra . $grupo->turno;
        $hojas = $this->getHojas();
        if (array_key_exists($nombre, $hojas)) {
            $h = $hojas[$nombre];
        } else {
            $cols = array (3,10,40,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7,7);
            $rows = array(15,15,15,15,15,15,15,15,51);
            $h = $this->agregar_hoja($nombre, $rows, $cols);
            $h->cc_max = 8;
        }
        $this->contenido($h, $grupo,$alumnos);
        $this->propiedades($h);
    }

    public function hoja_vacia($msj,$grupo=null,$par='') {

        if($grupo!=null)
        $nombre = $grupo->verOfertaClave() . $grupo->grado. $grupo->letra . $grupo->turno;
        else
        $nombre='HEKADEMOS '.$par;

        $h = $this->agregar_hoja($nombre);
        $h->xls->write(0, 0, $msj);
    }

    public function contenido(&$h, $grupo,$alumnos) {
        $ciclo   = $grupo->ciclo();
        $cursos  = $grupo->cursos();
        $this->encabezado($h,$grupo,count($cursos));
        $asistencias = $grupo->asistencias($cursos);
        $calificaciones = $grupo->calificaciones();

        $st = $this->getEstilos();
        //$h->xls->write($h->rr, $h->cc, '#', $st['TH.BGOrange']);
        //$h->xls->mergeCells($h->rr, $h->cc, $h->rr+1, $h->cc);
        $h->write_merge('#', $h->rr, $h->cc, $h->rr+1, $h->cc, $st['TH.BGOrange']);
        $h->cc++;

        //$h->xls->write($h->rr, $h->cc, 'Codigo', $st['TH.BGOrange']);
        //$h->xls->mergeCells($h->rr, $h->cc, $h->rr+1, $h->cc);
        $h->write_merge('Codigo', $h->rr, $h->cc, $h->rr+1, $h->cc, $st['TH.BGOrange']);
        $h->cc++;
        //$h->xls->write($h->rr, $h->cc, 'Alumno', $st['TH.BGOrange']);
        //$h->xls->mergeCells($h->rr, $h->cc, $h->rr+1, $h->cc);
        $h->write_merge('Alumno', $h->rr, $h->cc, $h->rr+1, $h->cc, $st['TH.BGOrange']);
        foreach($cursos as $curso){
            if($curso->aprobado()){
            $h->cc++;
            //$h->xls->write($h->rr, $h->cc, utf8_decode($curso->materia), $st['TH.BGOrange']);
            //$h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc+1);
            $h->write_merge(utf8_decode($curso->materia), $h->rr, $h->cc, $h->rr, $h->cc+1, $st['TH.BGOrange']);
            $cc=$h->cc;

            $h->cc=$cc;
            $h->xls->write($h->rr+1, $h->cc, "%", $st['TH.BGYellow']);
            $h->cc++;
            $h->xls->write($h->rr+1, $h->cc, "EDO", $st['TH.BGYellow']);

            }
        }

        $h->cc_max=$h->cc;
        $h->nueva_linea();



        $n = 1;
         foreach($alumnos as $alumno){
             $td = ($n%2 == 0 ? 'Par' : '');
             $h->nueva_linea();
             $h->xls->write($h->rr, $h->cc, $n, $st['TD' . $td . '.Normal']); $h->cc++;
             $h->xls->writeString($h->rr, $h->cc, $alumno->codigo,  $st['TD' . $td . '.Normal']); $h->cc++;
             $h->xls->write($h->rr, $h->cc, utf8_decode($alumno->nombre()),  $st['TD' . $td . '.Normal']);
             foreach($cursos as $curso){
                if($curso->aprobado()){
                        $ast = $asistencias[$curso->id][$alumno->id];
                        if($ast['porcentaje'] == '' || $ast['porcentaje'] == '-'){
                            $ast['porcentaje'] = '-';
                            $oportunidad='-';
                        }elseif($ast['porcentaje']>=80){
                                $oportunidad='-';
                        }elseif($ast['porcentaje']>=60){
                                $oportunidad='EXT';
                        }elseif($ast['porcentaje']<60){
                                $oportunidad='SD';
                        }

                    $h->cc++;
                    $h->xls->write($h->rr, $h->cc, $ast['porcentaje'] ,  $st['TD' . $td . '.Normal']);

                    $h->cc++;
                    $h->xls->write($h->rr, $h->cc, $oportunidad,  $st['TD' . $td . '.Normal']);

                    }
             }
         $n++;
         }
         $h->rr_max=$h->rr;

        }

        public function encabezado(&$h,$grupo,$cursos) {
        $template=new Template();
        $ciclo=$grupo->ciclo();
        $st = $this->getEstilos();
        $h->nueva_linea();
        $h->xls->insertBitmap($h->rr, $h->cc, getcwd() . '/public/img/sp5/udg.bmp', 0, 15, 1, 1);
        $h->cc += 2+(2*$cursos);
        $h->xls->insertBitmap($h->rr, $h->cc, getcwd() . '/public/img/sp5/lp5.bmp', 160, 15, 1, 1);
        $h->nueva_linea();
        $h->cc=3;
        $h->xls->write($h->rr, $h->cc, $template->excel_escuela(), $st['H3']);
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc + 9);
        $h->nueva_linea();
        $h->cc=3;
        $h->xls->write($h->rr, $h->cc, $template->excel_subtitulo(), $st['H4']);
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc + 9);
        $h->nueva_linea();
        $h->cc=3;
        $turno='';
        switch($grupo->turno){
            case 'M': $turno="MATUTINO"; break;
            case 'V': $turno="VESPERTINO"; break;
            case 'N': $turno="NOCTURNO"; break;
        }
        $h->xls->write($h->rr, $h->cc, 'DERECHOS A EXAMEN', $st['H4']);
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc + 9);
        $h->nueva_linea();
        $h->cc=3;
        $h->xls->write($h->rr, $h->cc,utf8_decode(strToUpper($ciclo->numero.', '.$grupo->grado.$grupo->letra.' '.$turno)), $st['H4']);
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc + 9);
        $h->nueva_linea();
        $h->cc=3;
        $h->xls->write($h->rr, $h->cc, utf8_decode(strToUpper($grupo->verOferta('nombre'))), $st['H4']);
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc + 9);
        $h->nueva_linea();
        $h->nueva_linea();
        $h->xls->repeatRows(0,9);

        $h->xls->freezePanes(array(10, 3));

    }


    public function propiedades(&$h) {
        $h->xls->centerHorizontally();
        $h->xls->hideGridlines();
        $h->xls->printArea(0, 0, $h->rr_max, $h->cc_max);
        $h->xls->setFooter("HEKADEMOS 2 " . date("j/n/Y H:i"), 0);
        $h->xls->setLandscape();
        $h->xls->setMargins_LR(0.2);
        $h->xls->setMargins_TB(0.27);
        $h->xls->setPaper(3);
        $h->xls->setPrintScale(80);
        $h->xls->setZoom(80);
    }
}
?>