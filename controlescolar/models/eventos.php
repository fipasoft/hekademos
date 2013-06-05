<?php
// Hekademos, Creado el 02/11/2008
/**
 * Eventos
 *
 * @package
 * @author     mimeks <mimex@fipasoft.com.mx>
 * @copyright  2008 FiPa Software (contacto at fipasoft.com.mx)
 * @license    http://www.gnu.org/licenses/gpl.html  GNU General Public License (GPL)
 *
 */
class Eventos extends ActiveRecord{
    public $categoria;

    public function eventoEspecial($eve){
        $evento=$this->find_first("clave='".trim($eve->clave)."-ESP'");

        if($evento->id!='')
        return $evento->id;
        else return -1;
    }

    public function todos(){
        $categorias=new Categorias();
        $categorias=$categorias->find();

        $cats=array();
        foreach($categorias as $categoria){
            $cats[$categoria->id]=$categoria->nombre;
        }

        $eventos=$this->find();

        for($i=0;$i<count($eventos);$i++){
            $eventos[$i]->categoria=$cats[$eventos[$i]->categorias_id];
        }

        return $eventos;
    }

    public function todosPorCategoria(){
        $categorias=new Categorias();
        $categorias=$categorias->find();

        $cats=array();
        foreach($categorias as $categoria){
            $cats[$categoria->id]=$categoria->nombre;
        }

        $eventos=$this->find();

        $cateEvento=array();

        for($i=0;$i<count($eventos);$i++){
            $eventos[$i]->categoria=$cats[$eventos[$i]->categorias_id];
            $cateEvento[$eventos[$i]->categoria][$eventos[$i]->id]=$eventos[$i];
        }

        return $cateEvento;
    }

    public function porClave($clave){
        $eventos=$this->find("clave LIKE '".$clave."%'");

        return $eventos;
    }


}
?>
