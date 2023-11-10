<?php

session_start();
    require 'database.php';

    if (!empty($_POST['user']) && !empty($_POST['password'])){
        $records = $conn->prepare('SELECT id, user, password FROM users WHERE user = :user');
        $records->bindParam(':user', $_POST['user']);
        $records->execute();
        $results = $records->fetch(PDO::FETCH_ASSOC);

        $message = '';

        if (is_array($results) && count($results) > 0 && password_verify($_POST['password'], $results['password'])){
            $_SESSION['user_id'] = $results['id'];
            header("Location: /Proyecto/principal.php");
        }
        else{
            $message = 'Error, los datos ingresados no son correctos';
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="logsig.css">
    <title>Login</title>
</head>
<body>
    <div>
        <?php require 'header.php'?>

        <?php if(!empty($message)): ?>
            <p><?= $message ?></p>
        <?php endif; ?>

        <h1 class="head">Inicar Sesion</h1>
        <span><a href="singup.php"></a></span>
    <form action="login.php" method="post">
        <input type="text" name="user" placeholder="Introduce tu usuario" required>
        <input type="password" name="password" placeholder="Introduce tu contraseña" required>
        <input type="submit" value="Continuar">
    </form>
    <p>¿No tienes cuenta? <a href="signup.php">Registrate</a></p>
    </div>
</body>
</html>