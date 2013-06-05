<?php
Kumbia :: import('lib.excel.main');

class XLSInscritostrayectorias extends Reporte{
    private $ciclo;
    public function XLSInscritostrayectorias($id){

        $this->Reporte('Inscritos trayectorias.xls');
        $id=intval($id,10);
        $periodotrayectoria=new Periodotrayectoria();
        $periodotrayectoria=$periodotrayectoria->find($id);
        if($periodotrayectoria->id!=""){

                $alumnos = new Alumnos();
                $alumnos = $alumnos->find_all_by_sql(
                "SELECT alumnos.*
                            FROM
                            alumnos
                            INNER JOIN periodosalumnos ON alumnos.id=periodosalumnos.alumnos_id
                            INNER JOIN periodotrayectoriaalumno ON periodosalumnos.id = periodotrayectoriaalumno.periodosalumnos_id
                            WHERE periodotrayectoriaalumno.periodotrayectoria_id=".$periodotrayectoria->id
                );

        $periodo = new Periodo();
        $periodo = $periodo->find($periodotrayectoria->periodo_id);

        $ciclo = new Ciclos();
        $this->ciclo = $ciclo->find($periodo->ciclos_id);


        if(count($alumnos)>0){
            $trayectoria = new Trayectoriaespecializante();
            $trayectoria = $trayectoria->find($periodotrayectoria->trayectoriaespecializante_id);
            $this->hoja($trayectoria,$alumnos);
        }else{
            $this->hoja_vacia_alumnos();
        }
        }else{
            $this->hoja_vacia();
        }


    }


    public function hoja($trayectoria,$alumnos){
        $nombre = substr(strToUpper(utf8_decode($trayectoria->nombre)),0,30);
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
        $this->contenido($h, $alumnos,$trayectoria);
        $this->propiedades($h);
    }

    public function hoja_vacia(){
        $nombre = 'HEKADEMOS';
        $h = $this->agregar_hoja($nombre);
        $h->xls->write(0, 0, "Los datos no son validos.");
    }

    public function hoja_vacia_alumnos($nombre=''){
        $h = $this->agregar_hoja($nombre);
        $h->xls->write(0, 0, "Ningun alumno inscrito.");
    }

    public function contenido(&$h, $alumnos,$trayectoria){
        $this->encabezado($h,$trayectoria);
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
    public function encabezado(&$h,$trayectoria) {
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
        $h->xls->write($h->rr, $h->cc, 'ALUMNOS INSCRITOS A LA TRAYECTORIA', $st['H4']);
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc + 6);
        $h->nueva_linea();
        $h->xls->write($h->rr, $h->cc,strToUpper(utf8_decode($trayectoria->nombre)), $st['H4']);
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc + 6);
        $h->nueva_linea();
        $h->xls->write($h->rr, $h->cc, '', $st['H4']);
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