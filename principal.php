<?php
     session_start();

     require 'database.php';
     
  if (isset($_SESSION['user_id'])) {
    $records = $conn->prepare('SELECT id, user, password FROM users WHERE id = :id');
    $records->bindParam(':id', $_SESSION['user_id']);
    $records->execute();
    $results = $records->fetch(PDO::FETCH_ASSOC);

    $usuario = null;

    if ($records->rowCount() > 0) {
        $usuario = $results;
    }
  }
     if (isset($_SESSION['user_id'])){
        $usuario = $results;
 
         // Obtener todas las publicaciones, no solo las del usuario actual
         $query = 'SELECT * FROM publicaciones ORDER BY fecha DESC';
         $statement = $conn->prepare($query);
         
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
    <title>Pagina Principal</title>
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

        
    <p id="bienvenida">Bienvenido "<?= $usuario['user']; ?>"</p>
    <div class="contenedor-grid">
      <div class="class-elem1"> <!--Aside-->
        <ul class="opc">
          <li><a href="crearP.php"><svg xmlns="http://www.w3.org/2000/svg" class="log" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
            <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
            <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
          </svg> Publicar</a></li>
          <li><a href="perfil.php"><svg xmlns="http://www.w3.org/2000/svg" class="log" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
          <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
          <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z"/>
        </svg> Mi perfil</a></li>

        </ul>
      </div>
      <div class="class-elem2">
            <p>Sugerencias para ti!</p>
            <div class="sugerencias">
                <div class="row row-cols-1 row-cols-md-4"> <!-- Aqui vamos a utilizar Bootstrap para organizar en filas de 4 -->
                    <?php foreach ($publicaciones as $publicacion) : ?>
                        <div class="col mb-4">
                            <div class="publicacion">
                                <div class="visualizacion<?= $publicacion['tipo_visualizacion']; ?>">
                                    <header id="enc"><h1 class="pub-tit"><?= $publicacion['titulo']; ?></h1></header>
                                    <figure id="imgs">
                                        <img class="imag" src="<?= $publicacion['imagen']; ?>" alt="<?= $publicacion['titulo']; ?>">
                                    </figure>
                                    <div id="txt" class="texto">
                                        <p><?= $publicacion['contenido']; ?></p>
                                    </div>
                                </div>
                                <div class="info-publicacion">
                                    <p>Publicado por: <?= $publicacion['usuario']; ?> el <?= $publicacion['fecha']; ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

      <div class="class-elem3"> <!--Footer-->
        
      </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>