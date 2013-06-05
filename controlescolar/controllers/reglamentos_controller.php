<?php

Kumbia :: import('app.componentes.*');

class ReglamentosController extends ApplicationController {
    public $template = "system";
    
    public function index($pag = ''){
        $reglamentos = new Reglamentos();
        $controlador = $this->controlador;
        $accion = $this->accion;
        $path = $this->path = KUMBIA_PATH;
        
        
        // busqueda
        $b = new Busqueda($controlador, $accion);

        // genera las condiciones
        $b->establecerCondicion(
            'nombre',
            "nombre LIKE '%" . $b->campo('nombre') . "%' "
        );
        
        $b->establecerCondicion(
            'descripcion',
            "descripcion LIKE '%" . $b->campo('descripcion') . "%' "
        );

        $c = $b->condicion();
        $this->busqueda = $b;

        // cuenta todos los registros
        $this->registros = $reglamentos->count(($c == '' ? '' : $c));

        // paginacion
        $paginador = new Paginador($controlador, $accion);
        if($pag != ''){
            $paginador->guardarPagina($pag);
        }
        $paginador->estableceRegistros($this->registros);
        $paginador->generar();
        $this->paginador = $paginador;

        // ejecuta la consulta
        $this->reglamentos = $reglamentos->find(
                            'conditions: ' . ($c == "" ? "1" : $c),
                            'order: nombre ',
                            'limit: ' . ($paginador->pagina() * $paginador->rpp()) . ', '
                                      . $paginador->rpp()
                          );
        // acl
        $usr_login = Session :: get_data('usr.login');
        $this->acl = array ();
        $this->acl_art = array();
        $acl = new gacl_extra();
        $acos_arr = array (
            'reglamentos' => array(
                'index',
                'agregar',
                'buscar',
                'editar',
                'eliminar',
                'ver',
                'importar'
            )
        );
        $acos_arr_art = array (
            'articulos' => array(
                'agregar'
            )
        );
        $this->acl = $acl->acl_check_multiple($acos_arr, $usr_login);
        $this->acl=$this->acl["reglamentos"];
        $this->acl_art = $acl->acl_check_multiple($acos_arr_art, $usr_login);
        $this->acl_art=$this->acl_art["articulos"];
    }
    
    
    public function agregar(){
        try{
        if($this->post('nombre') == ''){
            $this->option = 'captura';
        }else{
            $reglamento = new Reglamentos();
            $reglamento->nombre = $this->post('nombre');
            $reglamento->descripcion = $this->post('descripcion');
            if($this->post('inicio') != '')
                $reglamento->inicio = Utils :: convierteFechaMySql($this->post('inicio'));
            if($this->post('fin') != '')
                $reglamento->fin = Utils :: convierteFechaMySql($this->post('fin'));    
            if($reglamento->save()){
                $historial=new Historial();
                $historial->ciclos_id= Session :: get_data('ciclo.id');
                $historial->usuario=Session :: get_data('usr.login');
                $historial->descripcion='Agrego el reglamento '.$reglamento->nombre;
                $historial->controlador="reglamentos";
                $historial->accion="agregar";
                $historial->saved_at=date("Y-m-d H:i:s");
                $historial->save();
                $this->option = 'exito';
            }else{
                throw Exception('Error al agregar el reglamento.');
            }
        }
        }catch(Exception $e){
            $this->option = "error";
            $this->error = $e->getMessage();
        }                
    }
    
    public function editar($id = ''){
        try{
        $reglamento = new Reglamentos();
        if($id != ''){
            $this->option = 'captura';
            $id = intval($id, 10);
            $this->reglamento = $reglamento->find($id);
            if($this->reglamento->id == ''){
                $this->option = 'error';
                $this->error = ' El c&oacute;digo de reglamento no existe.';
            }
        }else if($this->post('id') != ''){
            mysql_query("BEGIN") or die("REG_EDI_1");
            $reglamento = $reglamento->find($this->post('id'));
            $reglamento->nombre = $this->post('nombre');
            $reglamento->descripcion = $this->post('descripcion');
            $reglamento->inicio = Utils :: convierteFechaMySql($this->post('inicio'));
            $reglamento->fin = Utils :: convierteFechaMySql($this->post('fin'));
            if($reglamento->save()){
                $historial=new Historial();
                $historial->ciclos_id= Session :: get_data('ciclo.id');
                $historial->usuario=Session :: get_data('usr.login');
                $historial->descripcion='Edito el reglamento '.$reglamento->nombre;
                $historial->controlador="reglamentos";
                $historial->accion="editar";
                $historial->saved_at=date("Y-m-d H:i:s");
                $historial->save();
                $this->option = 'exito';
                mysql_query("COMMIT") or die("REG_EDI_1");
            }else{
                mysql_query("ROLLBACK") or die("REG_EDI_1");
                throw Exception('Error al editar el reglamento.');
            }     
        }
        }catch(Exception $e){
            $this->option = "error";
            $this->error = $e->getMessage();
        }
    }
    
