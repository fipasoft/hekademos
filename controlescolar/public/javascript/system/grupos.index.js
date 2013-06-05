function init(){
    a = $('aSearch');
    if(a){
    a.href = 'javascript:;';
    a.onclick = function(){
                div_sw('search');
                }
    }

    reset = $('reset');
    if(reset){
    reset.onclick = function(){
        Effect.DropOut($('search'));
        frm_reset('frm_search');
    }
    }

    if($('cicloBtn')){
    $('cicloBtn').onclick = function(){
        if($('cicloActual').style.display == 'none'){
            $('cicloSel').style.display = 'none';
            Effect.Appear('cicloActual');
        }else{
            $('cicloActual').style.display = 'none';
            Effect.Appear('cicloSel');
        }
    }
    }
    if($('cicloSelect')){
    $('cicloSelect').onchange = function (){$('frm_ciclo').submit();}
    }
}
addDOMLoadEvent(init);