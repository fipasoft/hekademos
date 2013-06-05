<?php
/*
 * Created on 08/03/2009
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

 class Inconsistencias{
     private $usuarios;
    private $tipo;
    private $inconsistencias;
    private $total;
    private $errores;
    private $inconsistencia;
    private $pagina;
    private $todos;
    private $conectado;

    public function Inconsistencias(){
        $this->usuarios=array();
        $this->tipo='';
        $this->inconsistencias=array();
        $this->total=0;
        $this->errores=array();
        $this->inconsistencia="T";
        $this->porPagina=10;
        $this->pagina=1;
        $this->fecha='';
        $this->todos=false;
        $this->conectado=false;
    }

    public function colocaPagina($p){
        $this->pagina=$p-1;
    }

    public function estaConectado(){
        return $this->conectado;
    }


    public function colocaFecha($f){
        $this->fecha=$f;
    }

    public function colocaUsuarios($usr){
        if(is_array($usr))
            $this->usuarios=$usr;

    }

    public function colocaTabla($t){
        $this->tipo=$t;
    }

    public function colocaTipos($t){
        if($t==""){
            $this->inconsistencia="T";
        }else{
            $this->inconsistencia=$t;
        }
    }


    public function total(){
        return $this->total;
    }

    private function inconsistenciaImpar($eventos){
        if(count($eventos)%2!=0)
            return true;
            else
            return false;
    }

    private function inconsistenciaES($eventos){
        //revisar combinacion entrada->salida->entrada->salida->n
        $estado=true; //entrada
        foreach($eventos as $evento){
            $tipo=$evento->PodDoorIndex;

            if($tipo==2)
            $tp=false;
            elseif($tipo==1)
            $tp=true;

            if($tp==$estado){
                $estado=!$estado;
            }else return true;
        }

        return !$estado;
    }

    public function obtenError($codigo){
        if($this->errores[$codigo]!=null)
        return $this->errores[$codigo];
        else
        return "";
    }

    public function obtenInconsistencias(){
        $viewEvents=new ViewEvents();
        if($viewEvents->db==true){
            $this->conectado=true;
        if(count($this->usuarios)>0 && $this->tipo!=''){
            $fecha=$this->fecha;
            $inicio=(($this->pagina+1)*$this->porPagina)-$this->porPagina;
            $fin=$inicio+$this->porPagina;
            $cond='';
            $incide=array();
            foreach($this->usuarios as $u){
                $cond.=" UserInfo3='".$u->codigo."' OR";
            }

            $cond=substr($cond,0,strlen($cond)-2);

            $viewEvents=$viewEvents->find_all_by_sql(
                "SELECT
                        ViewUser.UserInfo3,ViewEvents.UniqueID,CAST(([PanelLocalDT] - 2) AS datetime) AS fecha,ViewEvents.DoorNumberText,
                        ViewEvents.CardNumber,ViewEvents.UserNumberText,ViewEvents.AreaNumberText,ViewDoor.DoorNumber,ViewDoor.PodDoorIndex
              FROM [Director].[dbo].[ViewUser]
                  INNER JOIN [Director].[dbo].[ViewEvents] ON ViewUser.CardNumber=ViewEvents.CardNumber
                INNER JOIN [Director].[dbo].[ViewDoor] ON ViewEvents.DoorNumber=ViewDoor.DoorNumber
                  WHERE PanelLocalDT > CAST(CAST('".$fecha." 00:00:01' AS datetime) AS float)+2 AND
              PanelLocalDT < CAST(CAST('".$fecha." 23:59:59' AS datetime) AS float)+2 AND (".$cond.") ORDER BY PanelLocalDT"
            );

            $eventos=array();
            foreach($viewEvents as $e){
                $eventos[$e->UserInfo3][]=$e;
                $u=$this->usuarios[$e->UserInfo3];
                if($u!=null){
                $u->tarjeta=$e->CardNumber;
                $this->usuarios[$e->UserInfo3]=$u;
                }
            }

            $this->errores=array();
            $inconsistencias=array();
            foreach($this->usuarios as $u){
                $evs=$eventos[$u->codigo];
                if($evs!=null && count($evs)>0){
                    if($this->inconsistencia!="S"){
                    $encontrada=false;
                    if($this->inconsistencia=="T" || $this->inconsistencia=="A"){
                        if($this->inconsistenciaImpar($evs)){
                            $inconsistencias[]=$u;
                            $this->errores[$u->codigo]="Accesos Impares";
                            $encontrada=true;
                        }
                    }

                    if(($this->inconsistencia=="T" || $this->inconsistencia=="E") && !$encontrada){
                        if($this->inconsistenciaES($evs)){
                            $inconsistencias[]=$u;
                            $this->errores[$u->codigo]="Entradas/Salidas no concuerdan";
                        }
                    }
                    }


                }else{
                    if($this->inconsistencia=="S" || $this->inconsistencia=="T"){

                    $inconsistencias[]=$u;
                    $this->errores[$u->codigo]="No cuenta con ningun Acceso";
                    }
                }

            }

            $this->total=count($inconsistencias);
            $this->inconsistencias=array();
            if(!$this->todos){
                    for($inicio;$inicio<$fin && $inicio<$this->total;$inicio++){
                    $this->inconsistencias[]=$inconsistencias[$inicio];
                    }
            }else{
                $this->inconsistencias=$inconsistencias;
            }


        }else{
            $this->inconsistencias=array();
        }

        }else{
                $this->conectado=false;
                $this->inconsistencias=array();
            }
        return $this->inconsistencias;
    }

    public function colocaPorPagina($p){
        $this->porPagina=$p;
    }

    public function botones(){

        $botones=array();
        if($this->total>0){
            $rpp  =  10;
             $reg  =  $this->total;
             $np   =  10;
             $path="es/inconsistencias/".$this->fecha;

         $nav = false;
         $this->pagina = $pag  =  ($this->pagina < $reg / $rpp  ? $this->pagina : 0);

             // establecer intervalo de paginas
             if($pag <= $np / 2 || $reg / $rpp <= $np){
                 $ini = 0;
                 if($np <= ceil($reg / $rpp)){
                     $fin = $np;
                 }else{
                     $fin = ceil($reg / $rpp);
                 }
             }else{
                 $nav = true;
                 $ini = $pag - intval($np / 2, 10);
                 if($pag + intval($np / 2, 10) <= ceil($reg / $rpp)){
                     $fin = $pag + intval($np / 2, 10);
                 }else{
                     $fin = ceil($reg / $rpp);
                 }
             }

             // generar botones
             if($nav){
                 $botones[] = new Boton($path . '/1',
                                              '<<',
                                              'boton',
                                              'inicial'
                                        );
             }
             for($p = $ini; $p < $fin; $p++){
                 $botones[] = new Boton($path . '/' . ($p + 1),
                                              $p + 1,
                                              ($p == $pag ? 'activo' : 'boton'),
                                              $p + 1
                                        );
             }
             if($reg / $rpp > $np && $pag + intval($np / 2, 10) < ceil($reg / $rpp)){
                 $botones[] = new Boton($path . '/' . ceil($reg / $rpp),
                                              '>>',
                                              'boton',
                                              'final'
                                        );
             }
             }
        return $botones;
    }

    public function obtenTodos($t){
        $this->todos=$t;
    }


 }
?>
