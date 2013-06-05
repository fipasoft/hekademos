<?php
Kumbia :: import('app.componentes.*');
Kumbia :: import('lib.kumbia.utils');
Kumbia :: import('lib.upload.main');
Kumbia :: import('lib.excel.main');
kumbia :: import('app.utils.*');

class AmonestacionesController extends ApplicationController {
    public $template = "system";

    public function agregar(){
        try{
            $clo = new Ciclos();
            $clo = $clo->find(Session :: get_data('ciclo.id'));
            $categorias = new Acategoria();
            $this->categorias = $categorias->find();
            $suspension = new Acategoria();
            $this->suspension = $suspension->find_first("nombre = 'Suspensión'");
            $grupal = new Acategoria();
            $this->grupal = $grupal->find_first("nombre = 'Grupal'");
            $grupos = new Grupos();
            $this->grupos = $grupos->find('ciclos_id = '.$clo->id.' order by grado,letra,turno,oferta_id');
            $reglamentos = new Reglamento();
            $this->reglamentos =$reglamentos->find_all_by_sql('SELECT distinct(reglamento.reglamentos_id ) FROM reglamento');
            //var_dump($this->reglamentos);exit; 
            if ($clo->abierto()) {
                if(trim($this->post("codigo"))!="" || $this->post('grupos') != ''){
                    $ciclo_id = Session :: get_data('ciclo.id');
                    $codigo = trim($this->post("codigo"));
                    $fecha = trim($this->post("fecha"));
                    $descripcion = trim($this->post("descripcion"));
                    if($fecha!="" && $descripcion!=""){
                        //////------------------------------------------------------------------------//////
                        $categorias = $categorias->find($this->post('amonestacion'));
                        if($categorias->nombre != 'Grupal'){
                        mysql_query("BEGIN") or die("AMO_AGR_1");
                        $alumno = new Alumnos();
                        $alumno = $alumno->find_first("codigo='".$codigo."'");
                        if($alumno->id!=""){
                            $estado = new Aestado();
                            if($this->post('aprobar') == '1'){
                                $estado = $estado->pornombre("Aprobada");
                            }else{
                                $estado = $estado->pornombre("No aprobada");
                            }
                            $amonestacion = new Amonestacion();
                            $amonestacion->aestado_id = $estado->id;
                            $amonestacion->fecha = Utils::convierteFechaMySql($fecha);
                            $amonestacion->descripcion = $descripcion;
                            //$amonestacion->alumnos_id = $alumno->id;
                            $amonestacion->ciclos_id = $ciclo_id;
                            $amonestacion->acategoria_id = $this->post('amonestacion');
                            /////                        
                            
                            $categorias->find($this->post('amonestacion'));
                            if($categorias->nombre == 'Suspensión'){
                                $amonestacion->inicio = Utils :: convierteFechaMySql($this->post('inicio'));
                                $amonestacion->fin = Utils :: convierteFechaMySql($this->post('fin'));
                            }
                            
                            ////
                            if($amonestacion->save()){
                                $amonestados = new Amonestados();
                                $amonestados->alumnos_id = $alumno->id;
                                $amonestados->amonestacion_id = $amonestacion->id;
                                if($amonestados->save()){
                                // guardando img en el servidor
                                if ($_FILES['imagen']['tmp_name'] != '') {
                                    $img = new Upload($_FILES['imagen'], 'es_ES');
                                    if ($img->uploaded) {
                                        $img->image_convert = 'jpg';
                                        $img->jpeg_quality = 100;
                                        $img->file_new_name_body = "tmp_".  $amonestacion->id . "_" . $alumno->codigo;
                                        $img->image_resize = false;
                                        $img->image_ratio_y = true;
                                        $img->file_overwrite = true;
                                        $img->file_auto_rename = false;
                                        $amonestacion->imagen = "tmp_" . $amonestacion->id . "_" . $alumno->codigo.'.jpg';
                                        $img->Process('./public/img/amonestaciones');
                                        if (!$img->processed) {
                                            $this->option = 'error';
                                            $this->error .= 'No se pudo procesar el archivo de imagen: ' . $img->error;
                                        }

                                        $upload = "./public/img/amonestaciones/".$amonestacion->imagen;
                                        $img=new Imagen($upload);
                                        if($img->getHeight()>1100){
                                            $upload2= "./public/img/amonestaciones/".$amonestacion->id . "_" . $alumno->codigo;
                                            $img->ThumbnailAjustaAlto(1100,$upload2);
                                            chmod($upload2, 0777);
                                            unlink($upload);
                                            $amonestacion->imagen = $amonestacion->id . "_" . $alumno->codigo.'.jpg';
                                        }

                                        $img=new Imagen('./public/img/amonestaciones/'.$amonestacion->imagen);
                                        $th = "./public/img/amonestaciones/th_".$amonestacion->imagen;
                                        $th = str_replace(".jpg","",$th);
                                        $img->ThumbnailAjustaAlto(100,$th);


                                        // guardando el path de la imagen
                                        if (!$amonestacion->save()) {
                                            mysql_query("ROLLBACK") or die("AMO_AGR_1");
                                            throw  new Exception('Error al guardar la direcci&oacute;n de la imagen en la BD.');
                                            
                                        }
                                    } else {
                                        mysql_query("ROLLBACK") or die("AMO_AGR_1");
                                        throw new Exception('No se pudo subir el archivo de imagen: ' . $img->error);
                                    }
                                }
                                
                                ////

                                
                                $reglamentos = $this->post('reglamentos');
                                $articulos = $this->post('articulos');
                                for($i = 0 ; $i < count($reglamentos) ; $i++){
                                    if($reglamentos[$i] != '' && $articulos[$i] != ''){
                                        $infracciones = new Infringe();
                                        $infringe = new Infringe();
                                        $reglamento = new Reglamento();
                                        $reglamento = $reglamento->find_first('reglamentos_id = '.$reglamentos[$i].' AND articulo_id = '.$articulos[$i]);
                                        if($reglamento->id != ''){
                                            $infracciones = $infracciones->find('reglamento_id = '.$reglamento->id.' AND amonestacion_id = '.$amonestacion->id);
                                            if(count($infracciones) < 1){
                                            $infringe->reglamento_id = $reglamento->id;
                                            $infringe->amonestacion_id = $amonestacion->id;
                                            if(!$infringe->save()){//Guarda la infraccion
                                                mysql_query("ROLLBACK") or die("AMO_AGR_1");
                                                throw new Exception('Error al guardar la infracci&oacute;n.');
                                            }
                                            }
                                        }
                                    }
                                }
                                
                                //////
                                $historial=new Historial();
                                $historial->ciclos_id= Session :: get_data('ciclo.id');
                                $historial->usuario=Session :: get_data('usr.login');
                                $historial->descripcion='Agrego '.($this->post('aprobar') == '1' ? 'y aprobo ' : '').'una amonestacion para el alumno '.$alumno->codigo. ' con la fecha '.$amonestacion->fecha;
                                $historial->controlador="amonestaciones";
                                $historial->accion="agregar";
                                $historial->saved_at=date("Y-m-d H:i:s");
                                $historial->save();
                                mysql_query("COMMIT") or die("AMO_AGR_1");
                                $this->option = "exito";
                                
                                
                                }else{
                                    mysql_query("ROLLBACK") or die("AMO_AGR_1");
                                    throw new Exception('Error en la BD.');
                                }
                            }else{
                                mysql_query("ROLLBACK") or die("AMO_AGR_1");
                                throw new Exception('Error en la BD.');
                            }
                        }else{
                            mysql_query("ROLLBACK") or die("AMO_AGR_1");
                            throw new Exception('Los datos no son validos.');
                        }
                        }else{ /////GRUPAL
                            mysql_query("BEGIN") or die("AMO_AGR_1");
                            $grupos = new Grupos();
                            $grupos = $grupos->find($this->post('grupos'));
                            $alumnos = $grupos->alumnos();
                            $amon = $this->post('amonestacion');
                            $reglamentos = $this->post('reglamentos');
                            $articulos = $this->post('articulos');
                            $estado = new Aestado();
                                if($this->post('aprobar') == '1'){
                                    $estado = $estado->pornombre("Aprobada");
                                }else{
                                    $estado = $estado->pornombre("No aprobada");
                                }
                            $amonestacion = new Amonestacion();
                            $amonestacion->aestado_id = $estado->id;
                            $amonestacion->fecha = Utils::convierteFechaMySql($fecha);
                            $amonestacion->descripcion = $descripcion;
                            $amonestacion->ciclos_id = $ciclo_id;
                            $amonestacion->acategoria_id = $amon;
                            if($amonestacion->save()){
                            foreach($alumnos as $alumno){
                                $amonestados = new Amonestados();
                                $amonestados->amonestacion_id = $amonestacion->id;
                                $amonestados->alumnos_id = $alumno->id;
                            if($amonestados->save()){
                                // guardando img en el servidor
                                if ($_FILES['imagen']['tmp_name'] != '') {
                                    $img = new Upload($_FILES['imagen'], 'es_ES');
                                    if ($img->uploaded) {
                                        $img->image_convert = 'jpg';
                                        $img->jpeg_quality = 100;
                                        $img->file_new_name_body = "tmp_".  $amonestacion->id . "_" . $alumno->codigo;
                                        $img->image_resize = false;
                                        $img->image_ratio_y = true;
                                        $img->file_overwrite = true;
                                        $img->file_auto_rename = false;
                                        $amonestacion->imagen = "tmp_" . $amonestacion->id . "_" . $alumno->codigo.'.jpg';
                                        $img->Process('./public/img/amonestaciones');
                                        if (!$img->processed) {
                                            $this->option = 'error';
                                            $this->error .= 'No se pudo procesar el archivo de imagen: ' . $img->error;
                                        }

                                        $upload = "./public/img/amonestaciones/".$amonestacion->imagen;
                                        $img=new Imagen($upload);
                                        if($img->getHeight()>1100){
                                            $upload2= "./public/img/amonestaciones/".$amonestacion->id . "_" . $alumno->codigo;
                                            $img->ThumbnailAjustaAlto(1100,$upload2);
                                            chmod($upload2, 0777);
                                            unlink($upload);
                                            $amonestacion->imagen = $amonestacion->id . "_" . $alumno->codigo.'.jpg';
                                        }

                                        $img=new Imagen('./public/img/amonestaciones/'.$amonestacion->imagen);
                                        $th = "./public/img/amonestaciones/th_".$amonestacion->imagen;
                                        $th = str_replace(".jpg","",$th);
                                        $img->ThumbnailAjustaAlto(100,$th);


                                        // guardando el path de la imagen
                                        if (!$amonestacion->save()) {
                                            mysql_query("ROLLBACK") or die("AMO_AGR_1");
                                            throw new Exception('Error al guardar la direcci&oacute;n de la imagen en la BD.');
                                            
                                        }
                                    } else {
                                        mysql_query("ROLLBACK") or die("AMO_AGR_1");
                                        throw new Exception('No se pudo subir el archivo de imagen: ' . $img->error);
                                    }
                                }
                                
                                ////

                                
                            $reglamentos = $this->post('reglamentos');
                                $articulos = $this->post('articulos');
                                for($i = 0 ; $i < count($reglamentos) ; $i++){
                                    if($reglamentos[$i] != '' && $articulos[$i] != ''){
                                        $infracciones = new Infringe();
                                        $infringe = new Infringe();
                                        $reglamento = new Reglamento();
                                        $reglamento = $reglamento->find_first('reglamentos_id = '.$reglamentos[$i].' AND articulo_id = '.$articulos[$i]);
                                        if($reglamento->id != ''){
                                            $infracciones = $infracciones->find('reglamento_id = '.$reglamento->id.' AND amonestacion_id = '.$amonestacion->id);
                                            if(count($infracciones) < 1){
                                            $infringe->reglamento_id = $reglamento->id;
                                            $infringe->amonestacion_id = $amonestacion->id;
                                            if(!$infringe->save()){//Guarda la infraccion
                                                mysql_query("ROLLBACK") or die("AMO_AGR_1");
                                                throw new Exception('Error al guardar la infracci&oacute;n.');
                                            }
                                            }
                                        }
                                    }
                                }
                                
                                //////
                                $historial=new Historial();
                                $historial->ciclos_id= Session :: get_data('ciclo.id');
                                $historial->usuario=Session :: get_data('usr.login');
                                $historial->descripcion='Agrego '.($this->post('aprobar') == '1' ? 'y aprobo ' : '').'una amonestacion para el alumno '.$alumno->codigo. ' con la fecha '.$amonestacion->fecha;
                                $historial->controlador="amonestaciones";
                                $historial->accion="agregar";
                                $historial->saved_at=date("Y-m-d H:i:s");
                                $historial->save();
                                mysql_query("COMMIT") or die("AMO_AGR_1");
                                $this->option = "exito";
                            }else{
                                mysql_query("ROLLBACK") or die("AMO_AGR_1");
                                throw new Exception('Error en la BD.');
                            }
                            }
                            
                            }else{
                                mysql_query("ROLLBACK") or die("AMO_AGR_1");
                                throw new Exception('Error en la BD');
                            }
                        }
                        //////--------------------------------------------------------------------------------///////
                    }else{
                        mysql_query("ROLLBACK") or die("AMO_AGR_1");
                        throw new Exception('Los datos no son validos.');
                    }

                }else{
                    $this->option = "captura";
                }
            }else{
                mysql_query("ROLLBACK") or die("AMO_AGR_1");
                throw new Exception('El ciclo esta cerrado');
            }
        }catch(Exception $e){
            $this->option = "error";
            $this->error = $e->getMessage();
        }
    }

