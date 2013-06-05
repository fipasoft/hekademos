<?php
class PDFReportes {
    private $codigo;
    private $nombre;
    function PDFReportes() {
    }

    public function accesosPDF($alumno,$dia){
        $accesos=array();
    if($dia=="")
        $dia=date("Y-m-d",time());

        $accesos["date"]=$dia;


    $afxuser=new AFxUser();
    if($afxuser->db==true){
    $afxuser=$afxuser->find_first("UserInfo3='".$alumno['codigo']."'");
    if($afxuser->CardNumber==""){
        $accesos["valido"]=false;
        $accesos["error"]="El alumno no cuenta con informacion de su tarjeta.";
    }else{

        $accesos["valido"]=true;
        $accesos["tarjeta"]=$afxuser->CardNumber;
        $f=split("-",$dia);
        if(checkdate($f[1],$f[2],$f[0])){
                try{
                $date=new DateTime($dia);
                $accesos["fecha"]=$date->format("j")." de ". mes_espanol($date->format("m"))." de ".$date->format("Y");
                $eventos=new ViewEvents();
                //se resta 2.041666667 por que la fecha esta adelantada con 2 dias y 1 hora, .041666667 igual a 1 hora en el formato
                //de fecha de SQL Server
                $q="SELECT ViewUser.UserInfo3,ViewEvents.UniqueID,CAST((ViewEvents.PanelLocalDT - 2.041666667) AS datetime) AS fecha,ViewEvents.DoorNumberText,
                     ViewEvents.CardNumber,ViewEvents.UserNumberText,ViewEvents.AreaNumberText,ViewDoor.DoorNumber,ViewDoor.PodDoorIndex
                      FROM [Director].[dbo].[ViewUser]
                          INNER JOIN [Director].[dbo].[ViewEvents] ON ViewUser.CardNumber=ViewEvents.CardNumber
                        INNER JOIN [Director].[dbo].[ViewDoor] ON ViewEvents.DoorNumber=ViewDoor.DoorNumber
                          WHERE PanelLocalDT > CAST(CAST('".$dia." 00:00:01' AS datetime) AS float)+2.041666667 AND
                      PanelLocalDT < CAST(CAST('".$dia." 23:59:59' AS datetime) AS float)+2.041666667 AND (ViewUser.CardNumber='".$afxuser->CardNumber."')";
                $accesos["eventos"]=$eventos->find_all_by_sql($q);
                $entradas=0;
                $salidas=0;
                foreach($accesos["eventos"] as $e){
                    if($e->esEntrada()==true)
                        $entradas++;
                        elseif($e->esSalida()==true)
                        $salidas++;
                }
                $accesos["entradas"]=$entradas;
                $accesos["salidas"]=$salidas;

        }catch(ActiveRecordException $e){
                $accesos["valido"]=false;
                $accesos["error"]="Ocurrio un error en la base de datos.<br/>".$q;


                }catch(Exception $e){
                $accesos["valido"]=false;
                $accesos["error"]="La fecha no es valida.";

                }
        }



        }
    $codigo = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">' .
        '<html>' .
        '<head>' .
        '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />' .
        $this->estilo() .
        '</head>';
    $codigo .= '<body>';
    $codigo.='<h1>Accesos del dia '.$accesos["fecha"].'</a></h1>';
    $codigo.='<br/>';
    $codigo.='<div id="ficha">';
    $codigo.='<table>';
    $codigo.='<tr><th>Código</th><td>'.$alumno["codigo"].'</td><th>Nombre</th><td>'.$alumno["nombre"].'</td></tr>';
    $codigo.='</table>';
    $codigo.='</div>';
    $codigo.='<br/>';
    if($accesos["valido"]==true){
        $eventos=$accesos["eventos"];
        if(count($eventos)>0){
            $codigo.='<h2>El alumno cuenta con '.$accesos["entradas"].' entrada '.($accesos["entradas"]==1? "" : "s").' y '.$accesos["salidas"].' salida'.($accesos["salidas"]==1? "" : "s").'.</h2>';
            $codigo.='<br/>';
            $codigo.='<table>';
            $codigo.='<tr><th>Evento</th><th>Hora</th><th>Puerta</th></tr>';
                $i = 0;
                foreach($eventos as $evento){
                $codigo.='<tr class="'.($i%2 == 0 ? '' : 'odd').'">';
                $tipo=utf8_encode(substr($evento->DoorNumberText,0,strlen($evento->DoorNumberText)-2));
                $codigo.='<td style="text-align:center">';
                $codigo.='<span class="t'.$evento->PodDoorIndex.'">';
                $codigo.=$evento->tipo();
                $codigo.='</span>';
                $codigo.='</td>';
                $codigo.='<td style="text-align:center">';
                $codigo.='<span class="t'.$evento->PodDoorIndex.'" >';
                $codigo.=$evento->fecha->format("H:i:s");
                $codigo.='</span>';
                $codigo.='</td>';
                $codigo.='<td style="text-align:center">';
                $codigo.='<span class="t'.$evento->PodDoorIndex.'">';
                $codigo.=$evento->DoorNumberText;
                $codigo.='</span>';
                $codigo.='<br/>';
                $codigo.='<span class="sub" style="color: rgb(119, 119, 119);">';
                $codigo.=utf8_encode($evento->AreaNumberText);
                $codigo.='</span>';
                $codigo.='</td>';
                $codigo.='</tr>';
            $i++;
                }
        $codigo.='</table>';
     }else{
    $codigo.='<p class="infoBox">';
    $codigo.='<br/>';
    $codigo.='El alumno no cuenta con ningun acceso el '.$accesos["fecha"].'.';
    $codigo.='</p>';
     }
     }else{
    $codigo.='<p class="errorBox">';
    $codigo.='<br/>';
    $codigo.=$accesos["error"];
    $codigo.='</p>';
     }
     $codigo .= '</body>';
     $codigo .= '</html>';
     return $codigo;
    }else{
        $codigo="<h1>La informacion no se encuentra disponoble en estos momentos.</h1>";
        return $codigo;
    }
    }

