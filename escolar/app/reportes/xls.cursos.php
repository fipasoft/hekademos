<?php
class XLSCursos extends Reporte{
    
    public function XLSCursos($ciclo_id, $grupo_id = ''){
        $grupos = new Grupos();
        $ciclos = new Ciclos();
        $ciclo = $ciclos->find($ciclo_id);
        
        if($grupo->id != ''){
            $grupo = $grupos->find($grupo->id);
            $glabel = $grupo->grado . $grupo->letra . $grupo->turno;
            $this->Reporte('Cursos ' . $glabel . ' ' . $ciclo->numero . '.xls');
            $this->hoja($grupo);
        }else{
            $this->Reporte('Cursos ' . $ciclo->numero . '.xls');
            $grupos = $grupos->find("conditions: ciclos_id = '" . $ciclo_id . "'");
            foreach($grupos as $grupo){
                $this->hoja($grupo);
            }
        }
    }

    public function hoja($grupo){
        $nombre = $grupo->grado . $grupo->turno;
        $hojas = $this->getHojas();
        if(array_key_exists($nombre, $hojas)){
            $h = $hojas[$nombre];
        }else{
            $cols = array( 0.1, 36, 10, 10, 10, 
                            10, 10, 10, 11, 34
                    );
            $h = $this->agregar_hoja($nombre, null, $cols);    
        }
        $this->contenido($h, $grupo);
    }

    public function contenido(&$h, $grupo){
        $st = $this->getEstilos();
        $cursos = new Cursos();
        $cursos = $cursos->find("conditions: grupos_id = '" . $grupo->id . "'");
        $h->cc++;
        $h->xls->write($h->rr, $h->cc, $grupo->verInfo(), $st['H2.Left']);
        $h->nueva_linea();
        $h->xls->write($h->rr, $h->cc, 'ID'); $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'Materia', $st['TD.BGOrange']); $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'L', $st['TD.BGYellow']); $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'M', $st['TD.BGYellow']); $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'I', $st['TD.BGYellow']); $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'J', $st['TD.BGYellow']); $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'V', $st['TD.BGYellow']); $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'S', $st['TD.BGYellow']); $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'Codigo del profesor', $st['TD.BGOrange']); $h->cc++;
        //$h->xls->write($h->rr, $h->cc, 'Prof. Nombre', $st['TD.BGOrange']); $h->cc++;
        $h->nueva_linea(); 
        $n = 0;
        foreach($cursos as $curso){
            $n++;
            $td = ($n%2 == 0 ? 'Par' : '');
            $materia = $curso->materia();
            $profesor = $curso->profesor();
            $h->xls->write($h->rr, $h->cc, $curso->id); $h->cc++;
            $h->xls->write($h->rr, $h->cc, utf8_decode($materia->nombre), $st['TD' . $td . '.Normal']); $h->cc++;
            for($i=0; $i<6; $i++){
                $h->xls->writeBlank($h->rr, $h->cc, $st['TD' . $td . '.Normal']); $h->cc++;    
            }
            $h->xls->writeBlank($h->rr, $h->cc, $st['TD' . $td . '.Normal']); $h->cc++;
            //$h->xls->write($h->rr, $h->cc, utf8_decode($profesor->codigo), $st['TD']); $h->cc++;
            //$h->xls->write($h->rr, $h->cc, utf8_decode($profesor->ap . ' ' . $profesor->am . ', ' . $profesor->nombre), $st['TD']);
            $h->nueva_linea(); 
        }
        if(count($cursos) <= 0){
            $h->xls->writeBlank(0, 0);
        }
        $h->nueva_linea();
    }

    public function datos(){
        
    }

    public function encabezado(){
        
    }
    
    public function propiedades(){
        
    }    
}
?>