<?php
Kumbia :: import('app.scripts.Inconsistencias');

class XLSEs extends Reporte{
    private $condicion;
    private $pagina;
    private $fecha;

    public function XLSEs($tipo,$fecha=""){
        $this->fecha=$fecha;
        $this->tipo="";

        if($tipo=="inconsistencias"){
            if($fecha!=""){
                $controlador='es';
                $accion='inconsistencias';
                $b = new Busqueda($controlador, $accion);
                $b->quitarCondicion('kumbia_path');
                $b->quitarCondicion('date');
                $b->quitarCondicion('inconsistencia');
                $usuarios=array();
                $tabla="";
                if($b->campo("tipo")=="" || $b->campo("tipo")=="P"){
                $this->tipo="profesores";
                $b->quitarCondicion('tipo');
                $b->quitarCondicion('grado');
                $b->quitarCondicion('letra');
                $b->quitarCondicion('turno');
                $b->quitarCondicion('oferta_id');
                $b->establecerCondicion('nombre', "CONCAT(TRIM(profesores.nombre), ' ', TRIM(profesores.ap), ' ', TRIM(profesores.am)) LIKE '%" . $b->campo('nombre') . "%' ");
                $c = $b->condicion();
                $profesores=new Profesores();
                $profesores=$profesores->find_all_by_sql(
                "SELECT * FROM profesores WHERE ".
                ($c==""? "1" : $c)." ORDER BY ap,am,nombre");

                foreach($profesores as $a){
                    //$a->foto = KUMBIA_PATH . 'img/' . ($a->foto == '' ? 'sp5/persona.png' : 'profesores/' . $a->foto . '?d=' . time());
                    $a->tipo_entidad="profesores";
                    $usuarios[$a->codigo] = $a;

                }
                $tabla="profesores";
                }elseif($b->campo("tipo")=="A"){
                $bach="General";
                if($b->campo('oferta_id')==2){
                $bach="Competencias";
                }
                $this->tipo="alumnos del ".$b->campo('grado').$b->campo('letra').$b->campo('turno')." ".$bach;
                $b->quitarCondicion('tipo');
                $b->establecerCondicion('nombre', "CONCAT(TRIM(alumnos.nombre), ' ', TRIM(alumnos.ap), ' ', TRIM(alumnos.am)) LIKE '%" . $b->campo('nombre') . "%' ");
                $b->establecerCondicion('oferta_id', "grupos.oferta_id  = '" . $b->campo('oferta_id') . "' ");
                $c = $b->condicion();
                $alumnos=new Alumnos();
                $alumnos=$alumnos->find_all_by_sql(
                "SELECT " .
                "alumnos.codigo, " .
                "alumnos.id, " .
                "alumnos.nombre, " .
                "alumnos.am, " .
                "alumnos.ap, " .
                "grupos.id AS grupos_id, " .
                "grupos.grado, " .
                "grupos.letra," .
                "grupos.turno, " .
                "alumnos.foto, " .
                "situaciones.nombre AS situacion " .
                "FROM " .
                "ciclos " .
                "Inner Join grupos ON ciclos.id = grupos.ciclos_id " .
                "Inner Join alumnosgrupo ON grupos.id = alumnosgrupo.grupos_id " .
                "Inner Join alumnos ON alumnosgrupo.alumnos_id = alumnos.id " .
                "Inner Join situaciones ON alumnos.situaciones_id = situaciones.id " .
                "WHERE " .
                "ciclos.id = '" . Session :: get_data("ciclo.id") . "' " .
                 ($c == "" ? "" : "AND " . $c) . " " .
                "ORDER BY " .
                "grupos.turno, grupos.grado, grupos.letra, alumnos.ap, alumnos.am, alumnos.nombre " );

                foreach($alumnos as $a){
                    //$a->foto = KUMBIA_PATH . 'img/' . ($a->foto == '' ? 'sp5/persona.png' : 'alumnos/' . $a->foto . '?d=' . time());
                    $a->tipo_entidad="alumnos";
                    $usuarios[$a->codigo] = $a;

                }
                $tabla="alumnos";
                }elseif($b->campo("tipo")!=""){
                $tipoPersonal=new Tipopersonal();
                $tipoPersonal=$tipoPersonal->find($b->campo('tipo'));
                $this->tipo=$tipoPersonal->nombre;
                $b->quitarCondicion('inicio');
                $b->quitarCondicion('fin');
                $b->quitarCondicion('tipo');
                $b->quitarCondicion('grado');
                $b->quitarCondicion('letra');
                $b->quitarCondicion('turno');
                $b->quitarCondicion('oferta_id');
                $b->establecerCondicion('nombre', "CONCAT(TRIM(personal.nombre), ' ', TRIM(personal.ap), ' ', TRIM(personal.am)) LIKE '%" . $b->campo('nombre') . "%' ");
                $c = $b->condicion();
                $personal=new Personal();
                $personal=$personal->find_all_by_sql(
                "SELECT * FROM personal WHERE ".
                ($c==""? "tipopersonal_id='".$b->campo('tipo')."'" : $c." AND tipopersonal_id='".$b->campo('tipo')."'"));
                foreach($personal as $p){
                    $p->tipo_entidad="personal";
                    $usuarios[$p->codigo] = $p;
                    $cond.=" (ViewUser.UserInfo3='".$p->codigo."') OR";
                }

                $tabla="personal";
                }

                $pagina=new Inconsistencias();
                $pagina->colocaUsuarios($usuarios);
                $pagina->colocaTabla($tabla);
                $pagina->colocaTipos($b->campo('inconsistencia'));
                $pagina->obtenTodos(true);
                $pagina->colocaFecha($fecha);
                $this->pagina=$pagina;
                $inconsistencias=$pagina->obtenInconsistencias();

                $this->Reporte('Inconsistencias del dia '. Utils::fecha_espanol($fecha)." ".($c != '' ? ' FILTRADO' : '' ). '.xls');
                $registros=count($inconsistencias);
                $r_usu=count($usuarios);

                if($registros>0 && $r_usu>0)
                $this->hoja($inconsistencias);
                else
                $this->hoja_vacia();
        }else{
            $this->Reporte('Inconsistencias.xls');
            $this->hoja_vacia();
        }

        }elseif($tipo=="accesos"){
        $controlador='es';
        $accion='index';

            $cond="";
            $controlador = "es";
            $accion = "index";
            $usuarios=array();
            // busqueda
            $b = new Busqueda($controlador, $accion);
            if($b->campo("inicio")!='' && $b->campo("fin")!=''){
            if($b->campo("tipo")=="" || $b->campo("tipo")=="A"){
                $this->tipo="alumnos";
                $b->establecerCondicion('nombre', "CONCAT(TRIM(alumnos.nombre), ' ', TRIM(ap), ' ', TRIM(am)) LIKE '%" . $b->campo('nombre') . "%' ");
                $b->establecerCondicion('oferta_id', "grupos.oferta_id  = '" . $b->campo('oferta_id') . "' ");
                $b->quitarCondicion('inicio');
                $b->quitarCondicion('fin');
                $b->quitarCondicion('tipo');
                // genera las condiciones
                $c = $b->condicion();
                $alumnos=new Alumnos();
                $alumnos = $alumnos->find_all_by_sql("SELECT " .
                "alumnos.codigo, " .
                "alumnos.id, " .
                "alumnos.ap, " .
                "alumnos.am, " .
                "alumnos.nombre, " .
                "alumnos.foto " .
                "FROM " .
                "ciclos " .
                "Inner Join grupos ON ciclos.id = grupos.ciclos_id " .
                "Inner Join alumnosgrupo ON grupos.id = alumnosgrupo.grupos_id " .
                "Inner Join alumnos ON alumnosgrupo.alumnos_id = alumnos.id " .
                "WHERE " .
                "ciclos.id = '" . Session::get_data("ciclo.id") . "'" .
                 ($c == "" ? "" : "AND " . $c) . " " );

                foreach($alumnos as $a){
                    $a->tipo_entidad="alumnos";
                    $usuarios[$a->codigo] = $a;
                    $cond.=" (ViewUser.UserInfo3='".$a->codigo."') OR";
                }

                }elseif( $b->campo("tipo")=="P"){
                $this->tipo="profesores";
                $b->quitarCondicion('inicio');
                $b->quitarCondicion('fin');
                $b->quitarCondicion('tipo');
                $b->quitarCondicion('grado');
                $b->quitarCondicion('letra');
                $b->quitarCondicion('turno');
                $b->quitarCondicion('oferta_id');
                $b->establecerCondicion('nombre', "CONCAT(TRIM(profesores.nombre), ' ', TRIM(profesores.ap), ' ', TRIM(profesores.am)) LIKE '%" . $b->campo('nombre') . "%' ");
                $c = $b->condicion();
                $profesores=new Profesores();
                $profesores=$profesores->find_all_by_sql(
                "SELECT * FROM profesores WHERE ".
                ($c==""? "1" : $c));

                foreach($profesores as $a){
                    $a->tipo_entidad="profesores";
                    $usuarios[$a->codigo] = $a;
                    $cond.=" (ViewUser.UserInfo3='".$a->codigo."') OR";

                }
                }elseif($b->campo("tipo")!=""){
                $tipoPersonal=new Tipopersonal();
                $tipoPersonal=$tipoPersonal->find($b->campo('tipo'));
                $this->tipo=$tipoPersonal->nombre;

                $b->quitarCondicion('inicio');
                $b->quitarCondicion('fin');
                $b->quitarCondicion('tipo');
                $b->quitarCondicion('grado');
                $b->quitarCondicion('letra');
                $b->quitarCondicion('turno');
                $b->quitarCondicion('oferta_id');
                $b->establecerCondicion('nombre', "CONCAT(TRIM(personal.nombre), ' ', TRIM(personal.ap), ' ', TRIM(personal.am)) LIKE '%" . $b->campo('nombre') . "%' ");
                $c = $b->condicion();
                $personal=new Personal();
                $personal=$personal->find_all_by_sql(
                "SELECT * FROM personal WHERE ".
                ($c==""? "tipopersonal_id='".$b->campo('tipo')."'" : $c." AND tipopersonal_id='".$b->campo('tipo')."'"));
                foreach($personal as $p){
                    $p->tipo_entidad="personal";
                    $usuarios[$p->codigo] = $p;
                    $cond.=" (ViewUser.UserInfo3='".$p->codigo."') OR";
                }


                }


                if($cond!=""){
                    $ini=$b->campo("inicio");
                    $fi=$b->campo("fin");

                    $ini=split(" ",$b->campo("inicio"));
                    $inicio=Utils::convierteFechaMySql($ini[0])." ".$ini[1];
                    $this->inicio=Utils::fecha_espanol(Utils::convierteFechaMySql($ini[0]))." ".$ini[1];

                    $fi=split(" ",$b->campo("fin"));
                    $fin=Utils::convierteFechaMySql($fi[0])." ".$fi[1];
                    $this->fin=Utils::fecha_espanol(Utils::convierteFechaMySql($fi[0]))." ".$fi[1];


                    $cond=substr($cond,0,strlen($cond)-2);

                    $eventos=new ViewEvents();
                    //se resta 2.041666667 por que la fecha esta adelantada con 2 dias y 1, .041666667 igual a 1 hora en el formato
                    // de fecha de SQL Server

                    $eventos=$eventos->find_all_by_sql(
                    "SELECT ViewUser.UserInfo3,ViewEvents.UniqueID,CAST(([PanelLocalDT] - 2.041666667) AS datetime) AS fecha,ViewEvents.DoorNumberText,
                                ViewEvents.CardNumber,ViewEvents.UserNumberText,ViewEvents.AreaNumberText,ViewDoor.DoorNumber,ViewDoor.PodDoorIndex
                      FROM [Director].[dbo].[ViewUser]
                          INNER JOIN [Director].[dbo].[ViewEvents] ON ViewUser.CardNumber=ViewEvents.CardNumber
                        INNER JOIN [Director].[dbo].[ViewDoor] ON ViewEvents.DoorNumber=ViewDoor.DoorNumber
                          WHERE PanelLocalDT > CAST(CAST('".$inicio."' AS datetime) AS float)+2.041666667 AND
                      PanelLocalDT < CAST(CAST('".$fin."' AS datetime) AS float)+2.041666667 AND (".$cond.") ORDER BY PanelLocalDT DESC"

                     );
                    $c=    "SELECT ViewUser.UserInfo3,ViewEvents.UniqueID,CAST(([PanelLocalDT] - 2) AS datetime) AS fecha,ViewEvents.DoorNumberText,
                                ViewEvents.CardNumber,ViewEvents.UserNumberText,ViewEvents.AreaNumberText,ViewDoor.DoorNumber,ViewDoor.PodDoorIndex
                      FROM [Director].[dbo].[ViewUser]
                          INNER JOIN [Director].[dbo].[ViewEvents] ON ViewUser.CardNumber=ViewEvents.CardNumber
                        INNER JOIN [Director].[dbo].[ViewDoor] ON ViewEvents.DoorNumber=ViewDoor.DoorNumber
                          WHERE PanelLocalDT > CAST(CAST('".$inicio."' AS datetime) AS float)+2 AND
                      PanelLocalDT < CAST(CAST('".$fin."' AS datetime) AS float)+2 AND (".$cond.") ORDER BY PanelLocalDT DESC";
                    $registros=count($eventos);
                        }else{
                            $registros=0;
                            $eventos=array();
                }
            $this->Reporte('Accesos de '.$this->tipo.'.xls');
            if($registros>0)
            $this->hojaAccesos($eventos);
            else{
            $this->hoja_vacia();

            }
        }else{
            $this->Reporte('Accesos.xls');
            $this->hoja_vacia();
        }
        }else{
            $this->Reporte('Accesos.xls');
            $this->hoja_vacia();
        }

    }

