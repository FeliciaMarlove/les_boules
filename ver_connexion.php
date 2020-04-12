<?php 
	session_start();
	require_once('./utilitaires/MyPdo.service.php');
	try {
		// récupère une instance du Singleton MyPdo
		$connexion = MyPdo::getInstance();
		$email = $_POST['email'];
		$mdp = $_POST['motdepasse'];
		// récupère l'utilisateur sur base de l'e-mail
		$reqPrepa = $connexion->prepare("SELECT * FROM TBL_CLIENT WHERE EMAIL = ?");
		$reqPrepa->execute(array($email));
		// si la requête ne retourne pas de résultat, l'utilisateur n'existe pas et on arrête le traitement (rechargement de la page)
		if ($reqPrepa->rowCount() === 0) {
			echo '<script type="text/javascript">';
			echo 'alert("Aucun compte n\'est associé à cette adresse e-mail, veuillez créer un compte")';
			echo '</script>';
			echo '<script type="text/javascript"> window.location = \'./connexion.php\'; </script>';
		} else {
			// récupère le mot de passe en base de données
			$resultat = $reqPrepa->fetch($connexion::FETCH_OBJ);
			$pwdCompare = $resultat->PASSWORD;
			// si le mot de passe entré et le mot de passe en base de données sont égaux, enregistre le nom du client et son id dans des variables de session 
			if ($pwdCompare === $mdp) {
				$_SESSION['client'] = $resultat->ID_CLIENT;
				$_SESSION['utilisateur'] = $resultat->NOM_PRENOM;
				// et poursuit vers l'eshop
				header('Location: ./eshop.php');
			} else {
				//sinon on alerte l'utilisateur (la page est rechargée)
				echo '<script type="text/javascript">';
				echo 'alert("Mot de passe incorrect")';
				echo '</script>';
				echo '<script type="text/javascript"> window.location = \'./connexion.php\'; </script>';
			}
		}
	} catch(PDOException $e) {
		exit("Problème de BD : ".$e->getMessage());
	} catch (Exception $e) {
		exit("Le site a rencontré un problème : ".$e->getMessage());
	} finally {
		$connexion = null;
	}
?>