
function ver(tipo) {
    var titulo = document.getElementById('Titulo').value;
    var imgUrl = document.getElementById('imgUrl').value;
    var contenido = document.getElementById('cont').value;

    var encabezado = document.getElementById('enc');
    var img = document.getElementById('imgP');
    var texto = document.getElementById('txt');

    encabezado.innerHTML = '<h1>' + titulo + '</h1>';
    img.src = imgUrl;
    img.alt = 'Imagen ' + titulo;
    texto.innerHTML = contenido;
    texto.style.color = 'wite';

    var container = document.getElementById('container');
    
            //aplicamos el estilo segun el tipo de visualizacionq eu desee
    container.className = 'visualizacion' + tipo;
}

function mostrarMensaje(mensaje) {
    alert(mensaje);
}