    public function calificacionesPDF($id, $id_ciclo) {
        $code = "";
        $consulta = "SELECT alumnos.id AS alumnos_id,alumnos.codigo,alumnos.ap,alumnos.am,alumnos.nombre,grupos.id AS grupo_id,grupos.grado,grupos.letra,grupos.turno,ciclos.numero" .
        " FROM alumnos,alumnosgrupo,grupos,ciclos WHERE alumnosgrupo.grupos_id=grupos.id AND alumnosgrupo.alumnos_id='" . $id . "' AND alumnos.id='" . $id . "' AND ciclos.id='" . $id_ciclo . "' AND grupos.ciclos_id='" . $id_ciclo . "'";

        $db = new db("localhost", "hekademos", "hekademos", "hekademos");
        $alumno_grupo = $db->fetch_one($consulta);
        $val = "";
        $consulta = "SELECT cursos.id,materias.id AS materia_id,materias.clave,materias.nombre AS materia_nombre,cursos.profesores_id FROM alumnoscursos,cursos,grupos,materias WHERE alumnoscursos.alumnos_id=" . $id . " AND alumnoscursos.cursos_id=cursos.id AND
                            grupos.id=cursos.grupos_id AND grupos.ciclos_id='" . $id_ciclo . "' AND materias.id=cursos.materias_id ORDER BY materias.nombre";
        $cursos = $db->in_query($consulta);

        $datos_retorno = array ();
        $datos_cursos = array ();
        $perds = array ();
        foreach ($cursos as $curso) {
            $consulta = "SELECT codigo,nombre,ap,am FROM profesores WHERE id=" . $curso["profesores_id"];
            $profesor = $db->fetch_one($consulta);
            $profesor = "" . $profesor["ap"] . " " . $profesor["am"] . " " . $profesor["nombre"];
            $consulta = "SELECT valor,oportunidades_id FROM calificaciones WHERE cursos_id=" . $curso["id"] . " AND alumnos_id=" . $alumno_grupo["alumnos_id"] . " order by oportunidades_id";
            $calificaciones = $db->in_query($consulta);

            $ordinario = "";
            $extra = "";
            $estado = "-";
            foreach ($calificaciones as $cal) {
                if ($cal["oportunidades_id"] == 1) {
                    $ordinario = $cal["valor"];
                    //$existe_ordinario=1;
                    if ($ordinario > 59 || $ordinario=='A') {
                        $estado = "Aprobado";
                    } else
                        $estado = "Reprobado";

                } else
                    if ($cal["oportunidades_id"] == 2) {
                        $extra = $cal["valor"];
                        //$existe_extra=1;
                        if ($extra > 59) {
                            $estado = "Aprobado";
                        } else
                            $estado = "Reprobado";

                    }
            }

            $consulta = "SELECT * FROM calificacionesparciales WHERE cursos_id=" . $curso["id"] . " AND alumnos_id=" . $alumno_grupo["alumnos_id"];
            $parciales = $db->in_query($consulta);
            $datos_cursos[$curso["id"]] = array (
                "id" => $curso["id"],
                "profesor" => $profesor,
                "materia_nombre" => $curso["materia_nombre"],
                "clave" => $curso["clave"],
                "materia_id" => $curso["materia_id"],
                "ordinario" => $ordinario,
                "extra" => $extra,
                "status" => $estado
            );
            if (sizeof($parciales) > 0) {
                foreach ($parciales as $parcial) {

                    $datos_retorno[$curso["id"]][$parcial["periodo"]] = array (
                        "alumno_id" => $alumno_grupo["alumnos_id"],
                        "grupo_id" => $alumno_grupo["grupo_id"],
                        "curso_id" => $curso["id"],
                        "periodo" => $parcial["crp_periodo"],
                        "valor" => $parcial["valor"]
                    );
                    $perds[$parcial["periodo"]] = $parcial["periodo"];
                }

            } else {
                $datos_retorno[$curso["id"]]["-1"] = array (
                    "alumno_id" => $alumno_grupo["alumnos_id"],
                    "grupo_id" => $alumno_grupo["grupo_id"],
                    "curso_id" => $curso["id"],
                    "periodo" => 0,
                    "valor" => 0
                );
                $perds["-1"] = "-1";
            }

        }

        $conValor = 0;

        $code = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">' .
        '<html>' .
        '<head>' .
        '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />' .
        $this->estilo() .
        '</head>';
        $code .= '<body>';
        $code .= '<h1>Calificaciones.</h1><p style="text-align:right;">Ciclo ' . $alumno_grupo["numero"] . '</p> ';
        $code .= $this->alumno();
        $code.='<br/><br/>';
        $code .= '<div style="margin-left:100px;text-align:center;width:60%;border:1px solid #FF6600;">
        ESTAS CALIFICACIONES SON PARCIALES Y NO TIENEN VALIDEZ OFICIAL.<br/>
        Para Cualquier aclaración, favor de acudir a la Dirección
        <br/>
        o a la Oficialía Mayor de esta Escuela.
        <br/>
        Teléfono 3832332333.
        </div>';

        $code .= '<br/><br/><div><table>
                    <thead>
                    <tr><th>Materia</th>';

        $llaves = array_keys($datos_retorno);
        sort($perds);
        if (sizeof($perds) > 0) {
            foreach ($perds as $p)
                if ($p != "-1")
                    $code .= '<th>' .
                    $p . '</th>';
        } else {
            $code .= '<th></th>';
        }

        $code .= '<th>Ordinario</th><th>Extraordinario</th><th>Estado</th></tr></thead><tbody>';
        $estilo = 0;
        foreach ($datos_retorno as $curso) {
            $llaves = array_keys($curso);

            $crs = $datos_cursos[$curso[$llaves[0]]["curso_id"]];

            $clase = "odd";
            if ($estilo % 2 != 0)
                $clase = "no_odd";

            $code .= '<tr class="' . $clase . '" >
                            <td><span class="sub">' . $crs["materia_nombre"] . '</span></td>';
            $estilo++;
            foreach ($perds as $p) {

                if ($curso[$p] != null && $p != -1) {
                    $periodo = $curso[$p];
                    if ($periodo["valor"] < 60) {
                        $conValor += $periodo["valor"];
                        $code .= '<td><span class="alerta">' . $periodo["valor"] . '</span></td>';
                    } else {
                        $conValor += $periodo["valor"];
                        $code .= '<td>' . $periodo["valor"] . '</td>';
                    }


                } else
                    if ($p != -1)
                        $code .= '<td>-</td>';
                    else
                        $periodo = $curso[$p];
            }

            if ($crs["ordinario"] > 59 || $crs["ordinario"]=='A' ){
                $code .= '<td>' .
                $crs["ordinario"] . "</td>";
                $conValor += 1;
            }else{
                if($crs["ordinario"]==""){
                $code .= "<td>-</td>";
                }else{
                $code .= '<td><span class="alerta">' .
                $crs["ordinario"] . "</span></td>";
                $conValor += 1;
                }
            }
            if ($crs["extra"] > 59)
                $code .= '<td>' .
                $crs["extra"] . "</td>";
            else{
                if($crs["extra"]==""){
                $code .= "<td>-</td>";
                }else{
                $code .= '<td><span class="alerta">' .
                $crs["extra"] . "</span></td>";
                }
            }
            if($crs["status"]=='Reprobado')
            $code .= '<td><span class="alerta">' . $crs["status"] . "</span></td>";
            else
            $code .= "<td>" . $crs["status"] . "</td>";
            $code .= '</tr>';

        }

        $code .= '</tboby></table></div>' . $this->footer();
        $code .= '</body></html>';

        if ($conValor == 0) {
            $code = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">' .
            '<html>' .
            '<head>' .
            '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />' .
            $this->estilo() .
            '</head>';
            $code .= '<body>';
            $code .= '<h1>Calificaciones.</h1>';
            $code .= '<p align="right" style="width:80%">Ciclo ' . $alumno_grupo["numero"] . '</p>';
            $code .= '<br/><br/><p class="infoBox"><br/>No existen registros de calificaciones del alumno para el ciclo actual.</p><br/><br/>' . $this->footer();
            ;
            $code .= '</body></html>';

        }
        $db->close();

        return $code;

    }

