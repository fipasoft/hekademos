<?php

kumbia::import('app.componente.*');
kumbia::import('app.Utils.*');
kumbia::import('app.constantes.*');

class BlogC extends Componente{
    public $elements;
    public $numReg;
    public $num_rows;
    public $elemento_indice;

    public $elements_publicados;
    public $num_rows_pub;

    public $comentarios;
    public function BlogC($nm,$ttl,$id_com,$elemento_indice,$nr=10){
        $this->numReg=$nr;
         $this->setTable('blog');
         $this->setId($id_com);
         $this->setName($nm);
         $this->setTitle($ttl);
         $this->getMyDat();
         $this->elemento_indice=$elemento_indice;
        $this->getElements();
        parent::Componente();
    }

   private function getElements1(){
        $elementos=new sugerencia();
        $this->elements=$elementos->find('blog_id='.$this->id);
    }

    private function getMyDat(){
         $cmp_est=new blog();
        $componente=$cmp_est->find_first('componente_id='.$this->getId());
        $this->id=$componente->Id;

     }

    public function getComentarios(){
    $elementos=new post();


    $elementos=$elementos->find('conditions: blog_id='.$this->id);

    $this->comentarios=array();
    foreach($elementos as $post){
    $comentario=new comentario();
    $total=$comentario->count("post_id=".$post->id." AND verificado=1");
    $this->comentarios[$post->id]=$total;
    }
    }

     private function getElements(){

        $elementos=new post();


        $this->elements=$elementos->find('conditions: blog_id='.$this->id,"limit: ".$this->elemento_indice.",".$this->numReg."","order: fecha desc" );

        $this->num_rows = $elementos->count('blog_id='.$this->id);


        $this->elements_publicados=$elementos->find('conditions: blog_id='.$this->id." AND publicado=1","limit: ".$this->elemento_indice.",".$this->numReg."","order: fecha desc" );

        $this->num_rows_pub = $elementos->count('blog_id='.$this->id." AND publicado=1");

     }


}

?>