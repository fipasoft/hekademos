function init(){

    frm_agregar=$('frm_agregar');
    if(frm_agregar){
        frm_agregar.onsubmit=function (){
                if(!frm_validar_radiogroup($$('.chk_alu')) )
            {
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