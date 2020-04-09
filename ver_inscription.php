<?php 
	session_start();
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
			echo '<script type="text/javascript">';
			echo 'alert("Cette adresse e-mail existe déjà, cliquez sur Se connecter pour accéder à votre compte.")';
			echo '</script>';
			echo '<script type="text/javascript"> window.location = \'./inscription.php\'; </script>';
		} else {
			$date = new DateTime();
			$villeDto = $_POST['localite'];
			// trouve l'espace blanc (sépraration entre le code postal et le nom de la ville)
			$startNomVille = strpos($villeDto, " ") + 1;
			// récupère l'id de la ville en base de données sur base de ce qui est reçu du client
			$requete = $connexion->prepare("SELECT ID_VILLE FROM TBL_VILLE WHERE VILLE = ? AND CPOST = ?");
			$requete->execute(array(substr($villeDto, $startNomVille), substr($villeDto, 0, $startNomVille - 1)));
			$idVille = $requete->fetchColumn();
			// prépare la requête pour insérer l'enregistrement en base de données
			$reqPreparee = $connexion->prepare("INSERT INTO `TBL_CLIENT`(`NOM_PRENOM`, `ADRESSE`, `ID_VILLE`, `EMAIL`, `PASSWORD`, `OPTIN`) VALUES (?,?,?,?,?,?)");
			// exécute la requête préparée avec les informations récupérées depuis le formulaire HTML
			$reqPreparee->execute(array($_POST['nom'],$_POST['adresse'],$idVille,strtolower(trim($_POST['email'])),$_POST['motdepasse'],$date->format('Y-m-d H:i:s')));
			// récupère l'id du dernier enregistrement 
			$id = $connexion->lastInsertId();
			// récupère le client enregistré en DB
			$client = $connexion->query("SELECT * FROM TBL_CLIENT WHERE ID_CLIENT = ".$id);
			$x = $client->fetch();
			// enregistre le client dans une variable de session
			$_SESSION['client'] = $x;
			// enregistre le nom de l'utilisateur dans une variable de session
			$_SESSION['utilisateur'] = $_POST['nom'];
			// redirige vers l'eshop
			header('Location: ./eshop.php');
		}
	} catch(PDOException $e) {
		exit("Problème de BD : ".$e->getMessage());
	} catch (Exception $e) {
		exit("Le site a rencontré un problème : ".$e->getMessage());
	} finally {
		$connexion = null;
	}
?>