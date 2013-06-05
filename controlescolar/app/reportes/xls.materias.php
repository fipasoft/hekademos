<?php
class XLSMaterias extends Reporte{
    private $condicion;
    private $ciclo;

    public function XLSMaterias($ciclo_id, $grupo_id = ''){
        $controlador='materias';
        $accion='index';
        // busqueda
        $b = new Busqueda($controlador, $accion);
        $b->campos();
        $b->establecerCondicion(
            'clave',
            "materias.clave LIKE '%" . $b->campo('clave') . "%' "
        );

        $b->establecerCondicion(
            'nombre',
            "materias.nombre LIKE '%" . $b->campo('nombre') . "%' "
        );

        // genera las condiciones
        $this->condicion=$c = $b->condicion(array('oferta_id'));

        $ciclo=new Ciclos();
        $ciclo=$ciclo->find_first($ciclo_id);
        $this->Reporte('Materias ' . $ciclo->numero . '  ' . ($c != '' ? ' FILTRADO' : '' ). '.xls');
        $semestres=array(1,2,3,4,5,6);
        $ofertas=new Oferta();
        $ofertas=$ofertas->find();
        foreach($semestres as $semestre){
            foreach($ofertas as $o){
            $materias=$this->datosSemestre($semestre,$o->id);
                if(count($materias)>0){
                if($o->id==2)
                $this->hoja_competencia($semestre,$materias,$o);
                else
                $this->hoja($semestre,$materias,$o);
            }
            }
        }
        $hojas = $this->getHojas();
            if(count($hojas)==0)
                $this->hoja_vacia();

    }

    public function hoja($semestre,$materias,$oferta){
        $nombre = $semestre.' Semestre '.$oferta->clave;
        $hojas = $this->getHojas();
        if(array_key_exists($semestre, $hojas)){
            $h = $hojas[$nombre];
        }else{
            $cols = array( 0.1, 10, 30, 50, 10,
                            5, 50
                    );
            $h = $this->agregar_hoja($nombre, null, $cols);
            $h->cc_max = 16;
        }
        $this->contenido($h, $semestre,$materias);
        $this->propiedades($h);
    }

    public function hoja_competencia($semestre,$materias,$oferta){
        $nombre = $semestre.' Semestre '.$oferta->clave;
        $hojas = $this->getHojas();
        if(array_key_exists($semestre, $hojas)){
            $h = $hojas[$nombre];
        }else{
            $cols = array( 0.1, 10, 30, 50, 10,
                            5, 50,10,10,10,15,20
                    );
            $h = $this->agregar_hoja($nombre, null, $cols);
            $h->cc_max = 16;
        }
        $this->contenido_competencia($h, $semestre,$materias);
        $this->propiedades($h);
    }
    public function hoja_vacia(){
        $nombre = 'HEKADEMOS';
        $h = $this->agregar_hoja($nombre);
        $h->xls->write(0, 0, "No hay registros que coincidan con esas condiciones");
    }

