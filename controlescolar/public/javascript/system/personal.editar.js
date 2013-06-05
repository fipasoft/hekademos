function init(){
    aceptar = $('aceptar');
    if(aceptar){
    aceptar.onclick = function(){
        f = $('frm_editar');
        if(    frm_validar_campos(['nombre', 'ap', 'am'])
        ){
            f.submit();
        }
    }
    }
    
    cancelar = $('cancelar');
    if(cancelar){
    cancelar.onclick = function(){
        if(confirm('Al cancelar se perderan los cambios hechos en este formulario, desea continuar?')){
            document.location.href = '../';
        }
    }
    }
    
    fecha = $('fnacimiento');
    calendario = $('cal');
    if(fecha){
        fecha.onblur = function(){ valFecha(fecha) };
        calendario.href = 'javascript:;';
        if(calendario){
            Calendar.setup({
                button: calendario.id,
                electric : false,
                inputField : fecha.id,
                ifFormat : '%d/%m/%Y'            
            });
        }
    }
    
    b1 = $('iCambiar');
    if(b1){
        b1.onclick = function(){
            $('cambiar').value = true;
            Effect.Appear('examinar');
            Effect.BlindUp('imagen');
        }
    }
    
    b2 = $('iCancelar');
    if(b2){
        b2.onclick = function(){
            $('cambiar').value = false;
            Effect.BlindDown('imagen');
            Effect.DropOut('examinar');
        }
    }
}
addDOMLoadEvent(init);