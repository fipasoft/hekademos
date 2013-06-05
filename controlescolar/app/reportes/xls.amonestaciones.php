<?php
class XLSAmonestaciones extends Reporte{
    private $ciclo;

    public function XLSAmonestaciones($ciclo_id){
        $controlador='amonestaciones';
        $accion='index';

        $b = new Busqueda($controlador, $accion);
        $b->establecerCondicion('fecha', "fecha = '" . Utils::convierteFechaMySql($b->campo('fecha')) . "' ");
        $b->establecerCondicion('aestado_id', "aestado_id = '" . $b->campo('aestado_id') . "' ");
        $b->establecerCondicion('acategoria_id', "acategoria_id = '" . $b->campo('acategoria_id') . "' ");
        $b->establecerCondicion('nombre', "CONCAT(TRIM(alumnos.nombre), ' ', TRIM(ap), ' ', TRIM(am)) LIKE '%" . $b->campo('nombre') . "%' OR
                                            CONCAT(TRIM(ap), ' ', TRIM(am), ' ', TRIM(alumnos.nombre)) LIKE '%" . $b->campo('nombre') . "%' ");
        
        $b->establecerCondicion('oferta_id', "grupos.oferta_id  = '" . $b->campo('oferta_id') . "' ");
        $b->campos();
        // genera las condiciones
        $c = $b->condicion();
        $amonestaciones = new Amonestacion();
        $amonestaciones=$amonestaciones->find_all_by_sql(
                            "SELECT amonestacion.*,alumnos.id as alumno_id FROM
                                amonestados
                                INNER JOIN alumnos ON amonestados.alumnos_id = alumnos.id
                                INNER JOIN amonestacion ON amonestados.amonestacion_id = amonestacion.id
                                INNER JOIN alumnosgrupo ON alumnos.id = alumnosgrupo.alumnos_id
                                INNER JOIN grupos ON alumnosgrupo.grupos_id = grupos.id
                                WHERE ".
        ($c == "" ? "amonestacion.ciclos_id='".$ciclo_id."' AND grupos.ciclos_id='".$ciclo_id."'" : "amonestacion.ciclos_id='".$ciclo_id."' AND grupos.ciclos_id='".$ciclo_id."' AND ".$c)." ORDER BY fecha DESC ");
        
                            $ciclo=new Ciclos();
                            $this->ciclo=$ciclo=$ciclo->find($ciclo_id);
                            $this->Reporte('Amonestaciones ' . $ciclo->numero . '  ' . ($c != '' ? ' FILTRADO' : '' ). '.xls');
                                
                            if(count($amonestaciones) > 0){
                                $pmes = $this->pormes($amonestaciones);
                                foreach($pmes as $anio=> $meses){
                                    foreach($meses as $mes => $ams){
                                        $n = Utils::mes_espanol($mes)." ".$anio;
                                        $this->hoja($n,$ams);
                                    }
                                }
                            }else{
                                $this->hoja_vacia();
                            }

                            $hojas = $this->getHojas();
                            if(count($hojas)==0)
                            $this->hoja_vacia();


    }
    
    public function pormes($amonestaciones){
        $pmes = array();
        foreach($amonestaciones as $a){
            $fecha = new Datetime($a->fecha);
            $pmes[$fecha->format("Y")][$fecha->format("m")][] = $a;
        }
        
        return $pmes;
    }

    public function hoja($nombre,$amonestaciones){
        $hojas = $this->getHojas();
        if(array_key_exists($nombre, $hojas)){
            $h = $hojas[$nombre];
        }else{
            $cols = array( 20, 30, 20, 50, 50, 50);
            $h = $this->agregar_hoja($nombre, null, $cols);
            $h->cc_max = 7;
        }
        $this->contenido($h, $nombre, $amonestaciones);
        $this->propiedades($h);
    }

    public function hoja_vacia(){
        $nombre = 'HEKADEMOS';
        $h = $this->agregar_hoja($nombre);
        $h->xls->write(0, 0, "No hay registros que coincidan con esas condiciones");
    }

    public function contenido(&$h,$n,$amonestaciones){
        $this->encabezado($h,$n);
        $st = $this->getEstilos();
        $h->xls->write($h->rr, $h->cc, 'Fecha', $st['TD.BGYellow']); $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'Estado', $st['TD.BGYellow']); $h->cc++;
        $h->xls->write($h->rr, $h->cc, utf8_decode('Código'), $st['TD.BGYellow']); $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'Nombre', $st['TD.BGYellow']); $h->cc++;
        $h->xls->write($h->rr, $h->cc, utf8_decode('Descripción'), $st['TD.BGYellow']); $h->cc++;
        $h->xls->write($h->rr, $h->cc, utf8_decode('Infracción'), $st['TD.BGYellow']); $h->cc++;
        $h->nueva_linea();
        $n = 0;
        foreach($amonestaciones as $a){
            //var_dump($a);exit;
            $alumno = new Alumnos();
            $alumno = $alumno->find($a->alumno_id);
            //var_dump($alumno);exit;
            $e = $a->estado();
            $infracciones_str = '';
            $salto = '
';
            $infracciones = new Reglamento();
            $infracciones = $infracciones->find_all_by_sql('SELECT reglamento.* FROM '.
                'amonestacion INNER JOIN infringe ON amonestacion.id = infringe.amonestacion_id '.
                'INNER JOIN reglamento on infringe.reglamento_id = reglamento.id '.
                'WHERE amonestacion.id = '.$a->id);
            if(count($infracciones) > 0){
            foreach($infracciones as $infraccion){
                if($infraccion->id != ''){
                    $reglamento = new Reglamentos();
                    $articulo = new Articulo();
                    $reglamento = $reglamento->find($infraccion->reglamentos_id);
                    $articulo = $articulo->find($infraccion->articulo_id);
                    $infracciones_str .= 'Articulo '.$articulo->numero.' del reglamento '.$reglamento->nombre.$salto;
                }
            }
            }else{
                $infracciones_str = 'No asignado.';
            }
            $n++;
            $td = ($n%2 == 0 ? 'Par' : '');
            $h->xls->write($h->rr, $h->cc, Utils::fecha_espanol($a->fecha), $st['TD' . $td . '.Normal']);$h->cc++;
            $h->xls->write($h->rr, $h->cc, $e->nombre, $st['TD' . $td . '.Normal']);$h->cc++;
            $h->xls->writeString($h->rr, $h->cc,$alumno->codigo, $st['TD' . $td . '.Normal']); $h->cc++;
            $h->xls->write($h->rr, $h->cc, utf8_decode($alumno->nombre()), $st['TD' . $td . '.Normal']);$h->cc++;
            $h->xls->write($h->rr, $h->cc, utf8_decode($a->descripcion), $st['TD' . $td . '.Normal']);$h->cc++;
            $h->xls->write($h->rr, $h->cc, utf8_decode($infracciones_str), $st['TD' . $td . '.Normal']);$h->cc++;
            $h->cc++;
            $h->nueva_linea();
        }

