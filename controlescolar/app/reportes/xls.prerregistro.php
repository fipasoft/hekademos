<?php
class XLSPlantilla extends Reporte {
    private $departamento;
    private $ciclo;

    public function XLSPlantilla( $c_id ) {
        $ciclo = new Ciclos();
        $ciclo = $ciclo->find( $c_id );
        $this->Reporte('Prerregistro.xls');
        
        if ( $ciclo->id != '' ) {
            
            $this->ciclo = $ciclo;
            $this->hoja();
            $this->hoja1();
            
        } else {
            
            $this->hoja_vacia();
            
        }
        
    }

    public function hoja(){
        
        $nombre = 'RESUMEN';
            
        $hojas = $this->getHojas();
        
        $cols = array (
            15,
            30,
            15,
            15,
            15,
            15,
            15,
            15,
            40
        );
        
        $h = $this->agregar_hoja($nombre, $rows, $cols);
        $h->cc_max = 8;

        $this->contenido( $h );
        $this->propiedades( $h );
        
    }
    
    public function hoja1(){
        
        $nombre = 'IMPRIMIR';
        $hojas = $this->getHojas();
        
        $cols = array (
            15,
            50,
            25,
            25,
        );
        
        $h = $this->agregar_hoja($nombre, $rows, $cols);
        $h->cc_max = 8;

        $this->contenido1( $h );
        $this->propiedades( $h );
        
    }

    public function hoja_vacia() {
        
        $nombre = 'HEKADEMOS';
        $h = $this->agregar_hoja($nombre);
        $h->xls->write( 0, 0, "No hay registros que coincidan con esas condiciones" );
        
    }

    public function contenido1( &$h ) {
        
        $this->encabezado( $h );
        $st = $this->getEstilos();
        
        $profesores = new Profesores();
        $profesores = $profesores->find_all_by_sql(
                    "SELECT profesores.* FROM 
                    profesores
                    INNER JOIN prerregistro ON profesores.id = prerregistro.profesores_id
                    GROUP BY profesores.id"
        );
        
        $n = 0;
        
        $profesores = $this->profesores();
        
        foreach($profesores as $profesor){
            $h->xls->write($h->rr, $h->cc, 'Codigo', $st['TD.BGYellow']); $h->cc++;
            $h->xls->write($h->rr, $h->cc, 'Nombre', $st['TD.BGYellow']); $h->cc++;
            $h->xls->write($h->rr, $h->cc, 'Horas', $st['TD.BGYellow']); $h->cc++;
            $h->nueva_linea();
        
            $h->xls->write($h->rr, $h->cc, utf8_decode($profesor->codigo), $st['TD' . $td . '.Normal']); $h->cc++;
            $h->xls->write($h->rr, $h->cc, utf8_decode($profesor->nombre()), $st['TD' . $td . '.Normal']); $h->cc++;
            $h->xls->write($h->rr, $h->cc, $profesor->hAsign, $st['TD' . $td . '.Normal']); $h->cc++;
            $h->nueva_linea();
            $h->nueva_linea();
            
            $cursos = $profesor->prerregistrocursos();
            $h->cc++;
            $h->xls->write($h->rr, $h->cc, 'Unidad de aprendizaje', $st['TD.BGYellow']); $h->cc++;
            $h->xls->write($h->rr, $h->cc, 'Grupo', $st['TD.BGYellow']); $h->cc++;
            $h->xls->write($h->rr, $h->cc, 'hrsXsemana', $st['TD.BGYellow']); $h->cc++;
            $h->nueva_linea();
            
            foreach($cursos as $curso){
                $h->cc++;
                $materia = $curso->materia();
                $grupo = $curso->grupo();
                $h->xls->write($h->rr, $h->cc, utf8_decode($materia->nombre), $st['TD' . $td . '.Normal']); $h->cc++;
                $h->xls->write($h->rr, $h->cc, utf8_decode($grupo->grado).utf8_decode($grupo->letra).utf8_decode($grupo->turno), $st['TD' . $td . '.Normal']); $h->cc++;
                $materiahrs = new Materiahrs();
                $materiahrs = $materiahrs->find_first("materias_id='".$materia->id."'");
                $h->xls->write($h->rr, $h->cc, $materiahrs->horas, $st['TD' . $td . '.Normal']); $h->cc++;
                $h->nueva_linea();
            }
            $h->cc++;$h->cc++;
            $h->xls->write($h->rr, $h->cc, 'Total', $st['TD.BGYellow']); $h->cc++;
            $h->xls->write($h->rr, $h->cc, $profesor->hPre, $st['TD' . $td . '.Normal']); $h->cc++;
            $h->nueva_linea();$h->nueva_linea();$h->nueva_linea();
        }
        

        $h->rr_max = $h->rr + 1;
    }
    
