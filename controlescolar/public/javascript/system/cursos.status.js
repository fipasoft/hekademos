function init(){

cancelar = $('cancelar');
	if(cancelar){
	cancelar.onclick = function(){
		document.location.href = '../';
	}
	}

aceptar = $('aceptar');
	if(aceptar){
	aceptar.onclick = function(){
		f = $('frm_confirmar');
		if(	frm_validar_campos(['estado_id']) ){
			f.submit();
		}
	}
	}

}

addDOMLoadEvent(init);