    public function kardexPDF($alumno_id) {
        $consulta = " SELECT  calificaciones.alumnos_id, calificaciones.valor, calificaciones.oportunidades_id,";
        $consulta .= "  cursos.materias_id, cursos.profesores_id,";
        $consulta .= "  grupos.grado, grupos.letra,";
        $consulta .= "  grupos.turno,";
        $consulta .= "  ciclos.id as ciclo_id, ciclos.numero, ciclos.inicio, ciclos.fin,ciclos.avance,";
        $consulta .= "  materias.clave, materias.nombre AS nombre_materia,";
        $consulta .= "  profesores.nombre AS nombre_profesor, profesores.ap AS ap_profesor, profesores.am AS am_profesor,";
        $consulta .= "  oportunidades.nombre As oportunidad";
        $consulta .= " FROM  calificaciones, cursos, grupos, ciclos, materias, profesores, oportunidades";
        $consulta .= " WHERE  calificaciones.cursos_id= cursos.id AND  grupos.id= cursos.grupos_id AND  grupos.ciclos_id= ciclos.id AND  materias.id= cursos.materias_id AND  profesores.id= cursos.profesores_id AND  oportunidades.id= calificaciones.oportunidades_id AND  calificaciones.alumnos_id='" . $alumno_id . "'";
        $consulta .= " order by  ciclos.inicio, materias.nombre, calificaciones.oportunidades_id";
        $db = new db("localhost", "hekademos", "hekademos", "hekademos");
        $elementos = $db->in_query($consulta);
        $db->close();
        $ciclos = array ();
        $index = 0;
        $this->total_cursos = 0;
        $puntajes = 0;
        if (sizeof($elementos) > 0) {
            foreach ($elementos as $ele) {
                if($ele["avance"]==1){
                $ciclos[$ele["ciclo_id"]][$index] = $ele;
                $puntajes += $ele["valor"];
                $index++;
                $this->total_cursos++;
                }
            }

            if ($this->total_cursos > 0)
                $this->promedio = ($puntajes / $this->total_cursos);
            else
                $this->promedio = 0;
            $escolar = new EscolarWeb();
            $alumno = $escolar->getAlumno($alumno_id);

            $code = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"><html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />' .
            $this->estilo() .
            '</head>';

            $code .= '<body><h1>Kardex.</h1>';

            $code .= '<div>';
            $code .= '<table summary="Datos Personales del Alumno.">';
            $code .= '<thead>';
            $code .= '<tr><th colspan="3">Ficha del alumno.</th></tr>';
            $code .= '</thead>';
            $code .= '<tbody>';
            $img_path = "http://localhost" . KUMBIA_PATH . "public/img/sp5/persona.jpg";
            if (strlen(trim($alumno["foto"])) > 0 && file_exists("C:\\xampp\\htdocs\\sp5\\public\\img\\alumnos\\".$alumno["foto"]))
                $img_path = "http://localhost/sp5/img/alumnos/".$alumno["foto"];
            $code .= '
                <tr class="odd"><td>Código</td><td>' . $alumno["codigo"] . '</td><td rowspan="3"><img height="100" src="' . $img_path . '" /></td></tr>
                <tr class="no_odd"><td>Situación</td><td>' . $alumno["situacion"] . '</td></tr>
                <tr class="odd"><td>Nombre</td><td>' . $alumno["nombre"] . " " . $alumno["ap"] . " " . $alumno["am"] . '</td></tr>
                <tr class="no_odd"><td>Fecha de Admisión</td><td colspan="2">' . fecha_espanol($alumno["admision"]) . '</td></tr>
                </tbody>
                </table>';
            $code .= '</div>';

            $code .= '<br/><br/>';

            $code .= '<div>';
            $code .= '<table>';
            $code .= '<thead>';
            $code .= '<tr><th>Materias cursadas</th><th>Promedio</th></tr>';
            $code .= '</thead>';
            $code .= '<tbody>';
            $code .= '<tr class="odd"><td>' . $alumno["aprobadas"] . '</td>';
            if ($alumno["promedio"] < 60)
                $code .= '<td><span class="alerta">' .
                $alumno["promedio"] . '</span></td></tr>';
            else
                $code .= '<td>' .
                $alumno["promedio"] . '</td></tr>';

            $code .= '</tbody>';
            $code .= '</table>';
            $code .= '</div>';

            $code .= '<br/>';
            $code .= '<div>';
            foreach ($ciclos as $ciclo) {
                $llav=array_keys($ciclo);
                $cic=$ciclo[$llav[0]];
                $code .= '<div>';
                $code.= '<h3>Ciclo '.$cic["numero"].'</h3>';
                $code .= '<table>';
                $code .= '<thead>';
                $code .= '<tr><th>Ciclo</th><th>Clave</th><th>Materia</th><th>Calificación</th><th>Profesor</th><th>Tipo</th></tr>';
                $code .= '</thead>';
                $code .= '<tbody>';
                $cont = 0;
                foreach ($ciclo as $ele) {
                    $class = "";
                    if ($cont % 2 == 0)
                        $class = ' class="odd" ';

                    $cont++;
                    $code .= '<tr ' . $class . '>' .
                    '<td><span style="color: rgb(119, 119, 119);" class="sub">' . $ele["numero"] . '</span></td>' .
                    '<td><span style="color: rgb(119, 119, 119);" class="sub">' . $ele["clave"] . '</span></td>' .
                    '<td><span class="sub">' . $ele["nombre_materia"] . '</span></td>';
                    if ($ele["valor"] < 60)
                        $code .= '<td><span class="alerta" >' .
                        $ele["valor"] . '</span></td>';
                    else
                        $code .= '<td>' .
                        $ele["valor"] . '</td>';

                    if($ele["oportunidades_id"]==2)
                    $op='<span class="extraordinario">'.$ele["oportunidad"].'</span>';
                    else
                    $op=$ele["oportunidad"];

                    $code .= '<td><span style="color: rgb(119, 119, 119);" class="sub">' . $ele["nombre_profesor"] . " " . $ele["ap_profesor"] . " " . $ele["am_profesor"] . '</span></td>' .
                    '<td>' . $op . '</td>' .
                    '</tr>';

                }
                $code .= '</tbody>';
                $code .= '</table>';
                $code .= '</div>';

            }
            $code .= '</div>' . $this->footer();
            $code .= '</body></html>';
        } else {
            $code = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"><html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />' .
            $this->estilo() .
            '</head>';
            $code .= '<body><h1>Kardex.</h1>';
            $code .= '<br/><br/><p class="infoBox"><br/>El alumno no cuenta con con materias cursadas.</p>';
            $code .= $this->footer();
            $code .= '</body></html>';

            $code .= '<body><h1>Kardex.</h1>';

        }
        return $code;

    }

