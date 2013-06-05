function init(){
	editar = $('editar');
	if(editar){
	editar.onclick = function(){
		f = $('frm_editar');
		if(	frm_validar_campos(['nombre'])
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

}
addDOMLoadEvent(init);