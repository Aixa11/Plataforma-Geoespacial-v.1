<?php
session_start();
session_regenerate_id(true);
$_SESSION = array();
session_destroy();
header('Location: index.php');
exit();
?>
