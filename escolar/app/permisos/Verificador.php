<?php
class Verificador{
    public $elementos;
    public $contenidos;
    function Verificador(){
        $this->getElements();
    }

    private function getElements(){
    $consulta="SELECT wp5modulo.mod_controlador,wp5contenido.con_vista,wp5contenido.con_nombre,wp5modulo.mod_status,wp5contenido.con_status  FROM wp5modulo,wp5contenido ";
    $consulta.=" WHERE wp5modulo.Id=wp5contenido.modulo_id";
    $consulta.=" order by wp5modulo.Id,wp5contenido.Id";

    $db = new db("localhost", "hekademos", "hekademos", "hekademos");
   $this->elementos=$db->in_query($consulta);
   $this->contenidos=array();
   foreach($this->elementos as $contenido){
   $this->contenidos[$contenido['mod_controlador']][$contenido['con_vista']]=array(0=>$contenido['mod_status'],1=>$contenido['con_status']);
    }

    $this->contenidos['sesion']['restringir']=array(0=>"1",1=>"1");
    $this->contenidos['contacto']['guardar']=array(0=>"1",1=>"1");
    $this->contenidos['sesion']['autenticar']=array(0=>"1",1=>"1");
    $this->contenidos['sesion']['abrir']=array(0=>"1",1=>"1");
    $this->contenidos['sesion']['cerrar']=array(0=>"1",1=>"1");
    $this->contenidos['admin']['index']=array(0=>"1",1=>"1");
    $this->contenidos['modulo']['cambiaStatus']=array(0=>"1",1=>"1");

    $this->contenidos['nuestraprepa']['acerca']=array(0=>"1",1=>"1");
    $this->contenidos['nuestraprepa']['blog_comentarios']=$this->contenidos['nuestraprepa']['blog'];
    $this->contenidos['nuestraprepa']['agregar_comentarios']=$this->contenidos['nuestraprepa']['blog'];
     $db->close();
    }

    public function contenidoPublicado($controlador,$accion){
        $controlador=strtolower($controlador);
        $accion=strtolower($accion);
        $contenido=$this->contenidos[$controlador][$accion];
        if( ($contenido[0]=="1" && $contenido[1]=="1") || $this->contenidoAdmin($controlador) || $this->Escolar($controlador))return 1;
        else return 0;

    }

    private function Escolar($controlador){
            switch ($controlador) {
        case "escolar" :
            return 1;
        default :
            return 0;
            }
    }

    private function contenidoAdmin($controlador){
    switch ($controlador) {
        case "modulo" :
            return 1;
        case "contenido" :
            return 1;
        case "archivo" :
            return 1;
        case "categoriasdescargas" :
            return 1;
        case "categoriasmultimedia" :
            return 1;
        case "descargas" :
            return 1;
        case "mmf" :
            return 1;
        case "multimedia" :
            return 1;
        case "sugerencias" :
            return 1;
        case "texto" :
            return 1;
        case "blog" :
            return 1;
        case "post" :
            return 1;
        case "controlescolar" :
            return 1;
        default :
            return 0;
    }
    }


}

?>
