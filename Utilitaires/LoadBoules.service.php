<?php
require_once('./utilitaires/MyPdo.service.php');
class LoadBoules {
	private static $XMLurl;
	// méthode statique pour charger les articles depuis un fichier XML
	public static function chargerXML($url) {
		try {
				// récupèrer une instance du Singleton MyPdo pour avoir une seule connexion à la DB
				$connexion = MyPdo::getInstance();
				// récupère les enregistrements de la table TBL_ARTICLE en DB
				$allBoules = $connexion->query("select * from TBL_ARTICLE");
				// récupère le nombre d'enregistrements (lignes)
				$nbrEnregistrements = $allBoules->rowCount();
				// s'il n'y a pas d'articles dans la table, importer les articles depuis le fichier xml
				if ($nbrEnregistrements == 0) {
					$XMLurl = $url;
					// récupère le flux depuis le XML
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL, $XMLurl);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					$xmlresponse  = curl_exec($ch);
					curl_close($ch);
					// charge le flux
					$xml = simplexml_load_string($xmlresponse);
					// insère les enregistrements dans la base de données
                    foreach($xml as $item) {
                    	$connexion->query("INSERT INTO TBL_ARTICLE VALUES (".$item->id.",'".$item->lib."','".$item->description."',".$item->prix.",".$item->stock.",'".$item->image."')");
                    }
				}
			} catch(PDOException $e) {
				exit("Erreur ouverture BD : ".$e->getMessage());
			} catch(Exception $e) {
				exit("Un problème est survenu : ".$e->getMessage());
			}
		}
	}
?>