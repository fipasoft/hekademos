<?php
class XLSHistorial extends Reporte{
    private $condicion;
    private $ciclo;

    public function XLSHistorial($ciclo_id){
        $controlador='historial';
        $accion='index';
        $b = new Busqueda($controlador, $accion);
        $b->establecerCondicion('saved_at', "saved_at  LIKE '" . Utils::convierteFechaMySql(trim($b->campo('saved_at'))) . "%' ");
        // genera las condiciones
        $this->condicion =$c = $b->condicion();

        if($b->campo('controlador')==''){
            $historial=new Historial();
            $controladores=$historial->obtenControladores();
            $cons='';
            foreach($controladores as $cr){
                $cons.=" controlador LIKE '%$cr%' OR ";

            }
        $cons=substr($cons,0,strlen($cons)-3);
        $c.= ($c == "" ? "" : "AND " . "") ." ($cons) ";
        $this->condicion =$c;
        }else{
        $b->establecerCondicion('controlador', "controlador LIKE '".$b->campo('controlador')."'");
        }


        $ciclo=new Ciclos();
        $this->ciclo=$ciclo=$ciclo->find_first($ciclo_id);
        $this->Reporte('Historial ' . $ciclo->numero . '  ' . ($c != '' ? ' FILTRADO' : '' ). '.xls');

        $fechas=$this->datos_historial();
        foreach($fechas as $llave=>$f){
            foreach($f as $k=>$d){

                $this->hoja(utf8_decode(strToUpper(Utils::mes_espanol($k)).' DEL '.$llave),$d);
            }
        }

        if(count($this->getHojas())==0)
            $this->hoja_vacia();
    }

    public function hoja($fecha,$datos){
        $nombre = $fecha;
        $hojas = $this->getHojas();
        if(array_key_exists($nombre, $hojas)){
            $h = $hojas[$nombre];
        }else{
            $cols = array( 0.1, 20, 70, 15, 15,
                           25
                    );
            $h = $this->agregar_hoja($nombre, null, $cols);
            $h->cc_max = 16;
        }
        $this->contenido($h, $datos, $fecha);
        $this->propiedades($h);
    }

    public function hoja_vacia(){
        $nombre = 'SP5';
        $h = $this->agregar_hoja($nombre);
        $h->xls->write(0, 0, "No hay registros que coincidan con esas condiciones");
    }

    public function contenido(&$h, $datos,$fecha){

        $st = $this->getEstilos();

        $this->encabezado($h,$fecha);
        $h->xls->write($h->rr, $h->cc, 'ID'); $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'Usuario', $st['TD.BGYellow']); $h->cc++;
        $h->xls->write($h->rr, $h->cc, utf8_decode('Descripción'), $st['TD.BGYellow']); $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'Controlador', $st['TD.BGYellow']); $h->cc++;
        $h->xls->write($h->rr, $h->cc, utf8_decode('Acción'), $st['TD.BGYellow']); $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'Fecha', $st['TD.BGYellow']); $h->cc++;
        $h->nueva_linea();
        $n = 0;

            foreach($datos as $his){
            $n++;
            $td = ($n%2 == 0 ? 'Par' : '');
            $h->xls->write($h->rr, $h->cc, $his->id); $h->cc++;
            $h->xls->write($h->rr, $h->cc,utf8_decode($his->usuario), $st['TD' . $td . '.Normal']); $h->cc++;
            $h->xls->write($h->rr, $h->cc, utf8_decode($his->descripcion), $st['TD' . $td . '.Normal']);$h->cc++;
            $h->xls->write($h->rr, $h->cc, utf8_decode($his->controlador), $st['TD' . $td . '.Normal']);$h->cc++;
            $h->xls->write($h->rr, $h->cc, utf8_decode($his->accion), $st['TD' . $td . '.Normal']);$h->cc++;
            $h->xls->write($h->rr, $h->cc, utf8_decode(Utils::fecha_espanol($his->saved_at).' '.substr($his->saved_at,10)), $st['TD' . $td . '.Normal']);$h->cc++;

            $h->nueva_linea();
            }


        $h->nueva_linea();
        $h->rr_max = $h->rr;
    }

    public function encabezado(&$h,$fecha){
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
        $h->xls->write($h->rr, $h->cc, 'HISTORIAL DEL MES DE '.$fecha, $st['H4']);
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc + 6);
        $h->nueva_linea();
        $h->xls->write($h->rr, $h->cc,'', $st['H4']);
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
        $h->xls->setFooter("SP5 ".date("j/n/Y H:i"), 0);
        $h->xls->setLandscape();
        $h->xls->setMargins_LR(0.2);
        $h->xls->setMargins_TB(0.27);
        $h->xls->setPaper(3);
        $h->xls->setPrintScale(80);
        $h->xls->setZoom(80);
    }

    public function datos_historial(){
        $c = $this->condicion;
        $ciclo = $this->ciclo;
        $historial = new Historial();

        $historial = $historial->find_all_by_sql(
                            "SELECT " .
                            "* " .
                            "FROM " .
                            "historial " .
                            "WHERE " .
                            "ciclos_id = '" . $ciclo->id . "'" .
                             ($c == "" ? "" : "AND " . $c) . "  ".
                            "ORDER BY " .
                            "saved_at DESC,controlador,accion,usuario "
                               );
        $this->condicion = $c;

        $fecha=array();
        foreach($historial as $h){
            $fecha[substr($h->saved_at,0,4)][substr($h->saved_at,5,2)][]=$h;
        }

        return $fecha;
    }

}
?>