/* Codigo para cargar el segundo combo box con las cajas de una sucursal
 * Jorge Luis Maureira A.
 * Morrigan SPA.

 */

addEvent(window, 'load', inicializarEventos, false);

function inicializarEventos() {
    var select1 = document.getElementById('sucursal');
    addEvent(select1, 'change', mostrar, false);
}
var conexion1;
function mostrar(e) {
    var codigo = document.getElementById('sucursal').value;
    if (codigo !== 0) {
        conexion1 = crearXMLHttpRequest();
        conexion1.onreadystatechange = procesarEventos;
        conexion1.open('GET', '/index.php/AJAX/Controlador_ajax_cajero/Mostrar_cajas?sucursal=' + codigo, true);
        conexion1.send(null);
    } else {
        var select2 = document.getElementById('cajas');
        select2.options.length = 0;
    }
}
function procesarEventos() {
    if (conexion1.readyState === 4) {
        var d = document.getElementById('esperando');
        d.innerHTML = '';
        var xml = conexion1.responseXML;
        var nombre = xml.getElementsByTagName('caja'); //Obtengo el Nombre
        var ide = xml.getElementsByTagName('id'); // Optengo el ID
        var select2 = document.getElementById('cajas');
        select2.options.length = 0;
        for (f = 0; f < nombre.length; f++)
        {
            var op = document.createElement('option'); // Creo el elemento
            //    op.setAttribute("value",pals[f]);
            op.value = ide[f].firstChild.nodeValue; //Obtengo los datos del XML id
            op.text = nombre[f].firstChild.nodeValue; //Obtengo los datos del XML pals
            //x[i].getElementsByTagName("TITLE")[0].childNodes[0].nodeValue
            // var texto=document.createTextNode(nombre[f].firstChild.nodeValue);
            //op.appendChild(texto);

            select2.appendChild(op);
        }
    } else
    {
        var d = document.getElementById('esperando');
        // d.innerHTML = '<img src="../cargando.gif">';
    }
}

function addEvent(elemento, nomevento, funcion, captura)
{
    if (elemento.attachEvent)
    {
        elemento.attachEvent('on' + nomevento, funcion);
        return true;
    } else
    if (elemento.addEventListener)
    {
        elemento.addEventListener(nomevento, funcion, captura);
        return true;
    } else
        return false;
}

function crearXMLHttpRequest()
{
    var xmlHttp = null;
    if (window.ActiveXObject)
        xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
    else
    if (window.XMLHttpRequest)
        xmlHttp = new XMLHttpRequest();
    return xmlHttp;
}