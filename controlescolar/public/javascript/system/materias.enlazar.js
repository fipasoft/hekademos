function init(){
	aceptar = $('aceptar');
	if(aceptar){
	aceptar.onclick = function(){
		f = $('frm_enlazar');
		f.submit();
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
	
	mas = $('agregar');
	if(mas){
		mas.href = 'javascript:;';
		mas.onclick = function(){
			new Ajax.Updater('materias', '../series/', { 
				   method: 'post',
				   onLoading: function(){ $('spinner').show(); $('agregar').hide(); },
				   onComplete: function() { $('spinner').hide(); $('agregar').show()},
				   parameters: {id: $('id').value},
				   insertion: Insertion.Bottom
				});
		}
	}
}
addDOMLoadEvent(init);