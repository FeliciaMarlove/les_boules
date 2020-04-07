<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>E-shop de boules de Noël</title>
</head>
<body class="bodyHome">
	<div class="bandeau">
        <h2>E-shop Les boules</h2>
    </div>
    <div class="formulaire">
        <p style="font-size: 20px"><strong>Se connecter</strong></p>
			<form action="./ver_connexion.php" method="POST" id="formulaire">
				<p>
	                <label for="email">Email *</label>
	                <input type="text" style="max-width:350px;" name="email" id="email" required oninput="doStateMailCheck()">
	                <bdi id="stateEmail"></bdi>
	            </p>
	            <p>
	                <bdi id="tip-email" class="tip"></bdi>
	            </p>
	            <p>
	                <label for="pwd">Password *</label>
	                <input type="password" name="motdepasse" id="pwd" required oninput="doStatePasswordCheck()">
	                <bdi id="statePassword"></bdi>
	            </p>
	            <p>
	                <bdi id="tip-pwd" class="tip"></bdi>
	            </p>
				<p><button type="submit" value="Submit" id="send" onclick="doConnectCheck()">Connexion</button></p>
				<p><br>Pas encore inscrit ?<br><button onclick="window.location.href = './inscription.php'">Créer un compte</button></p> 
			 </form>
    </div>
</body>
<script src="connexion.js"></script>

<?php 
	require_once('./utilitaires/MyPdo.service.php');
	try {
		// récupère une instance du Singleton MyPdo pour avoir une seule connexion à la DB
		$connexion = MyPdo::getInstance();
		

	} catch(PDOException $e) {
		exit("Erreur ouverture BD : ".$e->getMessage());
	} catch (Exception $e) {
		exit("Le site a rencontré un problème : ".$e->getMessage());
	} finally {
		$connexion = null;
	}
?>
