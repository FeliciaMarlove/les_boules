<?php 
	require_once('./utilitaires/MyPdo.service.php');
	try {
		// récupère une instance du Singleton MyPdo 
		$connexion = MyPdo::getInstance();
		// récupère les articles dans la base de données
		$boules_table = $connexion->prepare("SELECT * FROM TBL_ARTICLE");
		$boules_table->execute();
		$boules = $boules_table->fetchAll(PDO::FETCH_ASSOC);
		// écrit un fichier XML à partir des informations récupérées en base de données
		$x = new XMLWriter();
		$x->openMemory();
		$x->setIndent(true);
		$x->startDocument('1.0','UTF-8');
		$x->startElement('boutique');
		foreach ($boules as $boule) {
			$x->startElement('article');
		    $x->startElement('id');
		    $x->text($boule['ID_ARTICLE']);
			$x->endElement();
			$x->startElement('lib');
			$x->text($boule['LIB_ARTICLE']);
			$x->endElement();
			$x->startElement('description');
			$x->text($boule['DES_ARTICLE']);
			$x->endElement();
			$x->startElement('prix');
			$x->text($boule['PRIX_ARTICLE']);
			$x->endElement();
			$x->startElement('stock');
			$x->text($boule['STOCK_ARTICLE']);
			$x->endElement();
			$x->startElement('illustration'); // écrire "image" ou "img" cause des problèmes dans le XML !
			$x->text($boule['IMG_ARTICLE']);
			$x->endElement();
			$x->endElement(); // ferme l'élément article
    	}
    	$x->endElement(); // ferme l'élément boutique
    	$xml = $x->outputMemory();
    	// renvoie le XML créé suite à l'appel de la requête AJAX
		echo $xml;
	} catch(PDOException $e) {
		exit("Problème de BD : ".$e->getMessage());
	} catch (Exception $e) {
		exit("Le site a rencontré un problème : ".$e->getMessage());
	} finally {
		$connexion = null;
	}
?>