<?php
//Ingresa tu password y genera el password hash
$password = 'secretpassword';
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

echo "Contraseña original: " . $password . "<br>";
echo "Contraseña hasheada: " . $hashedPassword;
?>