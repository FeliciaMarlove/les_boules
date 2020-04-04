<?php 
	require_once('./utilitaires/MyPdo.service.php');
	try {
		// récupère une instance du Singleton MyPdo pour avoir une seule connexion à la DB
		$connexion = MyPdo::getInstance();
		// vérifie que l'email récupéré du formulaire n'est pas déjà enregistré en base de données
		$email = $_POST['email'];
		// enregistre une requête préparée pour le select
		$reqPreparee = $connexion->prepare("SELECT * FROM TBL_CLIENT WHERE EMAIL = ?");
		//exécute la requête préparée avec les infos passées en paramètre pour remplacer le "?"
		$reqPreparee->execute(array(strtolower(trim("$email"))));
		// si la requête renvoie des résultats (rowCount > 0), l'e-mail est déjà en base de données
		if ($reqPreparee->rowCount() > 0) {

		// sinon, on enregistre dans la base de données
		} else {
			$date = new DateTime();
			$timeStamp = $date->getTimestamp();
			$reqPreparee = $connexion->prepare("INSERT INTO `TBL_CLIENT`(`NOM_PRENOM`, `ADRESSE`, `ID_VILLE`, `EMAIL`, `PASSWORD`, `OPTIN`) VALUES (?,?,?,?,?,?)");
			// exécute la requête préparée avec les informations récupérées dans le POST depuis le formulaire HTML
			$reqPreparee->execute(array($_POST['nom'],$_POST['adresse'],$_POST['localite'],$_POST['email'],$_POST['motdepasse'],$timeStamp));
			header('Location: ./eshop.php');
		}
		
	} catch(PDOException $e) {
		exit("Erreur ouverture BD : ".$e->getMessage());
	} catch (Exception $e) {
		exit("Le site a rencontré un problème : ".$e->getMessage());
	}
?>