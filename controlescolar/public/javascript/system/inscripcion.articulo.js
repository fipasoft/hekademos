function init(){
	cancelar = $('cancelar');
	if(cancelar){
	cancelar.onclick = function(){
		if(confirm('Al cancelar se perderan los cambios hechos en este formulario, desea continuar?')){
			document.location.href = '../../cursos/';
		}
	}
	}

	cancelar_c = $('cancelar_c');
	if(cancelar_c){
	cancelar_c.onclick = function(){
		if(confirm('Al cancelar se perderan los cambios hechos en este formulario, desea continuar?')){
			document.location.href = '../cursos/';
		}
	}
	}

}
addDOMLoadEvent(init);