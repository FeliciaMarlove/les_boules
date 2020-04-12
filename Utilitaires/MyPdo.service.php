<?php
class MyPdo extends PDO {
	private static $instance;
	// renvoie une instance unique du Singleton MyPdo
	public static function getInstance() {
		if(is_null(self::$instance)) {
			self::$instance = new MyPdo();
		}
		return self::$instance;
	}
	// constructeur de MyPdo, privé car Singleton -> on n'instanciera pas de MyPdo depuis les autres classes
	private function __construct() {
		// vérifie que le fichier de config existe et est lisible
		$file = 'dev.ini';
		if (!$settings = parse_ini_file($file, TRUE)) throw new exception('Fichier de configuration '.$file.' introuvable');
		// construit la chaîne de connexion à la base de données
		$dns = $settings['BD']['driver'] .
		':host=' . $settings['BD']['dbHost'] .
		';dbname=' .$settings['BD']['dbName'];
		// appelle le constructeur de la classe PDO avec les paramètres récupérés du fichier de config dev.ini ; le dernier paramètre permet de try/catch les erreurs causées par MyPdo
		parent::__construct($dns, $settings['BD']['dbUser'], $settings['BD']['dbPwd'], array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
		} 	
}