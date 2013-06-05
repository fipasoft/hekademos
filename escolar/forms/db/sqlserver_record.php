<?php
class SqlserverRecord {
public $db;
public $source;
private $dumped = false;

    function SqlserverRecord($table='', $dump=true){
        try{
        if(!$table){
            $this->model_name();
        } else {
            $this->source = strtolower($table);
        }

        $this->conex=Config :: read();
        /* Connect using Windows Authentication. */
        $this->db = sqlsrv_connect( $this->conex->sqlserver->host, array("Database" => $this->conex->sqlserver->name));

        if( $this->db )
        {
        $this->dump_info();
        }
        }catch(Exception $e){

        }

    }


    /**
     * Obtiene el nombre de la relacion en el RDBM a partir del nombre de la clase
     *
     */
    private function model_name(){
        $this->source = get_class($this);
        $this->source = strtolower($this->source);
    }



    private function dump_info(){
    $tablas=Session::get_data("sqlserver.tablas");
    $campos=$tablas[$this->conex->sqlserver->name];
    if($campos==null){
        $sql="select COLUMN_NAME from ".$this->conex->sqlserver->name.".information_schema.columns
        WHERE  TABLE_SCHEMA='dbo' AND TABLE_CATALOG='".$this->conex->sqlserver->name."' AND TABLE_NAME='".$this->source."'";

        /* Execute the query. */
        $stmt = sqlsrv_query($this->db, $sql);
        if( $stmt === false )
            {
                 throw new ActiveRecordException("Ocurrio un error con la consulta ".$sql);
                return false;

            }

                $cs=array();
                while($row = sqlsrv_fetch_array($stmt)){
                    if(!isset($this->$row["COLUMN_NAME"])){
                        $cs[]=$row["COLUMN_NAME"];
                        $this->$row["COLUMN_NAME"]= "";
                    }

                    }

            $tablas[$this->conex->sqlserver->name]=$cs;
            Session::set_data("sqlserver.tablas",$tablas);

            }else{

                foreach($campos as $c){
                    if(isset($this->$c)){
                        $this->$c["COLUMN_NAME"]= "";
                    }

                }
            }

    }

        /**
     * Realiza un conteo de filas
     *
     * @param string $what
     * @return integer
     */
    public function count($what=''){
        $select = "SELECT count(*) as count ";

        $select.= " FROM {$this->conex->sqlserver->name}.dbo.{$this->source}";

        if($what!='')
        $select.=" WHERE ".$what;

        /* Execute the query. */
        $all_results = sqlsrv_query($this->db, $select);
        if( $all_results === false )
            {
                 throw new ActiveRecordException("Ocurrio un error con la consulta ".$select);
                return false;

            }



        $result =  sqlsrv_fetch_array($all_results);
        return $result["count"];

    }

        /**
     * Find data on Relational Map table
     *
     * @param string $what
     * @return ActiveRecord Cursor
     */
    public function find($what=''){
        $select = "SELECT * ";

        $select.= " FROM {$this->conex->sqlserver->name}.dbo.{$this->source}";

        if($what!='')
        $select.=" WHERE ".$what;

        /* Execute the query. */
        $all_results = sqlsrv_query($this->db, $select);
        if( $all_results === false )
            {
                 throw new ActiveRecordException("Ocurrio un error con la consulta ".$select);
                return false;

            }


            $results=array();
        while($result =  sqlsrv_fetch_array($all_results)){
            $results[] = $this->dump_result($result);
        }



            return $results;
    }

    /**
     * Return Fist Record
     *
     * @param mixed $what
     * @param boolean $debug
     * @return ActiveRecord Cursor
     */
    public function find_first($what=''){
        $select = "SELECT TOP 1 * ";

            $select.= " FROM {$this->conex->sqlserver->name}.dbo.{$this->source}";

        if($what!='')
        $select.=" WHERE ".$what;

        /* Execute the query. */
        $result = sqlsrv_query($this->db, $select);
        if( $result === false )
            {
                 throw new ActiveRecordException($this->conex->sqlserver->host."Ocurrio un error con la consulta ".var_dump($this->db));
                return false;

            }

            while($row = sqlsrv_fetch_array($result)){
                $this->dump_result_self($row);
                $resp = $this->dump_result($row);
            }


        return $resp;
    }

    /**
     * Return records
     *
     * @param mixed $what
     * @param int $records
     * @return ActiveRecord Cursor
     */
    public function find_records($what='',$records){
        if($records>0){
        $select = "SELECT TOP ".$records." * ";

            $select.= " FROM {$this->conex->sqlserver->name}.dbo.{$this->source}";

        if($what!='')
        $select.=" WHERE ".$what;

        /* Execute the query. */
        $result = sqlsrv_query($this->db, $select);
        if( $result === false )
            {
                 throw new ActiveRecordException("Ocurrio un error con la consulta ".$select);
                return false;

            }

            while($row = sqlsrv_fetch_array($result)){
                $this->dump_result_self($row);
                $resp = $this->dump_result($row);
            }


        return $resp;
        }else{
            return array();
        }
    }

    /**
     * Find all records in this table using a SQL Statement
     *
     * @param string $sqlQuery
     * @return ActiveRecord Cursor
     */
    public function find_all_by_sql($sqlQuery){
            /* Execute the query. */
        $all_results = sqlsrv_query($this->db, $sqlQuery);
        if( $all_results === false )
            {
                 throw new ActiveRecordException("Ocurrio un error con la consulta ".$sqlQuery);
                return false;

            }


            $results=array();
        while($result =  sqlsrv_fetch_array($all_results))
            $results[] = $this->dump_result($result);


            return $results;

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

function close(){

        /* Close the connection. */
        sqlsrv_close($this->db);
}

}
?>