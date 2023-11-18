<?php
  session_start();
  $message = '';
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

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['Titulo'];
    $imagen = $_POST['imgUrl'];
    $contenido = $_POST['cont'];
    
    // Obtenermos el valor del campo oculto tipo_visualizacion
    $tipo_visualizacion = isset($_POST['tipo_visualizacion']) ? $_POST['tipo_visualizacion'] : 1;

    date_default_timezone_set('America/Mexico_City');

    // obtenemos la fecha y hora actual
    $fecha = date("Y-m-d H:i:s");

    // inseramos datos en la tabla 'publicaciones'
    $sql = "INSERT INTO publicaciones (usuario, fecha, titulo, imagen, contenido, tipo_visualizacion) 
            VALUES (:usuario, :fecha, :titulo, :imagen, :contenido, :tipo_visualizacion)";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':usuario', $usuario['user']);
    $stmt->bindParam(':fecha', $fecha);
    $stmt->bindParam(':titulo', $titulo);
    $stmt->bindParam(':imagen', $imagen);
    $stmt->bindParam(':contenido', $contenido);
    $stmt->bindParam(':tipo_visualizacion', $tipo_visualizacion); 

    if ($stmt->execute()) {
        $message = "Publicación creada con éxito";
    } else {
        $message = "Error al crear la publicación: " . $stmt->errorInfo()[2];
    }

    header('Location: principal.php');
    exit();
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Publicacion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="estilogral.css">
    
    <script src="visualizar.js"></script>
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

    <p id="bienvenida">En que estas pensando el día de hoy!!! <?= $usuario['user']; ?></p>

    <div class="formulario">
        <div class="contenedor" id="Registro">
            <header><h1>Publicacion</h1></header>
            <form action="crearP.php" method="POST" onsubmit="mostrarMensaje('<?php echo $message; ?>')">
                <label for="Titulo">Titulo de la publicacion:</label>
                <input type="text" id="Titulo" name="Titulo" required>
                <label for="imgUrl">URL de la imagen:</label>
                <input type="text" id="imgUrl" name="imgUrl" required>
                <label for="cont">Contenido de la publicacion:</label>
                <input type="text" id="cont" name="cont" required>

                <input type="hidden" id="tipo_visualizacion" name="tipo_visualizacion" value="1">
                  <!-- Generamos una variable oculta para almacenar el tipo de visualizacioncon la que
                        se publica el contenudo -->

                <label for="vista">Tipo de visualizacion</label>
                <div class="botones-container">
                    <input type="button" value="1" onclick="ver(1)">
                    <input type="button" value="2" onclick="ver(2)">
                    <input type="button" value="3" onclick="ver(3)">
                </div>
                <input type="submit" value="Publicar" class="pub">
            </form>
        </div>
        <div class="contenedor" id="container" name="pubfinal">
            <header id="enc"><h1>Titulo de la publicacion</h1></header>
            <figure id="imgs">
                <img id="imgP" src="img/insertar.png" alt="Imagen X">
            </figure>
            <div id="txt">
                <p>Inserte el contenido a visualizar <br>(Tipo de visualización 1)</p>
            </div>
        </div>
    </div>

    </div>
</body>
</html>