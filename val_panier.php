<?php
        session_start();
        require_once('./utilitaires/MyPdo.service.php');
        $client = $_SESSION['client'];
        $post = json_decode($_POST['commande']);
        // récupère les id du string pour en faire un tableau de strings séparés
        $idBoulesCommandees = explode(' ', $post);
        for ($i = 0 ; i < $idBoulesCommandees.length ; $i++) {
            // rassembler articles mm id -> une seule ligne, qté++ -> nouvelle table
        }

        
        // enregistrement en BD


        try {
            // récupère une instance du Singleton MyPdo pour avoir une seule connexion à la DB
            $connexion = MyPdo::getInstance();
            // 
            // echo "true"; // check en JS
        } catch(PDOException $e) {
            exit("Problème de BD : ".$e->getMessage());
            echo false;
        } catch (Exception $e) {
            exit("Le site a rencontré un problème : ".$e->getMessage());
        } finally {
            $connexion = null;
        }
    ?>