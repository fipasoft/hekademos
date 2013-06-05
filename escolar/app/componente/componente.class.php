<?php
class Componente{
    private $nombre;    
    private $titulo;
    private $tabla;
    private $id_componente;
    public $contenido_padre;
    public $modulo;
        
    public function Componente(){
    $this->getArbol();
    }
    
    public function setName($value){
        $this->nombre=$value;
    }
   

    public function getName(){
        return $this->nombre;
    }       

   
    public function getTitle(){
        return $this->titulo;
    }
    
    public function setTitle($value){
        $this->titulo=$value;
    }

    public function getTable(){
        return $this->tabla;
    }
    
    public function setTable($value){
        $this->tabla=$value;
    }
    
    public function getId(){
        return $this->id_componente;
    }
    
    public function setId($value){
        $this->id_componente=$value;
    }
    
   private function getArbol(){
   
   $componente= new wp5componente();
   $componente=$componente->find_first("Id=".$this->id_componente); 
   
    $this->contenido_padre= new wp5contenido();
   $this->contenido_padre=$this->contenido_padre->find_first("Id=".$componente->contenido_id);   
   
   $this->modulo= new wp5modulo();
   $this->modulo=$this->modulo->find_first("Id=".$this->contenido_padre->modulo_id);
       
   } 
}

?>