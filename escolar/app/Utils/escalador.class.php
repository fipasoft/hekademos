<?php
kumbia::import('app.Utils.*');
class Escalador {
private $WIDTH;
private $HEIGHT;
private $imagen;
private $error;
    public function Escalador($w,$h,$img){
            $this->error=false;
            $this->WIDTH=$w;
            $this->HEIGHT=$h;
            $this->imagen= $img;

   }


    public function Escalar(){
        $wImg=$this->imagen->getWidth();
        $hImg=$this->imagen->getHeight();
        if($wImg>$this->WIDTH || $hImg>$this->HEIGHT){
            $difW=$wImg-$this->WIDTH;
            $difH=$hImg-$this->HEIGHT;

            if($difW>=$difH){
                $val=$wImg/$difW;
                $esca=100/$val;
                $valor=100-$esca;
                return $valor;
            }else{

                $val=$hImg/$difH;
                $esca=100/$val;
                $valor=100-$esca;
                return $valor;
            }
        }else{ //La escala es 100
            return 100;
        }

    }


     public function EscalarAlto(){
        $hImg=$this->imagen->getHeight();
        if($hImg>$this->HEIGHT){
            $difH=$hImg-$this->HEIGHT;

                $val=$hImg/$difH;
                $esca=100/$val;
                $valor=100-$esca;
                return $valor;

        }else{ //La escala es 100
            return 100;
        }

    }

    public function EscalarAncho(){
        $wImg=$this->imagen->getWidth();
        if($wImg>$this->WIDTH){
            $difW=$wImg-$this->WIDTH;

                $val=$wImg/$difW;
                $esca=100/$val;
                $valor=100-$esca;
                return $valor;
        }else{ //La escala es 100
            return 100;
        }

    }

}


?>