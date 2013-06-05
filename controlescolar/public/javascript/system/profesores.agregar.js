function init(){
	agregar = $('agregar');
	if(agregar){
	agregar.onclick = function(){
		f = $('frm_agregar');
		if(	frm_validar_campos(['codigo', 'nombre', 'ap', 'am'])
		){
			f.submit();
		}
	}
	}
	
	cancelar = $('cancelar');
	if(cancelar){
	cancelar.onclick = function(){
		if(confirm('Al cancelar se perderan los cambios hechos en este formulario, desea continuar?')){
			document.location.href = './';
		}
	}
	}
	
	campo = $('codigo');
	if(campo){
		campo.onkeyup = function() {
			new Ajax.Updater('check', './disponible/', { 
			   method: 'post',
			   onLoading: function(){ $('spinner').show(); $('check').hide(); },
			   onComplete: function() { $('spinner').hide(); $('check').show()},
			   parameters: {tabla: 'Profesores', campo: campo.id, valor: campo.value}
			});
		}
	}
	
	fecha = $('fnacimiento');
	calendario = $('calendare');
	if(fecha){
		fecha.onblur = function(){ valFecha(fecha) };
		calendario.href = 'javascript:;';
		if(calendario){
			Calendar.setup({
				button: calendario.id,
				electric : false,
				inputField : fecha.id,
				ifFormat : '%d/%m/%Y'			
			});
		}
	}	
}
addDOMLoadEvent(init);