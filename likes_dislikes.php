<?php
session_start();
require 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && isset($_POST['postId'])) {
    $action = $_POST['action'];
    $postId = $_POST['postId'];

    // Aqui verificar si el usuario ya ha votado en esta publicación en la sesión actual
    $key = 'voted_' . $postId;
    if (isset($_SESSION[$key]) && $_SESSION[$key] === $action) {
        echo json_encode(['error' => 'Ya has votado en esta publicación.']);
        exit();
    }

    // Obtenemos el voto actual del usuario en esta publicación
    $currentVote = ($_SESSION[$key]) ?? '';

    // valisdamos
    if ($action === 'like' || $action === 'dislike') {
        // se actualiza la base de datos según la acción
        $updateQuery = "UPDATE publicaciones SET ";
        if ($currentVote === 'like') {
            $updateQuery .= "likes = likes - 1, dislikes = dislikes + 1 ";
        } elseif ($currentVote === 'dislike') {
            $updateQuery .= "dislikes = dislikes - 1, likes = likes + 1 ";
        } else {
            // Nuevo voto
            if ($action === 'like') {
                $updateQuery .= "likes = likes + 1 ";
            } else {
                $updateQuery .= "dislikes = dislikes + 1 ";
            }
        }
        $updateQuery .= "WHERE id = :postId";

        $updateStatement = $conn->prepare($updateQuery);
        $updateStatement->bindParam(':postId', $postId);

        if ($updateStatement->execute()) {
            // se registra el nuevo voto en la sesión para evitar votos múltiples
            $_SESSION[$key] = $action;

            // devolvemos el nuevo recuento de likes y dislikes
            $selectQuery = "SELECT likes, dislikes FROM publicaciones WHERE id = :postId";
            $selectStatement = $conn->prepare($selectQuery);
            $selectStatement->bindParam(':postId', $postId);
            $selectStatement->execute();
            $result = $selectStatement->fetch(PDO::FETCH_ASSOC);

            echo json_encode($result);
        } else {
            echo json_encode(['error' => 'Error al actualizar la base de datos.']);
        }
    } else {
        echo json_encode(['error' => 'Acción no válida.']);
    }
} else {
    echo json_encode(['error' => 'Solicitud no válida.']);
}
?>
