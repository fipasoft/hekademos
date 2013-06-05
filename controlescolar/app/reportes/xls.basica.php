<?php
class XLSBasica extends Reporte{
    private $condicion;
    private $ciclo;

    public function XLSBasica($ciclo_id){
        $ciclo = new Ciclos();
        $this->ciclo = $ciclo->find($ciclo_id);

        $this->Reporte('Basica fin de cursos ' . $this->ciclo->numero . '.xls');
        $this->hoja('EB 01 - B');
        $this->hoja1('EB 05');

    }

    public function hoja($nombre){
        $hojas = $this->getHojas();
        if(array_key_exists($nombre, $hojas)){
            $h = $hojas[$nombre];
        }else{
            $cols = array(10, 42, 10, 10, 6, 6, 6, 6, 6, 6, 6, 6, 6, 6, 6, 6, 6, 6, 6, 6, 6);
            $h = $this->agregar_hoja($nombre, null, $cols);
            $h->cc_max = 13;
        }
        $this->contenido($h);
        $this->propiedades($h);
    }


    public function hoja1($nombre){
        $hojas = $this->getHojas();
        if(array_key_exists($nombre, $hojas)){
            $h = $hojas[$nombre];
        }else{
            $cols = array(10, 42);
            for($i=0; $i<50; $i++){
                $cols[] = 5;
            }
            $cols[7] = 12;
            $cols[13] = 12;
            $cols[19] = 12;
            $cols[25] = 12;
            $cols[31] = 12;
            $cols[37] = 12;
            $cols[43] = 12;
            
            $h = $this->agregar_hoja($nombre, null, $cols);
            $h->cc_max = 13;
        }
        $this->contenido1($h);
        $this->propiedades1($h);
    }

    public function hoja_vacia(){
        $nombre = 'HEKADEMOS';
        $h = $this->agregar_hoja($nombre);
        $h->xls->write(0, 0, "No hay registros que coincidan con esas condiciones");
    }
    
