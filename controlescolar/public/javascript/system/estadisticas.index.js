function init(){
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
