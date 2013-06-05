<?php
class Librerias{
    function tinyMCE($controlador,$vista){
        $vistas=array();
        $vistas["nuestraprepa"]["blog_comentarios"]="1";

        $vistas["texto"]["editar"]="1";

        $vistas["post"]["nuevo"]="1";
        $vistas["post"]["editar"]="1";

        $vistas["mmf"]["nuevo"]="1";
        $vistas["mmf"]["editar"]="1";

        $vistas["archivo"]["nuevo"]="1";
        $vistas["archivo"]["editar"]="1";

        if($vistas[$controlador][$vista]!=null)
            return javascript_include_tag("tiny_mce/tiny_mce");
            else return "";
    }

    function flashembed($controlador,$vista){
        $vistas=array();
        $vistas["director"]["video"]="1";

        if($vistas[$controlador][$vista]!=null)
            return javascript_include_tag("flowplayer/flashembed.min");
            else return "";

    }

}

?>