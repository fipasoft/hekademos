var host_gbl="/hekademos/escolar/";

function trim(cadena)
{
    for(i=0; i<cadena.length; )
    {
        if(cadena.charAt(i)==" ")
            cadena=cadena.substring(i+1, cadena.length);
        else
            break;
    }

    for(i=cadena.length-1; i>=0; i=cadena.length-1)
    {
        if(cadena.charAt(i)==" ")
            cadena=cadena.substring(0,i);
        else
            break;
    }

    return cadena;
}


function onlytext(box){

    var ValidChars = "0123456789abcdefghijklmn�opqrstvuwxyz ";
    var Char;
    for (i = 0; i < box.value.length; i++)
    {
       Char = box.value.charAt(i);
       Char=new String(Char);

       if (ValidChars.indexOf(Char.toLowerCase()) == -1)
        {
          box.value=box.value.replace(Char,'');
       }
    }



}