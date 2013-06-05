function init(){


	cancelar = document.getElementById('cancelar_c');
	if(cancelar){
	cancelar.onclick = function(){
		document.location.href = '../../../accesos';
	}
	}
}
addDOMLoadEvent(init);