function init(){
	aceptar = $('aceptar');
	if(aceptar){
	aceptar.onclick = function(){
		f = $('frm_editar');
		if(	frm_validar_campos(['nombre', 'ap', 'am'])){
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
	
	fecha = $('fnacimiento');
	calendario = $('cal');
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
	
	b1 = $('iCambiar');
	if(b1){
		b1.onclick = function(){
			$('cambiar').value = true;
			Effect.Appear('examinar');
			Effect.BlindUp('imagen');
		}
	}
	
	b2 = $('iCancelar');
	if(b2){
		b2.onclick = function(){
			$('cambiar').value = false;
			Effect.BlindDown('imagen');
			Effect.DropOut('examinar');
		}
	}
	
	var m = 0;
	$$('input.codigo').each( function(campo){
		m++;
		campo.n = m;
		campo.onkeyup = function() {
			var n = campo.n;
			new Ajax.Updater('check' + n, '../../alumnos/info/', { 
			   method: 'post',
			   onLoading: function(){ $('spinner' + n).show(); $('check' + n).hide(); },
			   onComplete: function() { $('spinner' + n).hide(); $('check' + n).show()},
			   parameters: {tabla: 'Alumnos', campo: campo.className, valor: campo.value}
			});
		}
	});
	
	mas = $('bMas');
	if(mas){
		var i = $('i').value;
		mas.href = 'javascript:;';
		mas.onclick = function(){
			i++;
			var ht = '<label>C&oacute;digo' + 
						'<br /> ' +
						'<input type="text" name="alumnos_id[]" id="alumnos_id' + i +  '" ' + 
						       'size="10" maxlength="12" class="codigo"/>' +
					 '</label>' +
					 '<img id="spinner' + i +  '" src="../../public/img/sp5/spinner.gif" style="display:none"/>' +
					 '<br />' +
					 '<div id="check' + i + '" class="check"></div>' +
					 '<div id="codigos' + (i + 1) + '" class="codigos"></div>';
			$('codigos' + i).update(ht);
			var campo2 = $('alumnos_id' + i);
			campo2.n = i;
			campo2.onkeyup = function() {
				var n = campo2.n;
				ajax = new Ajax.Updater('check' + n, '../../alumnos/info/', { 
				   method: 'post',
				   onLoading: function(){ $('spinner' + n).show(); $('check' + n).hide(); },
				   onComplete: function() { $('spinner' + n).hide(); $('check' + n).show()},
				   parameters: {tabla: 'Alumnos', campo: campo2.className, valor: campo2.value}
				});
			}
		}
	}

}
addDOMLoadEvent(init);