function init(){
	aceptar = $('agregar');
	if(aceptar){
	aceptar.onclick = function(){
		f = $('frm_agregar');
		if(frm_validar_campos(['numero','descripcion'])){
			if(esEntero($('numero').value) && $('disponible').value == '1')
					f.submit();
			else
				Effect.Shake($('numero'));
		}
	}
	}

	cancelar = $('cancelar');
	if(cancelar){
	cancelar.onclick = function(){
		if(confirm('Al cancelar se perderan los cambios hechos en este formulario, desea continuar?')){
			document.location.href = $('path').value+'reglamentos/ver/'+$('reglamento').value;
		}
	}
	}
	
	campo = $('numero');
	if(campo){
		campo.onkeyup = function() {
			if(campo.value != ''){
				if(esEntero(campo.value)){
					new Ajax.Updater('check', '../revisar_numero/', {
						method: 'post',
						onLoading: function(){ $('spinner').show(); $('check').hide(); },
						onComplete: function() { $('spinner').hide(); $('check').show()},
						parameters: {numero : campo.value , reglamento : $('reglamento').value }
					});
				}else{
					campo.value = '';
					$('check').hide();
				}				
			}else{
				$('check').hide();
			}
		}
	}

}


addDOMLoadEvent(init);