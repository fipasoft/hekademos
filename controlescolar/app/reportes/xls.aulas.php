<?php
class XLSAulas extends Reporte{
    private $condicion;
    private $ciclo;

    public function XLSAulas(){
        $controlador='aulas';
        $accion='index';
        $b = new Busqueda($controlador, $accion);
        $b->campos();
        // genera las condiciones
        $c = $b->condicion();

        // ejecuta la consulta
        $aulas=new Aulas();
        $aulas=$aulas->find(
                            ($c == "" ? "1" : $c)." ORDER BY clave "
        );



        $this->Reporte('Aulas '  .($c != '' ? ' FILTRADO' : '' ). '.xls');
        if(count($aulas)>0)
        $this->hoja($aulas);
        else
            $this->hoja_vacia();

    }

    public function hoja($aulas){
        $nombre = 'Aulas';
        $hojas = $this->getHojas();
        if(array_key_exists($nombre, $hojas)){
            $h = $hojas[$nombre];
        }else{
            $cols = array( 30, 10, 30);
            $h = $this->agregar_hoja($nombre, null, $cols);
            $h->cc_max = 13;
        }
        $this->contenido($h, $aulas);
        $this->propiedades($h);
    }

    public function hoja_vacia(){
        $nombre = 'HEKADEMOS';
        $h = $this->agregar_hoja($nombre);
        $h->xls->write(0, 0, "No hay registros que coincidan con esas condiciones");
    }

    public function contenido(&$h, $aulas){
        $this->encabezado($h);
        $st = $this->getEstilos();
        $h->xls->write($h->rr, $h->cc, '', $st['TD.NoBorder']); $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'Clave', $st['TD.BGYellow']); $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'Nombre', $st['TD.BGYellow']); $h->cc++;
        $h->nueva_linea();
        $n = 0;
        foreach($aulas as $aula){
            $n++;
            $td = ($n%2 == 0 ? 'Par' : '');
            $h->xls->writeString($h->rr, $h->cc, "", $st['TD.NoBorder']);$h->cc++;
            $h->xls->writeString($h->rr, $h->cc, $aula->clave, $st['TD' . $td . '.Normal']);$h->cc++;
            $h->xls->writeString($h->rr, $h->cc, $aula->nombre, $st['TD' . $td . '.Normal']);$h->cc++;
            $h->cc++;
            $h->nueva_linea();
        }


        $h->nueva_linea();
        $h->rr_max = $h->rr;
    }

    public function encabezado(&$h){
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
        $h->xls->write($h->rr, $h->cc, 'Aulas', $st['H4']);
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
        $h->xls->printArea(0, 0, $h->rr_max, $h->cc_max);
        $h->xls->setFooter("HEKADEMOS ".date("j/n/Y H:i"), 0);
        $h->xls->setLandscape();
        $h->xls->setMargins_LR(0.2);
        $h->xls->setMargins_TB(0.27);
        $h->xls->setPaper(3);
        $h->xls->setPrintScale(80);
        $h->xls->setZoom(80);
    }

}
?>