function ver(tipo, publicacionId) {
    console.log('Función ver ejecutada con tipo:', tipo, 'y publicacionId:', publicacionId);

    var titulo = document.getElementById('Titulo').value;
    var imgUrl = document.getElementById('imgUrl').value;
    var contenido = document.getElementById('cont').value;

    console.log('Valores de titulo, imgUrl, y contenido:', titulo, imgUrl, contenido);

    var encabezado = document.getElementById('enc');
    var img = document.getElementById('imgP');
    var texto = document.getElementById('txt');

    console.log('Elementos encontrados:', encabezado, img, texto);

    if (encabezado && img && texto) {
        encabezado.innerHTML = '<h1>' + titulo + '</h1>';
        img.src = imgUrl;
        img.alt = 'Imagen ' + titulo;
        texto.innerHTML = contenido;
        texto.style.color = 'white';

        var container = document.getElementById('container');
        container.className = 'visualizacion' + tipo;

        console.log('Contenido actualizado con éxito.');
    } else {
        console.error('No se encontraron algunos elementos necesarios.');
    }
}

function mostrarMensaje(mensaje) {
    alert(mensaje);
}


