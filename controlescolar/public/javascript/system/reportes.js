var elemento = {
	activar: function(campo){
		campo = $(campo);
		$$('#' + campo.id + ' input, #' + campo.id + ' select').each(function( e ){
			e.enable();
			e.removeAttribute('_counted');
		});
		$$('#' + campo.id + ' input.switch').each(function( e ){
			e.checked = true;
			e.removeAttribute('_counted');
		});
		campo.show();
	},
	
	desactivar: function(id){
		$$('#' + id + ' input, #' + id + ' select').each( function(campo){
			if(campo.type == 'text'){
				campo.clear();
			}else if(campo.type == 'checkbox'){
				campo.checked = false;
				var trId = campo.id.sub('chk', 'tr');
				trId = $(trId);
				if( trId ){
					trId.removeClassName('selected');
				}
			}else{
				campo.selectedIndex = 0;
			}
			campo.disable();
			campo.removeAttribute('_counted');
		});
		$(id).hide();
	},
	
	fecha: function( e ){
		$$( (e ? '#' + e + ' ' : '') + '.fecha').each( function(campo){
			campo.onblur = function(){ valFecha(campo) };
			boton = $('btn_' + campo.id);
			if( boton ){
				boton.href = 'javascript:;';
				Calendar.setup({
					button: boton.id,
					electric : false,
					inputField : campo.id,
					ifFormat : '%d/%m/%Y'			
				});
			}else{
				Calendar.setup({
					electric : false,
					inputField : campo.id,
					ifFormat : '%d/%m/%Y'			
				});
			}
			campo.removeAttribute('_counted');
		});
	},
		
	moneda: function(e){
		$$( (e ? '#' + e + ' ' : '') + '.moneda' ).each( function(campo){
			campo.onblur = function(){
				validar_cantidad(campo);
			}
			campo.removeAttribute('_counted');
		});
	}
};