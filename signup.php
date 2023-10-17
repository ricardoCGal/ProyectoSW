<?php
$message = '';
    require 'database.php';

    if (!empty($_POST['user']) && !empty($_POST['password'])){
        $sql = "INSERT INTO users (user, password) VALUES(:user, :password)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':user', $_POST['user']);
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $stmt->bindParam(':password', $password);

        if ($stmt->execute()){
            $message = 'Usuario creado correctamente';
        }
        else{
            $message = 'Ocurrio un error al crear el usuario, Intentar nuevamente';
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <link rel="stylesheet" href="logsig.css">
</head>
<body>
    <div>
    <?php require 'header.php'?>

    <?php if(!empty($message)): ?>
        <p><?= $message?></p>
    <?php endif; ?>

    <h1>Registrate</h1>
    <span><a href="login.php"></a></span>
    <form action="signup.php" method="post">
        <input type="text" name="user" placeholder="Introduce tu usuario">
        <input type="password" name="password" placeholder="Introduce tu contraseña">
        <input type="password" name="confirma-password" placeholder="Confirma tu contraseña">
        <input type="submit" value="Continuar">
    </form>
    <p>¿Ya tienes una cuenta? <a href="login.php">Inicia sesion</a></p>
    </div>
</body>
</html>