<?php 
	require_once('./utilitaires/MyPdo.service.php');
	try {
		// récupère une instance du Singleton MyPdo pour avoir une seule connexion à la DB
		$connexion = MyPdo::getInstance();
		$email = $_POST['email'];
		$mdp = $_POST['motdepasse'];
		// récupère l'utilisateur sur base de l'e-mail
		$reqPrepa = $connexion->prepare("SELECT * FROM TBL_CLIENT WHERE EMAIL = ?");
		$reqPrepa->execute(array($email));
		// si la requête ne retourne pas de résultat, l'utilisateur n'existe pas et on arrête le traitement
		if ($reqPrepa->rowCount() === 0) {
			echo '<script type="text/javascript">';
			echo 'alert("Aucun compte n\'est associé à cette adresse e-mail, veuillez créer un compte")';
			echo '</script>';
			echo '<script type="text/javascript"> window.location = \'./connexion.php\'; </script>';
		} else {
			// récupère le mot de passe en base de données
			$resultat = $reqPrepa->fetch($connexion::FETCH_OBJ);
			$pwdCompare = $resultat->PASSWORD;
			// si le mot de passe entré et le mot de passe en base de données sont égaux, on peut poursuivre vers l'e-shop
			if ($pwdCompare === $mdp) {
				header('Location: ./eshop.php');
			} else {
				//sinon on alerte l'utilisateur
				echo '<script type="text/javascript">';
			echo 'alert("Mot de passe incorrect")';
			echo '</script>';
			echo '<script type="text/javascript"> window.location = \'./connexion.php\'; </script>';
			}
		}
	} catch(PDOException $e) {
		exit("Erreur BD : ".$e->getMessage());
	} catch (Exception $e) {
		exit("Le site a rencontré un problème : ".$e->getMessage());
	} finally {
		$connexion = null;
	}
?>