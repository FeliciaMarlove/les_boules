<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>E-shop de boules de Noël</title>   
</head>
<body onload="readBoules()" class="bodyShop">
	<div class="bandeau">
        <h2>E-shop Les boules</h2>
    </div>
    <div class="topShop" >
        <p id='bonjour'>Bienvenue </p>
        <img src="./images/shopping-cart.png" height="50px" width="50px" style="padding: 5px" id="cartIcon">
        <div id="cartText">
            Nombre d'articles :
            <bdi id="cartTotal"></bdi>
            <br>
            Total :
            <bdi id="cartSomme"></bdi> €
            <br><br>
            <button id="valider" onclick="valider()">Valider</button>
        </div>
    </div>
    <div id="secbuttons">
    	 <button class="deco" onclick="window.location.href = 'utilitaires/deconnexion.php'">Déconnexion</button>
   	 	 <button class="prece" onclick="window.location.href = 'commandes.php'">Commandes précédentes</button>
    </div>
    <div class="tableContainer">
        <div id="articles" class="articles"></div>
    </div>
</body>
<script src="eshop.js"></script>
</html>
<?php
        session_start();
        $nom = $_SESSION['utilisateur'];
        echo "<script>document.getElementById('bonjour').append('".$nom."')</script>"; 
        require_once('./utilitaires/MyPdo.service.php');
        require_once('./utilitaires/LoadBoules.service.php');
        try {
            // récupérer une instance du Singleton MyPdo pour avoir une seule connexion à la DB
            $connexion = MyPdo::getInstance();
            // charger les boules dans la base de données si la table est vide
            $getBoules = LoadBoules::chargerXML('https://www.zeus2025.be/exe/boutique.xml');
        } catch(PDOException $e) {
            exit("Problème de BD : ".$e->getMessage());
        } catch (Exception $e) {
            exit("Le site a rencontré un problème : ".$e->getMessage());
        } finally {
            $connexion = null;
        }
?>