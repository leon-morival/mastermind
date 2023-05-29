<?php
include('./connect_db.php');
// Générer un code secret aléatoire
function genererCodeSecret($longueur)
{
    $couleurs = ['R', 'V', 'B', 'J', 'O', 'M']; // Couleurs possibles
    $code = '';

    for ($i = 0; $i < $longueur; $i++) {
        $indiceAleatoire = rand(0, count($couleurs) - 1);
        $code .= $couleurs[$indiceAleatoire];
    }

    return $code;
}

// Calculer le nombre de correspondances exactes (couleur et position correctes)
function calculerCorrespondancesExactes($proposition, $codeSecret)
{
    $correspondancesExactes = 0;

    for ($i = 0; $i < strlen($proposition); $i++) {
        if ($proposition[$i] === $codeSecret[$i]) {
            $correspondancesExactes++;
        }
    }

    return $correspondancesExactes;
}

// Calculer le nombre de correspondances de couleur (couleur correcte mais mauvaise position)
function calculerCorrespondancesCouleur($proposition, $codeSecret)
{
    $correspondancesCouleur = 0;

    $compteurCodeSecret = array_count_values(str_split($codeSecret));
    $compteurProposition = array_count_values(str_split($proposition));

    foreach ($compteurProposition as $couleur => $nombre) {
        if (isset($compteurCodeSecret[$couleur])) {
            $correspondancesCouleur += min($nombre, $compteurCodeSecret[$couleur]);
        }
    }

    return $correspondancesCouleur;
}

// Jouer au jeu
function jouer()
{
    session_start();


    if (isset($_POST['nouvellePartie'])) {
        // Démarrer une nouvelle partie en réinitialisant la session
        session_unset();
        session_destroy();
        header("Location: index.php");
        exit;
    }

    if (!isset($_SESSION['codeSecret'])) {
        $_SESSION['codeSecret'] = genererCodeSecret(4);
        $_SESSION['essaisRestants'] = 10;
        $_SESSION['resultatsPropositions'] = [];
    }

    $codeSecret = $_SESSION['codeSecret'];
    $essaisRestants = $_SESSION['essaisRestants'];
    $resultatsPropositions = $_SESSION['resultatsPropositions'];
    $retour = '';
    $proposition = '';


    if (isset($_POST['proposition'])) {
        $proposition = strtoupper($_POST['proposition']);
        $essaies++;

        if (strlen($proposition) !== 4 || !preg_match('/^[RVBJOM]+$/', $proposition)) {
            $retour = "Proposition invalide. Veuillez entrer un code de 4 lettres en utilisant les couleurs R, V, B, J, O, M.";
        } else {
            $correspondancesExactes = calculerCorrespondancesExactes($proposition, $codeSecret);
            $correspondancesCouleur = calculerCorrespondancesCouleur($proposition, $codeSecret);

            if ($correspondancesExactes === 4) {
                $retour = "Félicitations ! Vous avez deviné le code secret.";
                $sql = "INSERT INTO user (name, score) VALUES ('$nomJoueur', $score)";
                $conn->query($sql);
                // Réinitialiser la session de jeu
                session_unset();
                session_destroy();
            } else {
                $retour = " Correspondances exactes : $correspondancesExactes<br>Correspondances de couleur : $correspondancesCouleur";
                $essaisRestants--; // Décrémenter le nombre d'essais restants
                $_SESSION['essaisRestants'] = $essaisRestants; // Mettre à jour le nombre d'essais restants dans la session

                // Stocker la proposition et ses résultats
                $resultatsPropositions[] = [
                   
                    'proposition' => $proposition,
                    'correspondancesExactes' => $correspondancesExactes,
                    'correspondancesCouleur' => $correspondancesCouleur
                ];
                $_SESSION['resultatsPropositions'] = $resultatsPropositions;

                // Vérifier si le joueur a perdu
                if ($essaisRestants === 0) {
                    $retour = "Vous avez perdu ! Le code secret était : $codeSecret";
                    // Réinitialiser la session de jeu
                    session_unset();
                    session_destroy();
                }
            }
        }
    }

    include('index.tpl.php');
}

// Démarrer le jeu
jouer();

?>
