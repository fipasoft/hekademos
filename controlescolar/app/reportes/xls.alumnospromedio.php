<?php
Kumbia :: import('lib.excel.main');
class XLSalumnospromedio extends Reporte {
    private $condicion;
    public function XLSalumnospromedio($id = '') {
        $grupo = new Grupos();
        $grupo = $grupo->find($id);
        if($grupo->id != ''){
            if($grupo->asignado()){
                $alumnos = $grupo->alumnos();
                if(count($alumnos) > 0){

                    $this->Reporte('Alumnos '.$grupo->verInfo().'.xls');
                    $this->hoja($grupo,$alumnos);
                }else{

                    $this->hoja_vacia(' No hay alumnos inscritos en el grupo.');
                }
            }else{
                $this->hoja_vacia(' No esta asignado al grupo.');
            }
        }else{
            $this->hoja_vacia(' No se especific&oacute; un id de grupo v&aacute;lido.');
        }
    }

    public function hoja($grupo,$alumnos) {
        $nombre = $grupo->grado . $grupo->turno;
        $hojas = $this->getHojas();
        if (array_key_exists($nombre, $hojas)) {
            $h = $hojas[$nombre];
        } else {
            $cols = array (0.1,3,10,40,10,10);
            $h = $this->agregar_hoja($nombre, null, $cols);
            $h->cc_max = 8;
        }
        $this->contenido($h, $grupo,$alumnos);
        $this->propiedades($h);
    }

    public function hoja_vacia($msj) {
        $nombre = 'HEKADEMOS';
        $h = $this->agregar_hoja($nombre);
        $h->xls->write(0, 0, $msj);
    }

    public function contenido(& $h, $grupo,$alumnos) {
        $ciclo   = $grupo->ciclo();
        $this->encabezado($h,$grupo);
        $st = $this->getEstilos();
        $h->xls->write($h->rr, $h->cc, '', $st['TH.BGYellow']);
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr+1, $h->cc);
        $h->cc++;
        $h->xls->write($h->rr, $h->cc, '#', $st['TH.BGYellow']);
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr+1, $h->cc);
        $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'Codigo', $st['TH.BGYellow']);
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr+1, $h->cc);
        $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'Alumno', $st['TH.BGYellow']);
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr+1, $h->cc);
        $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'Aprobadas', $st['TH.BGYellow']);
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr+1, $h->cc);
        $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'Promedio', $st['TH.BGYellow']);
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr+1, $h->cc);
        $h->cc++;
        $h->nueva_linea();



        $n = 1;
         foreach($alumnos as $alumno){
             $td = ($n%2 == 0 ? 'Par' : '');
             $h->nueva_linea();
             $h->xls->writeString($h->rr, $h->cc, $alumno->id,  $st['TD' . $td . '.Normal']); $h->cc++;
             $h->xls->write($h->rr, $h->cc, $n, $st['TH.BGYellow']); $h->cc++;
             $h->xls->writeString($h->rr, $h->cc, $alumno->codigo,  $st['TD' . $td . '.Normal']); $h->cc++;
             $h->xls->write($h->rr, $h->cc, utf8_decode($alumno->nombre()),  $st['TD' . $td . '.Normal']);$h->cc++;
             $h->xls->writeString($h->rr, $h->cc, $alumno->aprobadas,  $st['TD' . $td . '.Normal']); $h->cc++;
             $h->xls->writeString($h->rr, $h->cc, $alumno->promedio,  $st['TD' . $td . '.Normal']); $h->cc++;
         $n++;
         }
         $h->rr_max=$h->rr;

        }

        public function encabezado(&$h,$grupo) {
        $ciclo=$grupo->ciclo();
        $st = $this->getEstilos();
        $template=new Template();
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
        $turno='';
        switch($grupo->turno){
            case 'M': $turno="MATUTINO"; break;
            case 'V': $turno="VESPERTINO"; break;
            case 'N': $turno="NOCTURNO"; break;
        }
        $h->xls->write($h->rr, $h->cc, 'RESUMEN DEL GRUPO', $st['H4']);
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc + 6);
        $h->nueva_linea();
            $h->xls->write($h->rr, $h->cc,utf8_decode(strToUpper($ciclo->numero.', '.$grupo->grado.$grupo->letra.' '.$turno)), $st['H4']);
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc + 6);
        $h->nueva_linea();
        $h->xls->write($h->rr, $h->cc, utf8_decode(strToUpper($grupo->verOferta('nombre'))), $st['H4']);
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc + 6);
        $h->nueva_linea();
        $h->nueva_linea();
        $h->xls->repeatRows(0,9);

        $h->xls->freezePanes(array(10, 0));

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
        $h->xls->setPrintScale(70);
        $h->xls->setZoom(80);
    }
}
?>