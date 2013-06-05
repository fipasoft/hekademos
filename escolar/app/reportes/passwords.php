<?php
/*
 * Created on 13/11/2008
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */


 class Passwords extends Reporte{
     function Passwords(){

     }

 public function obtener($turno){
        $this->db = new db("localhost", "root", "1234", "sop");
           $consulta="SELECT * FROM sop_alumnos WHERE alu_turno='$turno'";
           $registros=$this->db->in_query($consulta);

           $this->Reporte('passwords.xls');
        $alumnos=array();

        foreach($registros as $r){
            $alumnos[$r["alu_turno"]][$r["alu_grado_posible"]][$r["alu_grupo"]][$r['alu_id']]=$r;
        }

        foreach($alumnos as $turno){
            foreach($turno as $grado){
                foreach($grado as $grupo){
                    $this->hoja($grupo);
                }
            }
        }


           $this->db->close();

    }

     public function hoja($grupo){
        $llaves=array_keys($grupo);
        $nombre=($grupo[$llaves[0]]["alu_grado_posible"]-1).$grupo[$llaves[0]]["alu_grupo"].$grupo[$llaves[0]]["alu_turno"];
        $hojas = $this->getHojas();
        if(array_key_exists($nombre, $hojas)){
            $h = $hojas[$nombre];
        }else{
            $cols = array( 0.1, 80,36);
            $h = $this->agregar_hoja($nombre, null, $cols);
        }
        $this->contenido($h, $grupo);
    }

    public function contenido(&$h, $grupo){

        $llaves=array_keys($grupo);
        $st = $this->getEstilos();
        $h->cc++;
        $h->xls->write($h->rr, $h->cc, "Grado", $st['TD.BGLightyellowCenter'], $st['H2.Left']);$h->cc++;
        $h->xls->write($h->rr, $h->cc,($grupo[$llaves[0]]["alu_grado_posible"]-1), $st['H2.Left']);
        $h->nueva_linea();
        $h->cc++;
        $h->xls->write($h->rr, $h->cc, "Grupo", $st['TD.BGLightyellowCenter'], $st['H2.Left']);$h->cc++;
        $h->xls->write($h->rr, $h->cc, $grupo[$llaves[0]]["alu_grupo"], $st['H2.Left']);
        $h->nueva_linea();
        $h->cc++;
        $h->xls->write($h->rr, $h->cc, "Turno", $st['TD.BGLightyellowCenter'], $st['H2.Left']);$h->cc++;
        $h->xls->write($h->rr, $h->cc, $grupo[$llaves[0]]["alu_turno"], $st['H2.Left']);
        $h->nueva_linea();
        $h->nueva_linea();

         $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'Nombre', $st['TH.BGOrange']); $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'Codigo', $st['TH.BGOrange']); $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'Password', $st['TH.BGOrange']); $h->cc++;
        $h->nueva_linea();

        $n = 0;
        foreach($grupo as $alumno){
            $n++;
            $td = ($n%2 == 0 ? 'Par' : '');
            $h->cc++;
            $h->xls->write($h->rr, $h->cc, utf8_decode($alumno["alu_ap"]." ".$alumno["alu_am"]." ".$alumno["alu_nombre"]), $st['TD' . $td . '.Normal']); $h->cc++;
            $h->xls->write($h->rr, $h->cc, utf8_decode($alumno["alu_codigo"]), $st['TD' . $td . '.Normal']); $h->cc++;
            $h->xls->write($h->rr, $h->cc, utf8_decode($alumno["alu_password"]), $st['TD' . $td . '.Normal']); $h->cc++;
            $h->nueva_linea();
        }
        $h->nueva_linea();
    }

    public function datos(){

    }

    public function encabezado(){

    }

    public function propiedades(){

    }


 }
?>
