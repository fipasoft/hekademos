<?php
class Constantes{
private $portal;
private $imagenes_dir;

public function Constantes(){
$this->portal="http://148.202.65.105/";
$this->imagenes_dir="public/files/";
}

public function getPortal(){
return $this->portal;
}

public function getImagenesDir(){
return $this->imagenes_dir;
}

}

?>