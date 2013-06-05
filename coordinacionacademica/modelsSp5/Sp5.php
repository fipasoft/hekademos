<?php
/*
 * Created on 23/09/2009
 *
 * Clase padre para los modelos del sp5, provee los metodos para acceder a los datos
 * Los modelos son de solo lectura.
 */
 class Sp5{
    protected $tabla;
    private $db;
    public function Sp5($t){
        try{
        $this->tabla=$t;
        $this->db = new db("localhost", "pepe", "rock", "inventario");
        if(!$this->db){
                throw new ActiveRecordException("Ocurrio un error. No se pudo accedar a la base de datos del control escolar");
                return false;

        }

        }catch(Exception $e){
            throw new ActiveRecordException("Ocurrio un error. No se pudo accedar a la base de datos del control escolar");
                return false;
        }
    }

    public function find($param){
        $where=$this->obtenCondiciones($param);
        $sql="SELECT * FROM ".$this->tabla." ".$where;
        $this->db->query($sql);
        $results=array();
        if(mysql_num_rows()>0){
            while($result =  $this->db->fetch_array()){
                $results[] = $this->dump_result($result);
            }
            if(is_numeric($param)){//busqueda por id
                $this->dump_result_self($result);
                return $this->dump_result($result);
            }

            return $results;
        }else{
            return array();
        }

    }

    public function find_first($param){
        $where=$this->obtenCondiciones($param);
        $sql="SELECT * FROM ".$this->tabla." ".$where;
        $this->db->query($sql);
        $result =  $this->db->fetch_array();
        if(mysql_num_rows()>0){
            $this->dump_result_self($result);
            return $this->dump_result($result);
        }
    }

    public function find_all_by_sql($sql){
        $this->db->query($sql);
        $results=array();
        while($result =  $this->db->fetch_array()){
            $results[] = $this->dump_result($result);
        }
        return $results;

    }

    public function count($param){

    }

    private function obtenCondiciones($param){
        $param=trim($param);
        if($param==''){//sin parametros
            $where='';
        }elseif(is_numeric($param)){//numerico por id
            $where="id='$param'";
        }else{
            $where=$param;
        }

        return $where;

    }

        /**
     * Iguala los valores de un resultado de la base de datos
     * con sus correspondientes atributos de la clase
     *
     * @param array $result
     * @return ActiveRecord
     */
    public function dump_result_self($result){

        if(is_array($result)){
            foreach($result as $k => $r){
                if(!is_numeric($k)){
                    if (is_a($r, 'DateTime')){
                    $this->$k =$r;
                    }else{
                    $this->$k = stripslashes($r);

                    }
                    }
            }
        }

    }

        /**
     * Iguala los valores de un resultado de la base de datos
     * en un nuevo objeto con sus correspondientes
     * atributos de la clase
     *
     * @param array $result
     * @return ActiveRecord
     */
    function dump_result($result){
        $obj = clone $this;

        if(is_array($result)){
            foreach($result as $k => $r){
                if(!is_numeric($k)){
                    if (is_a($r, 'DateTime')){
                    $obj->$k =$r;
                    }else{
                    $obj->$k = stripslashes($r);

                    }
                }
            }
        }
        return $obj;
    }

    function close(){
        $this->db->close();
    }

 }
?>