    public function aprobar($id = ''){

        $clo = new Ciclos();
        $clo = $clo->find(Session :: get_data('ciclo.id'));
        if ($clo->abierto()) {
            if($id!=''){
                $amonestacion = new Amonestacion();
                $amonestacion = $amonestacion->find($id);
                if($amonestacion->id!=''){
                    $estado = new Aestado();
                    $estado = $estado->pornombre("No aprobada");
                    if($amonestacion->aestado_id == $estado->id){
                        $this->amonestacion = $amonestacion;
                        $amonestados = new Amonestados();
                        $this->amonestados = $amonestados->find('amonestacion_id = '.$amonestacion->id);
                        $this->option = "captura";
                    }else{
                        $this->option = "error";
                        $this->error = "El estado de la amonestación no permite aprobarla.";
                    }
                }else{
                    $this->option = "error";
                    $this->error = "Los datos no son validos.";
                }
            }elseif($this->post("id")){
                $amonestacion = new Amonestacion();
                $amonestacion = $amonestacion->find($this->post("id"));
                if($amonestacion->id!=''){
                    $estado = new Aestado();
                    $estado = $estado->pornombre("No aprobada");
                    if($amonestacion->aestado_id == $estado->id){
                        $aprobada = new Aestado();
                        $aprobada = $aprobada->pornombre("aprobada");
                        $amonestacion->aestado_id = $aprobada->id;
                        $amonestacion->save();
                        $this->option = "exito";

                        $amonestados = new Amonestados();
                        $amonestados = $amonestados->find('amonestacion_id = '.$amonestacion->id);
                        

                        $historial=new Historial();
                        $historial->ciclos_id= Session :: get_data('ciclo.id');
                        $historial->usuario=Session :: get_data('usr.login');
                        if(count($amonestados) > 1){
                            $grupo = $amonestados[0]->grupo();
                            $historial->descripcion='Aprobo la amonestacion para el grupo '.$grupo->grado.'°'.$grupo->letra.' '.$grupo->turno.' '.$grupo->verOferta(). ' con la fecha '.$amonestacion->fecha;
                        }else{
                            $alumno = new Alumnos();
                            $alumno = $alumno->find($amonestados[0]->alumnos_id);
                            $historial->descripcion='Aprobo la amonestacion para el alumno '.$alumno->codigo. ' con la fecha '.$amonestacion->fecha;    
                        }
                        $historial->controlador="amonestaciones";
                        $historial->accion="aprobar";
                        $historial->saved_at=date("Y-m-d H:i:s");
                        $historial->save();

                    }else{
                        $this->option = "error";
                        $this->error = "El estado de la amonestación no permite aprobarla.";
                    }
                }else{
                    $this->option = "error";
                    $this->error = "Los datos no son validos.";
                }
            }else{
                $this->option = "error";
                $this->error = "Los datos no son validos.";
            }
        }else{
            $this->option = "error";
            $this->error = ' El ciclo esta cerrado.';
        }
    }

