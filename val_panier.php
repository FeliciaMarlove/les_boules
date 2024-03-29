<?php
        session_start();
        require_once('./utilitaires/MyPdo.service.php');
        $idclient = $_SESSION['client'];
        $post = json_decode($_POST['commande']);
        $date = new DateTime();
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
        try {
            // récupère une instance du Singleton MyPdo
            $connexion = MyPdo::getInstance();
            // récupère le dernier numéro de commande
            $req = $connexion->query("SELECT MAX(NUM_COMMANDE) FROM TBL_COMMANDE");
            $num = $req->fetchColumn();
            // récupère l'année courante
            $annee = $date->format('yy');
            $numCommande = '';
            // démarre une transaction (pour assurer l'intégrité des données au niveau du stock)
            $connexion->beginTransaction();
            if ($num === null) {
                // s'il n'y a pas de commande dans la BD, commence une série
                $numCommande = $annee.'00001';
            } elseif (substr($num,0,4) === $annee) {
                // si le dernier numéro de commande est de l'année en cours, prend la valeur et incrémente de 1 pour créer le prochain numéro
                $x = substr($num, 5) + 1;
                // str_pad permet de remplir de '0' à gauche pour correspondre à la longueur demandée (5)
                $numCommande = $annee.'-'.str_pad($x, 5, 0, STR_PAD_LEFT);
            } else {
                // si le dernier numéro de commande n'est pas de l'année courante, on commence une série
                $numCommande = $annee.'-'.'00001';
            }
            $reqCmd = $connexion->prepare("INSERT INTO TBL_COMMANDE (NUM_COMMANDE, DATE_COMMANDE, ID_CLIENT) VALUES (?,?,?)");
            $reqCmd->execute(array($numCommande, $date->format('Y-m-d H:i:s'), $idclient));
            // récupère l'id de la commande créée
            $idCmd = $connexion->lastInsertId();
            // prépare la requête pour insérer les articles en base de données
            $reqDetail = $connexion->prepare("INSERT INTO `TBL_DETAIL_CMD`(`ID_CMD`, `ID_ARTICLE`, `QTE`) VALUES (".$idCmd.",?,?)");
            // prépare la requête pour adapter le stock des articles en base de données
            $reqStock = $connexion->prepare("UPDATE `TBL_ARTICLE` SET `STOCK_ARTICLE`= STOCK_ARTICLE - ? WHERE ID_ARTICLE = ?");
            // exécute les requêtes préparées pour chaque article dans le tableau "à enregistrer"
            for($b = 0 ; $b < count($aEnregistrer) ; $b++) {
                $reqDetail->execute(array($aEnregistrer[$b]->getId(),$aEnregistrer[$b]->getQte()));
                $reqStock->execute(array($aEnregistrer[$b]->getQte(), $aEnregistrer[$b]->getId()));
            }
            echo "true";
            // affecte en base de données les actions depuis "beginTransaction"
            $connexion->commit();
        } catch(PDOException $e) {
            // les changements depuis "beginTransaction" ne sont pas effectués
            $connexion->rollBack();
            echo "Problème de BD : ".$e->getMessage();
        } catch (Exception $e) {
            echo "Le site a rencontré un problème : ".$e->getMessage();
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

            function getQte() {
                return $this->qte;
            }
            
        }
    ?>