    private function header() {
        return '<div id="header">
                            <div class="content">
                            <img src="http://localhost' . KUMBIA_PATH . 'img/sp5/ban.jpg" />
                            </div>' .
        '</div>';
    }

    private function footer() {
        return '<div id="footer">' .
                '            <br/>
                            <br/>
                            <div class="content" style="text-align:right;">'.generar_fecha_hora().'</div>
                         </div>';

    }

    private function estilo() {
            return '<style>' .
            '.t1 {color:#99AA00;}'.
            '.t2 {color:#CC0000;}'.
            '.extraordinario {color:#EECC00;font-size:12px;font-weight:bold;}' .
            'td,th{border:1px #000;text-align:center;}' .
            'h2.sub {font-size:1.5em;line-height:1;margin:1.07em 0pt 0.535em;}' .
            '.alerta{color:#CC0000;font-weight: bold;font-size:12px;}' .
            '.falta{color:#CC0000;font-size:12px;}' .
            'p.infoBox {background-color:#FFFFFF;);border-color:#000;' .
            'line-height:200%;min-height:50px;text-align:center;}' .
            'table{width:80%;border-width: 1px 1px 1px 1px;border-spacing: 0px;border-style: outset outset outset outset;border-color:#000;margin-left:auto; margin-right:auto;border-collapse: separate;background-color: white;}
            table th {border-width: 1px 1px 1px 1px;padding: 1px 1px 1px 1px;border-style: inset inset inset inset;border-color: gray gray gray gray;background-color: white;}
            table td {border-width: 1px 1px 1px 1px;padding: 1px 1px 1px 1px;border-style: inset inset inset inset;border-color: gray gray gray gray;background-color: white;}' .
            'span.sub {color:#99AA00;font-size:60%;font-weight:bold;}' .
            'body{margin-left:5em;margin-right:5em;width:76em;}' .
            '#header, #options, #search, #pages{display:none;}
            div.content span.print{display:block;}
            #primary{margin:0em;}
            #content{margin: 0;}
            body{width:100%;margin:1em;paddin:0;font-size:60%;}' .
            'table.tabla_mes {margin: 0 auto;text-align:center;width:30%}' .
            ' p.tipBox{text-align:center;border:1px solid #FF6600;margin:0pt auto 10px;padding:4px 4px 10px 55px;vertical-align:middle;width:60%;}'.
            '</style>';

    }

    private function estilo_asistencias() {
        return '<style>' .
        '.extraordinario {color:#EECC00;font-size:12px;font-weight:bold;}' .
        'td,th{border:1px #000;text-align:center;}' .
        'h2.sub {font-size:1.5em;line-height:1;margin:1.07em 0pt 0.535em;}' .
        '.alerta{color:#CC0000;font-weight: bold;font-size:12px;}' .
        '.falta{color:#CC0000;font-size:12px;}' .
        'p.infoBox {background-color:#FFFFFF;);border-color:#000;' .
        'line-height:200%;min-height:50px;text-align:center;}' .
        'table{width:80%;border-width: 1px 1px 1px 1px;border-spacing: 0px;border-style: outset outset outset outset;border-color:#000;margin-left:auto; margin-right:auto;border-collapse: separate;background-color: white;}
        table th {border-width: 1px 1px 1px 1px;padding: 1px 1px 1px 1px;border-style: inset inset inset inset;border-color: gray gray gray gray;background-color: white;}
        table td {border-width: 1px 1px 1px 1px;padding: 1px 1px 1px 1px;border-style: inset inset inset inset;border-color: gray gray gray gray;background-color: white;}' .
        'body{margin-left:5em;margin-right:5em;width:76em;}' .
        '#header, #options, #search, #pages{display:none;}
        div.content span.print{display:block;}
        #primary{margin:0em;}
        #content{margin: 0;}
        body{width:100%;margin:1em;paddin:0;}' .
        'table.tabla_mes {margin:0pt;width:30%;}' .
        '</style>';

    }