    public function cancelar($id = ''){

        $clo = new Ciclos();
        $clo = $clo->find(Session :: get_data('ciclo.id'));
        if ($clo->abierto()) {
            if($id!=''){
                $amonestacion = new Amonestacion();
                $amonestacion = $amonestacion->find($id);
                if($amonestacion->id!=''){
                    $aprobada = new Aestado();
                    $aprobada = $aprobada->pornombre("aprobada");
                    if($amonestacion->aestado_id==$aprobada->id){
                        $this->amonestacion = $amonestacion;
                        $amonestados = new Amonestados();
                        $this->amonestados = $amonestados->find('amonestacion_id = '.$amonestacion->id);
                        $this->option ="captura";
                    }else{
                        $this->option = "error";
                        $this->error = "El estado de la amonestación no permite aprobarla.";
                    }
                }else{
                    $this->option = "error";
                    $this->error = "Los datos no son validos.";
                }
            }elseif($this->post("id")){
                $amonestacion = new Amonestacion();
                $amonestacion = $amonestacion->find($this->post("id"));
                if($amonestacion->id!=''){
                    $aprobada = new Aestado();
                    $aprobada = $aprobada->pornombre("aprobada");
                    if($amonestacion->aestado_id==$aprobada->id){

                        $cancelada = new Aestado();
                        $cancelada = $cancelada->pornombre("Cancelada");
                        $amonestacion->aestado_id = $cancelada->id;
                        $amonestacion->save();
                        $this->option = "exito";

                        $amonestados = new Amonestados();
                        $amonestados = $amonestados->find('amonestacion_id = '.$amonestacion->id);

                        $historial=new Historial();
                        $historial->ciclos_id= Session :: get_data('ciclo.id');
                        $historial->usuario=Session :: get_data('usr.login');
                        if(count($amonestados) > 1){
                            $grupo = $amonestados[0]->grupo();
                            $historial->descripcion='Cancelo la amonestacion para el grupo '.$grupo->grado.'°'.$grupo->letra.' '.$grupo->turno.' '.$grupo->verOferta(). ' con la fecha '.$amonestacion->fecha;
                        }else{
                            $alumno = new Alumnos();
                            $alumno = $alumno->find($amonestados[0]->alumnos_id);
                            $historial->descripcion='Cancelo la amonestacion para el alumno '.$alumno->codigo. ' con la fecha '.$amonestacion->fecha;    
                        }
                        $historial->controlador="amonestaciones";
                        $historial->accion="cancelar";
                        $historial->saved_at=date("Y-m-d H:i:s");
                        $historial->save();

                    }else{
                        $this->option = "error";
                        $this->error = "El estado de la amonestación no permite aprobarla.";
                    }
                }else{
                    $this->option = "error";
                    $this->error = "Los datos no son validos.";
                }
            }else{
                $this->option = "error";
                $this->error = "Los datos no son validos.";
            }
        }else{
            $this->option = "error";
            $this->error = ' El ciclo esta cerrado.';
        }
    }

