
function init(){
	aceptar = $('aceptar');
	if(aceptar){
	aceptar.onclick = function(){
		f = $('frm_editar');
		if(	frm_validar('frm_editar') ){
		if(ValidateDateTime('inicio') && ValidateDateTime('fin')){
					if(!validaFecha($('inicio').value,$('fin').value)){
						alert("La fecha de inicio tiene que ser menor a la fecha final.");
						return false;
					}
					}else
						return false;

			f.submit();
		}
	}
	}

	cancelar = $('cancelar');
	if(cancelar){
	cancelar.onclick = function(){
		if(confirm('Al cancelar se perderan los cambios hechos en este formulario, desea continuar?')){
			document.location.href = $('KUMBIA_PATH').value+'bloques/index/'+$('periodo_id').value;
		}
	}
	}

	fecha = $('inicio');
	if(fecha){
		if($('calendario')){
			Calendar.setup({
				button: 'calendario',
				electric : false,
				inputField : 'inicio',
				showsTime: true,
				timeFormat: '24',
				ifFormat : '%d/%m/%Y %H:%M'
			});
		}
	}

	fecha = $('fin');
	if(fecha){
		if($('calendario2')){
			Calendar.setup({
				button: 'calendario2',
				electric : false,
				inputField : 'fin',
				showsTime: true,
				timeFormat: '24',
				ifFormat : '%d/%m/%Y %H:%M'
			});
		}
	}
}

function validaFecha(fecha, fecha2){
fss1=fecha.split(" ");
fs1=fss1[0].split("/");
tt=fss1[1].split(":");
f1=new Date(fs1[2],fs1[1],fs1[0],tt[0],tt[1],0);

fss2=fecha2.split(" ");
fs2=fss2[0].split("/");
tt=fss2[1].split(":");
f2=new Date(fs2[2],fs2[1],fs2[0],tt[0],tt[1],0);

if(f1 < f2)
return true;
else
return false;

}

addDOMLoadEvent(init);