    public function contenido( &$h ) {
        
        $this->encabezado( $h );
        $st = $this->getEstilos();
        
        $h->xls->write($h->rr, $h->cc, 'Codigo', $st['TD.BGYellow']); $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'Nombre', $st['TD.BGYellow']); $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'Cursos', $st['TD.BGYellow']); $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'Horas asignadas', $st['TD.BGYellow']); $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'H. Frente a grupo', $st['TD.BGYellow']); $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'H. Descarga', $st['TD.BGYellow']); $h->cc++;
        $h->nueva_linea();
        
        $n = 0;
        
        $profesores = $this->profesores();
        
        foreach($profesores as $profesor){

            $n++;
            $td = ( $n % 2 == 0 ? 'Par' : '' );
            
            $h->xls->write($h->rr, $h->cc, $profesor->codigo, $st['TD' . $td . '.Normal']); $h->cc++;
            $h->xls->write($h->rr, $h->cc, utf8_decode( $profesor->nombre() ), $st['TD' . $td . '.Normal']); $h->cc++;
            $h->xls->write($h->rr, $h->cc, number_format( $profesor->nCursos, 0, '.', ''), $st['TD' . $td . '.Normal']); $h->cc++;    
            $h->xls->write($h->rr, $h->cc, number_format( $profesor->hAsign, 2, '.', ''), $st['TD' . $td . '.Normal']); $h->cc++;
            $h->xls->write($h->rr, $h->cc, number_format( $profesor->hPre, 2, '.', ''), $st['TD' . $td . '.Normal']); $h->cc++;
            $h->xls->write($h->rr, $h->cc, number_format( (  $profesor->hAsign - $profesor->hPre ), 2, '.', ''), $st['TD' . $td . '.Normal']); $h->cc++;
            $h->cc_max = $h->cc + 1;
            $h->nueva_linea();
        }
        

        $h->rr_max = $h->rr + 1;
    }

    public function encabezado( $h ) {
        $template = new Template();
        $ciclo = $this->ciclo;
        $st = $this->getEstilos();

        $h->xls->insertBitmap($h->rr, $h->cc, getcwd() . '/public/img/sp5/udg.bmp', 5, 8, 0.4, 0.8);
        $h->cc += 6;
        $h->xls->insertBitmap($h->rr, $h->cc, getcwd() . '/public/img/sp5/lp5.bmp', 160, 15, 0.7, 0.7);
        $h->nueva_linea();
        $h->xls->write($h->rr, $h->cc, $template->excel_escuela(), $st['H3']);
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc + 6);
        $h->nueva_linea();
        $h->xls->write($h->rr, $h->cc, $template->excel_subtitulo(), $st['H4']);
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc + 6);

        $h->nueva_linea();
        $h->nueva_linea();
        $h->nueva_linea();
        
    }

    public function profesores(){
        
        $profesores = new Profesores();
        
        return 
            $profesores->find_all_by_sql(
                "SELECT " .
                    "profesores.id, " .
                    "profesores.codigo, " .
                    "profesores.nombre, " .
                    "profesores.ap, " .
                    "profesores.am, " .
                    "profesores.foto, " .
                    "(SELECT hasignadas FROM contratoinfo WHERE profesores.id = contratoinfo.profesores_id ) AS hAsign, " .
                    "SUM( materiahrs.horas ) AS hPre, " .
                    "COUNT(prerregistro.cursos_id) AS nCursos " .
                "FROM " .
                    "profesores " .
                    "Left Join ( "  .
                        "prerregistro " .
                        "Inner Join cursos ON cursos.id = prerregistro.cursos_id " .
                        "Left Join materiahrs ON materiahrs.materias_id = cursos.materias_id " .
                    ") " .
                    "ON prerregistro.profesores_id = profesores.id " .
                "WHERE " .
                    "1 " .
                "GROUP BY " .
                     "profesores.id " .
                "ORDER BY " . 
                    "ap, am, nombre "
            );
        
    }
    
    public function propiedades( &$h ) {
        $h->xls->centerHorizontally();
        $h->xls->hideGridlines();
        $h->xls->printArea(0, 0, $h->rr_max, $h->cc_max);
        $h->xls->setFooter("HEKADEMOS " . date("j/n/Y H:i"), 0);
        $h->xls->setLandscape();
        $h->xls->setMargins_LR(0.2);
        $h->xls->setMargins_TB(0.27);
        $h->xls->setPaper(3);
        $h->xls->setPrintScale(60);
        $h->xls->setZoom(80);
        $h->xls->repeatRows(0,6);
        $h->xls->freezePanes(array(6, 0));
    }
    
}
?>