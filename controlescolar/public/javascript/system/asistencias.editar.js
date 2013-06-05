var tdMethods = {
	intercambiar: function(element){
		element = $(element);
		var id = element.id.split('_');
		id = id[1];
		var ast = 'ast_' + id;
		element.onclick = function(){
			$(ast).disabled = false;
			if(element.hasClassName('AST')){
				element.removeClassName('AST');
				element.addClassName('FTA');
				$(ast).value = 0;
			}else{
				element.removeClassName('FTA');
				element.addClassName('AST');
				$(ast).value = 1;
			}
		};
		return element;
	},
	desactivar: function(element){
		element = $(element);
		element.onclick = null;
		return element;
	}
};

function init(){
	cancelar = $('cancelar');
	if(cancelar){
	cancelar.onclick = function(){
		if(confirm('Al cancelar se perderan los cambios hechos en este formulario, desea continuar?')){
			document.location.href = './ver/' + $F('cursos_id');
		}
	}
	}
	
	Element.addMethods('TD', tdMethods);
	$$('.click').invoke('intercambiar');
	
	frm = $('frm_editar');
	if(frm){
		frm.onsubmit = function(){
			Effect.DropOut('botones');
			enEspera();
			$$('.click').invoke('desactivar');
		}
	}
}
addDOMLoadEvent(init);