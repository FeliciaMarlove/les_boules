<?php 
	require_once('./utilitaires/MyPdo.service.php');
	try {
		// récupère une instance du Singleton MyPdo pour avoir une seule connexion à la DB
		$connexion = MyPdo::getInstance();
		// vérifie que l'email n'est pas déjà enregistré en base de données
		$email = $_POST['email'];
		$reqPreparee = $connexion->prepare("SELECT * FROM TBL_CLIENT WHERE EMAIL = ?");
		$reqPreparee->execute(array("$email"));
		echo $reqPreparee->rowCount();
		
	} catch(PDOException $e) {
		exit("Erreur ouverture BD : ".$e->getMessage());
	} catch (Exception $e) {
		exit("Le site a rencontré un problème : ".$e->getMessage());
	}
?>