    public function asistenciasPDF($id, $id_ciclo) {
        ini_set("memory_limit","64M");
        $consulta = "SELECT alumnos.id AS alumnos_id,alumnos.codigo,alumnos.ap,alumnos.am,alumnos.nombre,grupos.id AS grupo_id,grupos.grado,grupos.letra,grupos.turno,ciclos.numero" .
        " FROM alumnos,alumnosgrupo,grupos,ciclos WHERE alumnosgrupo.grupos_id=grupos.id AND alumnosgrupo.alumnos_id='" . $id . "' AND alumnos.id='" . $id . "' AND ciclos.id='" . $id_ciclo . "' AND grupos.ciclos_id='" . $id_ciclo . "'";

        $db = new db("localhost", "hekademos", "hekademos", "hekademos");
        $alumno_grupo = $db->fetch_one($consulta);
        $val = "";
        $consulta = "SELECT cursos.id,materias.id AS materia_id,materias.clave,materias.nombre AS materia_nombre,cursos.profesores_id FROM alumnoscursos,cursos,grupos,materias WHERE alumnoscursos.alumnos_id=" . $id . " AND alumnoscursos.cursos_id=cursos.id AND
                            grupos.id=cursos.grupos_id AND grupos.ciclos_id='" . $id_ciclo . "' AND materias.id=cursos.materias_id";
        $cursos = $db->in_query($consulta);

        $datos_retorno = array ();
        $materias = array ();
        $mesesFecha=array();
           foreach($cursos as $curso){
               $consulta="SELECT id AS asistencia_id,asistenciasvalor_id,dia FROM asistencias WHERE cursos_id=".$curso["id"]." AND alumnos_id=".$alumno_grupo["alumnos_id"].' ORDER BY dia DESC';
               $asistencias=$db->in_query($consulta);

               $diasTotales=$db->in_query("SELECT * " .
                                "FROM  asistencias " .
                                "WHERE cursos_id = '" . $curso['id'] . "' " .
                                "GROUP BY dia " .
                                "ORDER BY dia DESC ");
            $dmaterias=array();
            foreach($diasTotales as $dt){
            $dmaterias[substr($dt["dia"],5,2)][substr($dt["dia"],8,2)]=$dt;
            }

               foreach($asistencias as $asistencia){

               $datos_retorno[substr($asistencia["dia"],5,2)][substr($asistencia["dia"],8,2)][$curso["id"]]=array(
                                "alumno_id" => $alumno_grupo["alumnos_id"], "grupo_id" => $alumno_grupo["grupo_id"], "curso_id" => $curso["id"],
                                "materia_id" => $curso["materia_id"], "clave" => $curso["clave"],"materia_nombre" => $curso["materia_nombre"],
                                "asistencia_id" => $asistencia["asistencias_id"], "asistenciavalor_id" => $asistencia["asistenciasvalor_id"],
                                "dia" => $asistencia["dia"]);

            $materias[$curso["id"]]=$curso["materia_nombre"];
            $mesesFecha[substr($asistencia["dia"],5,2)]=substr($asistencia["dia"],0,4).substr($asistencia["dia"],5,2);
            }

            foreach($dmaterias as $days){
                foreach($days as $day){
                    $eva=$datos_retorno[substr($day["dia"],5,2)][substr($day["dia"],8,2)][$day["cursos_id"]];
                    if($eva==null){
                        $datos_retorno[substr($day["dia"],5,2)][substr($day["dia"],8,2)][$day["cursos_id"]]=array(
                                "alumno_id" => $alumno_grupo["alumnos_id"], "grupo_id" => $alumno_grupo["grupo_id"], "curso_id" => $curso["id"],
                                "materia_id" => $curso["materia_id"], "clave" => $curso["clave"],"materia_nombre" => $curso["materia_nombre"],
                                "asistencia_id" => "-1", "asistenciavalor_id" => 0,
                                "dia" => $day["dia"]);
                    }

                }
            }
           }
        $cuerpo = '';
        $db->close();
        $code = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"><html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />' .
        $this->estilo() .
        '</head>';
        $code .= '<body>
        <h1>Asistencias</h1>' .
        '<br/><p style="text-align:right;">Ciclo ' . $alumno_grupo["numero"] . '</p>';
        $cuerpo='<br/>
        <br/>
        <p style="border-bottom:1px solid #000;width:80%;margin-left:10%;" ></p>
        <h2>Registro mensual</h2>
        <br/>
        <div style="text-align:center;" >
        <table style="width:200px;">
        <tbody>' .
                '
        <tr >' .
        '<td class="asistencia_1" style="width:18px">A</td>' .
        '<td >Asistencia</td>' .
        '<td class="asistencia_0" style="width:18px"><span class="falta">F</span></td>' .
        '<td >Falta</td>' .
        '<td style="width:18px" > </td>' .
        '<td>Sin clase</td>' .
        '</tr>
        </tbody>
        </table>
        </div>
        <br/>';

        $meses = array_keys($datos_retorno);
        //rsort($meses, SORT_NUMERIC);
        $resumen = array ();
    $cont=0;
    foreach($datos_retorno as $dia){
            $dia = $datos_retorno[$meses[$cont]];
            $cuerpo .= '<div style="text-align:left">
                                <h2 class="sub">'.mes_espanol($meses[$cont]) . '</h2>' .
                                        '<br/><br/><br/>
                                 <table class="tabla_mes">
                                 <thead>';
                $cont++;

            $dias = array_keys($dia);
            $cuerpo .= '<tr><th>Materia</th>';
            sort($dias);
            foreach ($dias as $d)
                if(substr($d,0,1)=='0')
                $cuerpo .= '<th>' .substr($d,1) . '</th>';
                else
                $cuerpo .= '<th>' .$d . '</th>';

            $cuerpo .= '</tr></thead>';

            $mats = array ();
            foreach ($dia as $curso) {
                foreach ($curso as $asistencia) {
                    $mats[$asistencia["materia_nombre"]][substr($asistencia["dia"], 8, 2)] = $asistencia;
                }
            }

            sort($materias);
            $cuerpo .= '<tbody>';

            for ($ct = 0; $ct < sizeof($materias); $ct++) {
                $m = $mats[$materias[$ct]];
                $estilo = "no_odd";
                if ($ct % 2 == 0)
                    $estilo = "odd";

                $cuerpo .= '<tr class="' . $estilo . '"><td><span class="sub">' . $materias[$ct] . '</span></td>';
                for ($index = 0; $index < sizeof($dias); $index++) {
                    $dd = $dias[$index];
                    $valor = $m[$dd];
                    if ($valor == null)
                        $cuerpo .= '<td class="asistencia_' .$valor["asistenciavalor_id"] . '">'.$valor["asistenciavalor_id"].'</td>';
                    else{
                        if($valor["asistenciavalor_id"]==0)
                        $cuerpo .= '<td class="asistencia_' .$valor["asistenciavalor_id"] . '" ><span class="falta">F</span></td>';
                        else
                        $cuerpo .= '<td class="asistencia_' .$valor["asistenciavalor_id"] . '" >A</td>';

                    }
                    if ($resumen[$valor["materia_nombre"]][$valor["asistenciavalor_id"]] == null)
                        $resumen[$valor["materia_nombre"]][$valor["asistenciavalor_id"]] = 1;
                    else
                        $resumen[$valor["materia_nombre"]][$valor["asistenciavalor_id"]] += 1;

                }
                $cuerpo .= '</tr>';
            }
            $cuerpo .= '</tbody></table></div>';
            $cuerpo .= $this->footer();
            $cuerpo .='<div style="page-break-after:always"></div>';
        }

        $res = '<br/>
                <br/>
                <p style="border-bottom:1px solid #000;width:80%;margin-left:10%;" ></p>';
        $res .= '<div><h2 >Resumen</h2>
                 <table>
                <thead>
                <tr>
                <th>Materia</th><th>Asistencias</th><th>Faltas</th><th>%</th><th>Estado</th>
                </tr>
                </thead>
                <tbody>';

        $size = sizeof($materias);
        if ($size > 0) {
            for ($index = 0; $index < $size; $index++) {
                $materia = $resumen[$materias[$index]];
                $estilo = "no_odd";
                if ($index % 2 == 0)
                    $estilo = "odd";

                $res .= '<tr class="' . $estilo . '">';

                $res .= '<td><span class="sub">' . $materias[$index] . "</span></td>";
                $res .= '<td>' . $materia["1"] . "</td>";

                if($materia["0"]=="")
                    $materia["0"]="0";

                $res .= '<td>' . $materia["0"] . "</td>";

                $total = $materia["0"] + $materia["1"] + $materia["3"];

                $r = $materia["1"] / $total;
                $porcentaje = $r * 100;

                $num=number_format($porcentaje, 2, '.', '');

                $cifra=explode('.',$num);

                if($cifra[1]==0)
                $num=$cifra[0];


                if ($porcentaje > 79)
                    $res .= '<td>' .$num . "</td>";
                elseif ($porcentaje > 59)
                    $res .= '<td ><span class="extraordinario">' .$num . "<span></td>";
                else
                    $res .= '<td><span class="alerta">' .$num . "<span></td>";

                if ($porcentaje > 79) {
                    $status = "Ordinario";

                } else
                    if ($porcentaje > 59) {
                        $status = '<span class="extraordinario">Extraordinario<span>';

                    } else {

                        $status = '<span class="alerta">Sin Derecho<span>';

                    }

                $res .= '<td  class="asistencia_">' . $status . "</td>";

                $res .= "</tr>";

            }
            $res .= "</tbody></table></div>";

            $res .= $this->footer();
            $res .='<div style="page-break-after:always"></div>';

                $code .=  $this->alumno(). $res . $cuerpo .'</body></html>';

        } else {
            $h = '<h1>Asistencias</h1>'; //<br/>Ciclo '.$alumno_grupo["numero"]
            $code .= '<br/><p class="infoBox"><br/>No existen registros de asistencias del alumno para el ciclo actual.</p><br/><br/>'. $this->footer() . '</body></html>';
        }

        //Generamos la salida
        return $code;
    }


