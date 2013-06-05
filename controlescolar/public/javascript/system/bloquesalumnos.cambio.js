function init(){


    frm_cambio = document.getElementById('frm_cambio');
    if(frm_cambio){
    frm_cambio.onsubmit= function(){
        if(!frm_validar('frm_cambio')){
             return false;
        }
        };
    }

    cancelar = document.getElementById('cancelar');
    if(cancelar){
    cancelar.onclick = function(){
        document.location.href = $("KUMBIA_PATH").value+'bloquesalumnos/index/'+$("bloque_id").value;
    }
    }
}
addDOMLoadEvent(init);