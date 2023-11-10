<?php
$message = '';
require 'database.php';

if (!empty($_POST['user']) && !empty($_POST['password']) && !empty($_POST['confirma-password'])){
    $user = $_POST['user'];
    $password = $_POST['password'];
    $confirmaPassword = $_POST['confirma-password'];

    if ($password !== $confirmaPassword) {
        $message = 'Las contraseñas no coinciden. Por favor, inténtalo de nuevo.';
    } else {
        $sql = "INSERT INTO users (user, password) VALUES(:user, :password)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':user', $user);
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $stmt->bindParam(':password', $hashedPassword);

        if ($stmt->execute()){
            $message = 'Usuario creado correctamente';
        } else {
            $message = 'Ocurrió un error al crear el usuario. Inténtalo nuevamente.';
        }
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
        <input type="text" name="user" placeholder="Introduce tu usuario" required>
        <input type="password" name="password" placeholder="Introduce tu contraseña" required>
        <input type="password" name="confirma-password" placeholder="Confirma tu contraseña" required>
        <input type="submit" value="Continuar">
    </form>
    <p>¿Ya tienes una cuenta? <a href="login.php">Inicia sesion</a></p>
    </div>
</body>
</html>