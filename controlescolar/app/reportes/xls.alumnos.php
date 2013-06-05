<?php
class XLSAlumnos extends Reporte{
    private $condicion;
    private $ciclo;

    public function XLSAlumnos($ciclo_id){
        $controlador='alumnos';
        $accion='index';
        $b = new Busqueda($controlador, $accion);
        $b->establecerCondicion('nombre', "CONCAT(TRIM(alumnos.nombre), ' ', TRIM(ap), ' ', TRIM(am)) LIKE '%" . $b->campo('nombre') . "%' ");
        $b->establecerCondicion('situacion', "situaciones.id  = '" . $b->campo('situacion') . "' ");
        $b->establecerCondicion('oferta_id', "grupos.oferta_id  = '" . $b->campo('oferta_id') . "' ");
        // genera las condiciones
        $this->condicion =$c = $b->condicion();

        $ciclo=new Ciclos();
        $this->ciclo=$ciclo=$ciclo->find_first($ciclo_id);
        $this->Reporte('Alumnos ' . $ciclo->numero . '  ' . ($c != '' ? ' FILTRADO' : '' ). '.xls');

            $grupos = $this->datos_grupos();

            if(count($grupos) > 0){
                foreach($grupos as $grupo){
                    if($this->cuenta_alumnos($grupo)>0)
                        $this->hoja($grupo);
                }
            }else{
                $this->hoja_vacia();
            }

            $hojas = $this->getHojas();
            if(count($hojas)==0)
                $this->hoja_vacia();


    }

    public function hoja($grupo){
        $nombre = $grupo['grado']. $grupo['letra'] . $grupo['turno'].' '.$grupo['clave'] ;
        $hojas = $this->getHojas();
        if(array_key_exists($nombre, $hojas)){
            $h = $hojas[$nombre];
        }else{
            $cols = array( 0.1, 10, 30, 20, 20,
                           35, 10, 15, 35, 23, 12, 5, 15, 15, 15, 15
                    );
            $h = $this->agregar_hoja($nombre, null, $cols);
            $h->cc_max = 16;
        }
        $this->contenido($h, $grupo);
        $this->propiedades($h);
    }

    public function hoja_vacia(){
        $nombre = 'HEKADEMOS';
        $h = $this->agregar_hoja($nombre);
        $h->xls->write(0, 0, "No hay registros que coincidan con esas condiciones");
    }

