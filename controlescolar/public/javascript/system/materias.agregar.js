function init(){
    aceptar = $('aceptar');
    if(aceptar){
    aceptar.onclick = function(){
        f = $('frm_agregar');
        oferta=$('oferta');
        if(oferta.value==2){
        if(    frm_validar_campos(['clave', 'nombre', 'semestre', 'tipo', 'oferta', 'creditos', 'duracion', 'horas', 'competencia',  'tipo_competencia'])
        ){
            f.submit();
        }
        }else{
        if(    frm_validar_campos(['clave', 'nombre', 'semestre', 'tipo', 'oferta'])
        ){
            f.submit();
        }
        }
    }
    }

    cancelar = $('cancelar');
    if(cancelar){
    cancelar.onclick = function(){
        if(confirm('Al cancelar se perderan los cambios hechos en este formulario, desea continuar?')){
            document.location.href = './';
        }
    }
    }

    campo = $('clave');
    if(campo){
        campo.onkeyup = function() {
            new Ajax.Updater('check', './disponible/', {
               method: 'post',
               onLoading: function(){ $('spinner').show(); $('check').hide(); },
               onComplete: function() { $('spinner').hide(); $('check').show()},
               parameters: {tabla: 'Materias', campo: campo.id, valor: campo.value}
            });
        }
    }

    oferta = $('oferta');
    if(oferta){
        oferta.onchange = function() {
            if(this.value==2){
            $('datos_competencia').show();
            }else{
            $('datos_competencia').hide();
            }
        }
    }

    competencia = $('competencia');
    if(competencia){
        competencia.onchange = function() {
            if(this.value=='gen' || this.value=='esp'){
            new Ajax.Updater('competencia_tipo', '/sp5/competencias/obtenertipos/', {
               method: 'post',
               onLoading: function(){ $('spinner2').show(); $('competencia_tipo').hide(); },
               onComplete: function() { $('spinner2').hide(); $('competencia_tipo').show()},
               parameters: {tipo: this.value}
            });
            }else{
            $('competencia_tipo').hide();
            }

        }
    }
            creditos=$('creditos');
            if(creditos){
            creditos.onblur = function(){
            if(!esEntero(this.value)){
                try{
                    this.activate();
                    Effect.Shake(this.id);
                }catch(e){
                    alert('Este elemento acepta solo valores enteros.');
                }
                this.value = '';

            }
            }
        }

            duracion=$('duracion');
            if(duracion){
            duracion.onblur = function(){
            if(!esEntero(this.value)){
                try{
                    this.activate();
                    Effect.Shake(this.id);
                }catch(e){
                    alert('Este elemento acepta solo valores enteros.');
                }
                this.value = '';

            }
            }
        }

            horas=$('horas');
            if(horas){
            horas.onblur = function(){
            if(!esEntero(this.value)){
                try{
                    this.activate();
                    Effect.Shake(this.id);
                }catch(e){
                    alert('Este elemento acepta solo valores enteros.');
                }
                this.value = '';

            }
            }
        }


    if($('datos_competencia'))
    $('datos_competencia').hide();

}
addDOMLoadEvent(init);