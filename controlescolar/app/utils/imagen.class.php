<?php
kumbia::import('app.utils.*');
class Imagen{
    private $source;
    private $image;
    public function Imagen($sr){
        $this->source=$sr;
        $ext=strtolower(substr($sr,strripos($sr, ".")+1));            
        
        switch($ext){
            case "jpg":
                $this->image =@imagecreatefromjpeg($this->source);
                break;
            case "jpeg":
                $this->image = @imagecreatefromjpeg($this->source);
                break;    
            case "gif":
                $this->image = @imagecreatefromgif($this->source);
                break;    
            case "png":
                $this->image = @imagecreatefrompng($this->source);
                break;    
        
        } 
    }

    public function getHeight(){
        return ImageSY($this->image);
    }

    public function getWidth(){
        return ImageSX($this->image);
    }
    
    public function ThumbnailAjustaAncho($w_deseado,$dest){
       $util=new Utileria();
        $escalador=new Escalador($w_deseado,0,$this);
        $escala=$escalador->EscalarAncho();    
        $h=$util->porcentaje($this->getHeight(),$escala);
        $w=$util->porcentaje($this->getWidth(),$escala);;    
        return $this->Thumbnail($h,$w,$dest);
    }
    

    public function ThumbnailAjustaAlto($h_deseado,$dest){
        $util=new Utileria();
        $escalador=new Escalador(0,$h_deseado,$this);
        $escala=$escalador->EscalarAlto();    
        $h=$util->porcentaje($this->getHeight(),$escala);
        $w=$util->porcentaje($this->getWidth(),$escala);    
        return $this->Thumbnail($h,$w,$dest);
    }
    
    public function Thumbnail($h,$w,$dest){
        $gd_d = imagecreatetruecolor($w, $h); // crea el recurso gd para la salida
        // desactivo el procesamiento automatico de alpha
        imagealphablending($gd_d, false);
        // hago que el alpha original se grabe en el archivo destino
        imagesavealpha($gd_d, true);
        imagecopyresampled($gd_d, $this->image, 0, 0, 0, 0, $w, $h, $this->getWidth(), $this->getHeight()); // redimensiona
        imagejpeg($gd_d, $dest.".jpg"); // graba
        // Se liberan recursos
        imagedestroy($gd_d);
        return $dest.".jpg";
    }

    public function destroy(){
        imagedestroy($this->image);
    }
}
?>