    public function fichaPDF($alumno_id) {
        $escolar = new EscolarWeb();
        $escolar = new escolarWeb();
        $alumno = $escolar->getAlumnoGrupo($alumno_id);
        if ($alumno == null) {
            $alumno = $escolar->getAlumno($alumno_id);
        }

        $tutores = $escolar->getTutoresDeAlumno($alumno_id);

        $code = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"><html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />' .
        $this->estilo() .
        '</head><body>'.
        '<h1>Ficha.</h1>';

        $code .= '<h2 class="sub">Alumno</h2><br/><br/><br/>';
        $code .= '<table>';
        $code .= '<thead>';
        $code .= '<tr><th colspan="3" scope="col">Personal.</th></tr>';
        $code .= '</thead>';
        $code .= '<tbody>';

        $img_path = "http://localhost" . KUMBIA_PATH . "public/img/sp5/persona.jpg";
        if (strlen(trim($alumno["foto"])) > 0 && file_exists("C:\\xampp\\htdocs\\sp5\\public\\img\\alumnos\\".$alumno["foto"]))
            $img_path = "http://localhost/sp5/img/alumnos/".$alumno["foto"];

        $code .= '<tr><th>Nombre</th><td>' . $alumno["nombre"] . " " . $alumno["ap"] . " " . $alumno["am"] . '</td><td rowspan="3"><img height="100" src="' . $img_path . '" alt="Foto del alumno ' . $alumno["nombre"] . " " . $alumno["ap"] . " " . $alumno["am"] . '" /></td></tr>
                <tr ><th>Domicilio</th><td>' . $alumno["domicilio"] . '</td></tr>
                <tr ><th>Telefono</th><td>' . $alumno["tel"] . '</td></tr>
                <tr ><th>Celular</th><td colspan="2">' . $alumno["cel"] . '</td></tr>
                <tr ><th>Email</th><td colspan="2">' . $alumno["mail"] . '</td></tr>
                <tr ><th>CURP</th><td colspan="2">' . $alumno["curp"] . '</td></tr>
                <tr ><th>Fecha de Nacimiento</th><td colspan="2">' . fecha_espanol($alumno["fnacimiento"]) . '</td></tr>
                <tr ><th>Sexo</th><td colspan="2">' . $alumno["sexo"] . '</td></tr>
                </tbody>
                </table>
            <br/><br/>';

        $code .= '
                <table>
                <thead>
                <tr><th colspan="2">Escolar.</th></tr>
                </thead>
                <tbody>
                <tr><th>Codigo</th><td>' . $alumno["codigo"] . '</td></tr>
                <tr><th>Situación</th><td>' . $alumno["situacion"] . '</td></tr>
                <tr><th>Fecha de Admisión</th><td>' . fecha_espanol($alumno["admision"]) . '</td></tr>';
        if (trim($alumno["grado"]) != "" && trim($alumno["letra"]) != "") {
            $code .= '<tr class="no_odd"><th>Grupo</th><td>' . $alumno["grado"] . " - " . $alumno["letra"] . '</td></tr>';
        }

        if (trim($alumno["turno"]) != "") {
            $code .= '<tr class="odd"><th>Turno</th><td>' . $escolar->regresaTurno($alumno["turno"]) . '</td></tr>';
        }
        $code .= '</tbody>
                </table><br/>';

                if (sizeof($tutores) > 0) {
            $code .= $this->footer();
            $code .='<div style="page-break-after:always"></div>';
            $code .= '<h2 class="sub">Tutores</h2><br/><br/>';
            foreach ($tutores as $tutor) {
                $code .= '<br/><br/><table>';
                $code .= '<thead>';
                $code .= '<tr><th colspan="3" scope="col">Personal.</th></tr>';
                $code .= '</thead>';
                $code .= '<tbody>';
                $img_path = "http://localhost" . KUMBIA_PATH . "public/img/sp5/persona.jpg";
                if (strlen(trim($tutor["foto"])) > 0 && file_exists("C:\\xampp\\htdocs\\sp5\\public\\img\\tutores\\".$tutor["foto"]))
                    $img_path="http://localhost/sp5/img/tutores/".$tutor["foto"];

                $code .= '<tr class="odd"><th>Nombre</th><td>' . $tutor["nombre"] . " " . $tutor["ap"] . " " . $tutor["am"] . '</td><td rowspan="3"><img height="100" src="' . $img_path . '" alt="Foto del tutor ' . $tutor["nombre"] . " " . $tutor["ap"] . " " . $tutor["am"] . '" /></td></tr>
                        <tr class="no_odd"><th>Domicilio</th><td>' . $tutor["domicilio"] . '</td></tr>
                        <tr class="odd"><th>Teléfono</th><td>' . $tutor["tel"] . '</td></tr>
                        <tr class="no_odd"><th>Celular</th><td colspan="2">' . $tutor["cel"] . '</td></tr>
                        <tr class="odd"><th>Email</th><td colspan="2">' . $tutor["mail"] . '</td></tr>

                        </tbody>
                        </table><br/>';
                        //<tr class="no_odd"><th>Fecha de Nacimiento</th><td colspan="2">' . fecha_espanol($tutor["fnacimiento"]) . '</td></tr>
                    //    <tr class="odd"><td>Sexo</td><td colspan="2">' . $tutor["sexo"] . '</td></tr>
            }

        }

        $code .= $this->footer() .
        '</body></html>';
        return $code;
    }


