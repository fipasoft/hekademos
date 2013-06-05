<?php
Kumbia :: import('lib.excel.main');
class XLSGrupos extends Reporte{
    public $db;
    public function XLSGrupos(){$this->code=""; }

    public function reportePorCiclo($ciclo_id){
        $this->db = new db("localhost", "root", "administrador", "hkd");
           $consulta="SELECT * FROM grupos WHERE ciclos_id='".$ciclo_id."'";
           $grupos=$this->db->in_query($consulta);

           $this->Reporte('Grupo ' .$ciclo_id.'xls');

        foreach($grupos as $grupo)
        $this->hoja($grupo);

           $this->db->close();
    }

    public function reportePorCicloTurno($ciclo_id,$turno){
        $this->db = new db("localhost", "root", "administrador", "hkd");
           $consulta="SELECT * FROM grupos WHERE ciclos_id='".$ciclo_id."' AND turno='".$turno."'";
           $grupos=$this->db->in_query($consulta);

           $this->Reporte('Grupo ' .$ciclo_id.' '.$turno.'.xls');

        foreach($grupos as $grupo)
        $this->hoja($grupo);

           $this->db->close();
    }

    public function reportePorCicloTurnoSemestre($ciclo_id,$turno,$semestre){
        $this->db = new db("localhost", "root", "administrador", "hkd");
           $consulta="SELECT * FROM grupos WHERE ciclos_id='".$ciclo_id."' AND turno='".$turno."' AND grado='".$semestre."'";
           $grupos=$this->db->in_query($consulta);

           $this->Reporte('Grupo ' .$ciclo_id.' '.$turno.'.xls');

        foreach($grupos as $grupo)
        $this->hoja($grupo);

           $this->db->close();

    }

    public function reportePorCicloTurnoSemestreGrupo($ciclo_id,$turno,$semestre,$grupo){
        $this->db = new db("localhost", "root", "administrador", "hkd");
           $consulta="SELECT * FROM grupos WHERE ciclos_id='".$ciclo_id."' AND turno='".$turno."' AND grado='".$semestre."' AND letra='".$grupo."'";
           $grupos=$this->db->in_query($consulta);

           $this->Reporte('Grupo ' .$ciclo_id.' '.$turno.'.xls');

        foreach($grupos as $grupo)
        $this->hoja($grupo);

           $this->db->close();

    }

    public function reportePorCicloTurnoGrupo($ciclo_id,$turno,$grupo){
        $this->db = new db("localhost", "root", "administrador", "hkd");
           $consulta="SELECT * FROM grupos WHERE ciclos_id='".$ciclo_id."' AND turno='".$turno."' AND letra='".$grupo."'";
           $grupos=$this->db->in_query($consulta);

           $this->Reporte('Grupo ' .$ciclo_id.' '.$turno.'.xls');

        foreach($grupos as $grupo)
        $this->hoja($grupo);

           $this->db->close();

    }

    public function reportePorGrupo($grupo_id){
        $this->db = new db("localhost", "root", "administrador", "hkd");
           $consulta="SELECT * FROM grupos WHERE id='".$grupo_id."'";
           $grupo=$this->db->fetch_one($consulta);
           $this->Reporte('Grupo ' . $grupo["grado"].$grupo["letra"].$grupo["turno"] . '.xls');
        $this->hoja($grupo);
           $this->db->close();
    }


    public function hoja($grupo){

        $nombre=$grupo["grado"].$grupo["letra"].$grupo["turno"];
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
        $consulta="SELECT alumnos.id,alumnos.codigo,alumnos.nombre,alumnos.ap,alumnos.am " .
               "FROM alumnosgrupo,alumnos " .
               "WHERE alumnosgrupo.grupos_id='".$grupo["id"]."' AND alumnosgrupo.alumnos_id=alumnos.id";
        $alumnos=$this->db->in_query($consulta);

        $ciclo=$this->db->fetch_one("SELECT * FROM ciclos WHERE id='".$grupo["ciclos_id"]."'");
        $st = $this->getEstilos();
        $h->cc++;
        $h->xls->write($h->rr, $h->cc, "Grado", $st['TD.BGLightyellowCenter'], $st['H2.Left']);$h->cc++;
        $h->xls->write($h->rr, $h->cc, $grupo["grado"], $st['H2.Left']);
        $h->nueva_linea();
        $h->cc++;
        $h->xls->write($h->rr, $h->cc, "Grupo", $st['TD.BGLightyellowCenter'], $st['H2.Left']);$h->cc++;
        $h->xls->write($h->rr, $h->cc, $grupo["letra"], $st['H2.Left']);
        $h->nueva_linea();
        $h->cc++;
        $h->xls->write($h->rr, $h->cc, "Turno", $st['TD.BGLightyellowCenter'], $st['H2.Left']);$h->cc++;
        $h->xls->write($h->rr, $h->cc, $grupo["turno"], $st['H2.Left']);
        $h->nueva_linea();
        $h->cc++;
        $h->xls->write($h->rr, $h->cc, "Calendario", $st['TD.BGLightyellowCenter'], $st['H2.Left']);$h->cc++;
        $h->xls->write($h->rr, $h->cc, $ciclo["numero"], $st['H2.Left']);
        $h->nueva_linea();
        $h->cc++;
        $h->xls->write($h->rr, $h->cc, "id_grupo", $st['TD.BGLightyellowCenter'], $st['H2.Left']);$h->cc++;
        $h->xls->write($h->rr, $h->cc, $grupo["id"], $st['H2.Left']);
        $h->nueva_linea();
        $h->nueva_linea();

        $h->xls->write($h->rr, $h->cc, 'ID'); $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'Nombre', $st['TH.BGOrange']); $h->cc++;
        $h->xls->write($h->rr, $h->cc, 'Codigo', $st['TH.BGOrange']); $h->cc++;
        $h->nueva_linea();

        $n = 0;
        foreach($alumnos as $alumno){
            $n++;
            $td = ($n%2 == 0 ? 'Par' : '');
            $h->xls->write($h->rr, $h->cc, $alumno["id"]); $h->cc++;
            $h->xls->write($h->rr, $h->cc, utf8_decode($alumno["ap"]." ".$alumno["am"]." ".$alumno["nombre"]), $st['TD' . $td . '.Normal']); $h->cc++;
            $h->xls->write($h->rr, $h->cc, utf8_decode($alumno["codigo"]), $st['TD' . $td . '.Normal']); $h->cc++;
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
