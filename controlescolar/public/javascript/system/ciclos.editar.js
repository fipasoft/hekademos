function init(){
    aceptar = $('aceptar');
    if(aceptar){
    aceptar.onclick = function(){
        f = $('frm_editar');
        if(    frm_validar('frm_editar') &&
            valPeriodo($('inicio').value, $('fin').value) &&
            valEjercicio($('inicio').value, $('annio').value)
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
    
    fecha = $('inicio');
    if(fecha){
        fecha.onblur = function(){ valFecha($('inicio')) };
        if($('calendario')){
            Calendar.setup({
                button: 'calendario',
                electric : false,
                inputField : 'inicio',
                ifFormat : '%d/%m/%Y'            
            });
        }
    }
    
    fecha = $('fin');
    if(fecha){
        fecha.onblur = function(){ valFecha($('fin')) };
        if($('calendario2')){
            Calendar.setup({
                button: 'calendario2',
                electric : false,
                inputField : 'fin',
                ifFormat : '%d/%m/%Y'            
            });
        }
    }
}
addDOMLoadEvent(init);