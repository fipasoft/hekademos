<?php
class menuPrincipal{
    public $elementos;
    function menuPrincipal(){
        $this->getMenu();
    }

    private function getMenu(){
    $consulta="SELECT wp5modulo.mod_titulo,wp5modulo.mod_controlador,wp5contenido.con_vista,wp5contenido.con_nombre,wp5contenido.con_titulo,wp5contenido.externo FROM wp5modulo,wp5contenido ";
    $consulta.=" WHERE wp5modulo.Id=wp5contenido.modulo_id AND wp5modulo.mod_status=1 AND wp5contenido.con_status=1 ";
    $consulta.=" order by wp5modulo.Id,wp5contenido.Id";

    $db = db::raw_connect();
       $this->elementos=$db->in_query($consulta);
       $db->close();
    }


}

?>