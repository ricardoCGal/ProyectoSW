function likeDislike(action, postId) {
    // Enviamos  la acción al servidor mediante AJAX
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);
                if (response.error) {
                    // Mostramos un mensaje de error al usuario
                    alert(response.error);
                } else {
                    // Actyalizamos la interfaz del usuario según sea necesario
                    console.log(response);
            
                    // Se actualiza el número de likes y dislikes en la interfaz
                    document.getElementById('likesCount').textContent = response.likes;
                    document.getElementById('dislikesCount').textContent = response.dislikes;
                }
            } else {
                //errores de la solicitud AJAX
                console.error('Error en la solicitud AJAX');
            }
        }
    };

    // Configuramos  la solicitud
    xhr.open('POST', 'likes_dislikes.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    // enviamos los datos
    xhr.send('action=' + action + '&postId=' + postId);
}

