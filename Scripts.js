/*window.onload = function () {       //Bienvenida al sitio
    var bienvenida = document.getElementById('bienvenida');
    bienvenida.style.display = 'block';
    setTimeout(function () {
        bienvenida.style.display = 'none';
    }, 3000);
}*/

function confirmarsalir(){          //Confirmacion de cerrar sesion
    if(confirm('¿Desea salir y cerrar sesion?')){
        window.location.href = 'logout.php'
    }else{
        event.preventDefault();
    }
}

function mostrarMensaje(mensaje) {
    alert(mensaje);
}