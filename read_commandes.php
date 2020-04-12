<?php
		function readCdes($idClt) {
			require_once('./utilitaires/MyPdo.service.php');
			try {
            // récupérer une instance du Singleton MyPdo pour avoir une seule connexion à la DB
            $connexion = MyPdo::getInstance();
            $reqCde = $connexion->prepare("SELECT * FROM TBL_COMMANDE WHERE ID_CLIENT = ? ORDER BY DATE_COMMANDE DESC");
            $reqCde->execute(array($idClt));
            // récupère un tableau contenant toutes les lignes retournées par la requête
            $commandes = $reqCde->fetchAll();
            $cdes = '';
            // s'il n'y a aucun résultat, le client n'a pas encore passé de commande 
            if (count($commandes) === 0) { 
            	$cdes = "<h4 class='ligne'>Aucune commande enregistrée</h4>"; 
            } else {
            	$cdes.="<h3>Vos commandes :</h3><br><br>";
            	 // prépare la requêtes pour récupérer les lignes de la commande
	            $reqLignes = $connexion->prepare("SELECT * FROM TBL_DETAIL_CMD WHERE ID_CMD = ?");
	            // prépare la requête pour récupérer l'article d'une ligne de commande
	            $reqArticle = $connexion->prepare("SELECT * FROM TBL_ARTICLE WHERE ID_ARTICLE = ?");
	            // pour chaque commande récupérée
	            foreach($commandes as $commande) {
	            	// récupère les lignes de commande
	            	$reqLignes->execute(array($commande['ID_CMD']));
	            	$lignes = $reqLignes->fetchAll();
	            	$total = 0;
	            	// pour chaque ligne sur la commande
	            	foreach ($lignes as $ligne) {
						$qte = $ligne['QTE'];
	            		$idArt = $ligne['ID_ARTICLE'];
	            		// exécute la requête pour récupérer l'article
	            		$reqArticle->execute(array($idArt));
	            		$article = $reqArticle->fetch();
	            		// incrémente le prix total de la commande du prix de l'article * la quantité sur la ligne
	            		$total += $qte * $article['PRIX_ARTICLE'];
	            	}
	            	// concatène un en-tête de commande (date_format permet de formater la date en jour/mois/année, strval récupère la valeur d'un numrique en string, number_format permet de déterminer le nombre de décimales d'un nombre)
	            	$cdes.="<h4 class='ligne'><div class='sec1'>Commande "
	            			.$commande['NUM_COMMANDE']."</div>  <div class='sec2'>"
	            			.date_format(new DateTime($commande['DATE_COMMANDE']), 'd/m/yy')."</div><div class='sec3'>Total : "
	            			.strval(number_format($total, 2))
	            			." €</div></h4><br> ";
	            	// pour chaque ligne
	            	foreach ($lignes as $ligne) {
	            		$idArt = $ligne['ID_ARTICLE'];
	            		// récupère l'article
	            		$reqArticle->execute(array($idArt));
	            		$article = $reqArticle->fetch(); 
	            		// concatène $cdes avec les détails de l'article sur une nouvelle ligne
	            		$link = '';
	            		$link = $article['IMG_ARTICLE'];
	            		$cdes.="<div class='ligne'><div class='sec1'><img height='35' width='35' src='".$link."'>&emsp;";
	            		$cdes.=$article['DES_ARTICLE']."</div> <div class='sec2'>".$ligne['QTE']." pc.</div><div class='sec3'>".number_format($article['PRIX_ARTICLE'], 2)." €</div></div><br>";
	            	}	
	            	$cdes.='<hr>';
	            }
            }
          	// ajoute l'éléement à la balise "commandesread" grâce à la DOM (addslashes permet d'échapper les caractères spéciaux automatiquement)
            echo "<script>document.getElementById('commandesread').innerHTML = '".addslashes(($cdes))."'</script>";
	        } catch(PDOException $e) {
	            exit("Problème de BD : ".$e->getMessage());
	        } catch (Exception $e) {
	            exit("Le site a rencontré un problème : ".$e->getMessage());
	        } finally {
	            $connexion = null;
	        }
		} 
?>