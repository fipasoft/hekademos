<?php
Kumbia :: import('lib.excel.main');
class XLSCalificaciones extends Reporte {
    private $condicion;
    private $materia;
    public function XLSCalificaciones($cursos) {
        ini_set("memory_limit","64M");
        $this->Reporte('Calificaciones.xls');
        if(count($cursos)==0 || !is_array($cursos)){
            $this->hoja_vacia('Datos invalidos');
        }else{
        foreach($cursos as $id){
            $id = intval($id, 10);
            $Cursos = new Cursos();
            $curso = $Cursos->find($id);
            if($curso->id != ''){

                    $alumnos         =          $curso->alumnosgrupo();
                    $this->materia         =  $curso->materia();
            $grupo=new Grupos();
            if($curso->asignado() || $grupo->estutorporid($curso->grupos_id)){
            if($curso->aprobado()){

                    if(count($alumnos) > 0){
                        $this->hoja($curso,$alumnos);
                    }else{
                        $this->hoja_vacia('No hay alumnos inscritos al curso.');
                    }
                    }else{
                    $this->hoja_vacia('El curso no esta aprobado.');
                    }
                }else{
                    $this->hoja_vacia(utf8_decode('El curso no esta dentro de su asignación.'));
                }
                }else{
                    $this->hoja_vacia('El curso no existe.');
                }
        }
        }

    }

    public function hoja($curso,$alumnos) {
        $nombre = utf8_decode($this->materia->nombre);
        $hojas = $this->getHojas();
        if (array_key_exists($nombre, $hojas)) {
            $h = $hojas[$nombre];
        } else {
            $cols = array (3,10,50);
            $h = $this->agregar_hoja($nombre, null, $cols);
            $h->cc_max = 8;
        }
        $this->contenido($h,$alumnos,$curso);
        $this->propiedades($h);
    }

    public function hoja_vacia($msj) {
        $nombre = 'SP5';
        $h = $this->agregar_hoja($nombre);
        $h->xls->write(0, 0, $msj);
    }

    public function contenido(&$h,$alumnos,$curso) {

        $parciales        =  $curso->parciales();
        $calificaciones  =  $curso->calificaciones();
        $hdrParciales    =  $curso->parcialesHdr();
        $nParciales        =  count($hdrParciales);

         $this->encabezado($h,$curso);
        $st = $this->getEstilos();
        $h->xls->write($h->rr, $h->cc, '#', $st['TH.BGYellow']);
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr+1, $h->cc);
        $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'Codigo', $st['TH.BGYellow']);
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr+1, $h->cc);
        $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'Alumno', $st['TH.BGYellow']);
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr+1, $h->cc);
        $h->cc++;

        if($nParciales > 0){
            $h->xls->write($h->rr, $h->cc, 'Parciales', $st['TH.BGYellow']);
            $h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc+$nParciales);
                $c=0;
            foreach($hdrParciales as $hdr){
            $h->xls->write($h->rr+1, $h->cc+$c, $hdr->periodo, $st['TH.BGYellow']);
            $c++;
            }
            $h->xls->write($h->rr+1, $h->cc+$c, 'Total', $st['TH.BGYellow']);

         }
         if($nParciales>0)
            $h->cc+=$nParciales+1;

            $h->xls->write($h->rr, $h->cc, 'Finales', $st['TH.BGYellow']);
            $h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc+1);

            $h->xls->write($h->rr+1, $h->cc, 'ORD', $st['TH.BGYellow']);
            $h->xls->write($h->rr+1, $h->cc+1, 'EXT', $st['TH.BGYellow']);

            $h->cc++;




        $i=0;
        $h->nueva_linea();
        foreach($alumnos as $alumno){
            $h->nueva_linea();
            $td = ($i%2 == 0 ? 'Par' : '');
            $h->xls->write($h->rr, $h->cc, $i+1, $st['TH.BGYellow']);$h->cc++;
            $nombre = $alumno->ap . ' ' . $alumno->am . ', '. $alumno->nombre;
            $h->xls->writeString($h->rr, $h->cc, utf8_decode($alumno->codigo), $st['TD' . $td . '.Normal']);$h->cc++;
            $art='';
                if($alumno->curso_articulo!=''){
                $art='{'.$alumno->curso_articulo.'}';
                }

            $h->xls->write($h->rr, $h->cc, utf8_decode($nombre).' '.$art, $st['TD' . $td . '.Normal']);$h->cc++;

            if($nParciales > 0){
                foreach($hdrParciales as $hdr){
                $valor = $parciales[$alumno->id][$hdr->periodo];
                $valor != '' ? $valor : '-';

                $h->xls->write($h->rr, $h->cc, $valor, $st['TD' . $td . '.Normal']);$h->cc++;


                }
                    $total = $parciales[$alumno->id]['total'];
                    $promedio = $parciales[$alumno->id]['promedio'] =  number_format($total / $nParciales, 1, '.', ',');
                $h->xls->write($h->rr, $h->cc, $promedio, $st['TD' . $td . '.Normal']);$h->cc++;

            }



            $ord=$calificaciones[$alumno->id]['ORD']['valor'] != '' ?$calificaciones[$alumno->id]['ORD']['valor'] : '-';
            $h->xls->write($h->rr, $h->cc, $ord, $st['TD' . $td . '.Normal']);$h->cc++;
            $ext=$calificaciones[$alumno->id]['EXT']['valor'] != '' ?$calificaciones[$alumno->id]['EXT']['valor'] : '-';
            $h->xls->write($h->rr, $h->cc, $ext, $st['TD' . $td . '.Normal']);$h->cc++;
            $i++;
        }
         $h->rr_max=$h->rr;
         $h->cc_max=$h->cc+5;
        }

    public function encabezado(&$h,$curso) {
        $grupo           =  $curso->grupo();
        $ciclo           =  $grupo->ciclo();

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
        $h->xls->write($h->rr, $h->cc, 'CALIFICACIONES DEL CURSO', $st['H4']);
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc + 6);
        $h->nueva_linea();
            $h->xls->write($h->rr, $h->cc,utf8_decode(strToUpper($ciclo->numero.', '.$grupo->grado.$grupo->letra.' '.$turno.', '.$this->materia->nombre)), $st['H4']);
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc + 6);
        $h->nueva_linea();
        $h->xls->write($h->rr, $h->cc, utf8_decode(strToUpper($grupo->verOferta('nombre'))), $st['H4']);
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc + 6);
        $h->nueva_linea();
        $h->nueva_linea();
        $h->xls->repeatRows(0,9);
        $h->xls->freezePanes(array(10, 0));
    }

    public function propiedades(&$h) {
        $h->xls->centerHorizontally();
        $h->xls->hideGridlines();
        $h->xls->printArea(0, 0, $h->rr_max, $h->cc_max);
        $h->xls->setFooter("SP5 " . date("j/n/Y H:i"), 0);
        $h->xls->setLandscape();
        $h->xls->setMargins_LR(0.2);
        $h->xls->setMargins_TB(0.27);
        $h->xls->setPaper(3);
        $h->xls->setPrintScale(70);
        $h->xls->setZoom(80);
    }
}
?>