    public function editar($id = ''){
        try{

            $clo = new Ciclos();
            $clo = $clo->find(Session :: get_data('ciclo.id'));
            if ($clo->abierto()) {
                if(trim($this->post("id"))!=""){
                    $id = trim($this->post("id"));
                    $codigo = trim($this->post("codigo"));
                    $fecha = trim($this->post("fecha"));
                    $descripcion = trim($this->post("descripcion"));
                    if($fecha!="" && $descripcion!="" && $id!=""){
                        mysql_query("BEGIN") or die("AMO_AGR_1");                        
                        $amonestacion = new Amonestacion();
                        $amonestacion = $amonestacion->find($id);
                        if($amonestacion->id!=""){
                            $estado = new Aestado();
                            $estado = $estado->pornombre("No aprobada");
                            if($amonestacion->aestado_id == $estado->id){
                                    $amonestacion->aestado_id = $estado->id;
                                    $amonestacion->fecha = Utils::convierteFechaMySql($fecha);
                                    $amonestacion->descripcion = $descripcion;
                                    if($this->post('tipo') == 'Suspensión'){
                                        $amonestacion->inicio = Utils::convierteFechaMySql($this->post('inicio'));
                                        $amonestacion->fin = Utils::convierteFechaMySql($this->post('fin'));
                                    }
                                    if($amonestacion->save()){

                                        // guardando img en el servidor
                                        if ($_FILES['imagen']['tmp_name'] != '') {
                                            $util=new Utileria();
                                            if($util->esImagen($_FILES['imagen']['name'])){
                                                unlink('./public/img/amonestaciones/'.$amonestacion->imagen);
                                                $img = new Upload($_FILES['imagen'], 'es_ES');
                                                if ($img->uploaded) {
                                                    $img->image_convert = 'jpg';
                                                    $img->jpeg_quality = 100;
                                                    $img->file_new_name_body = "tmp_". $amonestacion->id . "_" . $alumno->codigo;
                                                    $img->image_resize = false;
                                                    $img->image_ratio_y = true;
                                                    $img->file_overwrite = true;
                                                    $img->file_auto_rename = false;
                                                    $amonestacion->imagen = "tmp_". $amonestacion->id . "_" . $alumno->codigo.'.jpg';
                                                    $img->Process('./public/img/amonestaciones');
                                                    if (!$img->processed) {
                                                        $this->option = 'error';
                                                        $this->error .= 'No se pudo procesar el archivo de imagen: ' . $img->error;
                                                    }

                                                    $upload = "./public/img/amonestaciones/".$amonestacion->imagen;
                                                    $img=new Imagen($upload);
                                                    if($img->getHeight()>1100){
                                                        $nn=time();
                                                        $upload2= "./public/img/amonestaciones/".$amonestacion->id . "_" . $alumno->codigo;
                                                        $img->ThumbnailAjustaAlto(1100,$upload2);
                                                        unlink($upload);
                                                        $amonestacion->imagen = $amonestacion->id . "_" . $alumno->codigo.'.jpg';
                                                    }
                                                        
                                                    $img=new Imagen('./public/img/amonestaciones/'.$amonestacion->imagen);
                                                    $th = "./public/img/amonestaciones/th_".$amonestacion->imagen;
                                                    $th = str_replace(".jpg","",$th);
                                                    $img->ThumbnailAjustaAlto(100,$th);

                                                        

                                                    // guardando el path de la imagen
                                                    if (!$amonestacion->save()) {
                                                        mysql_query("ROLLBACK") or die("AMO_AGR_1");
                                                        throw new Exception('Error al guardar la direcci&oacute;n de la imagen en la BD.');
                                                    }
                                                } else {
                                                    $this->option = 'error';
                                                    $this->error .= 'No se pudo subir el archivo de imagen: ' . $img->error;
                                                }
                                            } else {
                                                $this->option = 'error';
                                                $this->error .= 'Se guardo la amonestación, pero la imagen no pudo ser guardada ya que no es un formato valido ' . $img->error;
                                            }
                                        }
                                        
                                        //////

                                        $infringe = new Infringe();
                                        $infringe = $infringe->delete('amonestacion_id = '.$amonestacion->id);
                                        
                                $reglamentos = $this->post('reglamentos');
                                $articulos = $this->post('articulos');
                                for($i = 0 ; $i < count($reglamentos) ; $i++){
                                    if($reglamentos[$i] != '' && $articulos[$i] != ''){
                                        $infracciones = new Infringe();
                                        $infringe = new Infringe();
                                        $reglamento = new Reglamento();
                                        $reglamento = $reglamento->find_first('reglamentos_id = '.$reglamentos[$i].' AND articulo_id = '.$articulos[$i]);
                                        if($reglamento->id != ''){
                                            $infracciones = $infracciones->find('reglamento_id = '.$reglamento->id.' AND amonestacion_id = '.$amonestacion->id);
                                            if(count($infracciones) < 1){
                                            $infringe->reglamento_id = $reglamento->id;
                                            $infringe->amonestacion_id = $amonestacion->id;
                                            if(!$infringe->save()){//Guarda la infraccion
                                                mysql_query("ROLLBACK") or die("AMO_AGR_1");
                                                throw new Exception('Error al guardar la infracci&oacute;n.');
                                            }
                                            }
                                        }
                                    }
                                }
                                    $amonestados = new Amonestados();
                                    $amonestados = $amonestados->find('amonestacion_id = '.$amonestacion->id);        
                                
                                
                                        /////
                                        $this->option = "exito";
                                        
                                        $historial=new Historial();
                                        $historial->ciclos_id= Session :: get_data('ciclo.id');
                                        $historial->usuario=Session :: get_data('usr.login');
                                    if(count($amonestados) > 1){
                                        $grupo = $amonestados[0]->grupo();
                                        $historial->descripcion='Edit la amonestacion para el grupo '.$grupo->grado.'°'.$grupo->letra.' '.$grupo->turno.' '.$grupo->verOferta(). ' con la fecha '.$amonestacion->fecha;
                                    }else{
                                        $alumno = new Alumnos();
                                        $alumno = $alumno->find($amonestados[0]->alumnos_id);
                                        $historial->descripcion='Edito la amonestacion para el alumno '.$alumno->codigo. ' con la fecha '.$amonestacion->fecha;    
                                    }
                                        $historial->controlador="amonestaciones";
                                        $historial->accion="editar";
                                        $historial->saved_at=date("Y-m-d H:i:s");
                                        $historial->save();
                                        mysql_query("COMMIT") or die("AMO_AGR_1");
                                        $this->option = "exito";

                                    }else{
                                        mysql_query("ROLLBACK") or die("AMO_AGR_1");
                                        throw new Exception('Error en la BD');
                                    }
                            }else{
                                mysql_query("ROLLBACK") or die("AMO_AGR_1");
                                throw new Exception('El estado acutal de la amonestación no permite edición');
                            }
                        }else{
                            mysql_query("ROLLBACK") or die("AMO_AGR_1");
                            throw new Exception('Los datos no son validos.');
                        }
                    }else{
                        $this->option = "error";
                        $this->error = "Los datos no son validos.";
                    }

                }else if($id != ''){
                    $amonestacion = new Amonestacion();
                    $amonestados = new Amonestados();
                    $alumno = new Alumnos();
                    $amonestacion = $amonestacion->find($id);
                    $categoria = new Acategoria();
                    $this->categoria = $categoria->find($amonestacion->acategoria_id);
                    if($amonestacion->id!=""){
                        $infringe = new Infringe();
                        $this->infracciones = $infringe->find('amonestacion_id = '.$amonestacion->id);
                        $reglamentos = new Reglamento();
                        $this->reglamentos =$reglamentos->find_all_by_sql('SELECT distinct(reglamento.reglamentos_id ) FROM reglamento');
                        $articulos = new Articulo();
                        $this->articulos = $articulos->find();
                        $this->reglamento = new Reglamento();
                        $estado = new Aestado();
                        $estado = $estado->pornombre("No aprobada");
                        if($amonestacion->aestado_id == $estado->id){
                            $this->amonestacion = $amonestacion;
                            $this->amonestados = $amonestados->find('amonestacion_id = '. $amonestacion->id);
                            if(count($this->amonestados) > 0)
                                $this->option = "captura";
                            else{
                                $this->option = "error";
                                $this->error = "Los datos no son validos.";
                            }                                

                        }else{
                            $this->option = "error";
                            $this->error = "El estado actual de la amonestación no permite la edición.";
                        }
                    }else{
                        $this->option = "error";
                        $this->error = "Los datos no son validos.";
                    }

                }else{
                    $this->option = "error";
                    $this->error = "Los datos no son validos.";
                }
            }else{
                $this->option = "error";
                $this->error = ' El ciclo esta cerrado.';
            }
        }catch(Exception $e){
            $this->option = "error";
            $this->error = $e->getMessage();
        }
    }


