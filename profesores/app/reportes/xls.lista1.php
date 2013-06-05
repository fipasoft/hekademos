<?php
Kumbia :: import('lib.excel.main');

class XLSLista1 extends Reporte{
    private $ciclo;
    private $clases;
    private $descansos;
    private $curso;

    public function XLSLista($curso_id){
        $curso=new Cursos();
        $this->curso=$curso=$curso->find($curso_id);
        $this->ciclo=$curso->ciclo();
        $dias=$curso->horariosDias();
        $this->Reporte('Lista ' . $curso->verMateriaNombre(). '.xls');
        if(count($dias)<=0){
            $this->hoja_vacia();
        }else{
        $alumnos=$curso->alumnosInscritos();
        $this->descansos=$this->obtenDescansos();
        $this->clases=$this->obtenDias($dias);
        $meses=array_keys($this->clases);

        foreach($meses as $mes){
        $this->hoja($curso,$mes,$alumnos);
        }
        }

    }


    public function hoja($curso,$mes,$alumnos){
        $nombre = Utils:: mes_espanol($mes);
        $nombre = utf8_decode($nombre);
        $hojas = $this->getHojas();
        if(array_key_exists($nombre, $hojas)){
            $h = $hojas[$nombre];
        }else{
            $cols = array(
                    15,70
                    );
            for($i=1;$i<30;$i++)
                $cols[]=3;

            $rows = array();
            for($i = 0; $i < 60; $i++){
                $rows[] = 18;
            }
            $h = $this->agregar_hoja($nombre, $rows, $cols);

        }
        $this->contenido($h, $alumnos, $mes);
        $this->propiedades($h);
    }

    public function hoja_vacia(){
        $nombre = 'SP5';
        $h = $this->agregar_hoja($nombre);
        $h->xls->write(0, 0, "No se ha definido el horario del curso.");
    }

    public function contenido(&$h, $alumnos, $mes){
        $ms=Utils:: mes_espanol($mes);
        $this->encabezado($h,$ms);
        $st = $this->getEstilos();
        $i = 1;
        $h->nueva_linea();
        $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'Nombre', $st['TD.BGYellow']);$h->cc++;
        $dias=$this->clases[$mes];

        foreach($dias as $dia){
                $h->xls->write($h->rr, $h->cc, $dia.' ', $st['TD.BGYellow']);$h->cc++;
            }
        $h->cc_max=$h->cc;

        foreach($alumnos as $alumno){
            $nombre = $alumno->ap . ' ' . $alumno->am . ', '. $alumno->nombre;
            $h->nueva_linea();
            $h->cc++;
            $h->xls->write($h->rr, $h->cc, utf8_decode($nombre), $st['TD.Normal']);$h->cc++;
            foreach($dias as $dia){
                $h->xls->write($h->rr, $h->cc,'', $st['TD.Normal']);$h->cc++;
            }
        }
        $h->rr_max=$h->rr;

    }

    public function encabezado(&$h,$ms){
        $st = $this->getEstilos();
        $grupo=$this->curso->grupo();
        $ciclo=$grupo->ciclo();

        $h->xls->write($h->rr, $h->cc, 'Ciclo', $st['TD.BGYellow']);$h->cc++;
        $h->xls->write($h->rr, $h->cc, $ciclo->numero, $st['TD.Normal']);$h->cc++;
        $h->nueva_linea();

        $h->xls->write($h->rr, $h->cc, 'Grupo', $st['TD.BGYellow']);$h->cc++;
        $h->xls->write($h->rr, $h->cc, $grupo->grado.$grupo->letra.' '.$grupo->verTurno().' , '.$grupo->verOferta(), $st['TD.Normal']);$h->cc++;
        $h->nueva_linea();


        $h->xls->write($h->rr, $h->cc, 'Materia', $st['TD.BGYellow']);$h->cc++;
        $h->xls->write($h->rr, $h->cc,  utf8_decode($this->curso->verMateriaNombre()), $st['TD.Normal']);$h->cc++;
        $h->nueva_linea();


        $h->xls->write($h->rr, $h->cc, 'Profesor', $st['TD.BGYellow']);$h->cc++;
        $h->xls->write($h->rr, $h->cc,  utf8_decode($this->curso->verProfesor()), $st['TD.Normal']);$h->cc++;
        $h->nueva_linea();

        $h->xls->write($h->rr, $h->cc, 'Mes', $st['TD.BGYellow']);$h->cc++;
        $h->xls->write($h->rr, $h->cc,  utf8_decode($ms),  $st['TD.Normal']);$h->cc++;
        $h->nueva_linea();
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


    public function obtenDias($dias){
        $clases=array();
        list($year,$mon,$day) = explode('-',$this->ciclo->inicio);
        $inicio=mktime(0,0,0,$mon,$day,$year);

        list($year,$mon,$day) = explode('-',$this->ciclo->fin);
        $fin=mktime(0,0,0,$mon,$day,$year);

        $actual=$inicio;
        while($actual<=$fin){

            $fecha = getdate($actual);
            if(in_array(strtoLower(Utils::textoPlano(Utils::dia_espanol_lista($fecha['wday']))),$dias) && !$this->descanso($actual)){
                $clases[$fecha['mon']][$fecha['mday']]=$fecha['mday'];
            }
            $f=date('Y-m-d',$actual);
            $actual=$this->sumaDia($f,1);
        }

        return $clases;

    }

    private function descanso($dia){
        foreach($this->descansos as $desc){
        list($year,$mon,$day) = explode('-',$desc->inicio);
        $inicio=mktime(0,0,0,$mon,$day,$year);

        list($year,$mon,$day) = explode('-',$desc->fin);
        $fin=mktime(0,0,0,$mon,$day,$year);

        if(($inicio<=$dia AND $dia<=$fin)){
            return true;
        }

        }
        return false;
    }

    private function obtenDescansos(){
        $eventos=new Eventos();
        $eventos=$eventos->porClave('dsc');
        $cond='';
        foreach($eventos as $eve){
        $cond.=" eventos_id=".$eve->id." OR ";

        }
        $cond=substr($cond,0,strlen($cond)-3);
        $roles=new Roles();
        $roles=$roles->find($cond);

        $cond='';
        foreach($roles as $rol){
            $cond.=" roles_id=".$rol->id." OR ";
        }
        $cond=substr($cond,0,strlen($cond)-3);

        $agenda=new Agenda();
        $agenda=$agenda->find("(".$cond.") AND ciclos_id=".$this->ciclo->id." AND activo=1 GROUP BY periodo");

        return $agenda;

    }


    function sumaDia($fecha,$dia){
        list($year,$mon,$day) = explode('-',$fecha);
        return mktime(0,0,0,$mon,$day+$dia,$year);
    }

}
?>