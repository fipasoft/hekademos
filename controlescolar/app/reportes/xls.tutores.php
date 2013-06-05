<?php
class XLSTutores extends Reporte{
    private $condicion;
    private $ciclo;

    public function XLSTutores($ciclo_id){
        $controlador='tutores';
        $accion='index';
        // busqueda
        $b = new Busqueda($controlador, $accion);

        // genera las condiciones
        $b->establecerCondicion(
            'nombre',
            "CONCAT(tutores.nombre, ' ', tutores.ap, ' ', tutores.am) LIKE '%" . $b->campo('nombre') . "%' "
        );
        $b->establecerCondicion('oferta_id', "grupos.oferta_id  = '" . $b->campo('oferta_id') . "' ");
        $this->condicion=$c = $b->condicion(array('ciclos_id'));

        $ciclo=new Ciclos();
        $this->ciclo=$ciclo=$ciclo->find_first($ciclo_id);
        $this->Reporte('Tutores ' . $ciclo->numero . '  ' . ($c != '' ? ' FILTRADO' : '' ). '.xls');

            $grupos = $this->datos_grupos();

            if(count($grupos) > 0){
                foreach($grupos as $grupo){
                    if($this->cuenta_tutores($grupo)>0)
                    $this->hoja($grupo);
                }
            }else{
                $this->hoja_vacia();
            }

            $tutores=$this->sin_alumno();
            if(count($tutores)>0)
            $this->hoja_sin_alumno();

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
            $cols = array( 0.1, 10, 45, 30, 30,
                            40, 10, 15, 50, 15, 10, 5, 15, 15, 15, 15
                    );
            $h = $this->agregar_hoja($nombre, null, $cols);
            $h->cc_max = 16;
        }
        $this->contenido($h, $grupo);
        $this->propiedades($h);
    }

    public function hoja_sin_alumno(){
        $nombre = "Sin tutorado";
        $hojas = $this->getHojas();
        if(array_key_exists($nombre, $hojas)){
            $h = $hojas[$nombre];
        }else{
            $cols = array( 0.1, 10, 30, 20, 20,
                            35, 10, 15, 35, 15, 10, 8
                    );
            $h = $this->agregar_hoja($nombre, null, $cols);
            $h->cc_max = 16;
        }
        $this->contenido($h, null);
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
        $h->xls->write($h->rr, $h->cc, 'alumno', $st['TD.BGYellow']); $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'Nombre', $st['TD.BGYellow']); $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'ap', $st['TD.BGYellow']); $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'am', $st['TD.BGYellow']); $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'domicilio', $st['TD.BGYellow']); $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'tel', $st['TD.BGYellow']); $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'cel', $st['TD.BGYellow']); $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'mail', $st['TD.BGYellow']); $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'fnacimiento', $st['TD.BGYellow']); $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'sexo', $st['TD.BGYellow']); $h->cc++;
        $h->nueva_linea();
        $n = 0;
        if($grupo!=null)
        $tutores=$this->datos_tutores($grupo);
        else
        $tutores=$this->sin_alumno();


        foreach($tutores as $tutor){
            $n++;
            $td = ($n%2 == 0 ? 'Par' : '');
            $h->xls->write($h->rr, $h->cc, $tutor['id']); $h->cc++;
            $h->xls->writeString($h->rr, $h->cc,$tutor['codigo'], $st['TD' . $td . '.Normal']); $h->cc++;
            $h->xls->write($h->rr, $h->cc, utf8_decode($tutor['nombre']), $st['TD' . $td . '.Normal']);$h->cc++;
            $h->xls->write($h->rr, $h->cc, utf8_decode($tutor['ap']), $st['TD' . $td . '.Normal']);$h->cc++;
            $h->xls->write($h->rr, $h->cc, utf8_decode($tutor['am']), $st['TD' . $td . '.Normal']);$h->cc++;
            $h->xls->write($h->rr, $h->cc, utf8_decode($tutor['domicilio']), $st['TD' . $td . '.Normal']);$h->cc++;
            $h->xls->writeString($h->rr, $h->cc, $tutor['tel'], $st['TD' . $td . '.Normal']);$h->cc++;
            $h->xls->writeString($h->rr, $h->cc, $tutor['cel'], $st['TD' . $td . '.Normal']);$h->cc++;
            $h->xls->write($h->rr, $h->cc, utf8_decode($tutor['mail']), $st['TD' . $td . '.Normal']);$h->cc++;
            $h->xls->write($h->rr, $h->cc, $tutor['fnacimiento'], $st['TD' . $td . '.Normal']);$h->cc++;
            $h->xls->write($h->rr, $h->cc, $tutor['sexo'], $st['TD' . $td . '.Normal']);$h->cc++;
            $h->cc++;
            $h->nueva_linea();
        }

        $h->nueva_linea();
        $h->rr_max = $h->rr;
    }

    public function encabezado(&$h,$grupo){
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
        if($grupo!=null){
        $turno='';
        switch($grupo['turno']){
            case 'M': $turno="MATUTINO"; break;
            case 'V': $turno="VESPERTINO"; break;
            case 'N': $turno="NOCTURNO"; break;
        }
        $h->xls->write($h->rr, $h->cc, 'TUTORES DEL '.$grupo['grado'].$grupo['letra'].' '.$turno, $st['H4']);
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc + 6);
        $h->nueva_linea();
        $h->xls->write($h->rr, $h->cc, strToUpper($grupo['nombre']), $st['H4']);
        }else{
        $h->xls->write($h->rr, $h->cc, 'TUTORES SIN ALUMNO', $st['H4']);
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc + 6);
        $h->nueva_linea();
        $h->xls->write($h->rr, $h->cc, '', $st['H4']);

        }
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

    public function datos_tutores($grupo){
        $c = $this->condicion;
        $ciclo = $this->ciclo;
        $tutores = db::raw_connect();
        // ejecuta la consulta
        $tutores = $tutores->in_query(
            "SELECT " .
                "tutores.*, " .
                "alumnos.codigo ".
            "FROM " .
                "tutores " .
                "Left Join (tutoria Inner Join alumnos ON alumnos.id = tutoria.alumnos_id " .
                            "Inner Join alumnosgrupo ON alumnosgrupo.alumnos_id = alumnos.id " .
                            "Inner Join grupos ON alumnosgrupo.grupos_id = grupos.id )" .
                "ON tutoria.tutores_id = tutores.id " .
                "WHERE grupos.id = '" . $grupo['id'] . "' " .
                ($c == "" ? "" : "AND " . $c . " ")  .
                "ORDER BY tutores.ap, tutores.am, tutores.nombre "

                );

        return $tutores;
    }

    public function cuenta_tutores($grupo){
        return count($this->datos_tutores($grupo));

    }

    public function sin_alumno(){
        $c = $this->condicion;
        $tutores = db::raw_connect();
        // ejecuta la consulta
        $tutores = $tutores->in_query(
            "SELECT
                 tutores.*,
                 alumnos.codigo
                 FROM
                tutores
                Left Join (tutoria Inner Join alumnos ON alumnos.id = tutoria.alumnos_id
                Inner Join alumnosgrupo ON alumnosgrupo.alumnos_id = alumnos.id
                Inner Join grupos ON alumnosgrupo.grupos_id = grupos.id )
                ON tutoria.tutores_id = tutores.id
                WHERE tutoria.tutores_id is null AND tutoria.alumnos_id IS NULL " .
                ($c == "" ? "" : "AND " . $c . " ")  .
                "ORDER BY tutores.ap, tutores.am, tutores.nombre "
                );
    return $tutores;
    }

}
?>