    public function eliminar($id = ''){

        $clo = new Ciclos();
        $clo = $clo->find(Session :: get_data('ciclo.id'));
        if ($clo->abierto()) {
            if($id != ''){
                $id = intval($id, 10);
                $amonestacion = new Amonestacion();
                $this->amonestacion = $amonestacion->find($id);
                $amonestados = new Amonestados();
                $this->amonestados = $amonestados->find('amonestacion_id = '.$amonestacion->id);
                //var_dump($this->amonestados);exit;
                if($this->amonestacion->id != ''){
                    $estado = new Aestado();
                    $estado = $estado->pornombre("No aprobada");

                    $cancelada = new Aestado();
                    $cancelada = $cancelada->pornombre("Cancelada");
                    if($this->amonestacion->aestado_id != $cancelada->id && $this->amonestacion->aestado_id != $estado->id){
                        $this->option = 'error';
                        $this->error = ' El estado de la amonestación no permite la eliminación.';
                    }else{
                        $this->option = 'captura';
                    }
                }else{
                    $this->option = 'error';
                    $this->error = ' La amonestación no existe.';
                }
            }else if($this->post('id') != ''){
                $this->option = '';
                $this->error = '';
                $amonestacion = new Amonestacion();
                $amonestacion = $amonestacion->find($this->post('id'));
                if($amonestacion->id != ''){
                $estado = new Aestado();
                $estado = $estado->pornombre("No aprobada");

                $cancelada = new Aestado();
                $cancelada = $cancelada->pornombre("Cancelada");

                if($amonestacion->aestado_id == $cancelada->id || $amonestacion->aestado_id == $estado->id){
                    try{
                        unlink('./public/img/amonestaciones/'.$amonestacion->imagen);
                        unlink('./public/img/amonestaciones/th_'.$amonestacion->imagen);
                        $this->option = 'exito';
                        
                        $amonestados = new Amonestados();
                        $amonestados = $amonestados->find('amonestacion_id = '.$amonestacion->id);
                        
                        $amonestacion->delete();

                        $historial=new Historial();
                        $historial->ciclos_id= Session :: get_data('ciclo.id');
                        $historial->usuario=Session :: get_data('usr.login');
                        if(count($amonestados) > 1){
                            $grupo = $amonestados[0]->grupo();
                            $historial->descripcion='Elimino la amonestacion para el grupo '.$grupo->grado.'°'.$grupo->letra.' '.$grupo->turno.' '.$grupo->verOferta(). ' con la fecha '.$amonestacion->fecha;
                        }else{
                            $alumno = new Alumnos();
                            $alumno = $alumno->find($amonestados[0]->alumnos_id);
                            $historial->descripcion='Elimino la amonestacion para el alumno '.$alumno->codigo. ' con la fecha '.$amonestacion->fecha;    
                        }
                        $historial->controlador="amonestaciones";
                        $historial->accion="eliminar";
                        $historial->saved_at=date("Y-m-d H:i:s");
                        $historial->save();

                    }catch(dbException $e){
                        $this->option = 'error';
                        $this->error .= ' Error al intentar eliminar de la BD. ' .
                                    'Posiblemente existan datos vinculados a la amonestación.';
                    }
                }else{
                    $this->option = 'error';
                    $this->error = ' El estado de la amonestación no permite la eliminación.';

                }

                }else{
                    $this->option = 'error';
                    $this->error = ' La amonestación no existe.';
                }
            }else{
                $this->option = 'error';
                $this->error = ' No se especific&oacute; la amonestación a eliminar.';
            }
        }else{
            $this->option = "error";
            $this->error = ' El ciclo esta cerrado.';
        }

    }

