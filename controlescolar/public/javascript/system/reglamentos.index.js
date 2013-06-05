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
}
addDOMLoadEvent(init);