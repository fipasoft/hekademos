function init(){
    cancelar = document.getElementById('cancelar');
    if(cancelar){
    cancelar.onclick = function(){
    document.location.href = $('KUMBIA_PATH').value+'amonestaciones/';
    }
    }
}
addDOMLoadEvent(init);