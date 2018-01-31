addEvent(window, 'load', inicializarEventos, false);

function inicializarEventos() {
    var select1 = document.getElementById('categoria');
    addEvent(select1, 'change', mostrar, false);
    var carga_familia = document.getElementById('proveedores');
    addEvent(carga_familia, 'change', mostrar_familia, false);
    var carga_subfamilia = document.getElementById('familias');
    addEvent(carga_subfamilia, 'change', mostrar_SubFamilia, false);
    var precio_p = document.getElementById('tipo');
    addEvent(precio_p, 'change', precio, false);
  
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
function procesarEventos() {
    if (conexion1.readyState === 4) {
        var d = document.getElementById('esperando');
        d.innerHTML = '';
        var xml = conexion1.responseXML;
        var nombre = xml.getElementsByTagName('proveedor'); //Obtengo el Nombre
        var ide = xml.getElementsByTagName('id'); // Optengo el ID
        var select2 = document.getElementById('proveedores');
        select2.options.length = 0;

        for (f = 0; f < nombre.length; f++)
        {
            var op = document.createElement('option'); // Creo el elemento
            op.value = ide[f].firstChild.nodeValue; //Obtengo los datos del XML id
            op.text = nombre[f].firstChild.nodeValue; //Obtengo los datos del XML pals
            select2.appendChild(op);

        }
        mostrar_familia();

    } else
    {
        var d = document.getElementById('esperando');
        // d.innerHTML = '<img src="../cargando.gif">';
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
        mostrar_SubFamilia();

    } else
    {
        var d = document.getElementById('esperando2');
        // d.innerHTML = '<img src="../cargando.gif">';
    }
}
var conexion3;
function mostrar_SubFamilia(e) {
    var codigo = document.getElementById('familias').value;
    if (codigo !== 0) {
        conexion3 = crearXMLHttpRequest();
        conexion3.onreadystatechange = procesar_SubFamilia;
        conexion3.open('GET', '/index.php/AJAX/Controlador_ajax_subfamilia/Mostrar?variable=' + codigo, true);
        conexion3.send(null);
    } else {
        var select_familia = document.getElementById('familias');
        select_familia.options.length = 0;
    }
}
function procesar_SubFamilia() {
    if (conexion3.readyState === 4) {
        var d = document.getElementById('esperando3');
        d.innerHTML = '';
        var xml = conexion3.responseXML;
        var nombre = xml.getElementsByTagName('subfamilia'); //Obtengo el Nombre
        var ide = xml.getElementsByTagName('id'); // Optengo el ID
        var select_familia = document.getElementById('subfamilias');
        select_familia.options.length = 0;
        for (f = 0; f < nombre.length; f++)
        {
            var op = document.createElement('option'); // Creo el elemento
            op.value = ide[f].firstChild.nodeValue; //Obtengo los datos del XML id
            op.text = nombre[f].firstChild.nodeValue; //Obtengo los datos del XML pals

            select_familia.appendChild(op);

        }
  codigo_producto();
    } else
    {
        var d = document.getElementById('esperando3');
        // d.innerHTML = '<img src="../cargando.gif">';
    }
}
function codigo_producto() {

      var c1 = document.getElementById('categoria');
        var t1 = c1.options[c1.selectedIndex].text;//esto es lo que ve el usuario 
        var c2 = document.getElementById('proveedores');
        var t2 = c2.options[c2.selectedIndex].text;//esto es lo que ve el usuario 
        var c3 = document.getElementById('familias');
        var t3 = c3.options[c3.selectedIndex].text;//esto es lo que ve el usuario
        var c4 = document.getElementById('subfamilias');
        var t4 = c4.options[c4.selectedIndex].text;//esto es lo que ve el usuario 
 var c5 = document.getElementById('nombre');
        var t5 = c5.value;//esto es lo que ve el usuario 

        $('[name="codigo"]').val("#" + t1.charAt(0) + t2.charAt(0) + t3.charAt(0) + t4.charAt(0)+t5);

}

function precio() {
     var tipo = document.getElementById('tipo').value;
    if(tipo==2){
        document.getElementById('precio').style.display='none';
         $('[name="precio"]').val(null);
    }else{
        document.getElementById('precio').style.display='block';
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