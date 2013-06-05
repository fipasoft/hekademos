<?php
// sp5, Creado el 29/08/2008
/**
 *
 * Copyright (C) 2008 FiPa Software (contacto at fipasoft.com.mx)
 * Funciones para obtener informacion usando la lista ACL de phpgacl
 * @package phpGACL
 * @author mimeks <mimex@fipasoft.com.mx>
 */
require_once 'gacl.class.php';
require_once 'gacl_api.class.php';

 class gacl_extra extends gacl_api{
     private $host;
     private $user;
     private $pass;
     private $db;
     private $host2;
     private $user2;
     private $pass2;
     private $db2;

    public function gacl_extra(){
        $this->host = 'localhost';
        $this->user = 'hekademos';
        $this->pass = 'hekademos';
        $this->db = 'acl_coordinacion';

        $this->host2 = 'localhost';
        $this->user2 = 'hekademos';
        $this->pass2 = 'hekademos';
        $this->db2 = 'hekademos';
    }

    /**
     * @method array Retorna un arreglo de acuerdo a las ACL verificadas $arr[aco_section][aco] = TRUE/FALSE
     * @param string $login Login del usuario, e.g. root, admin, etc...
     * @param array $acos_arr Un arreglo que contiene la estructura con los ACOs que se verificaran $arr[aco_section][aco].
     * @return array Un arreglo con los nombres de las secciones y objetos con la forma array[section][value] = section/value
     */
     public function acl_check_multiple($acos_arr, $login){
         $acl = new gacl();
         $acls = array();
         $db = new db($this->host, $this->user, $this->pass, $this->db);

         foreach($acos_arr as $section => $acos){
            foreach($acos as $aco){
                if( $acl->acl_check($section,  $aco,    'usuarios',  $login)  ||
                    $acl->acl_check($section,  $aco,    'usuarios',  $login)  ||
                    $acl->acl_check('ALL',     'ALL',   'usuarios',  $login)     )
                {
                    $acls[$section][$aco] = true;
                }else{
                    $acls[$section][$aco] = false;
                }
            }
        }

        $this->dbReset();
        return $acls;
     }

     public function dbReset(){
         $db = new db($this->host2, $this->user2, $this->pass2, $this->db2);
         return true;
     }

     public function get_parent_groups($groups){
        $ids=array();
        $acl=new gacl_api();
        foreach($groups as $group){
         $aro_id=strtolower($group);
         $gid=$acl->get_group_id($aro_id);
         $data=$acl->get_group_data($gid);
         $ids[$gid] =$data[3];
         $groot = $acl->get_root_group_id($gid);
        $data=$acl->get_group_data($groot);
        $ids[$groot] = $data[3];
        $gparent=$gid;
        while($gparent != $groot){
             $gparent = $acl->get_group_parent_id($gparent);
             $data=$acl->get_group_data($gparent);
             $ids[$gparent] =$data[3];
        }

        $gparent = $acl->get_group_parent_id($gparent);
        $data=$acl->get_group_data($gparent);
        $ids[$gparent] =$data[3];

        }
        return $ids;
     }

     public function get_users($aro_id){
         $acl = new gacl_api();
         $gid=$acl->get_group_id(strtolower($aro_id));
         //$grupos=$acl->get_group_children($gid);
         $grupos[]=$gid;
         $where='';
         foreach($grupos as $grupo){
                 $where .= ($where == '' ? 'WHERE ' : 'OR ');
                $where .= " group_id='$grupo' ";
         }

         $db = new db($this->host, $this->user, $this->pass, $this->db);
         $sql="SELECT " .
                 "groups_aro_map.aro_id,aro.name " .
             "FROM " .
                 "`groups_aro_map` " .
                 "Inner Join " .
                    "aro ON groups_aro_map.aro_id = aro.id " .
                    $where;

         $usuarios=$db->in_query($sql);
        $db->close();
        return $usuarios;

     }


     /**
      * @method array Retorna los ACOs asignados al usuario en la lista acl
      * @param string $login Login del usuario, e.g. root, admin, etc...
      * @param array $exc_groups (Opcional) Un arreglo que contiene los nombres de los grupos que no se incluiran en la b�squeda, es �til para no publicar en el menu algunos recursos.
      * @return array Un arreglo con los nombres de las secciones y objetos con la forma array[section][value] = section/value
      */
     public function get_user_acos($login, $exc_groups = array()){
         $acl = new gacl_api();
        $db = new db($this->host, $this->user, $this->pass, $this->db);
        // Consulta SQL base
        $sql = "SELECT " .
                    "aco_map.section_value, " .
                    "aco_map.value, " .
                    "aro_groups_map.group_id " .
                "FROM " .
                    "acl " .
                "Inner Join " .
                    "aco_map ON aco_map.acl_id = acl.id AND acl.allow = 1 " .
                "Inner Join " .
                    "aro_groups_map ON aro_groups_map.acl_id = acl.id " .
                "Inner Join " .
                    "aro_groups ON aro_groups_map.group_id = aro_groups.id ";

        // Se genera un arreglo con los ids de los grupos excluidos
        $excluir = array();
        foreach($exc_groups as $gx){
            $excluir [$gx] = $acl->get_group_id($gx);
        }

        // Se obtienen los ids de grupo del usuario para ver sus privilegios
        // Se obtienen los ids del grupo enviado
        $where = '';
        $ids = $acl->acl_get_groups('usuarios', $login);

        // se agregan los ids de los grupos padre
        foreach($ids as $id){
            $groot = $acl->get_root_group_id($id);
            $gparent = $id;
            while($gparent != $groot){
                $ids[] = $gparent = $acl->get_group_parent_id($gparent);
            }
        }
        // unifica el arreglo
        $ids = array_unique($ids);

        // se generan las condiciones de la consulta
        if($login == 'root'){
            foreach($excluir as $id){
                $where .= ($where == '' ? 'WHERE ' : 'AND ');
                $where .= "aro_groups.id !=  '" . $id . "' ";
            }
        }else{
            foreach($ids as $id){
                if( !array_search($id, $excluir) ){
                    $where .= ($where == '' ? 'WHERE ' : 'OR ');
                    $where .= "aro_groups.id =  '" . $id . "' ";
                }
            }
            if($where == ''){
                $where = 'WHERE 0 ';
            }
        }

        // se genera la consulta sql
        $sql = $sql . $where;

        // ejecucion de la consulta sql
        $db->query($sql);
        // genera una la estructura de retorno
        // array[section][name] = section/name
        $aco = array();
        while($item = $db->fetch_array('', db :: DB_ASSOC)){
            $section = $item['section_value'];
            $value = $item['value'];
            $aco[$section][$value] = $value;
        }

        $this->dbReset();
        return $aco;
     }

    /**
     * @method array Retorna los nombres de los grupos de la lista acl
      * @return array Un arreglo con los nombres de grupos con la forma array(g1, g2, ..., gn)
      */
     public function get_all_groups(){
         $acl = new gacl_api();
        $db = new db($this->host, $this->user, $this->pass, $this->db);
        // Consulta SQL base
        $sql =     "SELECT " .
                    "aro_groups.id AS gid, " .
                    "aro_groups.name AS gname " .
                "FROM " .
                    "aro_groups " .
                "WHERE " .
                    "1 " .
                "ORDER BY " .
                    "gname";

        $db->query($sql);
        // genera la estructura de retorno
        $groups = array();
        while($item = $db->fetch_array('', db :: DB_ASSOC)){
            $groups[$item['gid']] = $item['gname'];
        }

        $this->dbReset();
        return $groups;
     }

    /**
     * @method array Retorna los nombres de los grupos asignados al usuario en la lista acl
      * @param string $login Login del usuario, e.g. root, admin, etc...
      * @return array Un arreglo con los nombres de grupos con la forma array(g1, g2, ..., gn)
      */
     public function get_user_groups($login){
         $acl = new gacl_api();
        $db = new db($this->host, $this->user, $this->pass, $this->db);
        // Consulta SQL base
        $sql =     "SELECT " .
                    "aro_groups.name AS gname, " .
                    "aro.name " .
                "FROM " .
                    "groups_aro_map " .
                    "Inner Join aro_groups ON groups_aro_map.group_id = aro_groups.id " .
                    "Inner Join aro ON aro.id = groups_aro_map.aro_id " .
                "WHERE " .
                    "aro.name = '" . $login . "'";

        $db->query($sql);
        // genera la estructura de retorno
        $groups = array();
        while($item = $db->fetch_array('', db :: DB_ASSOC)){
            $groups[] = $item['gname'];
        }

        $this->dbReset();
        return $groups;
     }

     /**
      * @method array Retorna los ACOs asignados al grupo en la lista acl
      * @param integer $aro Aro
      * @return array Un arreglo con los nombres de las secciones y objetos con la forma array[section][value] = section/value
      */
     public function get_acos($aro){
         $acl = new gacl_api();

         if($this->is_group($aro)){
         $aro_id=strtolower($aro);
         $gid=$acl->get_group_id($aro_id);
         return $this->get_group_acos($gid);
         }
         else
         return $this->get_user_acos($aro);
}


     /** @method array Devuelve las Secciones de ACOs asignados al usuario en la lista acl
      * @param string $login Login del usuario, e.g. root, admin, etc...
      * @param array $exc_groups (Opcional) Un arreglo que contiene los nombres de los grupos que no se incluiran en la b�squeda, es �til para no publicar en el menu algunos recursos.
      * @return array Un arreglo con los nombres de las secciones con la forma array[section] = section
      */
     public function get_user_sections($login, $exc_groups = array()){
         $acl = new gacl_api();
        $db = new db($this->host, $this->user, $this->pass, $this->db);
        // Consulta SQL base
        $sql = "SELECT " .
                    "aco_map.section_value, " .
                    "aro_groups_map.group_id " .
                "FROM " .
                    "acl " .
                "Inner Join " .
                    "aco_map ON aco_map.acl_id = acl.id AND acl.allow = 1 " .
                "Inner Join " .
                    "aro_groups_map ON aro_groups_map.acl_id = acl.id " .
                "Inner Join " .
                    "aro_groups ON aro_groups_map.group_id = aro_groups.id " .
                "Inner Join " .
                    "aco ON aco_map.section_value = aco.section_value ";

        // Se genera un arreglo con los ids de los grupos excluidos
        $excluir = array();
        foreach($exc_groups as $gx){
            $excluir [$gx] = $acl->get_group_id($gx);
        }

        // Se obtienen los ids de grupo del usuario para ver sus privilegios
        $where = '';
        $ids = $acl->acl_get_groups('usuarios', $login);

        // se agregan los ids de los grupos padre
        foreach($ids as $id){
            $groot = $acl->get_root_group_id($id);
            $gparent = $id;
            while($gparent != $groot){
                $ids[] = $gparent = $acl->get_group_parent_id($gparent);
            }
        }
        // unifica el arreglo
        $ids = array_unique($ids);

        if($login == 'root'){
            foreach($excluir as $id){
                $where .= ($where == '' ? 'WHERE ' : 'AND ');
                $where .= "aro_groups.id !=  '" . $id . "' ";
            }
        }else{
            foreach($ids as $id){
                if(!array_search($id, $excluir)){
                    $where .= ($where == '' ? 'WHERE ' : 'OR ');
                    $where .= "aro_groups.id =  '" . $id . "' ";
                }
            }
            if($where == ''){
                $where = 'WHERE 0 ';
            }
        }

        $order = 'GROUP BY ' .
                    'aco_map.section_value ' .
                 'ORDER BY ' .
                    'aco.id ASC, ' .
                    'acl.id ASC ';

        // se genera la consulta sql
        $sql = $sql . $where. $order;

        // ejecucion de la consulta sql
        $db->query($sql);
        // genera una la estructura de retorno
        // array[section][name] = section/name
        $aco_sections = array();
        while($item = $db->fetch_array('', db :: DB_ASSOC)){
            $section = $item['section_value'];
            $aco_sections[$section] = $section;
        }

        $this->dbReset();
        return $aco_sections;
     }

         public function get_childs_groups($groups){
        $ids=array();
        $acl=new gacl_api();
        foreach($groups as $group){
         $aro_id=strtolower($group);
        $id=$acl->get_group_id($aro_id);
        $childs=$acl->get_group_children($id,'ARO','RECURSE');
        $c=$acl->get_group_data($id);
        $ids[$id]=$c[3];
        $esHijo=false;
        foreach($childs as $child){
            $c=$acl->get_group_data($child);
            $ids[$child]=$c[3];
        }
        }
        return $ids;
        }


     /**
      * @method array Retorna los ACOs asignados al grupo en la lista acl
      * @param integer $gid Id del grupo
      * @param array $exc_groups (Opcional) Un arreglo que contiene los nombres de los grupos que no se incluiran en la b�squeda, es �til para no publicar en el menu algunos recursos.
      * @return array Un arreglo con los nombres de las secciones y objetos con la forma array[section][value] = section/value
      */
     public function get_group_acos($gid, $exc_groups = array()){
         $acl = new gacl_api();
        $db = new db($this->host, $this->user, $this->pass, $this->db);
        // Consulta SQL base
        $sql = "SELECT " .
                    "aco_map.section_value, " .
                    "aco_map.value, " .
                    "aro_groups_map.group_id " .
                "FROM " .
                    "acl " .
                "Inner Join " .
                    "aco_map ON aco_map.acl_id = acl.id AND acl.allow = 1 " .
                "Inner Join " .
                    "aro_groups_map ON aro_groups_map.acl_id = acl.id " .
                "Inner Join " .
                    "aro_groups ON aro_groups_map.group_id = aro_groups.id ";

        // Se genera un arreglo con los ids de los grupos excluidos
        $excluir = array();
        foreach($exc_groups as $gx){
            $excluir [$gx] = $acl->get_group_id($gx);
        }

        // Se obtienen los ids del grupo enviado
        $where = '';
        $groot = $acl->get_root_group_id($gid);
        $gparent = $gid;
        $ids = array($gid);
        while($gparent != $groot){
            $ids[] = $gparent = $acl->get_group_parent_id($gparent);
        }

        foreach($ids as $id){
            if(!array_search($id, $excluir)){
                $where .= ($where == '' ? 'WHERE ' : 'OR ');
                $where .= "aro_groups.id =  '" . $id . "' ";
            }
        }

        $info_group = $acl->get_group_data($gid);
        $gname = $info_group[2];
        if($where == ''){
            if($gname == 'root'){
                foreach($excluir as $id){
                    $where .= ($where == '' ? 'WHERE ' : 'AND ');
                    $where .= "aro_groups.id !=  '" . $id . "' ";
                }
            }else{
                $where = 'WHERE 0 ';
            }
        }

        // se genera la consulta sql
        $sql = $sql . $where;

        // ejecucion de la consulta sql
        $db->query($sql);
        // genera una la estructura de retorno
        // array[section][name] = section/name
        $aco = array();
        while($item = $db->fetch_array('', db :: DB_ASSOC)){
            $section = $item['section_value'];
            $value = $item['value'];
            $aco[$section][$value] = $value;
        }

        $this->dbReset();
        return $aco;
     }

         public function is_group($aro){
        $acl = new gacl_api();
         $aro_id=strtolower($aro);
         $gid=$acl->get_group_id($aro_id);
         return $gid;
    }


 }
?>
