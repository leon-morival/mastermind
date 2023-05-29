<html>
<head>
    <title>Mastermind</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
</head>
<body>
    <div class="container">
        <h1 class="mt-4">Bienvenue dans Mastermind !</h1>
        <p>Essayez de deviner le code secret. Le code secret est composé de 4 couleurs. Les couleurs possibles sont R, V, B, J, O, M.</p>

        <div class="row">
            <div class="col-md-6">
                <form method="post" action="">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="proposition" name="proposition" maxlength="4" pattern="[RVBJOM]{4}" value="<?php echo $proposition; ?>" required>
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit">Valider la Proposition</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <p><?php echo $retour; ?></p>
                <p>Essais restants : <?php echo $essaisRestants; ?></p>

                <h3 class="mt-4">Sélection de couleur :</h3>
                <div class="selection-couleur" style="background-color: red" data-color="R"></div>
                <div class="selection-couleur" style="background-color: green" data-color="V"></div>
                <div class="selection-couleur" style="background-color: blue" data-color="B"></div>
                <div class="selection-couleur" style="background-color: yellow" data-color="J"></div>
                <div class="selection-couleur" style="background-color: orange" data-color="O"></div>
                <div class="selection-couleur" style="background-color: purple" data-color="M"></div>
            </div>

            <div class="col-md-6">
                <h3 class="mt-4">Propositions précédentes :</h3>
                <?php foreach ($resultatsPropositions as $resultat) : ?>
                    <div class="proposition-precedente">

                        <strong><?php echo $resultat['proposition']; ?></strong> - Correspondances exactes : <?php echo $resultat['correspondancesExactes']; ?> - Correspondances de couleur : <?php echo $resultat['correspondancesCouleur']; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <form method="post" action="">
            <button class="btn btn-primary mt-2" type="submit" name="nouvellePartie">Nouvelle Partie</button>
        </form>

  
        <div class="w-25">
            <?php
            include('./connect_db.php');
            $select_query = "SELECT * FROM `user` ORDER BY `score` LIMIT 10";
            $result_query = mysqli_query($con, $select_query);
            if (mysqli_num_rows($result_query) > 0) {
                echo "<ul class='list-group'>";
                echo "<li class='list-group-item active'>top 10</li>";
                while ($row = mysqli_fetch_assoc($result_query)) {
                    $name = $row['name'];
                    $score = $row['score'];
                    echo "<li class='list-group-item'>Nom : <strong>$name</strong> / Score: <strong>$score</strong></li>";
                }
                echo "</ul>";
            } else {
                echo "<p>Aucun résultat trouvé.</p>";
            }
            ?>
</div>
    </div>

    <script src="script.js"></script>
</body>
</html>
