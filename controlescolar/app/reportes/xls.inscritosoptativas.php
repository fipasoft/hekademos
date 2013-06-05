<?php
Kumbia :: import('lib.excel.main');

class XLSInscritosoptativas extends Reporte{
    private $ciclo;
    private $clases;
    private $descansos;
    private $curso;

    public function XLSInscritosoptativas($id){

        $this->Reporte('Inscritos.xls');
        $periodocurso=new Periodoscursos();
        $periodocurso=$periodocurso->find($id);
        if($periodocurso->id!=""){
            $this->curso=$curso=$periodocurso->curso();
            $alumnos=$periodocurso->alumnosinscritos();
        if(count($alumnos)>0){
        $periodo=new Periodo();
        $periodo=$periodo->find($periodocurso->periodo_id);
        $ciclo=new Ciclos();
        $this->ciclo=$ciclo->find($periodo->ciclos_id);
        $this->hoja($curso,$alumnos);
        }else{
            $this->hoja_vacia_alumnos($curso->verMateriaNombre());
        }
        }else{
            $this->hoja_vacia_alumnos($curso->verMateriaNombre());
        }


    }


    public function hoja($curso,$alumnos){
        $nombre = utf8_decode($curso->verMateriaNombre());
        $hojas = $this->getHojas();
        if(array_key_exists($nombre, $hojas)){
            $h = $hojas[$nombre];
        }else{
            $cols = array(
                    3,15,50
                    );
            $rows = array();
            for($i = 0; $i < 60; $i++){
                $rows[] = 18;
            }
            $h = $this->agregar_hoja($nombre, $rows, $cols);
            $h->cc_max = 1;
        }
        $this->contenido($h, $alumnos);
        $this->propiedades($h);
    }

    public function hoja_vacia(){
        $nombre = 'HEKADEMOS';
        $h = $this->agregar_hoja($nombre);
        $h->xls->write(0, 0, "Los cursos no existen.");
    }

    public function hoja_vacia_alumnos($nombre=''){
        $h = $this->agregar_hoja($nombre);
        $h->xls->write(0, 0, "Los cursos no existen.");
    }

    public function hoja_vacia_curso(){
        $nombre = 'HEKADEMOS';
        $h = $this->agregar_hoja($nombre);
        $h->xls->write(0, 0, "El curso no existe.");
    }

    public function contenido(&$h, $alumnos){
        $this->encabezado($h);
        $st = $this->getEstilos();
        $h->xls->write($h->rr, $h->cc, '#', $st['TD.BGYellow']);$h->cc++;
        $h->xls->write($h->rr, $h->cc, 'Codigo', $st['TD.BGYellow']);$h->cc++;
        $h->xls->write($h->rr, $h->cc, 'Nombre', $st['TD.BGYellow']);$h->cc++;

        $i=0;
        foreach($alumnos as $alumno){
            $td = ($i%2 == 0 ? 'Par' : '');
            $nombre = $alumno->ap . ' ' . $alumno->am . ', '. $alumno->nombre;
            $h->nueva_linea();
            $h->xls->write($h->rr, $h->cc, $i+1, $st['TH.BGYellow']);$h->cc++;
            $h->xls->writeString($h->rr, $h->cc, utf8_decode($alumno->codigo),  $st['TD' . $td . '.Normal']);$h->cc++;
            $gpo=$alumno->obtenerGrupoPorCiclo($this->ciclo->id);
            $h->xls->write($h->rr, $h->cc, utf8_decode($nombre)." (".$gpo->grado.$gpo->letra.$gpo->turno.")",  $st['TD' . $td . '.Normal']);$h->cc++;
            $i++;
        }

        $h->rr_max=$h->rr;
        $h->cc_max=$h->cc;

    }
    public function encabezado(&$h) {
        $template=new Template();
        $grupo=$this->curso->grupo();
        $ciclo=$grupo->ciclo();
        $materia=$this->curso->materia();
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
        $turno='';
        switch($grupo->turno){
            case 'M': $turno="MATUTINO"; break;
            case 'V': $turno="VESPERTINO"; break;
            case 'N': $turno="NOCTURNO"; break;
        }
        $h->xls->write($h->rr, $h->cc, 'ALUMNOS INSCRITOS AL CURSO', $st['H4']);
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc + 6);
        $h->nueva_linea();
        $h->xls->write($h->rr, $h->cc,utf8_decode(strToUpper($ciclo->numero.', '.$grupo->grado.$grupo->letra.' '.$turno.', '.$materia->nombre)), $st['H4']);
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc + 6);
        $h->nueva_linea();
        $h->xls->write($h->rr, $h->cc, utf8_decode(strToUpper($grupo->verOferta('nombre'))), $st['H4']);
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc + 6);
        $h->nueva_linea();
        $h->nueva_linea();
        $h->xls->repeatRows(0,8);
        $h->xls->freezePanes(array(9, 0));
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
        $h->xls->setPrintScale(70);
        $h->xls->setZoom(50);
    }
}
?>