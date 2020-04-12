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
            	$cdes = "<h4>Aucune commande enregistrée</h4>"; 
            } else {
            	 // prépare la requêtes pour récupérer les lignes de la commande
	            $reqLignes = $connexion->prepare("SELECT * FROM TBL_DETAIL_CMD WHERE ID_CMD = ?");
	            // prépare la requête pour récupérer l'article d'une ligne de commande
	            $reqArticle = $connexion->prepare("SELECT * FROM TBL_ARTICLE WHERE ID_ARTICLE = ?");
	            foreach($commandes as $commande) {
	            	$reqLignes->execute(array($commande['ID_CMD']));
	            	$lignes = $reqLignes->fetchAll();
	            	$total = 0;
	            	foreach ($lignes as $ligne) {
						$qte = $ligne['QTE'];
	            		$idArt = $ligne['ID_ARTICLE'];
	            		$reqArticle->execute(array($idArt));
	            		$article = $reqArticle->fetch();
	            		$total += $qte * $article['PRIX_ARTICLE'];
	            	}
	            	$cdes.="<h4>Commande "
	            			.$commande['NUM_COMMANDE']." "
	            			.$commande['DATE_COMMANDE']." "
	            			.strval($total)
	            			." €</h4><br> ";
	            	foreach ($lignes as $ligne) {
	            		$idArt = $ligne['ID_ARTICLE'];
	            		$reqArticle->execute(array($idArt));
	            		$article = $reqArticle->fetch(); 
	            		$link = '';
	            		$link = $article['IMG_ARTICLE'];
	            		$cdes.="<img src='".$link."'>"; // <img src='https://www.zeus2025.be/exe/img/a2.png'>
	            		$cdes.=$article['DES_ARTICLE']." ".$ligne['QTE']." ".$article['PRIX_ARTICLE']."<br>";
	            	}	
	            	$cdes.='<hr>';
	            	 

	            }
            }
           
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