<?php
Kumbia :: import('lib.excel.main');

class XLSAsistencias extends Reporte{
    private $curso;
    private $asistencias;
    private $nAsistencias;
    public function XLSAsistencias($curso_id = ''){
        ini_set("memory_limit","64M");
        $this->Reporte('Asistencias.xls');
        if($curso_id != ''){
        $curso_id = intval($curso_id, 10);
        $curso=new Cursos();
        $this->curso=$curso=$curso->find($curso_id);
        if(!$curso->id == ''){
            $grupo=new Grupos();
                if($curso->asignado() || $grupo->estutorporid($curso->grupos_id)){
                    if($curso->aprobado()){
                        $hdrAsistencias  =  $curso->asistenciasHdr();
                        $this->nAsistencias=$nAsistencias    =  count($hdrAsistencias);
                        if($nAsistencias>0){
                        $fechasHdr = array();
                        foreach($hdrAsistencias as $fecha){
                            $fechasHdr[substr($fecha->dia,0,4)][substr($fecha->dia,5,2)][substr($fecha->dia,8,2)]=$fecha;
                        }

                    $alumnos         =  $curso->alumnosgrupo();
                    $this->asistencias=array();
                    $this->obtenAsistencias($alumnos);
                    $this->hoja_resumen($curso,$alumnos);
                    foreach($fechasHdr as $a=>$anio){
                        foreach($anio as $m=>$mes){
                        $this->hoja($curso,Utils::mes_espanol($m).' '.$a,$alumnos,$mes);
                        }
                    }
                    }else{
                    $this->hoja_vacia('El curso no tiene registradas asistencias.');
                    }
                    }else{
                    $this->hoja_vacia('El curso no esta aprobado.');
                    }
                }else{
                    $this->hoja_vacia('El curso no esta dentro de su asignacion.');
                }
        }else{
                    $this->hoja_vacia('El curso no existe.');
                }

    }else{
                    $this->hoja_vacia('El curso no existe.');
                }
    }



    public function hoja($curso,$nombre,$alumnos,$datos){
        $hojas = $this->getHojas();
        if(array_key_exists($nombre, $hojas)){
            $h = $hojas[$nombre];
        }else{
            $cols = array (3,10,50);
            for($i=1;$i<30;$i++)
                $cols[]=3;

            $rows = array();
            for($i = 0; $i < 60; $i++){
                $rows[] = 18;
            }
            $h = $this->agregar_hoja($nombre, $rows, $cols);

        }
        $this->contenido($h,$curso, $alumnos, $datos, $nombre);
        $this->propiedades($h);
    }

    public function hoja_vacia($msj){
        $nombre = 'SP5';
        $h = $this->agregar_hoja($nombre);
        $h->xls->write(0, 0, $msj);
    }


    public function hoja_resumen($curso,$alumnos){
        $nombre='Resumen';
        $hojas = $this->getHojas();
        if(array_key_exists($nombre, $hojas)){
            $h = $hojas[$nombre];
        }else{
            $cols = array (3,10,50,5,5,10);
            for($i=1;$i<30;$i++)
                $cols[]=3;

            $rows = array();
            for($i = 0; $i < 60; $i++){
                $rows[] = 18;
            }
            $h = $this->agregar_hoja($nombre, $rows, $cols);

        }
        $this->contenido_resumen($h,$curso, $alumnos);
        $this->propiedades($h);


    }

