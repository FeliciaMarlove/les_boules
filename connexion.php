<?php 
	require_once('./utilitaires/MyPdo.service.php');
	try {
		// récupérer une instance du Singleton MyPdo pour avoir une seule connexion à la DB
		$connexion = MyPdo::getInstance();
		// charger les boules si la table est vide

	} catch(PDOException $e) {
		exit("Erreur ouverture BD : ".$e->getMessage());
	} catch (Exception $e) {
		exit("Le site a rencontré un problème : ".$e->getMessage());
	} finally {
		$connexion = null;
	}
?>
