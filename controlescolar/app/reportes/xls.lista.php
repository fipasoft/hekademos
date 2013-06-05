<?php
Kumbia :: import('lib.excel.main');

class XLSLista extends Reporte{
    private $curso;
    private $asistencias;
    private $nAsistencias;
    public function XLSLista($curso_id = ''){
        $this->Reporte('Lista.xls');
        if($curso_id != ''){
            $curso_id = intval($curso_id, 10);
            $curso=new Cursos();
            $this->curso=$curso=$curso->find($curso_id);
            if(!$curso->id == ''){
                if($curso->asignado()){
                    if($curso->aprobado()){
                        $hdrAsistencias  =  $curso->asistenciasFechasLista();
                        $this->nAsistencias=$nAsistencias    =  count($hdrAsistencias);
                        if($nAsistencias>0){
                            $fechasHdr = array();
                            foreach($hdrAsistencias as $fecha){
                                $fechasHdr[substr($fecha->dia,0,4)][substr($fecha->dia,5,2)][substr($fecha->dia,8,2)]=$fecha;
                            }

                            $alumnos         =  $curso->alumnosgrupo();
                            $this->asistencias=array();
                            $this->obtenAsistencias($alumnos);


                            //$this->hojaG($curso, "GENERAL", $alumnos, $fechasHdr);

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
        if(array_key_exists(utf8_decode($nombre), $hojas)){
            $h = $hojas[$nombre];
        }else{
            $cols = array (12,20,50,10,17);
            for($i=5;$i<55;$i++)
                $cols[$i]=3;

            $rows = array();
            for($i = 0; $i < 60; $i++){
                $rows[$i] = 18;
            }
                
            $rows[8] = 40;
                
            $h = $this->agregar_hoja($nombre, $rows, $cols);

        }
        $this->contenido($h,$curso, $alumnos, $datos,$nombre);
        $this->propiedades($h);
    }

    public function hojaG($curso,$nombre,$alumnos,$datos){
        $hojas = $this->getHojas();
        if(array_key_exists(utf8_decode($nombre), $hojas)){
            $h = $hojas[$nombre];
        }else{
            $cols = array (12,20,50,10,17);
            for($i=5;$i<305;$i++)
                $cols[$i]=2;

            $rows = array();
            for($i = 0; $i < 60; $i++){
                $rows[$i] = 18;
            }
                
            $rows[8] = 40;
                
            $h = $this->agregar_hoja($nombre, $rows, $cols);

        }
        $this->contenidoG($h,$curso, $alumnos, $datos,$nombre);
        $this->propiedades($h);
    }

    public function hoja_vacia($msj){
        $nombre = 'HEKADEMOS';
        $h = $this->agregar_hoja($nombre);
        $h->xls->write(0, 0, $msj);
    }



    public function contenido(&$h,$curso, $alumnos,$datos,$nombre){
        $this->encabezado($h,$curso,$nombre);
        $st = $this->getEstilos();
        $h->xls->write($h->rr, $h->cc, 'C', $st['TD.BGBlue']);
        $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'NO.CONTROL', $st['TD.BGBlue']);
        $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'NOMBRE DEL ALUMNO', $st['TD.BGBlue']);$h->cc++;
        $h->xls->write($h->rr, $h->cc, "", $st['TD.BGBlue']);$h->cc++;
        $h->xls->write($h->rr, $h->cc, "", $st['TD.BGBlue']);
        $h->xls->mergeCells($h->rr, $h->cc-2, $h->rr, $h->cc);
        $h->cc++;

        $dias = count($datos);
        $h->xls->write($h->rr-1, $h->cc, $nombre, $st['TD.BGBlue']);
        $dd = $dias - 2; 
        for($i=0; $i<=$dd; $i++){
            $h->cc++;
            $h->xls->write($h->rr-1, $h->cc, "", $st['TD.BGBlue']);
        }
        $h->xls->mergeCells($h->rr-1, $h->cc-$dd-1 , $h->rr-1, $h->cc);
            
        $h->cc -= $dd + 1;
        foreach($datos as $f){
            $h->xls->write($h->rr, $h->cc, substr($f->dia,8,2), $st['TD.BGBlue']);
            $h->cc++;

        }


        $i=0;
        foreach($alumnos as $alumno){
            $h->nueva_linea();
            $td = ($i%2 == 0 ? 'Par' : '');
            $h->xls->write($h->rr, $h->cc, $i+1, $st['TD.BGBlue']);$h->cc++;
            $nombre = $alumno->ap . ' ' . $alumno->am . ', '. $alumno->nombre;
            $h->xls->writeString($h->rr, $h->cc, utf8_decode($alumno->codigo), $st['TD.BGBlue']);$h->cc++;

            $h->xls->write($h->rr, $h->cc, utf8_decode($nombre), $st['TD.BGBlue']);$h->cc++;
            $h->xls->write($h->rr, $h->cc, "", $st['TD.BGBlue']);$h->cc++;
            $h->xls->write($h->rr, $h->cc, "", $st['TD.BGBlue']);
            $h->xls->mergeCells($h->rr, $h->cc-2, $h->rr, $h->cc);
            $h->cc++;
            foreach($datos as $hdr){
                $ast=$this->asistencias[$alumno->id][substr($hdr->dia,0,4)][substr($hdr->dia,5,2)][substr($hdr->dia,8,2)];
                $valor = $ast->valor;
                if($valor!='')
                $valor= $valor == 'FTA' ? 'F' : 'A';

                $h->xls->write($h->rr, $h->cc, $valor, $st['TD.BGBlue']);$h->cc++;

            }
            $i++;
        }

        $h->cc_max=$h->cc;

        $h->nueva_linea();
        $h->nueva_linea();
        $h->cc += 2;
        $h->xls->write($h->rr, $h->cc, "ESTA LISTA ES PARA USO ", $st['TD.Red']);$h->cc++;
        $h->xls->write($h->rr, $h->cc, "", $st['TD.Red']);$h->cc++;
        $h->xls->write($h->rr, $h->cc, "", $st['TD.Red']);
        $h->xls->mergeCells($h->rr, $h->cc-2, $h->rr, $h->cc);
        $h->cc += 1;
        $h->xls->write($h->rr, $h->cc, "FECHA DE EMISION", $st['TD.BGBlue']);
        for($i = 0;$i<=10;$i++){
            $h->cc++;
            $h->xls->write($h->rr, $h->cc, "", $st['TD.BGBlue']);
        }
        $h->xls->mergeCells($h->rr, $h->cc-11, $h->rr, $h->cc);





        $h->nueva_linea();
        $h->cc +=2;
        $h->xls->write($h->rr, $h->cc, "PERSONAL DEL PROFESOR", $st['TD.Red']);$h->cc++;
        $h->xls->write($h->rr, $h->cc, "", $st['TD.Red']);$h->cc++;
        $h->xls->write($h->rr, $h->cc, "", $st['TD.Red']);
        $h->xls->mergeCells($h->rr, $h->cc-2, $h->rr, $h->cc);
        $h->cc += 1;

        $now = new DateTime();

        $h->xls->write($h->rr, $h->cc, $now->format("d")."/".Utils::mes_espanol($now->format("m"))."/".$now->format("Y"), $st['TH.BGBlue']);
        for($i = 0;$i<=10;$i++){
            $h->cc++;
            $h->xls->write($h->rr, $h->cc, "", $st['TH.BGBlue']);
        }
        $h->xls->mergeCells($h->rr, $h->cc-11, $h->rr, $h->cc);

        if($h->cc_max < $h->cc)
        $h->cc_max = $h->cc + 1;
            
        $h->rr_max=$h->rr;

    }


    public function contenidoG(&$h,$curso, $alumnos,$fechasHdr,$nombre){
        $this->encabezado($h,$curso,$nombre);
        $st = $this->getEstilos();
        $st = $this->getEstilos();
        $h->xls->write($h->rr, $h->cc, 'C', $st['TD.BGBlue']);
        $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'NO.CONTROL', $st['TD.BGBlue']);
        $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'NOMBRE DEL ALUMNO', $st['TD.BGBlue']);$h->cc++;
        $h->xls->write($h->rr, $h->cc, "", $st['TD.BGBlue']);$h->cc++;
        $h->xls->write($h->rr, $h->cc, "", $st['TD.BGBlue']);
        $h->xls->mergeCells($h->rr, $h->cc-2, $h->rr, $h->cc);
        $h->cc++;

        $tt = count($alumnos);

        foreach($fechasHdr as $a=>$anio){
            foreach($anio as $m=>$mes){
                $h->xls->write($h->rr, $h->cc, substr(Utils::mes_espanol($m),0 ,3), $st['TD.BGBlue']);
                for($i = 1; $i <= $tt; $i++){
                    $h->xls->write($h->rr + $i, $h->cc, "", $st['TD.BGBlue']);
                }
                
                $h->xls->mergeCells($h->rr , $h->cc, $h->rr + $tt , $h->cc);
                $h->cc++;

                foreach($mes as $f=>$fecha){
                    $h->xls->write($h->rr, $h->cc, substr($fecha->dia,8,2), $st['TD.BGBlue']);
                    $h->cc++;
                }

                $h->xls->write($h->rr, $h->cc, "Tot", $st['TD.BGBlue']);
                $h->cc++;
            }

        }


        $i=0;
        foreach($alumnos as $alumno){
            $h->nueva_linea();
            $td = ($i%2 == 0 ? 'Par' : '');
            $h->xls->write($h->rr, $h->cc, $i+1, $st['TD.BGBlue']);$h->cc++;
            $nombre = $alumno->ap . ' ' . $alumno->am . ', '. $alumno->nombre;
            $h->xls->writeString($h->rr, $h->cc, utf8_decode($alumno->codigo), $st['TD.BGBlue']);$h->cc++;
            $art='';
            if($alumno->curso_articulo!=''){
                $art='{'.$alumno->curso_articulo.'}';
            }

            $h->xls->write($h->rr, $h->cc, utf8_decode($nombre).' '.$art, $st['TD.BGBlue']);$h->cc++;
            $h->xls->write($h->rr, $h->cc, "", $st['TD.BGBlue']);$h->cc++;
            $h->xls->write($h->rr, $h->cc, "", $st['TD.BGBlue']);
            $h->xls->mergeCells($h->rr, $h->cc-2, $h->rr, $h->cc);
            $h->cc++;
                
            foreach($fechasHdr as $a=>$anio){
                foreach($anio as $m=>$mes){
                    $h->cc++;
                    foreach($mes as $f=>$fecha){
                        $ast=$this->asistencias[$alumno->id][substr($fecha->dia,0,4)][substr($fecha->dia,5,2)][substr($fecha->dia,8,2)];
                        $valor = $ast->valor;
                        if($valor!='')
                        $valor= $valor == 'FTA' ? 'F' : 'A';

                        $h->xls->write($h->rr, $h->cc, $valor, $st['TD.BGBlue']);$h->cc++;
                    }
                    $h->xls->write($h->rr, $h->cc, "", $st['TD.BGBlue']);$h->cc++;
                        
                }
            }
            $i++;
        }

        $h->cc_max=$h->cc;

        $h->nueva_linea();
        $h->cc += 2;
        $h->xls->write($h->rr, $h->cc, "ESTA LISTA ES PARA USO ", $st['TD.Red']);$h->cc++;
        $h->xls->write($h->rr, $h->cc, "", $st['TD.Red']);$h->cc++;
        $h->xls->write($h->rr, $h->cc, "", $st['TD.Red']);
        $h->xls->mergeCells($h->rr, $h->cc-2, $h->rr, $h->cc);
        $h->cc += 18;
        $h->xls->write($h->rr, $h->cc, "FECHA DE EMISION", $st['TD.BGBlue']);
        for($i = 0;$i<=10;$i++){
            $h->cc++;
            $h->xls->write($h->rr, $h->cc, "", $st['TD.BGBlue']);
        }
        $h->xls->mergeCells($h->rr, $h->cc-11, $h->rr, $h->cc);





        $h->nueva_linea();
        $h->cc +=2;
        $h->xls->write($h->rr, $h->cc, "PERSONAL DEL PROFESOR", $st['TD.Red']);$h->cc++;
        $h->xls->write($h->rr, $h->cc, "", $st['TD.Red']);$h->cc++;
        $h->xls->write($h->rr, $h->cc, "", $st['TD.Red']);
        $h->xls->mergeCells($h->rr, $h->cc-2, $h->rr, $h->cc);
        $h->cc += 18;

        $now = new DateTime();

        $h->xls->write($h->rr, $h->cc, $now->format("d")."/".Utils::mes_espanol($now->format("m"))."/".$now->format("Y"), $st['TH.BGBlue']);
        for($i = 0;$i<=10;$i++){
            $h->cc++;
            $h->xls->write($h->rr, $h->cc, "", $st['TH.BGBlue']);
        }
        $h->xls->mergeCells($h->rr, $h->cc-11, $h->rr, $h->cc);


        if($h->cc_max < $h->cc)
        $h->cc_max = $h->cc + 1;
            
        $h->rr_max=$h->rr;

    }
    public function encabezado(&$h,$curso,$nombre) {
        $grupo               =    $curso->grupo();
        $ciclo               =    $grupo->ciclo();
        $materia           =    $curso->materia();
        $profesor         =    $curso->profesor();

        $st = $this->getEstilos();
        $template=new Template();
        $h->xls->write($h->rr, $h->cc, "COLEGIO DE ESTUDIOS CIENTIFICOS Y TECNOLOGICOS DEL ESTADO DE JALISCO", $st['H1.Blue']);
        for($i=0; $i<20; $i++){
            $h->cc++;
            $h->xls->write($h->rr, $h->cc, "", $st['H1.Blue']);
            
        }
        $h->xls->mergeCells($h->rr, $h->cc-20, $h->rr, $h->cc);
        $h->nueva_linea();
        $h->xls->write($h->rr, $h->cc, "LISTA DE ASISTENCIA", $st['H1.Blue']);
        for($i=0; $i<20; $i++){
            $h->cc++;
            $h->xls->write($h->rr, $h->cc, "", $st['H1.Blue']);
            
        }
        $h->xls->mergeCells($h->rr, $h->cc - 20, $h->rr, $h->cc );
        $h->nueva_linea();
        $h->xls->insertBitmap($h->rr, $h->cc, getcwd() . '/public/img/sp5/lista.bmp', 0, 0, 1.5, 1);

        $h->nueva_linea();
        $h->nueva_linea();
        $h->nueva_linea();
        $h->xls->write($h->rr, $h->cc, "GRUPO", $st['TH.BGBlue']);$h->cc++;
        $h->xls->write($h->rr, $h->cc, "PERIODO", $st['TH.BGBlue']);$h->cc++;
        $h->xls->write($h->rr, $h->cc, "CLAVE", $st['TH.BGBlue']);$h->cc++;
        $h->xls->write($h->rr, $h->cc, "MATERIA", $st['TH.BGBlue']);$h->cc++;
        $h->xls->write($h->rr, $h->cc, "", $st['TH.BGBlue']);
        $h->xls->mergeCells($h->rr, $h->cc - 1, $h->rr, $h->cc);


        $h->nueva_linea();
        $h->xls->write($h->rr, $h->cc, $grupo->verInfo(), $st['TD.BGBlue']);$h->cc++;
        $h->xls->write($h->rr, $h->cc, $ciclo->numero, $st['TD.BGBlue']);$h->cc++;
        $h->xls->write($h->rr, $h->cc, $materia->clave, $st['TD.BGBlue']);$h->cc++;
        $h->xls->write($h->rr, $h->cc, utf8_decode($materia->nombre), $st['TD.BGBlue']);$h->cc++;
        $h->xls->write($h->rr, $h->cc, "", $st['TH.BGBlue']);
        $h->xls->mergeCells($h->rr, $h->cc-1, $h->rr, $h->cc);


        $h->nueva_linea();
        $h->xls->write($h->rr, $h->cc, "PLANTEL", $st['TH.BGBlue']);$h->cc++;
        $h->xls->write($h->rr, $h->cc, "", $st['TH.BGBlue']);$h->cc++;
        $h->xls->write($h->rr, $h->cc, "", $st['TH.BGBlue']);
        $h->xls->mergeCells($h->rr, $h->cc-2, $h->rr, $h->cc);
        $h->cc++;
        $h->xls->write($h->rr, $h->cc, "CARRERA", $st['TH.BGBlue']);$h->cc++;
        $h->xls->write($h->rr, $h->cc, "", $st['TH.BGBlue']);
        $h->xls->mergeCells($h->rr, $h->cc-1, $h->rr, $h->cc);


        $h->nueva_linea();
        $h->xls->write($h->rr, $h->cc, "HEKADEMOS", $st['TD.BGBlue']);$h->cc++;
        $h->xls->write($h->rr, $h->cc, "", $st['TD.BGBlue']);$h->cc++;
        $h->xls->write($h->rr, $h->cc, "", $st['TD.BGBlue']);
        $h->xls->mergeCells($h->rr, $h->cc - 2, $h->rr, $h->cc );
        $h->cc++;
        $h->xls->write($h->rr, $h->cc, utf8_decode($grupo->verOferta()), $st['TD.BGBlue']);$h->cc++;
        $h->xls->write($h->rr, $h->cc, "", $st['TD.BGBlue']);
        $h->xls->mergeCells($h->rr, $h->cc-1, $h->rr, $h->cc);


        $h->nueva_linea();
        $h->xls->write($h->rr, $h->cc, "NOMBRE DEL PROFESOR", $st['TH.BGBlue']);$h->cc++;
        $h->xls->write($h->rr, $h->cc, "", $st['TD.BGBlue']);$h->cc++;
        $h->xls->write($h->rr, $h->cc, "", $st['TD.BGBlue']);$h->cc++;
        $h->xls->write($h->rr, $h->cc, "", $st['TD.BGBlue']);
        $h->xls->mergeCells($h->rr, $h->cc - 3, $h->rr, $h->cc );
        $h->cc++;
        $h->xls->write($h->rr, $h->cc, "SEMESTRE", $st['TH.BGBlue']);


        $nombre = $profesor->ap . ' ' . $profesor->am . ', '. $profesor->nombre;
            
        $h->nueva_linea();
        $h->xls->write($h->rr, $h->cc, utf8_decode($nombre), $st['TD.BGBlue']);$h->cc++;
        $h->xls->write($h->rr, $h->cc, "", $st['TD.BGBlue']);$h->cc++;
        $h->xls->write($h->rr, $h->cc, "", $st['TD.BGBlue']);$h->cc++;
        $h->xls->write($h->rr, $h->cc, "", $st['TD.BGBlue']);
        $h->xls->mergeCells($h->rr, $h->cc - 3, $h->rr, $h->cc );
        $h->cc++;
        $h->xls->write($h->rr, $h->cc, $materia->semestre, $st['TD.BGBlue']);

        $h->nueva_linea();
        $h->xls->write($h->rr, $h->cc, "", $st['TH.BGBlue']);$h->cc++;
        $h->xls->write($h->rr, $h->cc, "", $st['TH.BGBlue']);$h->cc++;
        $h->xls->write($h->rr, $h->cc, "", $st['TH.BGBlue']);$h->cc++;
        $h->xls->write($h->rr, $h->cc, "", $st['TH.BGBlue']);$h->cc++;
        $h->xls->write($h->rr, $h->cc, "", $st['TH.BGBlue']);
        $h->xls->mergeCells($h->rr, $h->cc-4, $h->rr, $h->cc);

        /*
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
            $h->xls->write($h->rr, $h->cc, 'LISTA DE ASISTENCIAS DE '.utf8_decode(strToUpper($nombre)), $st['H4']);
            $h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc + 6);
            $h->nueva_linea();
            $h->xls->write($h->rr, $h->cc,utf8_decode(strToUpper($ciclo->numero.', '.$grupo->grado.$grupo->letra.' '.$turno.', '.$materia->nombre)), $st['H4']);
            $h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc + 6);
            $h->nueva_linea();
            $h->xls->write($h->rr, $h->cc, utf8_decode(strToUpper($grupo->verOferta('nombre'))), $st['H4']);
            $h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc + 6);
            */
        $h->nueva_linea();
        $h->xls->repeatRows(0,13);
        $h->xls->freezePanes(array(13, 0));
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
        $h->xls->setPrintScale(60);
        $h->xls->setZoom(60);
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
