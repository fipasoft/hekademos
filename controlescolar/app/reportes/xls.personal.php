<?php
class XLSPersonal extends Reporte{
    private $condicion;

    public function XLSPersonal(){
        $controlador='personal';
        $accion='index';
        $b = new Busqueda($controlador, $accion);
        // genera las condiciones
        $b->establecerCondicion(
            'nombre',
            "CONCAT(nombre, ' ', ap, ' ', am) LIKE '%" . $b->campo('nombre') . "%' "
        );

        $c = $b->condicion(array("tipopersonal_id"));

        // ejecuta la consulta
        $personal = new Personal();

        $personal = $personal->find(
                            'conditions: ' . ($c == "" ? "1" : $c),
                            'order: ap, am, nombre '
                              );



        $this->Reporte('Personal ' .($c != '' ? ' FILTRADO' : '' ). '.xls');
        $elementos=array();

        foreach($personal as $p){
            $elementos[$p->tipopersonal_id][]=$p;
        }

        if(count($personal)>0)
        foreach($elementos as $k=>$e){
            $tipo=new Tipopersonal();
            $tipo=$tipo->find($k);
            $nombre=$tipo->nombre;
            $this->hoja($e,$nombre);
        }

        else{
            $hojas = $this->getHojas();
            if(count($hojas)==0)
                $this->hoja_vacia();
        }
    }

    public function hoja($elementos,$nombre){
        $hojas = $this->getHojas();
        if(array_key_exists($nombre, $hojas)){
            $h = $hojas[$nombre];
        }else{
            $cols = array( 0.1, 10, 30, 20, 20,
                           35, 10, 15, 35, 15, 20, 12, 5
                    );
            $h = $this->agregar_hoja($nombre, null, $cols);
            $h->cc_max = 13;
        }
        $this->contenido($h, $elementos);
        $this->propiedades($h);
    }

    public function hoja_vacia(){
        $nombre = 'HEKADEMOS';
        $h = $this->agregar_hoja($nombre);
        $h->xls->write(0, 0, "No hay registros que coincidan con esas condiciones");
    }

    public function contenido(&$h, $elementos){
        $this->encabezado($h);
        $st = $this->getEstilos();
        $h->xls->write($h->rr, $h->cc, 'ID'); $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'Codigo', $st['TD.BGYellow']); $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'Nombre', $st['TD.BGYellow']); $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'ap', $st['TD.BGYellow']); $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'am', $st['TD.BGYellow']); $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'domicilio', $st['TD.BGYellow']); $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'tel', $st['TD.BGYellow']); $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'cel', $st['TD.BGYellow']); $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'mail', $st['TD.BGYellow']); $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'rfc', $st['TD.BGYellow']); $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'curp', $st['TD.BGYellow']); $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'fnacimiento', $st['TD.BGYellow']); $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'sexo', $st['TD.BGYellow']); $h->cc++;
        $h->nueva_linea();
        $n = 0;
        foreach($elementos as $personal){
            $n++;
            $td = ($n%2 == 0 ? 'Par' : '');
            $h->xls->write($h->rr, $h->cc, $personal->id); $h->cc++;
            $h->xls->write($h->rr, $h->cc, utf8_decode($personal->codigo), $st['TD' . $td . '.Normal']); $h->cc++;
            $h->xls->write($h->rr, $h->cc, utf8_decode($personal->nombre), $st['TD' . $td . '.Normal']);$h->cc++;
            $h->xls->write($h->rr, $h->cc, utf8_decode($personal->ap), $st['TD' . $td . '.Normal']);$h->cc++;
            $h->xls->write($h->rr, $h->cc, utf8_decode($personal->am), $st['TD' . $td . '.Normal']);$h->cc++;
            $h->xls->write($h->rr, $h->cc, utf8_decode($personal->domicilio), $st['TD' . $td . '.Normal']);$h->cc++;
            $h->xls->writeString($h->rr, $h->cc, $personal->tel, $st['TD' . $td . '.Normal']);$h->cc++;
            $h->xls->writeString($h->rr, $h->cc, $personal->cel, $st['TD' . $td . '.Normal']);$h->cc++;
            $h->xls->write($h->rr, $h->cc, utf8_decode($personal->mail), $st['TD' . $td . '.Normal']);$h->cc++;
            $h->xls->write($h->rr, $h->cc, utf8_decode($personal->rfc), $st['TD' . $td . '.Normal']);$h->cc++;
            $h->xls->write($h->rr, $h->cc, utf8_decode($personal->curp), $st['TD' . $td . '.Normal']);$h->cc++;
            $h->xls->write($h->rr, $h->cc, $personal->fnacimiento, $st['TD' . $td . '.Normal']);$h->cc++;
            $h->xls->write($h->rr, $h->cc, $personal->sexo, $st['TD' . $td . '.Normal']);$h->cc++;
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
        $h->xls->write($h->rr, $h->cc, 'PERSONAL', $st['H4']);
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