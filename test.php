<?php 
	require_once('./Utilitaires/MyPdo.service.php');
	try {
		$connection = new MyPdo();
		
		
	} catch(PDOException $e) {
		exit("Erreur ouverture BD : ".$e->getMessage());
	}
  	
?>
