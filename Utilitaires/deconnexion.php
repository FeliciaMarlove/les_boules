<?php
session_start();
// détruit les variables de session
session_destroy();
// redirige vers la page de connexion
header('location: ../connexion.php');
?>