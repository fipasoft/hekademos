<?php
 class Utileria{
     public function upload_file($name, $dir,$file_name){
        $nom=$_FILES[$name]['tmp_name'];         
         if(strlen($nom)>0){
               move_uploaded_file($_FILES[$name]['tmp_name'],htmlspecialchars($dir."/".$file_name));
               return true;         
             } else return false;
     }

    public function esImagen($file){
    $ext=strtolower(substr($file,strripos($file, ".")+1));
    if($ext=="jpg" || $ext=="jpeg" || $ext=="gif" || $ext=="png")return 1;
    
    return 0;
    }
    
    public function esVideo($file){
    $ext=strtolower(substr($file,strripos($file, ".")+1));
    if($ext=="flv" || $ext=="swf")return 1;
    
    return 0;
    }
    
    public function porcentaje($cant,$porc){
    $val=$cant*($porc/100);
    
    return $val;
    }
    
    public function now(){
        $sGMTMySqlString = gmdate("Y-m-d H:i:s", time());
        return $sGMTMySqlString;
    }
     
 }
?>