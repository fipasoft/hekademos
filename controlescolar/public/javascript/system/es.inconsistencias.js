 function catcalc(cal) {
 var date = cal.date;
 var time = date.getTime();
 var date2 = new Date(time);
 window.location=$("kumbia_path").value+"es/inconsistencias/"+date2.print("%Y-%m-%d");
 }



function opciones(){
tipo=$('tipo');
div=$("opciones_alumno");
switch(tipo.value){
	case "A":
			div.show();
		break;
	default:
			div.hide();
		break;
}
}

function init(){
	frm_search=$('frm_search');
	if(frm_search){
	frm_search.onsubmit = function(){
				if($('tipo').value=="A")
					if(!frm_validar_campos(['grado','letra','turno','oferta_id']))
					return false;

				}

	}

	reset = $('reset');
	if(reset){
	reset.onclick = function(){
		Effect.DropOut($('search'));
		frm_reset('frm_search');
	}
	}

		tipo=$('tipo');
		if(tipo){
		tipo.onchange=function(){
		opciones();
		}
		opciones();
		}

	fecha = $('fecha');
	if(fecha){
	var f=$("date");
	fch=f.value.split("-");

 	date=new Date(fch[0],fch[1]-1,fch[2]);
		//fecha.onblur = function(){ valFecha($('inicio')) };
			Calendar.setup({
				button: 'fecha',
				electric : false,
				 onUpdate : catcalc,
				 date: date,
				ifFormat : '%d/%m/%Y'
			});




	}

		c = $('cicloBtn');
	if(c){
		c.onclick = function(){
			if($('cicloActual').style.display == 'none'){
				$('cicloSel').style.display = 'none';
				Effect.Appear('cicloActual');
			}else{
				$('cicloActual').style.display = 'none';
				Effect.Appear('cicloSel');
			}
		}
	}
	cS = $('cicloSelect');
	if(cS){
		cS.onchange = function (){$('frm_ciclo').submit();}
	}


}

addDOMLoadEvent(init);