    public function exportar($grp_id = '') {
        $this->set_response("view");
        require ('app/reportes/xls.amonestaciones.php');
        $ciclo_id = Session :: get_data('ciclo.id');
        $reporte = new XLSAmonestaciones($ciclo_id);
        $reporte->generar();
    }

    public function index($pag = ''){
        $ciclo_id = Session :: get_data('ciclo.id');
        $this->ciclo_id = $ciclo_id;
        $controlador = $this->controlador;
        $accion = $this->accion;
        
        
        $this->ofertas = new Oferta();
        $this->ofertas = $this->ofertas->find();
        
        $this->categoria = new Acategoria();
        $this->reglamento = new Reglamentos();
        $this->articulo = new Articulo();
        $this->categorias = new Acategoria();
        $this->categorias = $this->categorias->find();
        $b = new Busqueda($controlador, $accion);
        $b->establecerCondicion('fecha', "fecha = '" . Utils::convierteFechaMySql($b->campo('fecha')) . "' ");
        $b->establecerCondicion('aestado_id', "aestado_id = '" . $b->campo('aestado_id') . "' ");
        $b->establecerCondicion('acategoria_id', "acategoria_id = '" . $b->campo('acategoria_id') . "' ");
        $b->establecerCondicion('nombre', "CONCAT(TRIM(alumnos.nombre), ' ', TRIM(ap), ' ', TRIM(am)) LIKE '%" . $b->campo('nombre') . "%' OR
                                            CONCAT(TRIM(ap), ' ', TRIM(am), ' ', TRIM(alumnos.nombre)) LIKE '%" . $b->campo('nombre') . "%' ");
        
        $b->establecerCondicion('oferta_id', "grupos.oferta_id  = '" . $b->campo('oferta_id') . "' ");
        $b->campos();
        // genera las condiciones
        $c = $b->condicion();
        $amonestaciones = new Amonestados();
        $amonestados = new Amonestados();
        
        /*$this->registros=$amonestaciones->count_by_sql(
        "SELECT count(amonestacion.id) FROM
                                amonestacion
                                INNER JOIN alumnos ON amonestacion.alumnos_id = alumnos.id
                                WHERE ".
        ($c == "" ? "ciclos_id='".$ciclo_id."'" : "ciclos_id='".$ciclo_id."' AND ".$c));
        */
        
        $this->registros=$amonestados->count_by_sql(
        "SELECT count(amonestados.id) FROM
                                amonestados
                                INNER JOIN alumnos ON amonestados.alumnos_id = alumnos.id 
                                INNER JOIN amonestacion on amonestados.amonestacion_id = amonestacion.id
                                INNER JOIN alumnosgrupo ON alumnos.id = alumnosgrupo.alumnos_id
                                INNER JOIN grupos ON alumnosgrupo.grupos_id = grupos.id
                                WHERE ".
        ($c == "" ? "amonestacion.ciclos_id='".$ciclo_id."' AND grupos.ciclos_id='".$ciclo_id."'" : "amonestacion.ciclos_id='".$ciclo_id."' AND grupos.ciclos_id='".$ciclo_id."' AND ".$c));
        // paginacion
        $paginador = new Paginador($controlador, $accion);
        if($pag != ''){
            $paginador->guardarPagina($pag);
        }
        $paginador->estableceRegistros($this->registros);
        $paginador->generar();


        $this->busqueda = $b;

        $this->amonestaciones=$amonestaciones->find_all_by_sql(
                            "SELECT amonestados.* FROM
                                amonestados
                                INNER JOIN alumnos ON amonestados.alumnos_id = alumnos.id
                                INNER JOIN amonestacion ON amonestados.amonestacion_id = amonestacion.id
                                INNER JOIN alumnosgrupo ON alumnos.id = alumnosgrupo.alumnos_id
                                INNER JOIN grupos ON alumnosgrupo.grupos_id = grupos.id
                                WHERE ".
        ($c == "" ? "amonestacion.ciclos_id='".$ciclo_id."' AND grupos.ciclos_id='".$ciclo_id."'" : "amonestacion.ciclos_id='".$ciclo_id."' AND grupos.ciclos_id='".$ciclo_id."' AND ".$c)." ORDER BY fecha DESC ".
                            'limit ' . ($paginador->pagina() * $paginador->rpp()) . ', '
                            . $paginador->rpp()
                            );

                            $this->paginador = $paginador;
                            $this->option="vista";

                            $estados = new Aestado();
                            $this->estados = $estados->find();
                            $Ciclos = new Ciclos();
                            $this->ciclo = $Ciclos->find($ciclo_id);
                            $Ciclos = $Ciclos->find("columns: id, numero", "conditions: abierto = '1' ", "order: numero DESC");
                            $this->ciclos = $Ciclos;
                            // acl
                            $usr_login = Session :: get_data('usr.login');
                            $this->acl = array ();
                            $acl = new gacl_extra();
                            $acos_arr = array (
                                'amonestaciones' => array (
                                    'agregar',
                                    'aprobar',
                                    'cancelar',
                                    'editar',
                                    'eliminar',
                                    'exportar',
                                    'index',
                                    'ficha',
                                    'ver'
                                    ),
                                'reglamentos' => array(
                                    'index'
                                    )
                                    );
                            $this->acl = $acl->acl_check_multiple($acos_arr, $usr_login);


    }

    public function ficha(){
        $this->set_response("view");
        if(trim($this->post("codigo"))!=''){
            $alumno = new Alumnos();
            $alumno = $alumno->find_first("codigo='".trim($this->post("codigo"))."'");
            if($alumno->id!=""){
                $this->alumno = $alumno;
                $this->option = "ficha";
            }else{
                $this->option = "alert";
                $this->error = "Los datos no son validos";
            }
        }else{
            $this->option = "error";
            $this->error = "Los datos no son validos";
        }
    }

    public function ver($id = '',$id_alumno){
        if($id!=''){
            $amonestacion = new Amonestacion();
            $amonestacion = $amonestacion->find($id);
            if($amonestacion->id!=""){
                $this->option = "ver";
                $infracciones = new Reglamento();
                $this->infracciones = $infracciones->find_all_by_sql('SELECT reglamento.* FROM '.
                'amonestacion INNER JOIN infringe ON amonestacion.id = infringe.amonestacion_id '.
                'INNER JOIN reglamento on infringe.reglamento_id = reglamento.id '.
                'WHERE amonestacion.id = '.$id);
                $alumno = new Alumnos();
                $this->alumno = $alumno->find($id_alumno);
                $this->reglamento = new Reglamentos();
                $this->articulo = new Articulo();
                $categoria = new Acategoria();
                $this->categoria = $categoria->find($amonestacion->acategoria_id);
                $this->amonestacion = $amonestacion;
            }else{
                $this->option = "error";
                $this->error = "Los datos no son validos";
            }
        }else{
            $this->option = "error";
            $this->error = "Los datos no son validos";
        }
    }
    
    public function obtiene_articulos(){
        $this->set_response('view');
        //var_dump($this->post('reglamento'));exit;
        $inner = 'SELECT articulo.* FROM reglamento '.
                 'INNER JOIN articulo ON reglamento.articulo_id = articulo.id '.
                 'WHERE reglamento.reglamentos_id = '.$this->post('reglamento');
        $articulos = new Articulo();
        $articulos = $articulos->find_all_by_sql($inner);
        $respuesta = '';
        foreach($articulos as $articulo){
            $respuesta .= $articulo->id.'/'.$articulo->numero.'|'; 
        }
        $this->respuesta = substr($respuesta,0,-1);
    }
    
    public function obtiene_alumnos(){
        $this->set_response('view');
        $grupo = new Grupos();
        $grupo = $grupo->find($this->post('grupo'));
        $this->alumnos = $grupo->alumnos();
    }
    
    public function cuenta_alumnos(){
        $this->set_response('view');
        $grupo = new Grupos();
        $grupo = $grupo->find($this->post('grupo'));
        $this->numero = count($grupo->alumnos());
    }
}
?>