    function horarioPDF($id, $id_ciclo) {
        $consulta = "SELECT alumnos.id AS alumnos_id,alumnos.codigo,alumnos.ap,alumnos.am,alumnos.nombre,grupos.id AS grupo_id,grupos.grado,grupos.letra,grupos.turno,ciclos.numero,grupos.oferta_id" .
        " FROM alumnos,alumnosgrupo,grupos,ciclos WHERE alumnosgrupo.grupos_id=grupos.id AND alumnosgrupo.alumnos_id='" . $id . "' AND alumnos.id='" . $id . "' AND ciclos.id='" . $id_ciclo . "' AND grupos.ciclos_id='" . $id_ciclo . "'";

        $db = new db("localhost", "hekademos", "hekademos", "hekademos");
        $alumno_grupo = $db->fetch_one($consulta);

        $consulta = "SELECT * FROM dias ORDER BY id";
        $diasSemana = $db->in_query($consulta);

        $consulta = "SELECT cursos.id,cursos.inicio AS fecha_inicio,materias.id AS materia_id,materias.clave,materias.nombre AS materia_nombre,cursos.profesores_id,profesores.ap,profesores.am,profesores.nombre as profe FROM alumnoscursos,cursos,grupos,materias,profesores WHERE alumnoscursos.alumnos_id=" . $id . " AND alumnoscursos.cursos_id=cursos.id AND
                            grupos.id=cursos.grupos_id AND grupos.ciclos_id='" . $id_ciclo . "' AND materias.id=cursos.materias_id AND profesores.id=cursos.profesores_id ORDER BY materias.nombre,cursos.inicio ";
        $cursos = $db->in_query($consulta);
        $materias = array ();
        $profes = array ();
        $cond = "";
        $informacion = array ();
        $datos = array ();
        $ifm=array();
        $conds=array();
        if (count($cursos) > 0) {
            foreach ($cursos as $curso) {
                $ini  =  new DateTime( $curso["fecha_inicio"]);

                $cond=$conds[$ini->format('W')];
                if($cond==null)$cond='';

                $cond.=" horarios.cursos_id=".$curso["id"]." OR";
                $conds[$ini->format('W')]=$cond;

                $materias[$curso["id"]] = $curso["materia_nombre"];
                $profes[$curso["id"]] = $curso["ap"] . " " . $curso["am"] . " " . $curso["profe"];
                $ifm[$ini->format('W')][$curso["id"]]=array("curso_id"=>$curso["id"],
                                                 "fecha_inicio"=>$curso["fecha_inicio"],
                                                 "nombre"=>$curso["materia_nombre"],
                                                 "profe"=>$profes[$curso["id"]]);
                $informacion[$curso["id"]] = array (
                    "curso_id" => $curso["id"],
                    "fecha_inicio"=>$curso["fecha_inicio"],
                    "nombre" => $curso["materia_nombre"],
                    "profe" => $profes[$curso["id"]]
                );
            }
            foreach($conds as $key=>$cond){
            $cond = substr($cond, 0, strlen($cond) - 2);
            $consulta = "SELECT horarios.*,aulas.clave,aulas.nombre FROM horarios,aulas " .
            "WHERE (" . $cond . ") AND aulas.id=horarios.aulas_id ORDER BY horarios.dias_id,horarios.inicio";
            $clases = $db->in_query($consulta);
            $horario = array ();
            $bloques = array ();
            foreach ($clases as $clase) {
            $info=$informacion[$clase["cursos_id"]];

            $horario[$clase["dias_id"]][]=array("cursos_id"=>$clase["cursos_id"],
                                                                "nombre"=>$materias[$clase["cursos_id"]],
                                                                "inicio"=>$clase["inicio"],
                                                                "fin"=>$clase["fin"],
                                                                "aula"=>$clase["nombre"],
                                                                "clave_aula"=>$clase["clave"],
                                                                "dia"=>$clase["dias_id"],
                                                                "profesor"=>$profes[$clase["cursos_id"]],
                                                                "fecha_inicio"=>$info["fecha_inicio"]
        );


                $bloques[$clase["dias_id"]] = count(array_keys($horario[$clase["dias_id"]]));

            }

            arsort($bloques);

            $llaves = array_keys($bloques);
            $mas = $llaves[0];

            $banderaSab = false;
            $banderaDom = false;
            $ids = array_keys($horario);
            foreach ($ids as $id) {
                if ($id == 6)
                    $banderaSab = true;
                if ($id == 7) {
                    $banderaSab = true;
                    $banderaDom = true;
                }
            }

    for($b=0;$b<$bloques[$mas];$b++){

    foreach($diasSemana as $d){

    if(($d["id"]==6 && $banderaSab) || ($d["id"]==7 && $banderaDom) || $d["id"]<6){
        $cl=$horario[$d["id"]][$b];
        $horarios[$key][$b][$d["id"]]=$cl;

    }

    }
    }
    }
        }

        ksort($horarios);
            $code = "";
            $code = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"><html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />' .
            $this->estilo() .
            '</head>';
            $code .= '<body>';
            if (count($horarios) > 0) {
                $code .= '<h1>Horario</h1><p style="text-align:right;">Ciclo ' . $alumno_grupo["numero"] . '</p>';
                $code .= $this->alumno();
                $code .= '<div id="infoPrincipal">';
                $nm=1;
                foreach($horarios as $llv=>$datos){
                if($alumno_grupo["oferta_id"]==2)
                $code.='<h3>Horario '.strToLower(value_num($nm)).'</h3>';

                $nm++;

                $code.='<h4>Profesores</h4>
                        <table>
                        <tr>';
                if($alumno_grupo["oferta_id"]==2)
                    $code.='<th>Inicio</th>';

                $code.='<th>Materia</th>';
                $code.='<th>Profesor</th></tr>';
                $cont = 0;
                $informacion=$ifm[$llv];
                foreach ($informacion as $curso) {
                    $class = "";
                    if ($cont % 2 == 0)
                        $class = ' class="odd" ';

                    $code .= '<tr ' . $class . ' >';
                    if($alumno_grupo["oferta_id"]==2)
                    $code.='<td>'.fecha_espanol($curso["fecha_inicio"]).'</td>';

                    $code .= '<td><span class="sub">' . $curso["nombre"] . '</span></td><td><span class="sub" style="color: rgb(119, 119, 119);" >' . $curso["profe"] . '</span></td>
                        </tr>';
                    $cont++;
                }
                $code .= '</table>';

                $code.='<h4>Clases</h4>';
                $code.='<table>
                    <tr>';
                $semana = array_keys(current($datos));
                foreach ($semana as $dia) {
                    $code .= '<th>' . dia_espanol($dia) . '</th>';
                }
                $code .= '</tr>';

                foreach ($datos as $fila) {
                    $code .= '<tr>';
                    $cont = 0;
                    foreach ($fila as $celda) {
                        $clase = "";
                        if ($cont % 2 == 0)
                            $clase = ' class="par" ';

                        $code .= '<td ' . $clase . '>';
                        if ($celda["nombre"] != "") {
                            $code .= '<span class="sub">'.$celda["nombre"] . '</span><br/>' .
                            '<span class="sub" style="color: rgb(119, 119, 119);" >';
                            if($alumno_grupo['oferta_id']==2)$code.=convierteFecha($celda['fecha_inicio']).'<br/>';
                            $code.=substr($celda["inicio"], 0, 5) . ' - ' . substr($celda["fin"], 0, 5) . '<br/>' .
                            $celda["aula"] .
                            '</span>';

                        }

                        $code .= '</td>';

                        $cont++;
                    }

                    $code .= '</tr>';

                }
                $code .= '</table>
                            <br/>
                            <br/>';


                $code .= $this->footer();
                $code.='<div style="page-break-after:always"></div>';

                }

                    $code.='</div>';



            $code .= '</body></html>';

        } else {
            $code = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"><html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />' .
            $this->estilo() .
            '</head>';
            $code .= '<body>';
            $code .= '<h1>Horario</h1>
            <p style="text-align:right;">' . generar_fecha_hora() . '</p>';
            $code .= '<br/><br/>    <p class="infoBox"><br/>No existe horario del alumno para el ciclo actual.</p>';
            $code .= $this->footer() . '</body></html>';
        }

        $db->close();

        return $code;
    }

    function setDatos($c, $n) {
        $this->codigo = $c;
        $this->nombre = $n;
    }

    function alumno() {
        return '<div id="ficha">
                        <table>
                        <tr><th>Código</th><td>' . $this->codigo . '</td><th>Nombre</th><td>' . $this->nombre . '</td></tr>
                        </table>
                        </div>
                        <br/>';
    }

}
?>
