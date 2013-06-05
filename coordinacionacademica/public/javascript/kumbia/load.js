
/** KumbiaForms - PHP Rapid Development Framework *****************************
*
* Copyright (C) 2005-2007 Andr�s Felipe Guti�rrez (andresfelipe at vagoogle.net)
*
* Este framework es software libre; puedes redistribuirlo y/o modificarlo
* bajo los terminos de la licencia p�blica general GNU tal y como fue publicada
* por la Fundaci�n del Software Libre; desde la versi�n 2.1 o cualquier
* versi�n superior.
* Este framework es distribuido con la esperanza de ser util pero SIN NINGUN
* TIPO DE GARANTIA; sin dejar atr�s su LADO MERCANTIL o PARA FAVORECER ALGUN
* FIN EN PARTICULAR. Lee la licencia publica general para m�s detalles.
* Debes recibir una copia de la Licencia P�blica General GNU junto con este
* framework, si no es asi, escribe a Fundaci�n del Software Libre Inc.,
* 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*****************************************************************************/

function entero(x){ return parseInt(x); }
function integer(x){ return parseInt(x); }

function enable_browse(obj, action){
    var str = window.location.toString()
    window.location = $Kumbia.path+action+"/browse/"
}

//Handling Errors

if(document.all){
    onerror=handleErr

}
var txt=""
function handleErr(msg,url,l) {
    if(document.all){
        txt="KumbiaError: There was an error on this Application.\n\n"
        txt+="Error: " + msg + "\n"
        txt+="URL: " + url + "\n"
        txt+="Line: " + l + "\n\n"
        txt+="Please inform this error to your Software Provider.\n\n"
        alert(txt)
    }
    return true
}


