<?php
class XLSProfesores extends Reporte{
    private $condicion;
    private $ciclo;

    public function XLSProfesores($ciclo_id){
        $controlador='profesores';
        $accion='index';
        $b = new Busqueda($controlador, $accion);
        // genera las condiciones
        $b->establecerCondicion(
            'nombre',
            "CONCAT(nombre, ' ', ap, ' ', am) LIKE '%" . $b->campo('nombre') . "%' "
        );

        $c = $b->condicion();

        // ejecuta la consulta
        $profesores = new Profesores();

        $profesores = $profesores->find(
                            'conditions: ' . ($c == "" ? "1" : $c),
                            'order: ap, am, nombre '
                              );



        $ciclo=new Ciclos();
        $ciclo=$ciclo->find_first($ciclo_id);
        $this->Reporte('Profesores ' . $ciclo->numero . '  ' .($c != '' ? ' FILTRADO' : '' ). '.xls');
        if(count($profesores)>0)
        $this->hoja($profesores);
        else{
            $hojas = $this->getHojas();
            if(count($hojas)==0)
                $this->hoja_vacia();
        }
    }

    public function hoja($alumnos){
        $nombre = 'Profesores';
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
        $this->contenido($h, $alumnos);
        $this->propiedades($h);
    }

    public function hoja_vacia(){
        $nombre = 'SP5';
        $h = $this->agregar_hoja($nombre);
        $h->xls->write(0, 0, "No hay registros que coincidan con esas condiciones");
    }

    public function contenido(&$h, $profesores){
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
        foreach($profesores as $profesor){
            $n++;
            $td = ($n%2 == 0 ? 'Par' : '');
            $h->xls->write($h->rr, $h->cc, $profesor->id); $h->cc++;
            $h->xls->write($h->rr, $h->cc, utf8_decode($profesor->codigo), $st['TD' . $td . '.Normal']); $h->cc++;
            $h->xls->write($h->rr, $h->cc, utf8_decode($profesor->nombre), $st['TD' . $td . '.Normal']);$h->cc++;
            $h->xls->write($h->rr, $h->cc, utf8_decode($profesor->ap), $st['TD' . $td . '.Normal']);$h->cc++;
            $h->xls->write($h->rr, $h->cc, utf8_decode($profesor->am), $st['TD' . $td . '.Normal']);$h->cc++;
            $h->xls->write($h->rr, $h->cc, utf8_decode($profesor->domicilio), $st['TD' . $td . '.Normal']);$h->cc++;
            $h->xls->writeString($h->rr, $h->cc, $profesor->tel, $st['TD' . $td . '.Normal']);$h->cc++;
            $h->xls->writeString($h->rr, $h->cc, $profesor->cel, $st['TD' . $td . '.Normal']);$h->cc++;
            $h->xls->write($h->rr, $h->cc, utf8_decode($profesor->mail), $st['TD' . $td . '.Normal']);$h->cc++;
            $h->xls->write($h->rr, $h->cc, utf8_decode($profesor->rfc), $st['TD' . $td . '.Normal']);$h->cc++;
            $h->xls->write($h->rr, $h->cc, utf8_decode($profesor->curp), $st['TD' . $td . '.Normal']);$h->cc++;
            $h->xls->write($h->rr, $h->cc, $profesor->fnacimiento, $st['TD' . $td . '.Normal']);$h->cc++;
            $h->xls->write($h->rr, $h->cc, $profesor->sexo, $st['TD' . $td . '.Normal']);$h->cc++;
            $h->cc++;
            $h->nueva_linea();
        }


        $h->nueva_linea();
        $h->rr_max = $h->rr;
    }

    public function encabezado(&$h){
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
        $h->xls->write($h->rr, $h->cc, 'PROFESORES', $st['H4']);
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

}
?>