    public function hoja($inconsistencias){
        $nombre = 'Inconsistencias ';
        $hojas = $this->getHojas();
        if(array_key_exists($nombre, $hojas)){
            $h = $hojas[$nombre];
        }else{
            $cols = array( 15, 15, 50,50);
            $h = $this->agregar_hoja($nombre, null, $cols);
            $h->cc_max = 13;
        }
        $this->contenido($h, $inconsistencias);
        $this->propiedades($h);
    }

    public function hojaAccesos($eventos){
        $nombre = 'Acessos ';
        $hojas = $this->getHojas();
        if(array_key_exists($nombre, $hojas)){
            $h = $hojas[$nombre];
        }else{
            $cols = array( 15, 15, 15, 50, 20, 20, 10);
            $h = $this->agregar_hoja($nombre, null, $cols);
            $h->cc_max = 13;
        }
        $this->contenidoAccesos($h, $eventos);
        $this->propiedades($h);
    }

    public function hoja_vacia(){
        $nombre = 'HEKADEMOS';
        $h = $this->agregar_hoja($nombre);
        $h->xls->write(0, 0, "No hay registros que coincidan con esas condiciones");
    }

    public function contenido(&$h, $inconsistencias){
        $this->encabezado($h);
        $st = $this->getEstilos();
        $h->xls->write($h->rr, $h->cc, '', $st['TD.NoBorder']); $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'Codigo', $st['TD.BGYellow']); $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'Nombre', $st['TD.BGYellow']); $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'Inconsistencia', $st['TD.BGYellow']); $h->cc++;
        $h->nueva_linea();
        $n = 0;
        foreach($inconsistencias as $u){
            $n++;
            $td = ($n%2 == 0 ? 'Par' : '');
            $h->xls->writeString($h->rr, $h->cc, "", $st['TD.NoBorder']);$h->cc++;
            $h->xls->writeString($h->rr, $h->cc, utf8_decode($u->codigo), $st['TD' . $td . '.Normal']);$h->cc++;
            $h->xls->writeString($h->rr, $h->cc, utf8_decode($u->nombre()), $st['TD' . $td . '.Normal']);$h->cc++;
            $h->xls->writeString($h->rr, $h->cc, $this->pagina->obtenError($u->codigo), $st['TD' . $td . '.Normal']);$h->cc++;
            $h->cc++;
            $h->nueva_linea();

        }


        $h->nueva_linea();
        $h->rr_max = $h->rr;
    }


    public function contenidoAccesos(&$h, $eventos){
        $this->encabezadoAccesos($h);
        $st = $this->getEstilos();
        $h->xls->write($h->rr, $h->cc, '', $st['TD.NoBorder']); $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'Tarjeta', $st['TD.BGYellow']); $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'Codigo', $st['TD.BGYellow']); $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'Nombre', $st['TD.BGYellow']); $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'Hora', $st['TD.BGYellow']); $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'Puerta', $st['TD.BGYellow']); $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'Evento', $st['TD.BGYellow']); $h->cc++;
        $h->nueva_linea();
        $n = 0;
        $salto="
                ";
        foreach($eventos as $evento){
            $n++;
            $td = ($n%2 == 0 ? 'Par' : '');
            $h->xls->writeString($h->rr, $h->cc, "", $st['TD.NoBorder']);$h->cc++;
            $h->xls->writeString($h->rr, $h->cc, $evento->CardNumber, $st['TD' . $td . '.Normal']);$h->cc++;
            $h->xls->writeString($h->rr, $h->cc, $evento->UserInfo3, $st['TD' . $td . '.Normal']);$h->cc++;
            $h->xls->writeString($h->rr, $h->cc, $evento->UserNumberText, $st['TD' . $td . '.Normal']);$h->cc++;
            $h->xls->writeString($h->rr, $h->cc, $evento->fecha->format("H:i:s").$salto.$evento->fecha->format("d/m/Y"), $st['TD' . $td . '.Normal']);$h->cc++;
            $h->xls->writeString($h->rr, $h->cc, $evento->DoorNumberText.$salto.$evento->AreaNumberText, $st['TD' . $td . '.Normal']);$h->cc++;
            $h->xls->writeString($h->rr, $h->cc, $evento->tipo(), $st['TD' . $td . '.Normal']);$h->cc++;
            $h->cc++;
            $h->nueva_linea();
        }


        $h->nueva_linea();
        $h->rr_max = $h->rr;
    }

    public function encabezado(&$h){
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
        $h->xls->write($h->rr, $h->cc, 'Inconsistencias de accesos de '.$this->tipo, $st['H4']);
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc + 6);
        $h->nueva_linea();
        $h->xls->write($h->rr, $h->cc,Utils::fecha_espanol($this->fecha), $st['H4']);
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc + 6);
        $h->nueva_linea();
        $h->nueva_linea();
        $h->nueva_linea();
        $h->xls->repeatRows(0,8);
        $h->xls->freezePanes(array(9, 0));
    }


    public function encabezadoAccesos(&$h){
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
        $h->xls->write($h->rr, $h->cc, 'Accesos de '.$this->tipo, $st['H4']);
        $h->xls->mergeCells($h->rr, $h->cc, $h->rr, $h->cc + 6);
        $h->nueva_linea();
        $h->xls->write($h->rr, $h->cc,$this->inicio." A ".$this->fin, $st['H4']);
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
        $h->xls->printArea(0, 0, $h->rr_max, $h->cc_max);
        $h->xls->setFooter("HEKADEMOS ".date("j/n/Y H:i"), 0);
        $h->xls->setLandscape();
        $h->xls->setMargins_LR(0.2);
        $h->xls->setMargins_TB(0.27);
        $h->xls->setPaper(3);
        $h->xls->setPrintScale(80);
        $h->xls->setZoom(80);
    }

}
?>