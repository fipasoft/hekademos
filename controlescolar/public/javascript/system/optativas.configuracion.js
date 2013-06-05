var metodos_input = {
	validar: function(elemento){
	if(elemento.value=="" || !esEntero(elemento.value)){
	try{
			elemento.activate();
			elemento.value = '';
			Effect.Shake(elemento);
		}catch(err){
			alert('Solo acepta valores numericos');
		}
		return false;
		}
		return true;
	}
};

var metodos_form = {
	completa: function(elemento){
			if(!frm_validar(elemento.id))
				return false;

			$$('.numerico').each(function (campo){
				if(!campo.validar())
						return false
				});

				return true;

		}

};

function init(){
Element.addMethods('INPUT',metodos_input);
Element.addMethods('FORM',metodos_form);

frm_agregar=$('frm_agregar');
if(frm_agregar){
frm_agregar.onsubmit=function(){
	if(!frm_agregar.completa()){
	return false;
	}
}
}

$$('.numerico').each(function (campo){
campo.onblur=function(){ campo.validar();};
});

cancelar = document.getElementById('cancelar');
	if(cancelar){
	cancelar.onclick = function(){
		document.location.href = $('KUMBIA_PATH').value+'optativas/index/'+$('periodo_id').value;
	}
	}

}



addDOMLoadEvent(init);