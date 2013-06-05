function init(){
cancelar=$('cancelar');
if(cancelar){
cancelar.onclick=function(){

    if(confirm('Al cancelar se perderan los cambios hechos , desea continuar?')){
                        document.location.href = 'eliminar/'+$('cursos_id').value;
        }
                }
}
}


addDOMLoadEvent(init);