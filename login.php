<?php
session_start();
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = $_POST['username'];
  $password = $_POST['password'];

  if (verifyLogin($username, $password, $hashedPassword, $lastLogin)) {
    updateLastLogin($username); // Actualizar el campo last_login

    $_SESSION['username'] = $username;
    $_SESSION['last_action'] = time();

    if (isset($_COOKIE['username'])) {
      setcookie('username', '', time() - 3600);
    }

    header('Location: mapweb.php');
    exit();
  } else {
    $_SESSION['login_error'] = 'Invalid username or password.';
    header('Location: index.php');
    exit();
  }
} else {
  header('Location: index.php');
  exit();
}

function verifyLogin($username, $password, &$hashedPassword, &$lastLogin) {
  $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

  $query = "SELECT password, last_login FROM users WHERE username = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $stmt->bind_result($hashedPassword, $lastLogin);
  $stmt->fetch();
  $stmt->close();

  mysqli_close($conn);

  return password_verify($password, $hashedPassword);
}

function updateLastLogin($username) {
  $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

  $query = "UPDATE users SET last_login = NOW() WHERE username = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $stmt->close();

  mysqli_close($conn);
}
?>
