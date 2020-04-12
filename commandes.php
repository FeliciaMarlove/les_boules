<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>E-shop de boules de Noël</title> 
</head>
<body class="bodyOrders">
	<div class="bandeau">
        <h2>E-shop Les boules</h2>
    </div>
    <div class="topShop" >
        <p id='bonjour'>Bienvenue </p>
    </div>
    <div id="secbuttons">
    	 <button class="deco" onclick="window.location.href = 'utilitaires/deconnexion.php'">Déconnexion</button>
   	 	 <button class="prece" onclick="window.location.href = 'eshop.php'">Retour à la boutique</button>
    </div>
    <div id="commandesread"> </div>
</body>
</html>
<?php
        session_start();
        // récupère le nom de l'utilisateur depuis les variables de session
        $nom = $_SESSION['utilisateur'];
        // s'il n'y a pas d'utilisateur connecté on ne peut pas afficher la page et on retourne sur la page de connexion
        if ($nom === null) { header('Location: connexion.php'); exit(); }
        // affiche le nom du client connecté
        echo "<script>document.getElementById('bonjour').append('".$nom."')</script>"; 
        // inclut le script qui lit les commandes passées par le client depuis la base de données
        include_once('read_commandes.php');
        // récupère l'id du client connecté dans la variable de session
        $idClient = $_SESSION['client'];
        // appelle la fonction readCdes du fichier read_commandes.php en lui passant l'id du client connecté en paramètre
        readCdes($idClient);
?>