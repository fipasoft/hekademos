<?php
class XLSTutoresgrupos extends Reporte {
    private $condicion;
    private $ciclo;
    private $profesor;

    public function XLSTutoresgrupos($ciclo_id) {
        $ciclos = new Ciclos();
        $ciclo = $ciclos->find(intval($ciclo_id,10));
        $this->ciclo = $ciclo;
        $this->condicion = "";

            if($this->ciclo->id!=""){
                // busqueda
                $b = new Busqueda("tutoresgrupo", "index");
                if(trim($b->campo('tutor'))!=''){
                    $b->establecerCondicion('tutor', "CONCAT(profesores.nombre, ' ', profesores.ap, ' ', profesores.am) " .
                    "LIKE '%" . $b->campo('tutor') . "%'  OR CONCAT(profesores.ap, ' ', profesores.am, ' ', profesores.nombre) " .
                    "LIKE '%" . $b->campo('tutor') . "%' ");
                }else{
                    $b->establecerCondicion('tutor','');
                }

                if(trim($b->campo('codigo'))!=''){
                    $b->establecerCondicion('codigo', "profesores.codigo " .
                    "LIKE '%" . $b->campo('codigo') . "%' ");
                }else{
                    $b->establecerCondicion('codigo','');
                }

                $b->campos();

                // genera las condiciones
                $c2 = $c = $b->condicion(array('oferta_id'));
                $c .= ($c == '' ? '' : 'AND ') . "ciclos_id = '" . $this->ciclo->id . "'";

                // cuenta todos los registros
                $Grupos = new Grupos();
                if(trim($b->campo('tutor'))!='' || trim($b->campo('codigo'))!=''){
                $from = "grupos " .
                "INNER JOIN tutoresgrupos ON grupos.id = tutoresgrupos.grupos_id " .
                "INNER JOIN profesores ON tutoresgrupos.profesores_id  = profesores.id ";

                $registros = $Grupos->count_by_sql("SELECT COUNT(*) " .
                "FROM " . $from .
                "WHERE ".
                 ($c == '' ? '' :  $c));
                }else{
                $registros = $Grupos->count(($c == '' ? '1' :  $c));

                }

                if($registros==0){
                    $this->Reporte('Tutores de grupo.xls');
                    $this->hoja_vacia();
                }else{

                if(trim($b->campo('tutor'))!='' || trim($b->campo('codigo'))!=''){
                // ejecuta la consulta
                $grupos = $Grupos->find_all_by_sql(
                                    "SELECT grupos.* FROM grupos ".$from." WHERE ".
                                    ($c == "" ? "1" : $c)." ".
                                    'ORDER BY  grupos.turno, grupos.grado, grupos.letra '
                                      );
                }else{
                $grupos = $Grupos->find(
                                    ($c == "" ? "1" : $c)." ".
                                    'ORDER BY  grupos.turno, grupos.grado, grupos.letra '
                                     );

                }
            $this->Reporte('Tutores de grupo ' .$this->ciclo->numero.($c2 != '' ? ' FILTRADO' : '' ).'.xls');
            $this->hoja($this->ciclo->numero,$grupos);
            }
            }else{
            $this->Reporte('Tutores de grupo.xls');
            $this->hoja_vacia();
            }
    }

    public function hoja($nombre,$grupos) {
        $hojas = $this->getHojas();
        if (array_key_exists($nombre, $hojas)) {
            $h = $hojas[$nombre];
        } else {
            $cols = array (
                3,
                10,
                25,
                25,
                40
            );
            $h = $this->agregar_hoja($nombre, null, $cols);
            $h->cc_max = 8;
        }

        $this->contenido($h,$grupos);
        $this->propiedades($h);
    }

    public function hoja_vacia() {
        $nombre = 'SP5';
        $h = $this->agregar_hoja($nombre);
        $h->xls->write(0, 0, "No hay registros que coincidan con esas condiciones");
    }

    public function contenido(&$h,$grupos) {
        $this->encabezado($h);
        $st = $this->getEstilos();
        $salto='
        ';

        $h->nueva_linea();
        $h->xls->write($h->rr, $h->cc, '#', $st['TD.BGYellow']); $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'Grupo', $st['TD.BGYellow']); $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'Turno', $st['TD.BGYellow']); $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'Oferta', $st['TD.BGYellow']); $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'Tutor', $st['TD.BGYellow']); $h->cc++;
        $h->cc_max=$h->cc;

        $n = 0;
        $h->nueva_linea();
        foreach($grupos as $grupo){
            $n++;
            $td = ($n%2 == 0 ? 'Par' : '');
            $h->xls->write($h->rr, $h->cc, $n, $st['TD' . $td . '.NormalCenter']); $h->cc++;
            $h->xls->write($h->rr, $h->cc, $grupo->grado.$grupo->letra, $st['TD' . $td . '.NormalCenter']); $h->cc++;
            $h->xls->write($h->rr, $h->cc, $grupo->verTurno(), $st['TD' . $td . '.NormalCenter']); $h->cc++;
            $h->xls->write($h->rr, $h->cc, $grupo->verOferta(), $st['TD' . $td . '.NormalCenter']); $h->cc++;
            $encargados = $grupo->tutores();
            if(count($encargados) > 0){
                $linea='';
                 foreach($encargados as $encargado){
                     $linea.="*".utf8_decode($encargado->nombre()).$salto;
                 }
                     $h->xls->write($h->rr, $h->cc, $linea, $st['TD' . $td . '.NormalCenter']); $h->cc++;

             }else{
                $h->xls->write($h->rr, $h->cc, "Sin tutor", $st['TD' . $td . '.NormalCenter']); $h->cc++;
             }

            $h->nueva_linea();
        }

        $h->rr_max=$h->rr;


        }

    public function encabezado($h) {
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
        $h->xls->write($h->rr, $h->cc, 'TUTORES DE GRUPO  ', $st['H4']);
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc + 6);
        $h->nueva_linea();
        $h->xls->write($h->rr, $h->cc, 'CICLO '.$this->ciclo->numero, $st['H4']);
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc + 6);
        $h->xls->repeatRows(0,6);
        $h->xls->freezePanes(array(6, 0));
        $h->nueva_linea();
    }

    public function propiedades(& $h) {
        $h->xls->centerHorizontally();
        $h->xls->hideGridlines();
        $h->xls->printArea(0, 0, $h->rr_max, $h->cc_max);
        $h->xls->setFooter(utf8_decode("Coordinación academica ") . date("j/n/Y H:i"), 0);
        $h->xls->setPortrait();
        $h->xls->setMargins_LR(0.2);
        $h->xls->setMargins_TB(0.27);
        $h->xls->setPaper(3);
        $h->xls->setPrintScale(85);
        $h->xls->setZoom(80);
    }
}
?>