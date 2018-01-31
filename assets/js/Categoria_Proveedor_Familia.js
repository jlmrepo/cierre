/* 
 
 La idea de este Archivo es
 Cargar los proveedores por su categoria.
 
 Al accionarse el evento de la categoria
 llena el proveedor y la familia
 
 pero si el proveedor es cambiado la familia tambien tiene que reaccionar
 al evento.
 
 
 */



addEvent(window, 'load', inicializarEventos, false);

function inicializarEventos() {
    var select1 = document.getElementById('categoria');
    addEvent(select1, 'change', mostrar, false);
    var carga_familia = document.getElementById('proveedores');
    addEvent(carga_familia, 'change', mostrar_familia, false);
}
var conexion1;
function mostrar(e) {
    var codigo = document.getElementById('categoria').value;
    if (codigo !== 0) {
        conexion1 = crearXMLHttpRequest();
        conexion1.onreadystatechange = procesarEventos;
        conexion1.open('GET', '/index.php/AJAX/Controlador_ajax_proveedor/Mostrar?variable=' + codigo, true);
        conexion1.send(null);
    } else {
        var select2 = document.getElementById('proveedor');
        select2.options.length = 0;
    }
}
var conexion2;
function mostrar_familia(e) {
    var codigo = document.getElementById('proveedores').value;
    if (codigo !== 0) {
        conexion2 = crearXMLHttpRequest();
        conexion2.onreadystatechange = procesarFamilia;
        conexion2.open('GET', '/index.php/AJAX/Controlador_ajax_familia/Mostrar?variable=' + codigo, true);
        conexion2.send(null);
    } else {
        var select_familia = document.getElementById('familias');
        select_familia.options.length = 0;
    }
}
function procesarFamilia() {
    if (conexion2.readyState === 4) {
        var d = document.getElementById('esperando2');
        d.innerHTML = '';
        var xml = conexion2.responseXML;
        var nombre = xml.getElementsByTagName('familia'); //Obtengo el Nombre
        var ide = xml.getElementsByTagName('id'); // Optengo el ID
        var select_familia = document.getElementById('familias');
        select_familia.options.length = 0;
        for (f = 0; f < nombre.length; f++)
        {
            var op = document.createElement('option'); // Creo el elemento
            op.value = ide[f].firstChild.nodeValue; //Obtengo los datos del XML id
            op.text = nombre[f].firstChild.nodeValue; //Obtengo los datos del XML pals

            select_familia.appendChild(op);

        }
       
    } else
    {
        var d = document.getElementById('esperando2');
        // d.innerHTML = '<img src="../cargando.gif">';
    }
}



function procesarEventos() {
    if (conexion1.readyState === 4) {
        var d = document.getElementById('esperando');
        d.innerHTML = '';
        var xml = conexion1.responseXML;
        var nombre = xml.getElementsByTagName('proveedor'); //Obtengo el Nombre
        var ide = xml.getElementsByTagName('id'); // Optengo el ID
        var select2 = document.getElementById('proveedores');
        select2.options.length = 0;
        //Test
  //      var select3 = document.getElementById('familias');
    //    select3.options.length = 0;
        //

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
            /*
            var op2 = document.createElement('option');
            var datoss = document.getElementById('proveedores').value;

            op2.value = "1";
            op2.text = datoss;
            select3.appendChild(op2);
*/

      
        }
         mostrar_familia();
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