        $h->nueva_linea();
        $h->rr_max = $h->rr;
    }

    public function encabezado(&$h,$d){
        $st = $this->getEstilos();
        $template=new Template();
        $h->nueva_linea();
        $h->xls->insertBitmap($h->rr, $h->cc, getcwd() . '/public/img/sp5/udg.bmp', 0, 15, 1, 1);
        $h->cc += 6;
        $h->xls->insertBitmap($h->rr, $h->cc, getcwd() . '/public/img/sp5/lp5.bmp', 160, 15, 1, 1);
        $h->nueva_linea();
        $h->xls->write($h->rr, $h->cc,  $template->excel_escuela(), $st['H3']);
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc + 6);
        $h->nueva_linea();
        $h->xls->write($h->rr, $h->cc, $template->excel_subtitulo(), $st['H4']);
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc + 6);
        $h->nueva_linea();
        $h->xls->write($h->rr, $h->cc, 'AMONESTACIONES DE '.strtoupper($d),$st['H4']);
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc + 6);
        $h->nueva_linea();
        $h->xls->write($h->rr, $h->cc, "CICLO ".$this->ciclo->numero, $st['H4']);
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc + 6);
        $h->nueva_linea();
        $h->nueva_linea();
        $h->nueva_linea();
        $h->xls->repeatRows(0,8);
        $h->xls->freezePanes(array(9, 0));
    }

    public function propiedades(&$h){
        $h->xls->centerHorizontally();
        $h->xls->hideGridlines();
        $h->xls->printArea(0, 1, $h->rr_max, $h->cc_max);
        $h->xls->setFooter("HEKADEMOS 2 ".date("j/n/Y H:i"), 0);
        $h->xls->setLandscape();
        $h->xls->setMargins_LR(0.2);
        $h->xls->setMargins_TB(0.27);
        $h->xls->setPaper(3);
        $h->xls->setPrintScale(80);
        $h->xls->setZoom(80);
    }

}
?>