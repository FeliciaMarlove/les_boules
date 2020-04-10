<?php
        session_start();
        require_once('./utilitaires/MyPdo.service.php');
        $client = $_SESSION['client'];
        $post = json_decode($_POST['commande']);
        $aEnregistrer = [];
        // récupère les id du string pour en faire un tableau de strings séparés / array_filter permet d'ignorer les espaces en début et fin de chaîne
        $idBoulesCommandees = array_filter(explode(" ", $post));
        // parcourt les id pour regrouper les id identiques et enregistrer leur quantité
        for ($i = 0 ; $i < count($idBoulesCommandees) ; $i++) {
            $toAdd = true;
            // si le tableau est vide
            if (count($aEnregistrer) === 0) {
                // ajoute un nouvel objet "Boule" avec l'Id à l'index [i] et une quantité de 1, et retourne à la boucle FOR(i)
                $aEnregistrer[] = new Boule($idBoulesCommandees[$i]); continue; }
            // pour chaque boule dans la table "à enregistrer"  
            for($j = 0 ; $j < count($aEnregistrer) ; $j++) {
                // vérifie si la boule (id) est déjà présente
                if ($aEnregistrer[$j]->getId() === $idBoulesCommandees[$i]) { 
                    // incrémente de 1 la quantité et sort de la boucle FOR (j)
                    $aEnregistrer[$j]->plusUn();
                    $toAdd = false; break;
                } 
            }
            // si la variable toAdd n'a pas été déclarée false, la boule n'est pas encore dans la table à enregistrer
            if($toAdd === true) {
                // ajout dans la table comme nouvelle boule avec une quantité de 1
                $aEnregistrer[] = new Boule($idBoulesCommandees[$i]); 
                
            }
        }
        var_dump($aEnregistrer);

        
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

        // classe pour modéliser les objets "Boule"
        class Boule {
            private $id;
            private $qte;

            function __construct($id) {
                $this->id = $id;
                $this->qte = 1;
            }
           
            function plusUn() {
                $this->qte = $this->qte + 1;
            }

            function getId() {
                return $this->id;
            }
            
        }
    ?>