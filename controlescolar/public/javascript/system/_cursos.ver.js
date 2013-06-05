function sw_contenido(a){
	var id = $(a).id;
	var d = id.split('-');
	var padre = d[1];
	if(d.length > 2){
		d = d[1] + '-' + d[2];
	}else{
		d = d[1];
	}

	if($(d) && $(d).style.display == 'none'){
		$$('div.contenedor').each(function(div){
				var id = $(div).id;
				if(id != d){
					Effect.DropOut(id, { queue: 'end' });
					$('btn-' + id).className = '';
					$$('.op-' + id).invoke('hide');
				}
		});
		Effect.SlideDown($(d), { queue: 'end' });
		$$('.op-' + padre).invoke('show');
		$('btn-' + padre).className = 'activo';
	}
}

function init(){
	$$('#selector a','.op-asistencias a','.op-calificaciones a').each(function(a){
		$(a).href = 'javascript:;';		
		$(a).onclick = function(){
			sw_contenido(a);	
		}
	});
}
addDOMLoadEvent(init);