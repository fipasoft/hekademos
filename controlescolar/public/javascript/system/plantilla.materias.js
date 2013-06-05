function init(){
$("frm_agregar").onsubmit = function (){
var b = true;
    $$('.horas').each(function(campo){
        if(campo.value!="" && !esFloat(campo.value)){
            b = false;
            try{
                campo.focus();
                Effect.Shake(campo);

                }catch(e){
                alert('El campo solo acepta numeros');
                }
                return false;
        }
    });
    return b;
}
}

addDOMLoadEvent(init);
