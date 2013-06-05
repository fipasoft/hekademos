function init(){
	aceptar = document.getElementById('aceptar');
	if(aceptar){
	aceptar.onclick = function(){
		if(frm_validar('frm_estado')){
		f = document.getElementById('frm_estado');
		f.submit();
		}
	}
	}
	
	cancelar = document.getElementById('cancelar');
	if(cancelar){
	cancelar.onclick = function(){
		document.location.href = '../';
	}
	}	
}
addDOMLoadEvent(init);