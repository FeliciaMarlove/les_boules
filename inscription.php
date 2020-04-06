<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>E-shop de boules de Noël</title>
</head>
<body class="bodyHome">
    

    <div class="bandeau">
        <h2>E-shop Les boules</h2>
    </div>
    <div class="formulaire">
        <form action="./ver_inscription.php" method="POST" id="formulaire">
            <p>
                <label for="email">Email *</label>
                <input type="text" name="email" id="email" required oninput="doStateMailCheck()">
                <bdi id="stateEmail"></bdi>
            </p>
            <p>
                <bdi id="tip-email" class="tip"></bdi>
            </p>
            <p>
                <label for="pwd">Password *</label>
                <input type="password" name="motdepasse" id="pwd" required oninput="doStatePasswordCheck()">
                <bdi id="statePassword"></bdi>
            </p>
            <p>
                <bdi id="tip-pwd" class="tip"></bdi>
            </p>
            <p>
            <p>
                <label for="pwd2">Vérification</label>
                <input type="password" name="ver mot de passe" id="pwd2" required oninput="doStatePassword2Check()">
                <bdi id="statePassword2"></bdi>
            </p>
            <p>
                <bdi id="tip-pwd2" class="tip"></bdi>
            </p>
            <p>
            <p>
                <label for="name">Nom Prénom *</label>
                <input type="text" name="nom" id="name" required oninput="doStateNomPrenomCheck()">
                <bdi id="stateName"></bdi>
            </p>
            <p>
                <bdi id="tip-nom" class="tip"></bdi>
            </p>
            <p>
            <p>
                <label for="address">Adresse *</label>
                <input type="text" name="adresse" id="address" required oninput="doStateAddressCheck()">
                <bdi id="stateAddress"></bdi>
            </p>
            <p>
                <bdi id="tip-adresse" class="tip"></bdi>
            </p>
            <p>
                <label for="country">Pays</label>
                <select name="pays" id="country"  required oninput="doStateCountryCheck()">
                </select>
                <bdi id="stateCountry"></bdi>
            </p>
            <p>
                <bdi id="tip-pays" class="tip"></bdi>
            </p>
            <p>
                <!-- <label for="filtervilles">Code postal *</label><input type="text" id="filtervilles" >
                <label for="local-dropdown">Localité *</label>
                <select id="local-dropdown" name="localite" required oninput="doStateLocalityCheck()">
                </select>
                <bdi id="stateLocality"></bdi> -->
                <div class="autocomplete" style="width:300px;">
                <label for="local-dropdown">Localité *</label>
                <input id="local-dropdown" type="text" name="localite" placeholder="Tapez votre code postal" required oninput="doStateLocalityCheck()">
                <bdi id="stateLocality"></bdi>
                </div>
            </p>
            <p>
                <bdi id="tip-localite" class="tip"></bdi>
            </p>
            <p>
                <input type="checkbox" name="conditions générales" id="conditions">
                <label for="conditions">Accepter les conditions générales</label>
            </p>
            <p>
                <bdi id="tip-conditions" class="tip"></bdi>
            </p>

            <p><button type="submit" value="Submit" id="send" onclick="doValidateForm()">Inscription</button></p>
            <p><br>Vous avez déjà un compte ?<br><button onclick="window.location.href = './connexion.html'">Se connecter</button></p>            
        </form>
    </div>

</body>
<script src="inscription.js"></script>
<?php
        require_once('./utilitaires/MyPdo.service.php');
        try {
            $connexion = MyPdo::getInstance();
            // récupère la table des pays dans la BD
            $pays_table = $connexion->query("SELECT * FROM TBL_PAYS");
            $pays = array();
            // remplit un tableau avec les enregistrements de la DB
            foreach ($pays_table as $item) {
                $pays[] = $item;
            }
            // crée un fichier JSON depuis le tableau
            $json = json_encode($pays);
            // script JavaScript pour créer la liste déroulante dynamiquement en fonction du contenu JSON
            echo "<script type='text/javascript'>
                    const defautChoixPays = 'Choisissez votre pays dans la liste';
                    let pays_l = document.getElementById('country');
                    pays_l.length = 0;
                    let defaultOption = document.createElement('option');
                    defaultOption.text = defautChoixPays;
                    pays_l.append(defaultOption);
                    pays_l.selectedIndex = 0;
                    const pays_bd = $json;
                    let option;
                    for (let i = 0; i < pays_bd.length; i++) {
                        option = document.createElement('option');
                        option.innerText = pays_bd[i].NOM_PAYS;
                        option.value = pays_bd[i].CDE_PAYS;
                        option.setAttribute('id', 'code_p');
                        pays_l.append(option);
                    }
                    </script>"; 
        } catch(PDOException $e) {
            exit("Erreur BD : ".$e->getMessage());
        } catch (Exception $e) {
            exit("Le site a rencontré un problème : ".$e->getMessage());
        } finally {
            $connexion = null;
        }
    ?>
</html>