    public function contenido(&$h, $semestre,$materias){
        $this->encabezado($h,$semestre,'BACHILLERATO GENERAL');
        $st = $this->getEstilos();
        $h->xls->write($h->rr, $h->cc, 'ID'); $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'Clave', $st['TD.BGYellow']); $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'Nombre', $st['TD.BGYellow']); $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'Descripcion', $st['TD.BGYellow']); $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'Semestre', $st['TD.BGYellow']); $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'Tipo', $st['TD.BGYellow']); $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'Oferta', $st['TD.BGYellow']); $h->cc++;

        $n = 0;

        foreach($materias as $materia){
        $h->nueva_linea();
            $n++;
            $td = ($n%2 == 0 ? 'Par' : '');
            $h->xls->write($h->rr, $h->cc, $materia['id']); $h->cc++;
            $h->xls->write($h->rr, $h->cc,$materia['clave'], $st['TD' . $td . '.Normal']); $h->cc++;
            $h->xls->write($h->rr, $h->cc, utf8_decode($materia['nombre']), $st['TD' . $td . '.Normal']);$h->cc++;
            $h->xls->write($h->rr, $h->cc, utf8_decode($materia['descripcion']), $st['TD' . $td . '.Normal']);$h->cc++;
            $h->xls->write($h->rr, $h->cc, utf8_decode($materia['semestre']), $st['TD' . $td . '.Normal']);$h->cc++;
            $h->xls->write($h->rr, $h->cc, utf8_decode($materia['tipo']), $st['TD' . $td . '.Normal']);$h->cc++;
            $h->xls->write($h->rr, $h->cc, utf8_decode($materia['oferta_nombre']), $st['TD' . $td . '.Normal']);$h->cc++;
            $h->cc++;
            $h->cc_max = $h->cc;
        }


        $h->rr_max = $h->rr;


    }

    public function contenido_competencia(&$h, $semestre,$materias){
        $this->encabezado($h,$semestre,'BACHILLERATO POR COMPETENCIAS');
        $st = $this->getEstilos();
        $h->xls->write($h->rr, $h->cc, 'ID'); $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'Clave', $st['TD.BGYellow']); $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'Nombre', $st['TD.BGYellow']); $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'Descripcion', $st['TD.BGYellow']); $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'Semestre', $st['TD.BGYellow']); $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'Tipo', $st['TD.BGYellow']); $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'Oferta', $st['TD.BGYellow']); $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'Creditos', $st['TD.BGYellow']); $h->cc++;
        $h->xls->write($h->rr, $h->cc, utf8_decode('Duración'), $st['TD.BGYellow']); $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'hrsXsem', $st['TD.BGYellow']); $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'Competencia', $st['TD.BGYellow']); $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'Tray o Comp', $st['TD.BGYellow']); $h->cc++;
        $n = 0;

        foreach($materias as $materia){
        $h->nueva_linea();
            $n++;
            $td = ($n%2 == 0 ? 'Par' : '');
            $h->xls->write($h->rr, $h->cc, $materia['id']); $h->cc++;
            $h->xls->write($h->rr, $h->cc,$materia['clave'], $st['TD' . $td . '.Normal']); $h->cc++;
            $h->xls->write($h->rr, $h->cc, utf8_decode($materia['nombre']), $st['TD' . $td . '.Normal']);$h->cc++;
            $h->xls->write($h->rr, $h->cc, utf8_decode($materia['descripcion']), $st['TD' . $td . '.Normal']);$h->cc++;
            $h->xls->write($h->rr, $h->cc, utf8_decode($materia['semestre']), $st['TD' . $td . '.Normal']);$h->cc++;
            $h->xls->write($h->rr, $h->cc, utf8_decode($materia['tipo']), $st['TD' . $td . '.Normal']);$h->cc++;
            $h->xls->write($h->rr, $h->cc, utf8_decode($materia['oferta_nombre']), $st['TD' . $td . '.Normal']);$h->cc++;
            $h->xls->write($h->rr, $h->cc, utf8_decode($materia['creditos']), $st['TD' . $td . '.Normal']);$h->cc++;
            $h->xls->write($h->rr, $h->cc, utf8_decode($materia['duracion']), $st['TD' . $td . '.Normal']);$h->cc++;
            $h->xls->write($h->rr, $h->cc, utf8_decode($materia['horassemana']), $st['TD' . $td . '.Normal']);$h->cc++;
            if($materia['competencia']=='gen'){
            $comp='Competencia Generica';
            $competencia=new Competenciasgenericasmaterias();
            $competencia=$competencia->find_first('materias_id='.$materia['id']);
            $generica=new CompetenciaGenerica();
            $generica=$generica->find($competencia->competenciagenerica_id);
            $esp=$generica->nombre;
            }elseif($materia['competencia']=='esp'){
            $comp='Trayectoria Especializante';
            $trayectoria=new Trayectoriasespecializantesmaterias();
            $trayectoria=$trayectoria->find_first('materias_id='.$materia['id']);
            $especializante=new Trayectoriaespecializante();
            $especializante=$especializante->find($trayectoria->trayectoriaespecializante_id);
            $esp=$especializante->nombre;
            }

            $h->xls->write($h->rr, $h->cc, utf8_decode($comp), $st['TD' . $td . '.Normal']);$h->cc++;
            $h->xls->write($h->rr, $h->cc, utf8_decode($esp), $st['TD' . $td . '.Normal']);$h->cc++;
            $h->cc++;
            $h->cc_max = $h->cc;
        }


        $h->rr_max = $h->rr;


    }

    public function encabezado(&$h,$s,$t){
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
        $h->xls->write($h->rr, $h->cc, 'MATERIAS '.$s.' SEMESTRE', $st['H4']);
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc + 6);
        $h->nueva_linea();
        $h->xls->write($h->rr, $h->cc,$t, $st['H4']);
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

    public function datosSemestre($semestre,$oferta){
        // ejecuta la consulta
        $materias=db::raw_connect();

        $c.=$this->condicion.  ($this->condicion != '' ? " AND semestre='$semestre' " : " semestre='$semestre'" );
        $materias = $materias->in_query(
                "SELECT materias.*, oferta.nombre as oferta_nombre FROM
                            materias
                            Inner Join ofertasmaterias ON ofertasmaterias.materias_id=materias.id " .
                            " Inner Join oferta ON ofertasmaterias.oferta_id=oferta.id " .
                "WHERE " .
                    ($c == '' ? ' ofertasmaterias.oferta_id='.$oferta : $c.' AND ofertasmaterias.oferta_id='.$oferta) .
                " ORDER BY ".
                    "materias.semestre,materias.tipo,materias.nombre "
                    );

        return $materias;
    }
}
?>