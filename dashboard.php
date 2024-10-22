<?php
session_start();
require_once 'config.php';

// Verificar si el usuario está autenticado
if (!isset($_SESSION['username'])) {
  header('Location: index.php');
  exit();
}

// Verificar si la sesión ha expirado
if (time() - $_SESSION['last_action'] > 600) { // 10 minutos
  session_regenerate_id(true);
  $_SESSION = array();
  session_destroy();
  header('Location: index.php');
  exit();
}

// Actualizar la hora de la última acción
$_SESSION['last_action'] = time();
?>

<!DOCTYPE html>
<html>
<head>
  <title>Dashboard</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
  <h1>Welcome, <?php echo $_SESSION['username']; ?>!</h1>
  <a href="logout.php">Log out</a>
</body>
</html>