    public function contenido(&$h){
        $this->encabezado($h);
        $st = $this->getEstilos();
        $h->xls->write($h->rr, $h->cc, 'ESPECIALIDAD', $st['TH.GrayCenter']);
        $h->xls->write($h->rr, $h->cc + 1, "", $st['TH.GrayCenter']);
        $h->xls->write($h->rr + 1, $h->cc, "", $st['TH.GrayCenter']);
        $h->xls->write($h->rr + 1, $h->cc + 1, "", $st['TH.GrayCenter']);
        $h->xls->write($h->rr + 2, $h->cc, "", $st['TH.GrayCenter']);
        $h->xls->write($h->rr + 2, $h->cc + 1, "", $st['TH.GrayCenter']);

        $h->xls->mergeCells($h->rr, $h->cc, $h->rr + 2, $h->cc + 1); $h->cc++;
        $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'CVE ESP', $st['TH.GrayCenter']);
        for($i=1; $i<=2; $i++){
            $h->xls->write($h->rr + $i , $h->cc, "", $st['TH.GrayCenter']);
        }
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr + 2, $h->cc); $h->cc++;


        $h->xls->write($h->rr, $h->cc, 'MOD', $st['TH.GrayCenter']);
        for($i=1; $i<=2; $i++){
            $h->xls->write($h->rr + $i , $h->cc, "", $st['TH.GrayCenter']);
        }
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr + 2, $h->cc); $h->cc++;

        $h->xls->write($h->rr, $h->cc, 'AREA', $st['TH.GrayCenter']);
        for($i=1; $i<=2; $i++){
            $h->xls->write($h->rr + $i , $h->cc, "", $st['TH.GrayCenter']);
        }
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr + 2, $h->cc); $h->cc++;

        $h->xls->write($h->rr, $h->cc, utf8_decode('1° Y 2° SEMESTRE'), $st['TH.GrayCenter']);
        for($i=1; $i<=3; $i++){
            $h->xls->write($h->rr , $h->cc + $i, "", $st['TH.GrayCenter']);
        }
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc + 3);


        $h->xls->write($h->rr + 1, $h->cc, 'G', $st['TH.GrayCenter']);
        $h->xls->write($h->rr + 2 , $h->cc, "", $st['TH.GrayCenter']);

        $h->xls->mergeCells($h->rr + 1, $h->cc, $h->rr + 2, $h->cc); $h->cc++;

        $h->xls->write($h->rr + 1, $h->cc, utf8_decode('ALUMNOS'), $st['TH.GrayCenter']);
        for($i=1; $i<=2; $i++){
            $h->xls->write($h->rr + 1 , $h->cc + $i, "", $st['TH.GrayCenter']);
        }
        $h->xls->mergeCells($h->rr + 1, $h->cc, $h->rr + 1, $h->cc + 2);


        $h->xls->write($h->rr + 2, $h->cc, 'H', $st['TH.GrayCenter']); $h->cc++;
        $h->xls->write($h->rr + 2, $h->cc, 'M', $st['TH.GrayCenter']); $h->cc++;
        $h->xls->write($h->rr + 2, $h->cc, 'S', $st['TH.GrayCenter']); $h->cc++;

        $h->xls->write($h->rr, $h->cc, utf8_decode('3° Y 4° SEMESTRE'), $st['TH.GrayCenter']);
        for($i=1; $i<=3; $i++){
            $h->xls->write($h->rr , $h->cc + $i, "", $st['TH.GrayCenter']);
        }
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc + 3);


        $h->xls->write($h->rr + 1, $h->cc, 'G', $st['TH.GrayCenter']);
        $h->xls->write($h->rr + 2 , $h->cc, "", $st['TH.GrayCenter']);

        $h->xls->mergeCells($h->rr + 1, $h->cc, $h->rr + 2, $h->cc); $h->cc++;

        $h->xls->write($h->rr + 1, $h->cc, utf8_decode('ALUMNOS'), $st['TH.GrayCenter']);
        for($i=1; $i<=2; $i++){
            $h->xls->write($h->rr + 1 , $h->cc + $i, "", $st['TH.GrayCenter']);
        }
        $h->xls->mergeCells($h->rr + 1, $h->cc, $h->rr + 1, $h->cc + 2);


        $h->xls->write($h->rr + 2, $h->cc, 'H', $st['TH.GrayCenter']); $h->cc++;
        $h->xls->write($h->rr + 2, $h->cc, 'M', $st['TH.GrayCenter']); $h->cc++;
        $h->xls->write($h->rr + 2, $h->cc, 'S', $st['TH.GrayCenter']); $h->cc++;

        $h->xls->write($h->rr, $h->cc, utf8_decode('5° Y 6° SEMESTRE'), $st['TH.GrayCenter']);
        for($i=1; $i<=3; $i++){
            $h->xls->write($h->rr , $h->cc + $i, "", $st['TH.GrayCenter']);
        }
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc + 3);


        $h->xls->write($h->rr + 1, $h->cc, 'G', $st['TH.GrayCenter']);
        $h->xls->write($h->rr + 2 , $h->cc, "", $st['TH.GrayCenter']);

        $h->xls->mergeCells($h->rr + 1, $h->cc, $h->rr + 2, $h->cc); $h->cc++;

        $h->xls->write($h->rr + 1, $h->cc, utf8_decode('ALUMNOS'), $st['TH.GrayCenter']);
        for($i=1; $i<=2; $i++){
            $h->xls->write($h->rr + 1 , $h->cc + $i, "", $st['TH.GrayCenter']);
        }
        $h->xls->mergeCells($h->rr + 1, $h->cc, $h->rr + 1, $h->cc + 2);


        $h->xls->write($h->rr + 2, $h->cc, 'H', $st['TH.GrayCenter']); $h->cc++;
        $h->xls->write($h->rr + 2, $h->cc, 'M', $st['TH.GrayCenter']); $h->cc++;
        $h->xls->write($h->rr + 2, $h->cc, 'S', $st['TH.GrayCenter']); $h->cc++;


        $h->xls->write($h->rr, $h->cc, utf8_decode('TOTALES'), $st['TH.GrayCenter']);
        for($i=1; $i<=3; $i++){
            $h->xls->write($h->rr , $h->cc + $i, "", $st['TH.GrayCenter']);
        }
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc + 3);


        $h->xls->write($h->rr + 1, $h->cc, 'G', $st['TH.GrayCenter']);
        $h->xls->write($h->rr + 2 , $h->cc, "", $st['TH.GrayCenter']);

        $h->xls->mergeCells($h->rr + 1, $h->cc, $h->rr + 2, $h->cc); $h->cc++;

        $h->xls->write($h->rr + 1, $h->cc, utf8_decode('ALUMNOS'), $st['TH.GrayCenter']);
        for($i=1; $i<=2; $i++){
            $h->xls->write($h->rr + 1 , $h->cc + $i, "", $st['TH.GrayCenter']);
        }
        $h->xls->mergeCells($h->rr + 1, $h->cc, $h->rr + 1, $h->cc + 2);


        $h->xls->write($h->rr + 2, $h->cc, 'H', $st['TH.GrayCenter']); $h->cc++;
        $h->xls->write($h->rr + 2, $h->cc, 'M', $st['TH.GrayCenter']); $h->cc++;
        $h->xls->write($h->rr + 2, $h->cc, 'S', $st['TH.GrayCenter']); $h->cc++;

        $h->nueva_linea();
        $h->nueva_linea();
        $h->nueva_linea();
        $h->nueva_linea();

        $ofertas = new Oferta();
        $ofertas = $ofertas->find();

        $cg12 = 0;
        $h12 = 0;
        $m12 = 0;
        $s12 = 0;

        $cg34 = 0;
        $h34 = 0;
        $m34 = 0;
        $s34 = 0;

        $cg56 = 0;
        $h56 = 0;
        $m56 = 0;
        $s56 = 0;

        $cgtt = 0;
        $htt = 0;
        $mtt = 0;
        $sstt = 0;


        foreach($ofertas as $oferta){
            $cht = 0;
            $cmt = 0;
            $cgt = 0;

            $h->xls->write($h->rr, $h->cc, $oferta->nombre, $st['TD.Normal']); $h->cc++;
            $h->xls->write($h->rr , $h->cc, "", $st['TD.Normal']);
            $h->xls->mergeCells($h->rr, $h->cc-1, $h->rr, $h->cc);$h->cc++;

            $h->xls->write($h->rr, $h->cc, $oferta->clave, $st['TD.Normal']);$h->cc++;

            $h->xls->write($h->rr, $h->cc, '', $st['TD.Normal']); $h->cc++;
            $h->xls->write($h->rr, $h->cc, '', $st['TD.Normal']); $h->cc++;

            $grados = " grupos.grado = 1 OR grupos.grado = 2 ";
            $cg = $this->cuentaGrupos($grados,$oferta->id);
            $cg12 += $cg;
            $cgt += $cg;

            $h->xls->write($h->rr, $h->cc, $cg, $st['TD.NormalCenter']); $h->cc++;

            $ch = $this->cuentaAlumnos($grados,'H',$oferta->id);
            $cht += $ch;
            $h12 += $ch;

            $h->xls->write($h->rr, $h->cc, $ch, $st['TD.NormalCenter']); $h->cc++;

            $cm = $this->cuentaAlumnos($grados,'M',$oferta->id);
            $cmt += $cm;
            $m12 += $cm;

            $h->xls->write($h->rr, $h->cc, $cm, $st['TD.NormalCenter']); $h->cc++;

            $s = $ch + $cm;
            $s12 += $s;
            $h->xls->write($h->rr, $h->cc, $s, $st['TD.NormalCenter']); $h->cc++;

            $grados = " grupos.grado = 3 OR grupos.grado = 4 ";

            $cg = $this->cuentaGrupos($grados,$oferta->id);
            $cg34 += $cg;
            $cgt += $cg;

            $h->xls->write($h->rr, $h->cc, $cg, $st['TD.NormalCenter']); $h->cc++;

            $ch = $this->cuentaAlumnos($grados,'H',$oferta->id);
            $cht += $ch;
            $h34 += $ch;

            $h->xls->write($h->rr, $h->cc, $ch, $st['TD.NormalCenter']); $h->cc++;

            $cm = $this->cuentaAlumnos($grados,'M',$oferta->id);
            $cmt += $cm;
            $m34 += $cm;

            $h->xls->write($h->rr, $h->cc, $cm, $st['TD.NormalCenter']); $h->cc++;

            $s = $ch + $cm;
            $s34 += $s;
            $h->xls->write($h->rr, $h->cc, $s, $st['TD.NormalCenter']); $h->cc++;


            $grados = " grupos.grado = 5 OR grupos.grado = 6 ";
            $cg = $this->cuentaGrupos($grados,$oferta->id);
            $cg56 += $cg;
            $cgt += $cg;

            $h->xls->write($h->rr, $h->cc, $cg, $st['TD.NormalCenter']); $h->cc++;

            $ch = $this->cuentaAlumnos($grados,'H',$oferta->id);
            $cht += $ch;
            $h56 += $ch;

            $h->xls->write($h->rr, $h->cc, $ch, $st['TD.NormalCenter']); $h->cc++;

            $cm = $this->cuentaAlumnos($grados,'M',$oferta->id);
            $cmt += $cm;
            $m56 += $cm;

            $h->xls->write($h->rr, $h->cc, $cm, $st['TD.NormalCenter']); $h->cc++;

            $s = $ch + $cm;
            $s56 += $s;
            $h->xls->write($h->rr, $h->cc, $s, $st['TD.NormalCenter']); $h->cc++;


            //Totales
            $h->xls->write($h->rr, $h->cc, $cgt, $st['TD.GrisCentro']); $h->cc++;
            $cgtt += $cgt;

            $htt += $cht;
            $h->xls->write($h->rr, $h->cc, $cht, $st['TD.GrisCentro']); $h->cc++;

            $mtt += $cmt;
            $h->xls->write($h->rr, $h->cc, $cmt, $st['TD.GrisCentro']); $h->cc++;

            $stt = $cht + $cmt;
            $sstt += $stt;
            $h->xls->write($h->rr, $h->cc, $stt, $st['TD.GrisCentro']); $h->cc++;

            $h->nueva_linea();

        }

        $h->nueva_linea();
        $h->nueva_linea();
            
        $h->cc++;
        $h->cc++;
            
        $h->xls->write($h->rr, $h->cc, 'TOTALES', $st['TD.GrisCentroBold']);
        for($i=1; $i<=2; $i++){
            $h->xls->write($h->rr, $h->cc + $i, "", $st['TD.GrisCentroBold']);
        }
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc + 2); $h->cc++;$h->cc++;$h->cc++;

        $h->xls->write($h->rr, $h->cc, $cg12, $st['TD.GrisCentro']); $h->cc++;
        $h->xls->write($h->rr, $h->cc, $h12 , $st['TD.GrisCentro']); $h->cc++;
        $h->xls->write($h->rr, $h->cc, $m12, $st['TD.GrisCentro']); $h->cc++;
        $h->xls->write($h->rr, $h->cc, $s12, $st['TD.GrisCentro']); $h->cc++;

        $h->xls->write($h->rr, $h->cc, $cg34, $st['TD.GrisCentro']); $h->cc++;
        $h->xls->write($h->rr, $h->cc, $h34 , $st['TD.GrisCentro']); $h->cc++;
        $h->xls->write($h->rr, $h->cc, $m34, $st['TD.GrisCentro']); $h->cc++;
        $h->xls->write($h->rr, $h->cc, $s34, $st['TD.GrisCentro']); $h->cc++;

        $h->xls->write($h->rr, $h->cc, $cg56, $st['TD.GrisCentro']); $h->cc++;
        $h->xls->write($h->rr, $h->cc, $h56 , $st['TD.GrisCentro']); $h->cc++;
        $h->xls->write($h->rr, $h->cc, $m56, $st['TD.GrisCentro']); $h->cc++;
        $h->xls->write($h->rr, $h->cc, $s56, $st['TD.GrisCentro']); $h->cc++;


        $h->xls->write($h->rr, $h->cc, $cgtt, $st['TD.GrisCentro']); $h->cc++;
        $h->xls->write($h->rr, $h->cc, $htt , $st['TD.GrisCentro']); $h->cc++;
        $h->xls->write($h->rr, $h->cc, $mtt, $st['TD.GrisCentro']); $h->cc++;
        $h->xls->write($h->rr, $h->cc, $sstt, $st['TD.GrisCentro']); $h->cc++;
            

        $h->nueva_linea();
        $h->nueva_linea();
        $h->xls->write($h->rr, $h->cc, utf8_decode('RESPONSABLE DE LA INFORMACIÓN'), $st['TH.GrayCenter']);
        $h->xls->write($h->rr, $h->cc + 1, "", $st['TH.GrayCenter']);

        $h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc + 1); $h->cc += 3;

        $h->xls->write($h->rr, $h->cc, utf8_decode('RESPONSABLE DE LA VALIDACIÓN'), $st['TH.GrayCenter']);
        for($i=1; $i<=7; $i++){
            $h->cc++;
            $h->xls->write($h->rr , $h->cc, "", $st['TH.GrayCenter']);
        }
        $h->xls->mergeCells($h->rr, $h->cc - 7, $h->rr, $h->cc );$h->cc++;
        $h->cc++;
        $h->cc++;
        $h->cc++;

        $h->xls->write($h->rr, $h->cc, utf8_decode('DIRECTOR GENERAL'), $st['TH.GrayCenter']);
        for($i=1; $i<=6; $i++){
            $h->cc++;
            $h->xls->write($h->rr , $h->cc, "", $st['TH.GrayCenter']);
        }
        $h->xls->mergeCells($h->rr, $h->cc - 6, $h->rr, $h->cc);

        $h->nueva_linea();
        $h->xls->write($h->rr, $h->cc, utf8_decode(''), $st['TD.Normal']);
        $h->xls->write($h->rr, $h->cc + 1, "", $st['TD.Normal']);

        $h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc + 1); $h->cc += 3;

        $h->xls->write($h->rr, $h->cc, '', $st['TD.Normal']);
        for($i=1; $i<=7; $i++){
            $h->cc++;
            $h->xls->write($h->rr , $h->cc, "", $st['TD.Normal']);
        }
        $h->xls->mergeCells($h->rr, $h->cc - 7, $h->rr, $h->cc );$h->cc++;
        $h->cc++;
        $h->cc++;
        $h->cc++;


        $h->xls->write($h->rr, $h->cc, '', $st['TD.Normal']);
        for($i=1; $i<=6; $i++){
            $h->cc++;
            $h->xls->write($h->rr , $h->cc, "", $st['TD.Normal']);
        }
        $h->xls->mergeCells($h->rr, $h->cc - 6, $h->rr, $h->cc );

        $h->nueva_linea();
        $h->xls->write($h->rr, $h->cc, 'Nombre', $st['TH.GrayCenter']);$h->cc++;
        $h->xls->write($h->rr, $h->cc, "", $st['TH.GrayCenter']);$h->cc++;$h->cc++;


        $h->xls->write($h->rr, $h->cc, 'Nombre', $st['TH.GrayCenter']);$h->cc++;
        $h->xls->write($h->rr , $h->cc, "", $st['TH.GrayCenter']);
        $h->xls->mergeCells($h->rr, $h->cc - 1, $h->rr, $h->cc );$h->cc++;

        $h->xls->write($h->rr, $h->cc, "", $st['TD.Normal']);
        for($i=1; $i<=5; $i++){
            $h->cc++;
            $h->xls->write($h->rr , $h->cc, "", $st['TD.Normal']);
        }
        $h->xls->mergeCells($h->rr, $h->cc - 5, $h->rr, $h->cc );$h->cc++;
        $h->cc++;
        $h->cc++;
        $h->cc++;


        $h->xls->write($h->rr, $h->cc, 'Nombre', $st['TH.GrayCenter']);$h->cc++;
        $h->xls->write($h->rr , $h->cc, "", $st['TH.GrayCenter']);
        $h->xls->mergeCells($h->rr, $h->cc - 1, $h->rr, $h->cc );$h->cc++;

        $h->xls->write($h->rr, $h->cc, "", $st['TD.Normal']);
        for($i=1; $i<=4; $i++){
            $h->cc++;
            $h->xls->write($h->rr , $h->cc, "", $st['TD.Normal']);
        }
        $h->xls->mergeCells($h->rr, $h->cc - 4, $h->rr, $h->cc );


        $h->nueva_linea();
        $h->xls->write($h->rr, $h->cc, 'Cargo', $st['TH.GrayCenter']);;$h->cc++;
        $h->xls->write($h->rr, $h->cc, "", $st['TH.GrayCenter']);$h->cc++;$h->cc++;


        $h->xls->write($h->rr, $h->cc, 'Cargo', $st['TH.GrayCenter']);$h->cc++;
        $h->xls->write($h->rr , $h->cc, "", $st['TH.GrayCenter']);
        $h->xls->mergeCells($h->rr, $h->cc - 1, $h->rr, $h->cc );$h->cc++;

        $h->xls->write($h->rr, $h->cc, "", $st['TD.Normal']);
        for($i=1; $i<=5; $i++){
            $h->cc++;
            $h->xls->write($h->rr , $h->cc, "", $st['TD.Normal']);
        }
        $h->xls->mergeCells($h->rr, $h->cc - 5, $h->rr, $h->cc );$h->cc++;
        $h->cc++;
        $h->cc++;
        $h->cc++;


        $h->xls->write($h->rr, $h->cc, 'Cargo', $st['TH.GrayCenter']);$h->cc++;
        $h->xls->write($h->rr , $h->cc, "", $st['TH.GrayCenter']);
        $h->xls->mergeCells($h->rr, $h->cc - 1, $h->rr, $h->cc );$h->cc++;

        $h->xls->write($h->rr, $h->cc, "", $st['TD.Normal']);
        for($i=1; $i<=4; $i++){
            $h->cc++;
            $h->xls->write($h->rr , $h->cc, "", $st['TD.Normal']);
        }
        $h->xls->mergeCells($h->rr, $h->cc - 4, $h->rr, $h->cc );


        $h->nueva_linea();
        $h->xls->write($h->rr, $h->cc, 'Firma', $st['TH.GrayCenter']);;$h->cc++;
        $h->xls->write($h->rr, $h->cc, "", $st['TH.GrayCenter']);$h->cc++;$h->cc++;


        $h->xls->write($h->rr, $h->cc, 'Firma', $st['TH.GrayCenter']);;$h->cc++;
        $h->xls->write($h->rr , $h->cc, "", $st['TH.GrayCenter']);
        $h->xls->mergeCells($h->rr, $h->cc - 1, $h->rr, $h->cc );$h->cc++;

        $h->xls->write($h->rr, $h->cc, "", $st['TD.Normal']);
        for($i=1; $i<=5; $i++){
            $h->cc++;
            $h->xls->write($h->rr , $h->cc, "", $st['TD.Normal']);
        }
        $h->xls->mergeCells($h->rr, $h->cc - 5, $h->rr, $h->cc );$h->cc++;
        $h->cc++;
        $h->cc++;
        $h->cc++;

        $h->xls->write($h->rr, $h->cc, 'Firma', $st['TH.GrayCenter']);$h->cc++;
        $h->xls->write($h->rr , $h->cc, "", $st['TH.GrayCenter']);
        $h->xls->mergeCells($h->rr, $h->cc - 1, $h->rr, $h->cc );$h->cc++;

        $h->xls->write($h->rr, $h->cc, "", $st['TD.Normal']);
        for($i=1; $i<=4; $i++){
            $h->cc++;
            $h->xls->write($h->rr , $h->cc, "", $st['TD.Normal']);
        }
        $h->xls->mergeCells($h->rr, $h->cc - 4, $h->rr, $h->cc );

        $h->cc_max = $h->cc;
        $h->nueva_linea();
        $h->rr_max = $h->rr;
    }

    public function contenido1(&$h){
        $this->encabezado1($h);
        $st = $this->getEstilos();
        $h->xls->write($h->rr, $h->cc, 'ESPECIALIDAD', $st['TH.GrayCenter']);
        for($i=1; $i<=2; $i++){
            $h->xls->write($h->rr + $i, $h->cc, "", $st['TH.GrayCenter']);
        }

        $h->xls->write($h->rr, $h->cc + 1, "", $st['TH.GrayCenter']);

        $h->xls->mergeCells($h->rr, $h->cc, $h->rr + 2, $h->cc + 1); $h->cc++;
        $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'SEMESTRE', $st['TH.GrayCenter']);
        for($i=1; $i<42; $i++){
            $h->xls->write($h->rr , $h->cc + $i, "", $st['TH.GrayCenter']);
        }

        $h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc + 41);

        for($y = 1; $y <= 6; $y++){
            $h->xls->write($h->rr + 1, $h->cc, utf8_decode($y . '°'), $st['TH.GrayCenter']);
            for($j=1; $j<=5; $j++){
                $h->cc++;
                $h->xls->write($h->rr +1 , $h->cc, "", $st['TH.GrayCenter']);
            }

            $h->xls->mergeCells($h->rr+1, $h->cc - 5, $h->rr+1, $h->cc);$h->cc++;

        }

        $h->xls->write($h->rr+1, $h->cc, 'TOTALES', $st['TH.GrayCenter']);
        for($j=1; $j<=5; $j++){
            $h->cc++;
            $h->xls->write($h->rr +1 , $h->cc, "", $st['TH.GrayCenter']);
        }

        $h->xls->mergeCells($h->rr+1, $h->cc - 5, $h->rr+1, $h->cc);$h->cc++;


        $labels = array('A','1','2','3','4','5 O MAS');
        $h->cc-=42;

        for($y = 1; $y <= 7; $y++){
            foreach($labels as $label){
                $h->xls->write($h->rr + 2, $h->cc, $label, $st['TH.GrayCenter']);
                $h->cc++;
            }
        }

        $h->xls->mergeCells($h->rr+1, $h->cc - 5, $h->rr+1, $h->cc);$h->cc++;

        $h->nueva_linea();
        $h->nueva_linea();
        $h->nueva_linea();

        $ofertas = new Oferta();
        $ofertas = $ofertas->find();

        $cg12 = 0;
        $h12 = 0;
        $m12 = 0;
        $s12 = 0;

        $cg34 = 0;
        $h34 = 0;
        $m34 = 0;
        $s34 = 0;

        $cg56 = 0;
        $h56 = 0;
        $m56 = 0;
        $s56 = 0;

        $cgtt = 0;
        $htt = 0;
        $mtt = 0;
        $sstt = 0;

        $tabla = $this->datos();

        $rrcache = $h->rr + 1;
        $fila = 0;
        foreach($ofertas as $oferta){
            $cht = 0;
            $cmt = 0;
            $cgt = 0;

            $h->xls->write($h->rr, $h->cc, $oferta->nombre, $st['TD.Normal']); $h->cc++;
            $h->xls->write($h->rr , $h->cc, "", $st['TD.Normal']);
            $h->xls->mergeCells($h->rr, $h->cc-1, $h->rr, $h->cc);$h->cc++;

            //Tabla
            for($y = 1; $y <= 6; $y++){
                for($j=0; $j<=5; $j++){
                    $celda = $tabla[$oferta->id][$y][$j];
                    if($celda == ''){
                        $celda = '0';
                    }
                    
                    $h->xls->write($h->rr , $h->cc, $celda, $st['TD.Normal']);
                    $h->cc++;

                }
            }
            
            $h->xls->writeFormula($h->rr, $h->cc, "=SUM(C".($rrcache + $fila)."+I".($rrcache + $fila)."+O".($rrcache + $fila)."+U".($rrcache + $fila)."+AA".($rrcache + $fila)."+AG".($rrcache + $fila).")",$st['TD.GrisCentro']);$h->cc++;
            $h->xls->writeFormula($h->rr, $h->cc, "=SUM(D".($rrcache + $fila)."+J".($rrcache + $fila)."+P".($rrcache + $fila)."+V".($rrcache + $fila)."+AB".($rrcache + $fila)."+AH".($rrcache + $fila).")",$st['TD.GrisCentro']);$h->cc++;
            $h->xls->writeFormula($h->rr, $h->cc, "=SUM(E".($rrcache + $fila)."+K".($rrcache + $fila)."+Q".($rrcache + $fila)."+W".($rrcache + $fila)."+AC".($rrcache + $fila)."+AI".($rrcache + $fila).")",$st['TD.GrisCentro']);$h->cc++;
            $h->xls->writeFormula($h->rr, $h->cc, "=SUM(F".($rrcache + $fila)."+L".($rrcache + $fila)."+R".($rrcache + $fila)."+X".($rrcache + $fila)."+AD".($rrcache + $fila)."+AJ".($rrcache + $fila).")",$st['TD.GrisCentro']);$h->cc++;
            $h->xls->writeFormula($h->rr, $h->cc, "=SUM(G".($rrcache + $fila)."+M".($rrcache + $fila)."+S".($rrcache + $fila)."+Y".($rrcache + $fila)."+AE".($rrcache + $fila)."+AK".($rrcache + $fila).")",$st['TD.GrisCentro']);$h->cc++;
            $h->xls->writeFormula($h->rr, $h->cc, "=SUM(H".($rrcache + $fila)."+N".($rrcache + $fila)."+T".($rrcache + $fila)."+Z".($rrcache + $fila)."+AF".($rrcache + $fila)."+AL".($rrcache + $fila).")",$st['TD.GrisCentro']);$h->cc++;
            
            
            $h->nueva_linea();
            $fila++;

        }
        
        $h->xls->write($h->rr, $h->cc, 'TOTALES', $st['TD.GrisCentroBold']);$h->cc++;
        $h->xls->write($h->rr, $h->cc, "", $st['TD.GrisDerechoBold']);
        $h->xls->mergeCells($h->rr, $h->cc - 1, $h->rr, $h->cc);$h->cc++;
        $cofertas = count($ofertas)-1;
    
        $h->xls->writeFormula($h->rr, $h->cc, "=SUM(C".$rrcache.":C".($rrcache + $cofertas).")",$st['TD.GrisCentro']);$h->cc++;
        $h->xls->writeFormula($h->rr, $h->cc, "=SUM(D".$rrcache.":D".($rrcache + $cofertas).")",$st['TD.GrisCentro']);$h->cc++;
        $h->xls->writeFormula($h->rr, $h->cc, "=SUM(E".$rrcache.":E".($rrcache + $cofertas).")",$st['TD.GrisCentro']);$h->cc++;
        $h->xls->writeFormula($h->rr, $h->cc, "=SUM(F".$rrcache.":F".($rrcache + $cofertas).")",$st['TD.GrisCentro']);$h->cc++;
        $h->xls->writeFormula($h->rr, $h->cc, "=SUM(G".$rrcache.":G".($rrcache + $cofertas).")",$st['TD.GrisCentro']);$h->cc++;        
        $h->xls->writeFormula($h->rr, $h->cc, "=SUM(H".$rrcache.":H".($rrcache + $cofertas).")",$st['TD.GrisCentro']);$h->cc++;
        
        $h->xls->writeFormula($h->rr, $h->cc, "=SUM(I".$rrcache.":I".($rrcache + $cofertas).")",$st['TD.GrisCentro']);$h->cc++;
        $h->xls->writeFormula($h->rr, $h->cc, "=SUM(J".$rrcache.":J".($rrcache + $cofertas).")",$st['TD.GrisCentro']);$h->cc++;
        $h->xls->writeFormula($h->rr, $h->cc, "=SUM(K".$rrcache.":K".($rrcache + $cofertas).")",$st['TD.GrisCentro']);$h->cc++;
        $h->xls->writeFormula($h->rr, $h->cc, "=SUM(L".$rrcache.":L".($rrcache + $cofertas).")",$st['TD.GrisCentro']);$h->cc++;
        $h->xls->writeFormula($h->rr, $h->cc, "=SUM(M".$rrcache.":M".($rrcache + $cofertas).")",$st['TD.GrisCentro']);$h->cc++;
        
        $h->xls->writeFormula($h->rr, $h->cc, "=SUM(N".$rrcache.":N".($rrcache + $cofertas).")",$st['TD.GrisCentro']);$h->cc++;
        $h->xls->writeFormula($h->rr, $h->cc, "=SUM(O".$rrcache.":O".($rrcache + $cofertas).")",$st['TD.GrisCentro']);$h->cc++;
        $h->xls->writeFormula($h->rr, $h->cc, "=SUM(P".$rrcache.":P".($rrcache + $cofertas).")",$st['TD.GrisCentro']);$h->cc++;
        $h->xls->writeFormula($h->rr, $h->cc, "=SUM(Q".$rrcache.":Q".($rrcache + $cofertas).")",$st['TD.GrisCentro']);$h->cc++;
        $h->xls->writeFormula($h->rr, $h->cc, "=SUM(R".$rrcache.":R".($rrcache + $cofertas).")",$st['TD.GrisCentro']);$h->cc++;
        
        $h->xls->writeFormula($h->rr, $h->cc, "=SUM(S".$rrcache.":S".($rrcache + $cofertas).")",$st['TD.GrisCentro']);$h->cc++;
        $h->xls->writeFormula($h->rr, $h->cc, "=SUM(T".$rrcache.":T".($rrcache + $cofertas).")",$st['TD.GrisCentro']);$h->cc++;
        $h->xls->writeFormula($h->rr, $h->cc, "=SUM(U".$rrcache.":U".($rrcache + $cofertas).")",$st['TD.GrisCentro']);$h->cc++;
        $h->xls->writeFormula($h->rr, $h->cc, "=SUM(V".$rrcache.":V".($rrcache + $cofertas).")",$st['TD.GrisCentro']);$h->cc++;
        $h->xls->writeFormula($h->rr, $h->cc, "=SUM(W".$rrcache.":W".($rrcache + $cofertas).")",$st['TD.GrisCentro']);$h->cc++;
        
        $h->xls->writeFormula($h->rr, $h->cc, "=SUM(X".$rrcache.":X".($rrcache + $cofertas).")",$st['TD.GrisCentro']);$h->cc++;
        $h->xls->writeFormula($h->rr, $h->cc, "=SUM(Y".$rrcache.":Y".($rrcache + $cofertas).")",$st['TD.GrisCentro']);$h->cc++;
        $h->xls->writeFormula($h->rr, $h->cc, "=SUM(Z".$rrcache.":Z".($rrcache + $cofertas).")",$st['TD.GrisCentro']);$h->cc++;
        $h->xls->writeFormula($h->rr, $h->cc, "=SUM(AA".$rrcache.":AA".($rrcache + $cofertas).")",$st['TD.GrisCentro']);$h->cc++;        

        $h->xls->writeFormula($h->rr, $h->cc, "=SUM(AB".$rrcache.":AB".($rrcache + $cofertas).")",$st['TD.GrisCentro']);$h->cc++;
        $h->xls->writeFormula($h->rr, $h->cc, "=SUM(AC".$rrcache.":AC".($rrcache + $cofertas).")",$st['TD.GrisCentro']);$h->cc++;
        $h->xls->writeFormula($h->rr, $h->cc, "=SUM(AD".$rrcache.":AD".($rrcache + $cofertas).")",$st['TD.GrisCentro']);$h->cc++;
        $h->xls->writeFormula($h->rr, $h->cc, "=SUM(AE".$rrcache.":AE".($rrcache + $cofertas).")",$st['TD.GrisCentro']);$h->cc++;
        $h->xls->writeFormula($h->rr, $h->cc, "=SUM(AF".$rrcache.":AF".($rrcache + $cofertas).")",$st['TD.GrisCentro']);$h->cc++;
        
        $h->xls->writeFormula($h->rr, $h->cc, "=SUM(AG".$rrcache.":AG".($rrcache + $cofertas).")",$st['TD.GrisCentro']);$h->cc++;
        $h->xls->writeFormula($h->rr, $h->cc, "=SUM(AH".$rrcache.":AH".($rrcache + $cofertas).")",$st['TD.GrisCentro']);$h->cc++;
        $h->xls->writeFormula($h->rr, $h->cc, "=SUM(AI".$rrcache.":AI".($rrcache + $cofertas).")",$st['TD.GrisCentro']);$h->cc++;
        $h->xls->writeFormula($h->rr, $h->cc, "=SUM(AJ".$rrcache.":AJ".($rrcache + $cofertas).")",$st['TD.GrisCentro']);$h->cc++;
        $h->xls->writeFormula($h->rr, $h->cc, "=SUM(AK".$rrcache.":AK".($rrcache + $cofertas).")",$st['TD.GrisCentro']);$h->cc++;
        
        $h->xls->writeFormula($h->rr, $h->cc, "=SUM(AL".$rrcache.":AL".($rrcache + $cofertas).")",$st['TD.GrisCentro']);$h->cc++;
        $h->xls->writeFormula($h->rr, $h->cc, "=SUM(AM".$rrcache.":AM".($rrcache + $cofertas).")",$st['TD.GrisCentro']);$h->cc++;
        $h->xls->writeFormula($h->rr, $h->cc, "=SUM(AN".$rrcache.":AN".($rrcache + $cofertas).")",$st['TD.GrisCentro']);$h->cc++;
        $h->xls->writeFormula($h->rr, $h->cc, "=SUM(AO".$rrcache.":AO".($rrcache + $cofertas).")",$st['TD.GrisCentro']);$h->cc++;
        $h->xls->writeFormula($h->rr, $h->cc, "=SUM(AP".$rrcache.":AP".($rrcache + $cofertas).")",$st['TD.GrisCentro']);$h->cc++;
        $h->xls->writeFormula($h->rr, $h->cc, "=SUM(AQ".$rrcache.":AQ".($rrcache + $cofertas).")",$st['TD.GrisCentro']);$h->cc++;        
        $h->xls->writeFormula($h->rr, $h->cc, "=SUM(AR".$rrcache.":AR".($rrcache + $cofertas).")",$st['TD.GrisCentro']);$h->cc++;        
        $rrtotal = $h->rr + 1;    
        $h->nueva_linea();
        $h->xls->write($h->rr, $h->cc, utf8_decode('REPROBACIÓN'), $st['TD.GrisCentro']);
        $h->xls->write($h->rr, $h->cc + 1, "", $st['TD.GrisCentro']);
        $h->xls->write($h->rr+1, $h->cc, "", $st['TD.GrisCentro']);
        $h->xls->write($h->rr+1, $h->cc+1, "", $st['TD.GrisCentro']);
                
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr+1, $h->cc + 1);$h->cc++;$h->cc++;
        
        /////////////////////
        //1
        $h->xls->writeFormula($h->rr, $h->cc, "=SUM(D".$rrtotal.":H".$rrtotal.")", $st['TD.GrisCentro']);
        $h->xls->write($h->rr, $h->cc + 1, "", $st['TD.GrisCentro']);
        $h->xls->write($h->rr, $h->cc + 2, "", $st['TD.GrisCentro']);
        
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc + 2);
        
        $h->xls->writeFormula($h->rr + 1, $h->cc , "=C".$rrtotal, $st['TD.GrisCentro']);
        $h->xls->write($h->rr + 1, $h->cc + 1, "", $st['TD.GrisCentro']);
        $h->xls->write($h->rr + 1, $h->cc + 2, "", $st['TD.GrisCentro']);
        $h->xls->mergeCells($h->rr+ 1, $h->cc, $h->rr+ 1, $h->cc + 2);
        $h->cc++;$h->cc++;$h->cc++;
        
        $h->xls->write($h->rr, $h->cc, "'=", $st['TD.GrisCentro']);
        $h->xls->write($h->rr + 1, $h->cc, "", $st['TD.GrisCentro']);
                
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr+1, $h->cc);
        $h->cc++;
        
        $h->xls->writeFormula($h->rr, $h->cc, "=(C".($rrtotal + 1)."/C".($rrtotal + 2).")*100", $st['TD.GrisCentro']);
        $h->xls->write($h->rr, $h->cc + 1, "", $st['TD.GrisCentro']);
        $h->xls->write($h->rr + 1, $h->cc, "", $st['TD.GrisCentro']);
        $h->xls->write($h->rr + 1, $h->cc + 1, "", $st['TD.GrisCentro']);
                
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr+1, $h->cc +1);$h->cc++;
        $h->cc++;
        
        ///////////////////
        //2
        $h->xls->writeFormula($h->rr, $h->cc, "=SUM(J".$rrtotal.":N".$rrtotal.")", $st['TD.GrisCentro']);
        $h->xls->write($h->rr, $h->cc + 1, "", $st['TD.GrisCentro']);
        $h->xls->write($h->rr, $h->cc + 2, "", $st['TD.GrisCentro']);
        
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc + 2);
        
        $h->xls->writeFormula($h->rr + 1, $h->cc , "=I".$rrtotal, $st['TD.GrisCentro']);
        $h->xls->write($h->rr + 1, $h->cc + 1, "", $st['TD.GrisCentro']);
        $h->xls->write($h->rr + 1, $h->cc + 2, "", $st['TD.GrisCentro']);
        $h->xls->mergeCells($h->rr+ 1, $h->cc, $h->rr+ 1, $h->cc + 2);
        $h->cc++;$h->cc++;$h->cc++;
        
        $h->xls->write($h->rr, $h->cc, "'=", $st['TD.GrisCentro']);
        $h->xls->write($h->rr + 1, $h->cc, "", $st['TD.GrisCentro']);
                
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr+1, $h->cc);
        $h->cc++;
        
        $h->xls->writeFormula($h->rr, $h->cc, "=(I".($rrtotal + 1)."/I".($rrtotal + 2).")*100", $st['TD.GrisCentro']);
        $h->xls->write($h->rr, $h->cc + 1, "", $st['TD.GrisCentro']);
        $h->xls->write($h->rr + 1, $h->cc, "", $st['TD.GrisCentro']);
        $h->xls->write($h->rr + 1, $h->cc + 1, "", $st['TD.GrisCentro']);
                
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr+1, $h->cc +1);$h->cc++;
        $h->cc++;
        
        ////
        //3
        $h->xls->writeFormula($h->rr, $h->cc, "=SUM(P".$rrtotal.":T".$rrtotal.")", $st['TD.GrisCentro']);
        $h->xls->write($h->rr, $h->cc + 1, "", $st['TD.GrisCentro']);
        $h->xls->write($h->rr, $h->cc + 2, "", $st['TD.GrisCentro']);
        
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc + 2);
        
        $h->xls->writeFormula($h->rr + 1, $h->cc , "=O".$rrtotal, $st['TD.GrisCentro']);
        $h->xls->write($h->rr + 1, $h->cc + 1, "", $st['TD.GrisCentro']);
        $h->xls->write($h->rr + 1, $h->cc + 2, "", $st['TD.GrisCentro']);
        $h->xls->mergeCells($h->rr+ 1, $h->cc, $h->rr+ 1, $h->cc + 2);
        $h->cc++;$h->cc++;$h->cc++;
        
        $h->xls->write($h->rr, $h->cc, "'=", $st['TD.GrisCentro']);
        $h->xls->write($h->rr + 1, $h->cc, "", $st['TD.GrisCentro']);
                
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr+1, $h->cc);
        $h->cc++;
        
        $h->xls->writeFormula($h->rr, $h->cc, "=(O".($rrtotal + 1)."/O".($rrtotal + 2).")*100", $st['TD.GrisCentro']);
        $h->xls->write($h->rr, $h->cc + 1, "", $st['TD.GrisCentro']);
        $h->xls->write($h->rr + 1, $h->cc, "", $st['TD.GrisCentro']);
        $h->xls->write($h->rr + 1, $h->cc + 1, "", $st['TD.GrisCentro']);
                
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr+1, $h->cc +1);$h->cc++;
        $h->cc++;
        
        ////
        //4
        $h->xls->writeFormula($h->rr, $h->cc, "=SUM(V".$rrtotal.":Z".$rrtotal.")", $st['TD.GrisCentro']);
        $h->xls->write($h->rr, $h->cc + 1, "", $st['TD.GrisCentro']);
        $h->xls->write($h->rr, $h->cc + 2, "", $st['TD.GrisCentro']);
        
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc + 2);
        
        $h->xls->writeFormula($h->rr + 1, $h->cc , "=U".$rrtotal, $st['TD.GrisCentro']);
        $h->xls->write($h->rr + 1, $h->cc + 1, "", $st['TD.GrisCentro']);
        $h->xls->write($h->rr + 1, $h->cc + 2, "", $st['TD.GrisCentro']);
        $h->xls->mergeCells($h->rr+ 1, $h->cc, $h->rr+ 1, $h->cc + 2);
        $h->cc++;$h->cc++;$h->cc++;
        
        $h->xls->write($h->rr, $h->cc, "'=", $st['TD.GrisCentro']);
        $h->xls->write($h->rr + 1, $h->cc, "", $st['TD.GrisCentro']);
                
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr+1, $h->cc);
        $h->cc++;
        
        $h->xls->writeFormula($h->rr, $h->cc, "=(U".($rrtotal + 1)."/U".($rrtotal + 2).")*100", $st['TD.GrisCentro']);
        $h->xls->write($h->rr, $h->cc + 1, "", $st['TD.GrisCentro']);
        $h->xls->write($h->rr + 1, $h->cc, "", $st['TD.GrisCentro']);
        $h->xls->write($h->rr + 1, $h->cc + 1, "", $st['TD.GrisCentro']);
                
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr+1, $h->cc +1);$h->cc++;
        $h->cc++;
        
        ////
        //5
        $h->xls->writeFormula($h->rr, $h->cc, "=SUM(AB".$rrtotal.":AF".$rrtotal.")", $st['TD.GrisCentro']);
        $h->xls->write($h->rr, $h->cc + 1, "", $st['TD.GrisCentro']);
        $h->xls->write($h->rr, $h->cc + 2, "", $st['TD.GrisCentro']);
        
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc + 2);
        
        $h->xls->writeFormula($h->rr + 1, $h->cc , "=AA".$rrtotal, $st['TD.GrisCentro']);
        $h->xls->write($h->rr + 1, $h->cc + 1, "", $st['TD.GrisCentro']);
        $h->xls->write($h->rr + 1, $h->cc + 2, "", $st['TD.GrisCentro']);
        $h->xls->mergeCells($h->rr+ 1, $h->cc, $h->rr+ 1, $h->cc + 2);
        $h->cc++;$h->cc++;$h->cc++;
        
        $h->xls->write($h->rr, $h->cc, "'=", $st['TD.GrisCentro']);
        $h->xls->write($h->rr + 1, $h->cc, "", $st['TD.GrisCentro']);
                
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr+1, $h->cc);
        $h->cc++;
        
        $h->xls->writeFormula($h->rr, $h->cc, "=(AA".($rrtotal + 1)."/AA".($rrtotal + 2).")*100", $st['TD.GrisCentro']);
        $h->xls->write($h->rr, $h->cc + 1, "", $st['TD.GrisCentro']);
        $h->xls->write($h->rr + 1, $h->cc, "", $st['TD.GrisCentro']);
        $h->xls->write($h->rr + 1, $h->cc + 1, "", $st['TD.GrisCentro']);
                
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr+1, $h->cc +1);$h->cc++;
        $h->cc++;
        
        ////
        //6
        $h->xls->writeFormula($h->rr, $h->cc, "=SUM(AH".$rrtotal.":AL".$rrtotal.")", $st['TD.GrisCentro']);
        $h->xls->write($h->rr, $h->cc + 1, "", $st['TD.GrisCentro']);
        $h->xls->write($h->rr, $h->cc + 2, "", $st['TD.GrisCentro']);
        
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc + 2);
        
        $h->xls->writeFormula($h->rr + 1, $h->cc , "=AG".$rrtotal, $st['TD.GrisCentro']);
        $h->xls->write($h->rr + 1, $h->cc + 1, "", $st['TD.GrisCentro']);
        $h->xls->write($h->rr + 1, $h->cc + 2, "", $st['TD.GrisCentro']);
        $h->xls->mergeCells($h->rr+ 1, $h->cc, $h->rr+ 1, $h->cc + 2);
        $h->cc++;$h->cc++;$h->cc++;
        
        $h->xls->write($h->rr, $h->cc, "'=", $st['TD.GrisCentro']);
        $h->xls->write($h->rr + 1, $h->cc, "", $st['TD.GrisCentro']);
                
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr+1, $h->cc);
        $h->cc++;
        
        $h->xls->writeFormula($h->rr, $h->cc, "=(AG".($rrtotal + 1)."/AG".($rrtotal + 2).")*100", $st['TD.GrisCentro']);
        $h->xls->write($h->rr, $h->cc + 1, "", $st['TD.GrisCentro']);
        $h->xls->write($h->rr + 1, $h->cc, "", $st['TD.GrisCentro']);
        $h->xls->write($h->rr + 1, $h->cc + 1, "", $st['TD.GrisCentro']);
                
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr+1, $h->cc +1);$h->cc++;
        $h->cc++;
        
        ////
        //Totales
        $h->xls->writeFormula($h->rr, $h->cc, "=SUM(AN".$rrtotal.":AR".$rrtotal.")", $st['TD.GrisCentro']);
        $h->xls->write($h->rr, $h->cc + 1, "", $st['TD.GrisCentro']);
        $h->xls->write($h->rr, $h->cc + 2, "", $st['TD.GrisCentro']);
        
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc + 2);
        
        $h->xls->writeFormula($h->rr + 1, $h->cc , "=AM".$rrtotal, $st['TD.GrisCentro']);
        $h->xls->write($h->rr + 1, $h->cc + 1, "", $st['TD.GrisCentro']);
        $h->xls->write($h->rr + 1, $h->cc + 2, "", $st['TD.GrisCentro']);
        $h->xls->mergeCells($h->rr+ 1, $h->cc, $h->rr+ 1, $h->cc + 2);
        $h->cc++;$h->cc++;$h->cc++;
        
        $h->xls->write($h->rr, $h->cc, "'=", $st['TD.GrisCentro']);
        $h->xls->write($h->rr + 1, $h->cc, "", $st['TD.GrisCentro']);
                
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr+1, $h->cc);
        $h->cc++;
        
        $h->xls->writeFormula($h->rr, $h->cc, "=(AM".($rrtotal + 1)."/AM".($rrtotal + 2).")*100", $st['TD.GrisCentro']);
        $h->xls->write($h->rr, $h->cc + 1, "", $st['TD.GrisCentro']);
        $h->xls->write($h->rr + 1, $h->cc, "", $st['TD.GrisCentro']);
        $h->xls->write($h->rr + 1, $h->cc + 1, "", $st['TD.GrisCentro']);
                
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr+1, $h->cc +1);$h->cc++;
        $h->cc++;
        $h->cc_max = $h->cc;
                
        $h->nueva_linea();
        $h->nueva_linea();
                    

        $h->nueva_linea();
        $h->nueva_linea();
        $h->xls->write($h->rr, $h->cc, utf8_decode('RESPONSABLE DE LA INFORMACIÓN'), $st['TH.GrayCenter']);
        for($i=1; $i<=7; $i++){
            $h->cc++;
            $h->xls->write($h->rr , $h->cc, "", $st['TH.GrayCenter']);
        }
        
        $h->xls->mergeCells($h->rr, $h->cc-7, $h->rr, $h->cc ); $h->cc += 6;

        $h->xls->write($h->rr, $h->cc, utf8_decode('RESPONSABLE DE LA VALIDACIÓN'), $st['TH.GrayCenter']);
        for($i=1; $i<=12; $i++){
            $h->cc++;
            $h->xls->write($h->rr , $h->cc, "", $st['TH.GrayCenter']);
        }
        $h->xls->mergeCells($h->rr, $h->cc - 12, $h->rr, $h->cc );
        $h->cc += 6;
        

        $h->xls->write($h->rr, $h->cc, utf8_decode('DIRECTOR GENERAL'), $st['TH.GrayCenter']);
        for($i=1; $i<=12; $i++){
            $h->cc++;
            $h->xls->write($h->rr , $h->cc, "", $st['TH.GrayCenter']);
        }
        $h->xls->mergeCells($h->rr, $h->cc - 12, $h->rr, $h->cc);

        $h->nueva_linea();
        $h->xls->write($h->rr, $h->cc, '', $st['TD.Normal']);
        for($i=1; $i<=7; $i++){
            $h->cc++;
            $h->xls->write($h->rr , $h->cc, "", $st['TD.Normal']);
        }
        
        $h->xls->mergeCells($h->rr, $h->cc-7, $h->rr, $h->cc); $h->cc += 6;

        $h->xls->write($h->rr, $h->cc, '', $st['TD.Normal']);
        for($i=1; $i<=12; $i++){
            $h->cc++;
            $h->xls->write($h->rr , $h->cc, "", $st['TD.Normal']);
        }
        $h->xls->mergeCells($h->rr, $h->cc - 12, $h->rr, $h->cc );
        $h->cc += 6;

        $h->xls->write($h->rr, $h->cc, '', $st['TD.Normal']);
        for($i=1; $i<=12; $i++){
            $h->cc++;
            $h->xls->write($h->rr , $h->cc, "", $st['TD.Normal']);
        }
        $h->xls->mergeCells($h->rr, $h->cc - 12, $h->rr, $h->cc );

        $h->nueva_linea();
        $h->xls->write($h->rr, $h->cc, 'Nombre', $st['TH.GrayCenter']);$h->cc++;
        $h->xls->write($h->rr , $h->cc, "", $st['TH.GrayCenter']);
        for($i=1; $i<=6; $i++){
            $h->cc++;
            $h->xls->write($h->rr , $h->cc, "", $st['TH.GrayCenter']);
        }
        $h->xls->mergeCells($h->rr, $h->cc-6, $h->rr, $h->cc ); $h->cc += 6;
                


        $h->xls->write($h->rr, $h->cc, 'Nombre', $st['TH.GrayCenter']);$h->cc++;
        $h->xls->write($h->rr , $h->cc, "", $st['TH.GrayCenter']);
        $h->xls->mergeCells($h->rr, $h->cc - 1, $h->rr, $h->cc );$h->cc++;

        $h->xls->write($h->rr, $h->cc, "", $st['TD.Normal']);
        for($i=1; $i<=10; $i++){
            $h->cc++;
            $h->xls->write($h->rr , $h->cc, "", $st['TD.Normal']);
        }
        $h->xls->mergeCells($h->rr, $h->cc - 10, $h->rr, $h->cc ); 
        $h->cc += 6;
        

        $h->xls->write($h->rr, $h->cc, 'Nombre', $st['TH.GrayCenter']);$h->cc++;
        $h->xls->write($h->rr , $h->cc, "", $st['TH.GrayCenter']);
        $h->xls->mergeCells($h->rr, $h->cc - 1, $h->rr, $h->cc );$h->cc++;

        $h->xls->write($h->rr, $h->cc, "", $st['TD.Normal']);
        for($i=1; $i<=10; $i++){
            $h->cc++;
            $h->xls->write($h->rr , $h->cc, "", $st['TD.Normal']);
        }
        $h->xls->mergeCells($h->rr, $h->cc - 10, $h->rr, $h->cc );


        $h->nueva_linea();
        $h->xls->write($h->rr, $h->cc, 'Cargo', $st['TH.GrayCenter']);$h->cc++;
        $h->xls->write($h->rr , $h->cc, "", $st['TH.GrayCenter']);
        for($i=1; $i<=6; $i++){
            $h->cc++;
            $h->xls->write($h->rr , $h->cc, "", $st['TH.GrayCenter']);
        }
        $h->xls->mergeCells($h->rr, $h->cc-6, $h->rr, $h->cc ); $h->cc += 6;

        $h->xls->write($h->rr, $h->cc, 'Cargo', $st['TH.GrayCenter']);$h->cc++;
        $h->xls->write($h->rr , $h->cc, "", $st['TH.GrayCenter']);
        $h->xls->mergeCells($h->rr, $h->cc - 1, $h->rr, $h->cc );$h->cc++;

        $h->xls->write($h->rr, $h->cc, "", $st['TD.Normal']);
        for($i=1; $i<=10; $i++){
            $h->cc++;
            $h->xls->write($h->rr , $h->cc, "", $st['TD.Normal']);
        }
        $h->xls->mergeCells($h->rr, $h->cc - 10, $h->rr, $h->cc );
        $h->cc += 6;
        

        $h->xls->write($h->rr, $h->cc, 'Cargo', $st['TH.GrayCenter']);$h->cc++;
        $h->xls->write($h->rr , $h->cc, "", $st['TH.GrayCenter']);
        $h->xls->mergeCells($h->rr, $h->cc - 1, $h->rr, $h->cc );$h->cc++;

        $h->xls->write($h->rr, $h->cc, "", $st['TD.Normal']);
        for($i=1; $i<=10; $i++){
            $h->cc++;
            $h->xls->write($h->rr , $h->cc, "", $st['TD.Normal']);
        }
        $h->xls->mergeCells($h->rr, $h->cc - 10, $h->rr, $h->cc );


        $h->nueva_linea();
        $h->xls->write($h->rr, $h->cc, 'Firma', $st['TH.GrayCenter']);;$h->cc++;
        $h->xls->write($h->rr, $h->cc, "", $st['TH.GrayCenter']);
        for($i=1; $i<=6; $i++){
            $h->cc++;
            $h->xls->write($h->rr , $h->cc, "", $st['TH.GrayCenter']);
        }
        $h->xls->mergeCells($h->rr, $h->cc-6, $h->rr, $h->cc ); $h->cc += 6;
                

        $h->xls->write($h->rr, $h->cc, 'Firma', $st['TH.GrayCenter']);$h->cc++;
        $h->xls->write($h->rr , $h->cc, "", $st['TH.GrayCenter']);
        $h->xls->mergeCells($h->rr, $h->cc - 1, $h->rr, $h->cc );$h->cc++;

        $h->xls->write($h->rr, $h->cc, "", $st['TD.Normal']);
        for($i=1; $i<=10; $i++){
            $h->cc++;
            $h->xls->write($h->rr , $h->cc, "", $st['TD.Normal']);
        }
        $h->xls->mergeCells($h->rr, $h->cc - 10, $h->rr, $h->cc );
        $h->cc += 6;
        

        $h->xls->write($h->rr, $h->cc, 'Firma', $st['TH.GrayCenter']);$h->cc++;
        $h->xls->write($h->rr , $h->cc, "", $st['TH.GrayCenter']);
        $h->xls->mergeCells($h->rr, $h->cc - 1, $h->rr, $h->cc );$h->cc++;

        $h->xls->write($h->rr, $h->cc, "", $st['TD.Normal']);
        for($i=1; $i<=10; $i++){
            $h->cc++;
            $h->xls->write($h->rr , $h->cc, "", $st['TD.Normal']);
        }
        $h->xls->mergeCells($h->rr, $h->cc - 10, $h->rr, $h->cc );

        $h->nueva_linea();
        $h->rr_max = $h->rr;
    }

    public function encabezado1(&$h){
        $template=new Template();
        $st = $this->getEstilos();
        $h->xls->insertBitmap($h->rr, $h->cc, getcwd() . '/public/img/sp5/sep.bmp', 0, 0, .8, .8);
        $h->cc++;
        $h->xls->write($h->rr, $h->cc, utf8_decode('COORDINACIÓN DE ORGANISMOS DESCENTRALIZADOS'), $st['H1']);
        for($i=1; $i<=38; $i++){
            $h->cc++;
            $h->xls->write($h->rr, $h->cc, "", $st['H1']);
        }
        $h->xls->mergeCells($h->rr, $h->cc-38, $h->rr, $h->cc);
        $h->cc++;

        $h->xls->insertBitmap($h->rr, $h->cc, getcwd() . '/public/img/sp5/cecytes.bmp', 0, 0, .8, .8);
        $h->nueva_linea();
        $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'ESTATALES DE CECyTEs', $st['H1']);
        for($i=1; $i<=38; $i++){
            $h->cc++;
            $h->xls->write($h->rr, $h->cc, "", $st['H1']);
        }
        $h->xls->mergeCells($h->rr, $h->cc-38, $h->rr, $h->cc);

        $h->nueva_linea();
        $h->nueva_linea();
        $h->xls->write($h->rr, $h->cc, utf8_decode('                          ESTADÍSTICA BÁSICA'), $st['H1']);
        for($i=1; $i<=38; $i++){
            $h->cc++;
            $h->xls->write($h->rr, $h->cc, "", $st['H1']);
        }
        $h->xls->mergeCells($h->rr, $h->cc-38, $h->rr, $h->cc);
        $h->nueva_linea();
        $h->xls->write($h->rr, $h->cc, utf8_decode('                        REPROBACIÓN'), $st['H1']);
        for($i=1; $i<=38; $i++){
            $h->cc++;
            $h->xls->write($h->rr, $h->cc, "", $st['H1']);
        }
        $h->xls->mergeCells($h->rr, $h->cc-38, $h->rr, $h->cc);

        $h->cc++;$h->cc++;
        $h->xls->write($h->rr, $h->cc, 'Formato', $st['TH.BlancoNegro']);
        for($i=1; $i<=2; $i++){
            $h->cc++;
            $h->xls->write($h->rr, $h->cc, "", $st['TH.BlancoNegro']);
        }
        $h->xls->mergeCells($h->rr, $h->cc-2, $h->rr, $h->cc);

        $h->nueva_linea();
        $h->xls->write($h->rr, $h->cc,'', $st['H1']);
        for($i=1; $i<=38; $i++){
            $h->cc++;
            $h->xls->write($h->rr, $h->cc, "", $st['H1']);
        }
        $h->xls->mergeCells($h->rr, $h->cc-38, $h->rr, $h->cc);

        $h->cc++;$h->cc++;
        $h->xls->write($h->rr, $h->cc, 'EB 05', $st['TH.BlancoNegro']);
        for($i=1; $i<=2; $i++){
            $h->cc++;
            $h->xls->write($h->rr, $h->cc, "", $st['TH.BlancoNegro']);
        }
        $h->xls->mergeCells($h->rr, $h->cc-2, $h->rr, $h->cc);

        $h->nueva_linea();
        $h->nueva_linea();
        $h->xls->write($h->rr, $h->cc, utf8_decode('COLEGIO DE ESTUDIOS CIENTÍFICOS Y TECNOLÓGICOS DEL ESTADO DE:'), $st['TH.GrayCenter']);
        for($i=1; $i<=7; $i++){
            $h->cc++;
            $h->xls->write($h->rr, $h->cc, "", $st['TH.GrayCenter']);
        }
        $h->xls->mergeCells($h->rr, $h->cc-7, $h->rr, $h->cc);
        $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'JALISCO', $st['TH.GrayCenter']);
        for($i=1; $i<=5; $i++){
            $h->cc++;
            $h->xls->write($h->rr, $h->cc, "", $st['TH.GrayCenter']);
        }
        $h->xls->mergeCells($h->rr, $h->cc-5, $h->rr, $h->cc);
        $h->cc += 27;
        $h->xls->write($h->rr, $h->cc, 'FECHA', $st['TH.GrayCenter']);
        for($i=1; $i<=2; $i++){
            $h->cc++;
            $h->xls->write($h->rr, $h->cc, "", $st['TH.GrayCenter']);
        }
        $h->xls->mergeCells($h->rr, $h->cc-2, $h->rr, $h->cc);

        $h->nueva_linea();
        $h->xls->write($h->rr, $h->cc, 'PLANTEL', $st['TH.GrayCenter']);
        $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'TLAQUEPAQUE', $st['TH.GrayCenter']);
        for($i=1; $i<=5; $i++){
            $h->cc++;
            $h->xls->write($h->rr, $h->cc, "", $st['TH.GrayCenter']);
        }
        $h->xls->mergeCells($h->rr, $h->cc-5, $h->rr, $h->cc);
        $h->cc += 34;
        $h->xls->write($h->rr, $h->cc, 'D', $st['TH.GrayCenter']);
        $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'M', $st['TH.GrayCenter']);
        $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'A', $st['TH.GrayCenter']);
        $h->cc++;

        $h->nueva_linea();
        $h->xls->write($h->rr, $h->cc, utf8_decode('NÚMERO'), $st['TH.GrayCenter']);
        $h->cc++;
        $h->xls->write($h->rr, $h->cc, '2', $st['TH.GrayCenter']);
        for($i=1; $i<=5; $i++){
            $h->cc++;
            $h->xls->write($h->rr, $h->cc, "", $st['TH.GrayCenter']);
        }
        $h->xls->mergeCells($h->rr, $h->cc-5, $h->rr, $h->cc);
        $h->cc += 34;
        $now = new DateTime();

        $h->xls->write($h->rr, $h->cc, $now->format('d'), $st['TD.Normal']);
        $h->cc++;
        $h->xls->write($h->rr, $h->cc, $now->format('m'), $st['TD.Normal']);
        $h->cc++;
        $h->xls->write($h->rr, $h->cc, $now->format('y'), $st['TD.Normal']);
        $h->cc++;
        $h->nueva_linea();
        $h->cc +=29;
        $h->xls->write($h->rr, $h->cc, 'CICLO ESCOLAR:', $st['TH.GrayCenter']);
        for($i=1; $i<=2; $i++){
            $h->cc++;
            $h->xls->write($h->rr, $h->cc, "", $st['TH.GrayCenter']);
        }
        $h->xls->mergeCells($h->rr, $h->cc-2, $h->rr, $h->cc);$h->cc++;

        $h->xls->write($h->rr, $h->cc, $this->cicloEscolar(), $st['TH.GrayCenter']);
        for($i=1; $i<=3; $i++){
            $h->cc++;
            $h->xls->write($h->rr, $h->cc, "", $st['TH.GrayCenter']);
        }
        $h->xls->mergeCells($h->rr, $h->cc-3, $h->rr, $h->cc);$h->cc++;

        $h->nueva_linea();
        $h->nueva_linea();
    }

    public function encabezado(&$h){
        $template=new Template();
        $st = $this->getEstilos();
        $h->xls->insertBitmap($h->rr, $h->cc, getcwd() . '/public/img/sp5/sep.bmp', 0, 0, .8, .8);
        $h->cc++;
        $h->xls->write($h->rr, $h->cc, utf8_decode('COORDINACIÓN DE ORGANISMOS DESCENTRALIZADOS'), $st['H1']);
        for($i=1; $i<=15; $i++){
            $h->cc++;
            $h->xls->write($h->rr, $h->cc, "", $st['H1']);
        }
        $h->xls->mergeCells($h->rr, $h->cc-15, $h->rr, $h->cc);
        $h->cc++;

        $h->xls->insertBitmap($h->rr, $h->cc, getcwd() . '/public/img/sp5/cecytes.bmp', 0, 0, .8, .8);
        $h->nueva_linea();
        $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'ESTATALES DE CECyTEs', $st['H1']);
        for($i=1; $i<=18; $i++){
            $h->cc++;
            $h->xls->write($h->rr, $h->cc, "", $st['H1']);
        }
        $h->xls->mergeCells($h->rr, $h->cc-18, $h->rr, $h->cc);

        $h->nueva_linea();
        $h->nueva_linea();
        $h->xls->write($h->rr, $h->cc, utf8_decode('ESTADÍSTICA BÁSICA'), $st['H1']);
        for($i=1; $i<=19; $i++){
            $h->cc++;
            $h->xls->write($h->rr, $h->cc, "", $st['H1']);
        }
        $h->xls->mergeCells($h->rr, $h->cc-19, $h->rr, $h->cc);
        $h->nueva_linea();
        $h->xls->write($h->rr, $h->cc, utf8_decode('                        MATRÍCULA ESCOLAR'), $st['H1']);
        for($i=1; $i<=15; $i++){
            $h->cc++;
            $h->xls->write($h->rr, $h->cc, "", $st['H1']);
        }
        $h->xls->mergeCells($h->rr, $h->cc-15, $h->rr, $h->cc);

        $h->cc++;$h->cc++;
        $h->xls->write($h->rr, $h->cc, 'Formato', $st['TH.BlancoNegro']);
        for($i=1; $i<=2; $i++){
            $h->cc++;
            $h->xls->write($h->rr, $h->cc, "", $st['TH.BlancoNegro']);
        }
        $h->xls->mergeCells($h->rr, $h->cc-2, $h->rr, $h->cc);

        $h->nueva_linea();
        $h->xls->write($h->rr, $h->cc, '                        FIN DE SEMESTRE ' . $this->periodo(), $st['H1']);
        for($i=1; $i<=15; $i++){
            $h->cc++;
            $h->xls->write($h->rr, $h->cc, "", $st['H1']);
        }
        $h->xls->mergeCells($h->rr, $h->cc-15, $h->rr, $h->cc);

        $h->cc++;$h->cc++;
        $h->xls->write($h->rr, $h->cc, 'EB 01 - B', $st['TH.BlancoNegro']);
        for($i=1; $i<=2; $i++){
            $h->cc++;
            $h->xls->write($h->rr, $h->cc, "", $st['TH.BlancoNegro']);
        }
        $h->xls->mergeCells($h->rr, $h->cc-2, $h->rr, $h->cc);

        $h->nueva_linea();
        $h->nueva_linea();
        $h->xls->write($h->rr, $h->cc, utf8_decode('COLEGIO DE ESTUDIOS CIENTÍFICOS Y TECNOLÓGICOS DEL ESTADO DE:'), $st['TH.GrayCenter']);
        for($i=1; $i<=7; $i++){
            $h->cc++;
            $h->xls->write($h->rr, $h->cc, "", $st['TH.GrayCenter']);
        }
        $h->xls->mergeCells($h->rr, $h->cc-7, $h->rr, $h->cc);
        $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'JALISCO', $st['TH.GrayCenter']);
        for($i=1; $i<=5; $i++){
            $h->cc++;
            $h->xls->write($h->rr, $h->cc, "", $st['TH.GrayCenter']);
        }
        $h->xls->mergeCells($h->rr, $h->cc-5, $h->rr, $h->cc);
        $h->cc += 4;
        $h->xls->write($h->rr, $h->cc, 'FECHA', $st['TH.GrayCenter']);
        for($i=1; $i<=2; $i++){
            $h->cc++;
            $h->xls->write($h->rr, $h->cc, "", $st['TH.GrayCenter']);
        }
        $h->xls->mergeCells($h->rr, $h->cc-2, $h->rr, $h->cc);

        $h->nueva_linea();
        $h->xls->write($h->rr, $h->cc, 'PLANTEL', $st['TH.GrayCenter']);
        $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'TLAQUEPAQUE', $st['TH.GrayCenter']);
        for($i=1; $i<=5; $i++){
            $h->cc++;
            $h->xls->write($h->rr, $h->cc, "", $st['TH.GrayCenter']);
        }
        $h->xls->mergeCells($h->rr, $h->cc-5, $h->rr, $h->cc);
        $h->cc += 11;
        $h->xls->write($h->rr, $h->cc, 'D', $st['TH.GrayCenter']);
        $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'M', $st['TH.GrayCenter']);
        $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'A', $st['TH.GrayCenter']);
        $h->cc++;

        $h->nueva_linea();
        $h->xls->write($h->rr, $h->cc, utf8_decode('NÚMERO'), $st['TH.GrayCenter']);
        $h->cc++;
        $h->xls->write($h->rr, $h->cc, '2', $st['TH.GrayCenter']);
        for($i=1; $i<=5; $i++){
            $h->cc++;
            $h->xls->write($h->rr, $h->cc, "", $st['TH.GrayCenter']);
        }
        $h->xls->mergeCells($h->rr, $h->cc-5, $h->rr, $h->cc);
        $h->cc += 11;
        $now = new DateTime();

        $h->xls->write($h->rr, $h->cc, $now->format('d'), $st['TD.Normal']);
        $h->cc++;
        $h->xls->write($h->rr, $h->cc, $now->format('m'), $st['TD.Normal']);
        $h->cc++;
        $h->xls->write($h->rr, $h->cc, $now->format('y'), $st['TD.Normal']);
        $h->cc++;
        $h->nueva_linea();
        $h->cc += 9;
        $h->xls->write($h->rr, $h->cc, 'CICLO ESCOLAR:', $st['TH.GrayCenter']);
        for($i=1; $i<=2; $i++){
            $h->cc++;
            $h->xls->write($h->rr, $h->cc, "", $st['TH.GrayCenter']);
        }
        $h->xls->mergeCells($h->rr, $h->cc-2, $h->rr, $h->cc);$h->cc++;

        $h->xls->write($h->rr, $h->cc, $this->cicloEscolar(), $st['TH.GrayCenter']);
        for($i=1; $i<=3; $i++){
            $h->cc++;
            $h->xls->write($h->rr, $h->cc, "", $st['TH.GrayCenter']);
        }
        $h->xls->mergeCells($h->rr, $h->cc-3, $h->rr, $h->cc);$h->cc++;

        $h->nueva_linea();
        $h->nueva_linea();
    }


    public function cuentaAlumnos($grados,$sexo,$oferta_id){
        $sqlQuery = "SELECT
                    count(alumnos.id)
                    FROM 
                    alumnos 
                    INNER JOIN alumnosgrupo ON alumnos.id = alumnosgrupo.alumnos_id
                    INNER JOIN grupos ON alumnosgrupo.grupos_id = grupos.id
                    WHERE 
                    grupos.ciclos_id = ".$this->ciclo->id." AND 
                    (".$grados.") AND 
                    sexo = '".$sexo."' AND 
                    grupos.oferta_id = ".$oferta_id;

        $alumnos = new Alumnos();
        $cc = $alumnos->count_by_sql($sqlQuery);
        return $cc;
    }


    public function cuentaGrupos($grados,$oferta_id){
        $sqlQuery = "SELECT
                    COUNT(grupos.id)
                    FROM
                    grupos
                    WHERE ciclos_id = ".$this->ciclo->id." AND (".$grados.") AND oferta_id = ".$oferta_id;

        $grupos = new Grupos();
        $cc = $grupos->count_by_sql($sqlQuery);

        return $cc;
    }

    public function propiedades(&$h){
        $h->xls->centerHorizontally();
        $h->xls->hideGridlines();
        $h->xls->printArea(0, 0, $h->rr_max, $h->cc_max);
        $h->xls->setFooter("HEKADEMOS ".date("j/n/Y H:i"), 0);
        $h->xls->setLandscape();
        $h->xls->setMargins_LR(0.2);
        $h->xls->setMargins_TB(0.27);
        $h->xls->setPaper(3);
        $h->xls->setPrintScale(95);
        $h->xls->setZoom(65);
    }


    public function propiedades1(&$h){
        $h->xls->centerHorizontally();
        $h->xls->hideGridlines();
        $h->xls->printArea(0, 0, $h->rr_max, $h->cc_max);
        $h->xls->setFooter("HEKADEMOS ".date("j/n/Y H:i"), 0);
        $h->xls->setLandscape();
        $h->xls->setMargins_LR(0.2);
        $h->xls->setMargins_TB(0.27);
        $h->xls->setPaper(3);
        $h->xls->setPrintScale(60);
        $h->xls->setZoom(65);
    }

    public function periodo(){
        $inicio = new DateTime($this->ciclo->inicio);
        $fin = new DateTime($this->ciclo->fin);
        if($inicio->format("Y") == $fin->format("Y")){
            $periodo = Utils::mes_espanol($inicio->format("m")) . ' ' . Utils::mes_espanol($fin->format("m")) . ' ' . $inicio->format("Y");
        }else{
            $periodo = Utils::mes_espanol($inicio->format("m")) . ' ' . $inicio->format("Y") . ' ' . Utils::mes_espanol($fin->format("m")) . ' ' . $fin->format("Y");

        }

        return strtoupper($periodo);
    }

    public function cicloEscolar(){
        $inicio = new DateTime($this->ciclo->inicio);
        $fin = new DateTime($this->ciclo->fin);
        if($inicio->format("Y") == $fin->format("Y")){
            return $inicio->format("Y");
        }else{
            return $inicio->format("Y") . '-' . $fin->format("Y");
        }
    }


    public function datos(){
        $datos = array();
        $info = array();
        $q = "SELECT
                calificaciones.*
               FROM
                calificaciones
                INNER JOIN cursos ON calificaciones.cursos_id=cursos.id
                INNER JOIN grupos ON cursos.grupos_id = grupos.id
                INNER JOIN alumnoscursos ON cursos.id = alumnoscursos.cursos_id
                INNER JOIN alumnos ON alumnoscursos.alumnos_id = alumnos.id
                WHERE 
                grupos.ciclos_id='".$this->ciclo->id."'
            GROUP BY calificaciones.id
            ORDER BY alumnos.id,cursos.id";

        $ordinario=new Oportunidades();
        $ordinario=$ordinario->ordinario();

        $extra=new Oportunidades();
        $extra=$extra->extraordinario();

        $cals = new Calificaciones();
        $calificaciones = $cals->find_all_by_sql($q);


        foreach($calificaciones as $cal){
            $datos[$cal->alumnos_id][$cal->cursos_id][$cal->oportunidades_id]=$cal;
        }

        foreach($datos as $alumnos){
            foreach($alumnos as $c_id => $curso){
                $dato = $curso[$ordinario->id];
                if($dato == null){
                    //reprobado
                }else{
                    if($dato->valor >= 60 || $dato->valor == 'A'){
                        //Aprobado
                        $info[$dato->alumnos_id]['A'][] = $dato;
                            
                    }else{ //revisar extra
                        $alu_id = $dato->alumnos_id;
                        $dato = $curso[$extra->id];
                        if($dato == null){
                            $info[$alu_id]['R'][] = $dato;
                        }else{
                            if($dato->valor >= 60 || $dato->valor == 'A'){
                                //Aprobado
                                $info[$dato->alumnos_id]['A'][] = $dato;
                            }else{
                                $info[$dato->alumnos_id]['R'][] = null;
                            }
                        }
                    }
                }
            }
        }

        $tabla = array();
        $grupos = $this->alumnosgrupos();
        $grados = array();
        foreach($info as $a_id => $alumno){
            $grupo = $grupos[$a_id];
            if(count($alumno['R']) == 0){
                if($tabla[$grupo->oferta_id][$grupo->grado][0] == null){
                    $tabla[$grupo->oferta_id][$grupo->grado][0] = 1;
                }else{
                    $tabla[$grupo->oferta_id][$grupo->grado][0] += 1;
                }
            }elseif(count($alumno['R']) > 4){
                if($tabla[$grupo->oferta_id][$grupo->grado][5] == null){
                    $tabla[$grupo->oferta_id][$grupo->grado][5] = 1;
                }else{
                    $tabla[$grupo->oferta_id][$grupo->grado][5] += 1;
                }
            }else{
                if($tabla[$grupo->oferta_id][$grupo->grado][count($alumno['R'])] == null){
                    $tabla[$grupo->oferta_id][$grupo->grado][count($alumno['R'])] = 1;
                }else{
                    $tabla[$grupo->oferta_id][$grupo->grado][count($alumno['R'])] += 1;
                }
            }
        }

        return $tabla;
    }


    public function alumnosgrupos(){
        $grupos = new Alumnos();
        $grupos = $grupos->find_all_by_sql(
            "SELECT " .
                "grupos.id AS grupos_id, " .
                "grupos.grado, " .
                "grupos.letra, " .
                "grupos.turno, " .
                "grupos.oferta_id, " .
                "alumnos.id  ".
            "FROM " .
                "grupos Inner Join alumnosgrupo On grupos.id = alumnosgrupo.grupos_id " .
                "Inner Join alumnos On alumnosgrupo.alumnos_id = alumnos.id " .
            "WHERE " .
                " grupos.ciclos_id='".$this->ciclo->id."'"
                );

                $gps = array();
                foreach($grupos as $gp){
                    $gps[$gp->id] = $gp;
                }

                return $gps;

    }

}
?>
