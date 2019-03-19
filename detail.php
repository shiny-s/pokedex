<?php

    require "./DB.php";
    include "./menu.php";

    if (isset($_GET['pokemon'])) {
        $result = DB::getPokemonDetails($_GET['pokemon']);
        $pokemon = $result[0];
        echo "<form method='POST'>";
        if ($pokemon['evolution'] == 1) {
            $evo = "une évolution";
        } else {
            $evo = "un starter";
        }
        $types = $pokemon['type'];
        if (count($result) > 1) {
            $types .= sprintf(" - %s", $result[1]['type']);
        }
        echo sprintf(
            'id : %1$d, nom : %2$s, xp : %3$s, %4$s, %5$s',
            $pokemon['id'],
            $pokemon['nom'],
            $pokemon['type_courbe_niveau'],
            $evo,
            $types
        );
        echo "<br>";
        echo "<button name='send' type='submit'>Supprimer</button>";
        echo "</form>";

        if (isset($_POST['send'])) {
            if (DB::deletePokemon($_GET['pokemon'])) {
                header("Location: ./index.php?page=list");
            } else {
                echo "Une erreur est survenue";
            }
        }
    }

?>