    public function contenido_resumen(&$h,$curso, $alumnos){
        $this->encabezado_resumen($h,$curso);
        $st = $this->getEstilos();
        $h->xls->write($h->rr, $h->cc, '#', $st['TH.BGYellow']);
        $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'Codigo', $st['TH.BGYellow']);
        $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'Nombre', $st['TH.BGYellow']);
        $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'Ast', $st['TH.BGYellow']);
        $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'Fta', $st['TH.BGYellow']);
        $h->cc++;
        $h->xls->write($h->rr, $h->cc, '%', $st['TH.BGYellow']);
        $h->cc++;
        $i=0;
        foreach($alumnos as $alumno){
            $h->nueva_linea();
            $td = ($i%2 == 0 ? 'Par' : '');
            $h->xls->write($h->rr, $h->cc, $i+1, $st['TH.BGYellow']);$h->cc++;
            $nombre = $alumno->ap . ' ' . $alumno->am . ', '. $alumno->nombre;
            $h->xls->write($h->rr, $h->cc, utf8_decode($alumno->codigo), $st['TD' . $td . '.Normal']);$h->cc++;
            $art='';
                if($alumno->curso_articulo!=''){
                $art='{'.$alumno->curso_articulo.'}';
                }

            $h->xls->write($h->rr, $h->cc, utf8_decode($nombre).' '.$art, $st['TD' . $td . '.Normal']);$h->cc++;
            $i++;
            $asistencias = $curso->asistenciasAlumno($alumno->id);
                $ast = $asistencias['AST'];
                if($ast != ''){
                $fta=$this->nAsistencias - $asistencias['AST'];;
                }else{
                $ast= '-';
                $fta= '-';
                }

                if($this->nAsistencias != 0){
                    $prc = ($ast * 100 / $this->nAsistencias);
                    $prc = number_format($prc, 1, '.', ',');
                }else{
                    $prc = '-';
                }
            $h->xls->write($h->rr, $h->cc, $ast, $st['TD' . $td . '.Normal']);$h->cc++;
            $h->xls->write($h->rr, $h->cc, $fta, $st['TD' . $td . '.Normal']);$h->cc++;
            $h->xls->write($h->rr, $h->cc, $prc, $st['TD' . $td . '.Normal']);$h->cc++;
        }

        $h->rr_max=$h->rr;
        $h->cc_max=$h->cc;


    }


    public function contenido(&$h,$curso, $alumnos,$datos,$nombre){
        $this->encabezado($h,$curso,$nombre);
        $st = $this->getEstilos();
        $h->xls->write($h->rr, $h->cc, '#', $st['TH.BGYellow']);
        $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'Codigo', $st['TH.BGYellow']);
        $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'Nombre', $st['TH.BGYellow']);
        $h->cc++;
            foreach($datos as $f){
            $h->xls->write($h->rr, $h->cc, substr($f->dia,8,2), $st['TH.BGYellow']);
            $h->cc++;

             }


        $i=0;
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
            foreach($datos as $hdr){
                $ast=$this->asistencias[$alumno->id][substr($hdr->dia,0,4)][substr($hdr->dia,5,2)][substr($hdr->dia,8,2)];
                $valor = $ast->valor;
                $valor=$valor == '' || $valor == 'FTA' ? 'F' : 'A';
                $h->xls->write($h->rr, $h->cc, $valor, $st['TD' . $td . '.Normal']);$h->cc++;

            }
        $i++;
        }

        $h->rr_max=$h->rr;
        $h->cc_max=$h->cc;

    }

    public function encabezado(&$h,$curso,$nombre) {
        $grupo           =  $curso->grupo();
        $ciclo           =  $grupo->ciclo();
        $materia       =  $curso->materia();

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
        $h->xls->write($h->rr, $h->cc, 'ASISTENCIAS DE '.utf8_decode(strToUpper($nombre)), $st['H4']);
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc + 6);
        $h->nueva_linea();
        $h->xls->write($h->rr, $h->cc,utf8_decode(strToUpper($ciclo->numero.', '.$grupo->grado.$grupo->letra.' '.$turno.', '.$materia->nombre)), $st['H4']);
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc + 6);
        $h->nueva_linea();
        $h->xls->write($h->rr, $h->cc, utf8_decode(strToUpper($grupo->verOferta('nombre'))), $st['H4']);
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc + 6);
        $h->nueva_linea();
        $h->nueva_linea();
        $h->xls->repeatRows(0,8);
        $h->xls->freezePanes(array(9, 0));
    }


    public function encabezado_resumen(&$h,$curso) {
        $grupo           =  $curso->grupo();
        $ciclo           =  $grupo->ciclo();
        $materia       =  $curso->materia();

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
        $turno='';
        switch($grupo->turno){
            case 'M': $turno="MATUTINO"; break;
            case 'V': $turno="VESPERTINO"; break;
            case 'N': $turno="NOCTURNO"; break;
        }
        $h->xls->write($h->rr, $h->cc, 'RESUMEN DE ASISTENCIAS DEL CURSO', $st['H4']);
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc + 6);
        $h->nueva_linea();
        $h->xls->write($h->rr, $h->cc,utf8_decode(strToUpper($ciclo->numero.', '.$grupo->grado.$grupo->letra.' '.$turno.', '.$materia->nombre)), $st['H4']);
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc + 6);
        $h->nueva_linea();
        $h->xls->write($h->rr, $h->cc, utf8_decode(strToUpper($grupo->verOferta('nombre'))), $st['H4']);
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc + 6);
        $h->nueva_linea();
        $h->nueva_linea();
        $h->xls->repeatRows(0,8);
        $h->xls->freezePanes(array(9, 0));
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

    function obtenAsistencias($alumnos){
        $datos=array();
        foreach($alumnos as $alumno){
            $asistencias = $this->curso->asistenciasAlumnoInfo($alumno->id);
            foreach($asistencias as $asistencia){
                $datos[$alumno->id][substr($asistencia->dia,0,4)][substr($asistencia->dia,5,2)][substr($asistencia->dia,8,2)]=$asistencia;

            }

        }
        $this->asistencias=$datos;
        return $datos;
    }

    function sumaDia($fecha,$dia){
        list($year,$mon,$day) = explode('-',$fecha);
        return mktime(0,0,0,$mon,$day+$dia,$year);
    }

}
?>