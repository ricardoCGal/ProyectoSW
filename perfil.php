<?php
    session_start();

    require 'database.php';
    
    // Inicializamos $usuario con un valor predeterminado
    $usuario = null;

    if (isset($_SESSION['user_id'])){
        $records = $conn->prepare('SELECT id, user, password FROM users WHERE id = :id');
        $records->bindParam(':id', $_SESSION['user_id']);
        $records->execute();
        $results = $records->fetch(PDO::FETCH_ASSOC);

        if ($results && count($results) > 0){
            $usuario = $results;

            // Recuperamos las publicaciones realizadas por el usuario
            $query = 'SELECT * FROM publicaciones WHERE usuario = :usuario ORDER BY fecha DESC';
            $statement = $conn->prepare($query);
            $statement->bindParam(':usuario', $usuario['user']);
            
            if ($statement->execute()) {
                $publicaciones = $statement->fetchAll(PDO::FETCH_ASSOC);
            } else {
                die("Error al ejecutar la consulta SQL");
            }
        }
    }
    if (isset($_SESSION['user_id'])) {
        $usuario = $results;
    
        // Obtenemis todas las publicaciones del usuario con la información de likes y dislikes, u las ordenamos de mayor a menor
        $query = 'SELECT p.*, COALESCE(p.likes, 0) AS likes, COALESCE(p.dislikes, 0) AS dislikes
                  FROM publicaciones p
                  WHERE p.usuario = :usuario
                  ORDER BY (p.likes - p.dislikes) DESC, p.fecha DESC';
    
        $statement = $conn->prepare($query);
        $statement->bindParam(':usuario', $usuario['user']);
    
        if ($statement->execute()) {
            $publicaciones = $statement->fetchAll(PDO::FETCH_ASSOC);
        } else {
            die("Error al ejecutar la consulta SQL");
        }
    }
    
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="estilogral.css">
    <script src="Scripts.js"></script>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#" >
                <img src="img/control1.png" alt="Logo" class="d-inline-block align-text-top" id="logo">
            </a>
            <a href="principal.php" id="tit"> El Rincon Gamer</a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav justify-content-center" >
              <li class="nav-item">
                <a class="btn btn-dark" aria-current="page" href="principal.php">Inicio  <svg xmlns="http://www.w3.org/2000/svg" class="log" fill="currentColor" class="bi bi-house-door" viewBox="0 0 16 16">
                    <path d="M8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4.5a.5.5 0 0 0 .5-.5v-4h2v4a.5.5 0 0 0 .5.5H14a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.354 1.146ZM2.5 14V7.707l5.5-5.5 5.5 5.5V14H10v-4a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5v4H2.5Z"/>
                  </svg>
                </a>
              </li>
              <li id="salir" class="nav-item" >
                <a href="logout.php" class="btn btn-dark" onclick="confirmarsalir();">Salir 
                <svg xmlns="http://www.w3.org/2000/svg"  class="log" fill="currentColor" class="bi bi-door-closed" viewBox="0 0 16 16">
                <path d="M3 2a1 1 0 0 1 1-1h8a1 1 0 0 1 1 1v13h1.5a.5.5 0 0 1 0 1h-13a.5.5 0 0 1 0-1H3V2zm1 13h8V2H4v13z"/>
                <path d="M9 9a1 1 0 1 0 2 0 1 1 0 0 0-2 0z"/>
              </svg>
                </a>
              </li>
            </ul>
          </div>
        </div>
    </nav>
    <div class="container">
    <p id="bienvenida">Que tal "<?= $usuario['user']; ?>"</p>
            <br>
            <p id="bienvenida">¡ Estas son tus publicaciones !</p>
            <?php
            // Verificamos si hay publicaciones para mostrar
            if (isset($publicaciones) && is_array($publicaciones) && count($publicaciones) > 0) {
                foreach ($publicaciones as $publicacion):
            ?>
            
                    <div class="class-elem2">
                        <!-- Aquí vamos a mostrar la información de la publicación con los estilos adecuados -->
                        <div id="container" class="visualizacion<?= $publicacion['tipo_visualizacion']; ?>">
                            <header id="enc">
                                <h1 class="pub-tit"><?= $publicacion['titulo']; ?></h1>
                            </header>
                            <figure id="imgs">
                                <img class="imag3" src="<?= $publicacion['imagen']; ?>" alt="<?= $publicacion['titulo']; ?>">
                            </figure>
                            <div id="txt" class="texto">
                                <p><?= $publicacion['contenido']; ?></p>
                            </div>
                            <div class="info-publicacion">
                                <p>Publicado por: <?= $publicacion['usuario']; ?> el <?= $publicacion['fecha']; ?></p>
                                <button onclick="likeDislike('like', <?= $publicacion['id']; ?>)"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-hand-thumbs-up" viewBox="0 0 16 16">
                                    <path d="M8.864.046C7.908-.193 7.02.53 6.956 1.466c-.072 1.051-.23 2.016-.428 2.59-.125.36-.479 1.013-1.04 1.639-.557.623-1.282 1.178-2.131 1.41C2.685 7.288 2 7.87 2 8.72v4.001c0 .845.682 1.464 1.448 1.545 1.07.114 1.564.415 2.068.723l.048.03c.272.165.578.348.97.484.397.136.861.217 1.466.217h3.5c.937 0 1.599-.477 1.934-1.064a1.86 1.86 0 0 0 .254-.912c0-.152-.023-.312-.077-.464.201-.263.38-.578.488-.901.11-.33.172-.762.004-1.149.069-.13.12-.269.159-.403.077-.27.113-.568.113-.857 0-.288-.036-.585-.113-.856a2.144 2.144 0 0 0-.138-.362 1.9 1.9 0 0 0 .234-1.734c-.206-.592-.682-1.1-1.2-1.272-.847-.282-1.803-.276-2.516-.211a9.84 9.84 0 0 0-.443.05 9.365 9.365 0 0 0-.062-4.509A1.38 1.38 0 0 0 9.125.111L8.864.046zM11.5 14.721H8c-.51 0-.863-.069-1.14-.164-.281-.097-.506-.228-.776-.393l-.04-.024c-.555-.339-1.198-.731-2.49-.868-.333-.036-.554-.29-.554-.55V8.72c0-.254.226-.543.62-.65 1.095-.3 1.977-.996 2.614-1.708.635-.71 1.064-1.475 1.238-1.978.243-.7.407-1.768.482-2.85.025-.362.36-.594.667-.518l.262.066c.16.04.258.143.288.255a8.34 8.34 0 0 1-.145 4.725.5.5 0 0 0 .595.644l.003-.001.014-.003.058-.014a8.908 8.908 0 0 1 1.036-.157c.663-.06 1.457-.054 2.11.164.175.058.45.3.57.65.107.308.087.67-.266 1.022l-.353.353.353.354c.043.043.105.141.154.315.048.167.075.37.075.581 0 .212-.027.414-.075.582-.05.174-.111.272-.154.315l-.353.353.353.354c.047.047.109.177.005.488a2.224 2.224 0 0 1-.505.805l-.353.353.353.354c.006.005.041.05.041.17a.866.866 0 0 1-.121.416c-.165.288-.503.56-1.066.56z"/>
                                    </svg>
                                </button>
                                <span id="likesCount"><?= $publicacion['likes']; ?></span>
                                <button onclick="likeDislike('dislike', <?= $publicacion['id']; ?>)"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-hand-thumbs-down" viewBox="0 0 16 16">
                                    <path d="M8.864 15.674c-.956.24-1.843-.484-1.908-1.42-.072-1.05-.23-2.015-.428-2.59-.125-.36-.479-1.012-1.04-1.638-.557-.624-1.282-1.179-2.131-1.41C2.685 8.432 2 7.85 2 7V3c0-.845.682-1.464 1.448-1.546 1.07-.113 1.564-.415 2.068-.723l.048-.029c.272-.166.578-.349.97-.484C6.931.08 7.395 0 8 0h3.5c.937 0 1.599.478 1.934 1.064.164.287.254.607.254.913 0 .152-.023.312-.077.464.201.262.38.577.488.9.11.33.172.762.004 1.15.069.13.12.268.159.403.077.27.113.567.113.856 0 .289-.036.586-.113.856-.035.12-.08.244-.138.363.394.571.418 1.2.234 1.733-.206.592-.682 1.1-1.2 1.272-.847.283-1.803.276-2.516.211a9.877 9.877 0 0 1-.443-.05 9.364 9.364 0 0 1-.062 4.51c-.138.508-.55.848-1.012.964l-.261.065zM11.5 1H8c-.51 0-.863.068-1.14.163-.281.097-.506.229-.776.393l-.04.025c-.555.338-1.198.73-2.49.868-.333.035-.554.29-.554.55V7c0 .255.226.543.62.65 1.095.3 1.977.997 2.614 1.709.635.71 1.064 1.475 1.238 1.977.243.7.407 1.768.482 2.85.025.362.36.595.667.518l.262-.065c.16-.04.258-.144.288-.255a8.34 8.34 0 0 0-.145-4.726.5.5 0 0 1 .595-.643h.003l.014.004.058.013a8.912 8.912 0 0 0 1.036.157c.663.06 1.457.054 2.11-.163.175-.059.45-.301.57-.651.107-.308.087-.67-.266-1.021L12.793 7l.353-.354c.043-.042.105-.14.154-.315.048-.167.075-.37.075-.581 0-.211-.027-.414-.075-.581-.05-.174-.111-.273-.154-.315l-.353-.354.353-.354c.047-.047.109-.176.005-.488a2.224 2.224 0 0 0-.505-.804l-.353-.354.353-.354c.006-.005.041-.05.041-.17a.866.866 0 0 0-.121-.415C12.4 1.272 12.063 1 11.5 1z"/>
                                    </svg>
                                </button>
                                <span id="dislikesCount"><?= $publicacion['dislikes']; ?></span>
                            </div>
                        </div>
                    </div>
            <?php
                endforeach;
            } else {
                echo '<p>No hay publicaciones para mostrar, por favor crea una publicacion para vizualizarla en este apartado.</p>';
            }
            ?>
    </div>
    <div class="class-elem3">
        <div class="pie">
        <p>Pagina creada por: Ricardo Cortes Galindo <br>
        Redes: <a href="https://github.com/ricardoCGal"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-github" viewBox="0 0 16 16">
            <path d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27.68 0 1.36.09 2 .27 1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.012 8.012 0 0 0 16 8c0-4.42-3.58-8-8-8z"/>
            </svg></a><br>
        Fecha de creacion: 13 de Noviembre de 2023

        </p>
        </div>
    </div>
</body>
</html>