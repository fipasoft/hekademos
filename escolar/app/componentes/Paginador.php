<?php

class Paginador{
public $elements;
    public $cant_actual;
    public $numReg;
    public $num_rows;
    public $elemento_indice;
    public $controlador;
    public $vista;
    public $id_elemento;

function Paginador(){
}

public function setDatos($elemento_indice,$controlador,$vista,$id="default"){
$this->elemento_indice=$elemento_indice;
$this->controlador=$controlador;
$this->vista=$vista;
$this->id_elemento=$id;
}

public function datosPaginacion($elemento_indice,$num_rows,$numReg,$link){
$c=$num_rows-$elemento_indice;

if(($c)<$numReg)
$this->cant_actual=$c;
else
$this->cant_actual=$numReg;

$this->elemento_indice=$elemento_indice;
$this->num_rows=$num_rows;
$this->numReg=$numReg;
$this->link=$link;
}

function getCode(){
if($this->num_rows>0 ){
if($this->elemento_indice==0)
$code='';
else
$code=link_to($this->link," <<","title: Ir a la primera pagina");
        if($this->num_rows/$this->numReg<10){
            $j = 0;
            $l = 1;
            for($nK=0; $nK<($this->num_rows/$this->numReg); $nK++){
                if(($nK*$this->numReg)!=$this->elemento_indice){
                    $code.= " ".link_to($this->link.(($nK)*$this->numReg)," ".$l,"title: Ir a la pagina ".$l);
                }else
                    $code.= '<span class="selected"><b> '.$l.'</b></span>';
                $l++;
            }
          }else{
                  $cc=5*$this->numReg;
                if($this->elemento_indice>=$cc){
                        $nKi = ($this->elemento_indice-$cc)/$this->numReg;
                        if($this->elemento_indice+$cc<$this->num_rows)
                            $nKf = ($this->elemento_indice+$cc)/$this->numReg;
                        else
                            $nKf = $this->num_rows/$this->numReg;
                    }else{
                        $nKi = 0;
                        $nKf = 10;
                    }
                //$nKf=$this->num_rows/$this->numReg;
                for($nKi; $nKi<$nKf; $nKi++){
                    if(($nKi*$this->numReg)!=$this->elemento_indice){
                        $code.= " ".link_to($this->link.floor((($nKi)*$this->numReg))," ".floor(($nKi+1)),"title: Ir a la pagina ".floor(($nKi+1)));
                    }else
                        $code.= '<span class="selected"><b> '.floor(($nKi+1)).'</b></span>';

                }
          }
          $total=$this->num_rows/$this->numReg;
          $total=floor($total);
          $total=$total*$this->numReg;

          if($this->num_rows%$this->numReg==0)$total=$total-$this->numReg;

        if($this->elemento_indice!=$total)
          $code.=link_to($this->link.$total," >>","title: Ir a la ultima pagina");
          if($this->cant_actual>0){
          $code.= ' (';
          if($this->num_rows <= $this->numReg){
            $code.= 'Mostrando '.$this->num_rows.' elemento';
            ($this->num_rows==1?$t='': $t='s');
            $code.= $t;
          }else{
            $code.=  'Mostrando del '.($this->elemento_indice+1).' al ';
            $code.= ($this->elemento_indice+$this->cant_actual);

          }
          $code.= ')';
          }else{
              $code.="(No hay elementos para mostrar en la pagina actual.)";
          }
}else{
    $code="";
}
          return $code;
}

public function guarda(){
 if (Session :: isset_data('paginadores')){
    $paginadores = Session :: get_data('paginadores');

}else{
    $paginadores=array();
}

    $paginadores[$this->controlador][$this->vista][$this->id_elemento]=$this->elemento_indice;
    Session :: set_data('paginadores', $paginadores);
    setcookie("paginador",$paginadores[$this->controlador][$this->vista][$this->id_elemento],(time()+3600), '/');

}

public function obten(){
 if (Session :: isset_data('paginadores')){
    $paginadores = Session :: get_data('paginadores');
    if(isset($paginadores[$this->controlador][$this->vista][$this->id_elemento]))
        return $paginadores[$this->controlador][$this->vista][$this->id_elemento];
    else
        return 0;
}else{
    return 0;
}


}

public function cargaMemoria(){
$c = Session :: get_data('controlador_anterior');
$v = Session :: get_data('vista_anterior');
$id=Session :: get_data('id_anterior');
if($c==$this->controlador && $v==$this->vista && $id==$this->id_elemento){
$this->guarda();
$this->elemento_indice=$this->obten();
setcookie("paginador1",$this->controlador."/".$this->vista." igual ".$this->elemento_indice,(time()+3600), '/');
}else{
$this->elemento_indice=$this->obten();
setcookie("paginador1",$this->controlador."/".$this->vista." NO igual ".$this->elemento_indice,(time()+3600), '/');
$this->guarda();
}


}

}

?>