    public function contenido(&$h, $grupo){
        $this->encabezado($h,$grupo);
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
        $h->xls->write($h->rr, $h->cc, 'curp', $st['TD.BGYellow']); $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'fnacimiento', $st['TD.BGYellow']); $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'sexo', $st['TD.BGYellow']); $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'situacion', $st['TD.BGYellow']); $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'admision', $st['TD.BGYellow']); $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'promedio', $st['TD.BGYellow']); $h->cc++;
        $h->nueva_linea();
        $n = 0;
        $alumnos=$this->datos_alumnos($grupo);
        foreach($alumnos as $alumno){
            $n++;
            $td = ($n%2 == 0 ? 'Par' : '');
            $h->xls->write($h->rr, $h->cc, $alumno['id']); $h->cc++;
            $h->xls->writeString($h->rr, $h->cc,$alumno['codigo'], $st['TD' . $td . '.Normal']); $h->cc++;
            $h->xls->write($h->rr, $h->cc, utf8_decode($alumno['nombre']), $st['TD' . $td . '.Normal']);$h->cc++;
            $h->xls->write($h->rr, $h->cc, utf8_decode($alumno['ap']), $st['TD' . $td . '.Normal']);$h->cc++;
            $h->xls->write($h->rr, $h->cc, utf8_decode($alumno['am']), $st['TD' . $td . '.Normal']);$h->cc++;
            $h->xls->write($h->rr, $h->cc, utf8_decode($alumno['domicilio']), $st['TD' . $td . '.Normal']);$h->cc++;
            $h->xls->writeString($h->rr, $h->cc, $alumno['tel'], $st['TD' . $td . '.Normal']);$h->cc++;
            $h->xls->writeString($h->rr, $h->cc, $alumno['cel'], $st['TD' . $td . '.Normal']);$h->cc++;
            $h->xls->write($h->rr, $h->cc, utf8_decode($alumno['mail']), $st['TD' . $td . '.Normal']);$h->cc++;
            $h->xls->write($h->rr, $h->cc, utf8_decode($alumno['curp']), $st['TD' . $td . '.Normal']);$h->cc++;
            $h->xls->write($h->rr, $h->cc, $alumno['fnacimiento'], $st['TD' . $td . '.Normal']);$h->cc++;
            $h->xls->write($h->rr, $h->cc, $alumno['sexo'], $st['TD' . $td . '.Normal']);$h->cc++;
            $h->xls->write($h->rr, $h->cc, $alumno['situacion'], $st['TD' . $td . '.Normal']);$h->cc++;
            $h->xls->write($h->rr, $h->cc, $alumno['admision'], $st['TD' . $td . '.Normal']);$h->cc++;
            $h->xls->write($h->rr, $h->cc, $alumno['promedio'], $st['TD' . $td . '.Normal']);$h->cc++;
            $h->cc++;
            $h->nueva_linea();
        }

        $h->nueva_linea();
        $h->rr_max = $h->rr;
    }

    public function encabezado(&$h,$grupo){
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
        $turno='';
        switch($grupo['turno']){
            case 'M': $turno="MATUTINO"; break;
            case 'V': $turno="VESPERTINO"; break;
            case 'N': $turno="NOCTURNO"; break;
        }
        $h->xls->write($h->rr, $h->cc, 'ALUMNOS DEL '.$grupo['grado'].$grupo['letra'].' '.$turno, $st['H4']);
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc + 6);
        $h->nueva_linea();
        $h->xls->write($h->rr, $h->cc, strToUpper($grupo['nombre']), $st['H4']);
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

    public function datos_grupos(){
        $ciclo = $this->ciclo;
        $grupos = db::raw_connect();

        $from = "grupos " .
                "INNER JOIN oferta ON oferta.id = grupos.oferta_id ";

        $grupos = $grupos->in_query(
                            "SELECT " .
                                "grupos.id, " .
                                "grupos.grado, " .
                                "grupos.letra, " .
                                "grupos.turno, " .
                                "oferta.nombre, ".
                                "oferta.clave ".
                            "FROM " . $from .
                            "WHERE grupos.ciclos_id = '" . $ciclo->id . "' " .
                            "GROUP BY grupos.id " .
                            "ORDER BY grupos.turno, grupos.grado, grupos.letra, oferta.nombre "
                   );
        return $grupos;
    }

    public function cuenta_alumnos($grupo){
        return count($this->datos_alumnos($grupo));

    }

    public function datos_alumnos($grupo){
        $c = $this->condicion;
        $ciclo = $this->ciclo;
        $alumnos = db::raw_connect();

            $from = "alumnos " .
                "INNER JOIN alumnosgrupo ON alumnosgrupo.alumnos_id=alumnos.id " .
                "INNER JOIN grupos ON grupos.id=alumnosgrupo.grupos_id " .
                "INNER JOIN situaciones ON situaciones.id=alumnos.situaciones_id ";

        $alumnos = $alumnos->in_query(
                            "SELECT " .
                                "alumnos.*,situaciones.nombre as situacion ".
                            "FROM " . $from .
                            "WHERE grupos.id = '" . $grupo['id'] . "' " .
                            ($c == "" ? "" : "AND " . $c . " ")  .
                            "ORDER BY alumnos.ap, alumnos.am, alumnos.nombre "
                   );
        $this->condicion = $c;
        return $alumnos;
    }

}
?>