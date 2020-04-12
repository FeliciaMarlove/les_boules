<?php
require_once('./utilitaires/MyPdo.service.php');
        try {
            // récupère une instance du Singleton MyPdo
            $connexion = MyPdo::getInstance();
            // récupère le code pays depuis la requête Ajax du fichier JavaScript
            $cde_pays = $_GET['code'];
            // récupère les villes dont le code pays correspond à celui reçu
            $villes_table = $connexion->query("SELECT * FROM TBL_VILLE WHERE CDE_PAYS ='".$cde_pays."'");
            // crée un tableau des villes
            $villes = array();
            foreach ($villes_table as $item) {
                $villes[] = $item;
            }
            // convertit le tableau en JSON
            $json = json_encode($villes);
            // retourne le fichier JSON des villes selon le pays sélectionné au JavaScript qui a émis la requête Ajax
            echo $json;
        } catch(PDOException $e) {
            exit("Problème de BD : ".$e->getMessage());
        } catch (Exception $e) {
            exit("Le site a rencontré un problème : ".$e->getMessage());
        } finally {
            $connexion = null;
        }

?>