    public function eliminar($id = ''){
        try{
        $reglamento = new Reglamentos();
        if($id != ''){
            $this->option = 'captura';
            $id = intval($id,10);
            $this->reglamento = $reglamento->find($id);
            if($this->reglamento->id == ''){
                $this->option = 'error';
                $this->error = ' El c&oacute;digo de reglamento no existe.';
            }
        }else if($this->post('id') != ''){
            mysql_query("BEGIN") or die("AMO_AGR_1");
            $rel_art = new Reglamento();
            $rel_art = $rel_art->find('reglamentos_id = '. $this->post('id'));
            foreach($rel_art as $r_a){    
                $articulo = new Articulo();
                $articulo = $articulo->find($r_a->articulo_id);
                if(!$articulo->delete()){            //Borra los articulos ligados al reglamento
                    mysql_query("ROLLBACK") or die("AMO_AGR_1");
                    throw Exception('Error al borrar articulos.');
                }        
                if(!$r_a->delete()){        //Borra las ligaduras entre reglamentos y articulos
                    mysql_query("ROLLBACK") or die("AMO_AGR_1");
                    throw Exception('Error al borrar articulos.');
                }
            }
            $reglamento = $reglamento->find_first($this->post('id'));
            $nombre = $reglamento->nombre;
            if($reglamento->delete($this->post('id'))){        //Borra el reglamento
                $historial=new Historial();
                $historial->ciclos_id= Session :: get_data('ciclo.id');
                $historial->usuario=Session :: get_data('usr.login');
                $historial->descripcion='Elimino el reglamento '.$nombre;
                $historial->controlador="reglamentos";
                $historial->accion="eliminar";
                $historial->saved_at=date("Y-m-d H:i:s");
                $historial->save();
                $this->option = 'exito';
                mysql_query("COMMIT") or die("AMO_AGR_1");
            }else{
                mysql_query("ROLLBACK") or die("AMO_AGR_1");
                throw Exception('Error al borrar reglamento.');
            }
        }
        }catch(Exception $e){
            $this->option = "error";
            $this->error = $e->getMessage();
        }
    }
    
    public function ver($id = '', $pag = ''){
        $id = intval($id,10);
        $reg = new Reglamentos();
        //$reg_art = new Reglamento();
        $this->reg = $reg->find($id);
        //$this->reg_art = $reg_art->find('reglamentos_id = '. $id);
        $articulos = new Articulo();
        $controlador = $this->controlador;
        $accion = $this->accion;
        $path = $this->path = KUMBIA_PATH;
        
        
        // busqueda
        $b = new Busqueda($controlador, $accion);
        // genera las condiciones
        $b->establecerCondicion(
            'articulo',
            "articulo.numero = '" . $b->campo('articulo') . "' "
        );
        
        $b->establecerCondicion(
            'descripcion',
            "articulo.descripcion LIKE '%" . $b->campo('descripcion') . "%' "
        );
        
        $c = $b->condicion();
        $inner = 'SELECT articulo.* FROM reglamentos '.
                 'INNER JOIN reglamento ON reglamentos.id = reglamento.reglamentos_id '.
                 'INNER JOIN articulo on reglamento.articulo_id = articulo.id WHERE reglamentos.id = '. $id;
        $inner .= ($c != '' ? ' AND '.$c : '');
        $innerc = 'SELECT count(*) FROM reglamentos '.
                 'INNER JOIN reglamento ON reglamentos.id = reglamento.reglamentos_id '.
                 'INNER JOIN articulo on reglamento.articulo_id = articulo.id WHERE reglamentos.id = '. $id;
        $innerc .= ($c != '' ? ' AND '.$c : '');
        $this->busqueda = $b;
        // cuenta todos los registros

        $registros = $articulos->count_by_sql($innerc);
        //var_dump($registros);exit;
        
        // paginacion
        $paginador = new Paginador($controlador, $accion);
        if($pag != ''){
            $paginador->guardarPagina($pag);
        }
        //var_dump('jg');exit;
        $paginador->estableceRegistros($registros);
        $paginador->establecePath($controlador . '/' . $accion . '/' . $id );
        $paginador->generar();
        $this->paginador = $paginador;

        // ejecuta la consulta
        $this->articulos = $articulos->find_all_by_sql(
                            $inner.
                            ' order by articulo.numero'.
                            ' limit ' . ($paginador->pagina() * $paginador->rpp()) . ', '
                                      . $paginador->rpp()
                          );
        $usr_login = Session :: get_data('usr.login');
        $this->acl = array ();
        $acl = new gacl_extra();
        $acos_arr = array (
            'articulos' => array (
                'agregar',
                'editar',
                'eliminar',
                'revisar_numero',
                'buscar',
                'importar'
            )
        );
        $this->acl = $acl->acl_check_multiple($acos_arr, $usr_login);
        $this->acl=$this->acl["articulos"];
    }
    
    public function revisa_reglamento(){
        $this->set_response('view');
        $reglamentos = new Reglamentos();
        
        $inner = 'Select count(*) from reglamentos 
                  where nombre = "'.$this->post('reglamento').'"'.($this->post('anterior') != '' ? ' AND id != '.$this->post('anterior') : '' );    
        
        if($reglamentos->count_by_sql($inner) > 0){
            $this->disponible = false;            
        }else{
            $this->disponible = true;
        }
    }
    
    
}


?>