function init(){
	cancelar=$('cancelar');
	if(cancelar){
		cancelar.onclick=function (){
			if(confirm('Al cancelar se perderan los cambios hechos en este formulario, desea continuar?')){
			document.location.href = './../';
			}
		}
	}

	cancelar_c=$('cancelar_c');
	if(cancelar_c){
		cancelar_c.onclick=function (){
			if(confirm('Al cancelar se perderan los cambios hechos en este formulario, desea continuar?')){
			document.location.href = './';
			}
		}
	}

	aceptar=$('aceptar');
	if(aceptar){
		aceptar.onclick=function (){
			if(frm_validar_campos(['grupo'])){
			frm=$('frm_ubicar');
			if(frm)frm.submit();
			}

		}
	}
}

addDOMLoadEvent(init);