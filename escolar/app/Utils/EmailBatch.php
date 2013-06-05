<?php
class EmailBatch {

    private $paquetes;
    private $titulo;
    private $mensaje;
    private $id_notificacion;
    private $log;
    private $tipo;
    function EmailBatch($id, $dests, $tit, $ms, $t) {
        $this->id_notificacion = $id;
        $this->destinatarios = $dests;
        $this->titulo = $tit;
        $this->mensaje = $ms;
        $this->tipo = $t;
    }

    public function inicia() {
        $this->log = new Logger("notificacion_" . $this->id_notificacion);
        if ($this->tipo == "A") {
            foreach ($this->destinatarios as $destinatario) {
                $this->enviar($destinatario["nombre"], $destinatario["mail"]);
            }

        } else
            if ($this->tipo == "T") {

                foreach ($this->destinatarios as $destinatario) {
                    $this->enviar($destinatario["nombreT"], $destinatario["mail"]);
                }

            }
        //Se guarda al log
        $this->log->commit();
        //Cierra el Log
        $this->log->close();
    }

    private function enviar($nombre, $email) {

        /* we use the db_options and mail_options here */
        require_once "Mail/Queue.php";
        kumbia::import('config.mail');
        $config=new mail_config();

        $mail_queue =& new Mail_Queue($config->db_options, $config->mail_options);

        $from = 'rodion.raskolnikof@gmail.com';
        $to = $email;
        $message = $this->titulo . " " . $this->mensaje;

        $hdrs = array (
            'From' => $from,
            'To' => $to,
            'Subject' => "Respuesta a la sugerencia realizada."
        );

        /* we use Mail_mime() to construct a valid mail */
        $fecha = fecha_espanol(date('Y-m-j h:i:s'));
        $mime = & new Mail_mime();
        $mime->setTXTBody($fecha . " -- " . $message);
        $body = $mime->get();
        $hdrs = $mime->headers($hdrs);

        /* Put message to queue */
        $log_msj = "";
        if ($mail_queue->put($from, $to, $hdrs, $body)) {
            $log_msj = "[" . $fecha . "] [noticia] [email " . $email . "] Notificacion enviada con exito.";
        } else {
            $log_msj = "[" . $fecha . "] [error] [email " . $email . "] No se pudo enviar la notificacion " . $correo->ErrorInfo;
        }

        //Inicia una transacción
        $this->log->begin();
        //Esto queda pendiente hasta que se llame a commit para guardar
        //ó rollback para cancelar
        $this->log->log($log_msj, Logger :: WARNING);

    }

}
?>
