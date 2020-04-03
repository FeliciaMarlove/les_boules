<?php
class MyPdo extends PDO {
	public function __construct() {
		// vérifie que le fichier de config existe et est lisible
		$file = 'dev.ini';
		if (!$settings = parse_ini_file($file, TRUE)) throw new exception('Fichier de configuration '.$file.' introuvable');
		// construit la chaîne de connexion à la DB
		$dns = $settings['BD']['driver'] .
		':host=' . $settings['BD']['dbHost'] .
		';dbname=' .$settings['BD']['dbName'];

		// appel au constructeur de la classe PDO avec les paramètres récupérés du fichier de config dev.ini ; le dernier paramètre permet de try/catch les objets MyPdo
		parent::__construct($dns, $settings['BD']['dbUser'], $settings['BD']['dbPwd'], array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
 	}
}