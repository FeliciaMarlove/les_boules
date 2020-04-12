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
    <div id="commandesread">
        
        
    </div>
</body>
<!-- <script src=".js"></script> -->
</html>
<?php
        session_start();
        $nom = $_SESSION['utilisateur'];
        echo "<script>document.getElementById('bonjour').append('".$nom."')</script>"; 
        include_once('read_commandes.php');
        $idClient = $_SESSION['client'];
        readCdes($idClient);
?>