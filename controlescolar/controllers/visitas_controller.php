<?php
Kumbia :: import('app.componentes.*');
Kumbia :: import('lib.kumbia.utils');
Kumbia :: import('lib.upload.main');
Kumbia :: import('lib.excel.main');


class VisitasController extends ApplicationController {
    public $persistance = false;
    public $template = "system";

    function index($pag = ''){
        $path = $this->path = KUMBIA_PATH;
        $visitas = new Visitas();
        // busqueda
        $b = new Busqueda($this->controlador, $this->accion);
        $b->establecerCondicion('fecha', "visitas.fecha  LIKE '" . Utils::convierteFechaMySql(trim($b->campo('fecha'))) . "%' ");


        $c = $b->condicion();
        $this->busqueda = $b;

        
        // cuenta todos los registros
        $this->registros = $visitas->count_by_sql("SELECT " .
        "COUNT(*) " .
        "FROM " .
        "visitas " .
        " INNER JOIN usuarios ON visitas.usuarios_id = usuarios.id ".
        "WHERE " .
        ($c==""? '1' : $c)
        );

        // paginacion
        $paginador = new Paginador($this->controlador, $this->accion);
        if ($pag != '') {
            $paginador->guardarPagina($pag);
        }
        $paginador->estableceRegistros($this->registros);
        $paginador->generar();
        $this->paginador = $paginador;

        // ejecuta la consulta
        $this->visitas = $visitas->find_all_by_sql("SELECT " .
        "visitas.*,usuarios.login, CONCAT(ap,' ',am,' ',nombre) AS nombre " .
        "FROM " .
        "visitas " .
        " INNER JOIN usuarios ON visitas.usuarios_id = usuarios.id ".
        "WHERE " .
        ($c==""? '1' : $c). " ".
        "ORDER BY " .
        "fecha DESC " .
        "LIMIT " .
        ($paginador->pagina() * $paginador->rpp()) . ', ' .
        $paginador->rpp() . " ");
        $usuarios = new Usuarios();
        $this->usuarios = $usuarios->find();

    }

    function informacion($pag = ''){
        $path = $this->path = KUMBIA_PATH;
        $visitas = new Visitas();
        // busqueda
        $b = new Busqueda($this->controlador, $this->accion);
        $b->establecerCondicion('fecha', "visitas.fecha  LIKE '" . Utils::convierteFechaMySql(trim($b->campo('fecha'))) . "%' ");


        $c = $b->condicion();
        $this->busqueda = $b;

        
        // cuenta todos los registros
        $this->registros = $visitas->find_all_by_sql(
        "SELECT usuarios_id,count(visitas.id) as total,max(fecha) as ultima,usuarios.login, CONCAT(ap,' ',am,' ',nombre) AS nombre  FROM visitas
        INNER JOIN usuarios ON visitas.usuarios_id = usuarios.id
        WHERE tipo='e' AND ".
        ($c==""? '1' : $c). " ".
        "GROUP BY usuarios_id ".
        "ORDER BY " .
        "fecha " );
        $this->registros = count($this->registros);

        // paginacion
        $paginador = new Paginador($this->controlador, $this->accion);
        if ($pag != '') {
            $paginador->guardarPagina($pag);
        }
        $paginador->estableceRegistros($this->registros);
        $paginador->generar();
        $this->paginador = $paginador;

        // ejecuta la consulta
        $this->visitas = $visitas->find_all_by_sql(
        "SELECT usuarios_id,count(visitas.id) as total,max(fecha) as ultima,usuarios.login, CONCAT(ap,' ',am,' ',nombre) AS nombre  FROM visitas
        INNER JOIN usuarios ON visitas.usuarios_id = usuarios.id
        WHERE tipo='e' AND ".
        ($c==""? '1' : $c). " ".
        "GROUP BY usuarios_id ".
        "ORDER BY " .
        "ultima DESC " .
        "LIMIT " .
        ($paginador->pagina() * $paginador->rpp()) . ', ' .
        $paginador->rpp() . " ");
        
        $visitas = new Visitas();
        $visitas = $visitas->count_by_sql("SELECT count(*) FROM visitas
                    INNER JOIN usuarios ON visitas.usuarios_id = usuarios.id 
                    WHERE 
                    tipo='e' AND ".
                    ($c==""? '1' : $c)
        );
        $this->totalvisitas = $visitas;
        $usuarios = new Usuarios();
        $this->usuarios = $usuarios->find();

    }
    
}
?>
