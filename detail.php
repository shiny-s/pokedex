<?php

    require "./Pokemon.php";
    include "./menu.php";

    if (isset($_GET['pokemon'])) {
        if (!isset($_SESSION)) {
            session_start();
        }

        echo "<form method='POST'>";
        $i = 0;
        foreach ($_SESSION['pkmns'] as $pokemon) {
            if ($pokemon->getId() == $_GET['pokemon']) {
                if ($pokemon->getisEvolution()) {
                    $evo = "une évolution";
                } else {
                    $evo = "de base";
                }
                $types = $pokemon->getTypes()[0];
                if (isset($pokemon->getTypes()[1])) {
                    $types .= sprintf(" - %s", $pokemon->getTypes()[1]);
                }
                echo sprintf(
                    'id : %1$d, nom : %2$s, xp : %3$s, %4$s, %5$s',
                    $pokemon->getId(),
                    $pokemon->getName(),
                    $pokemon->getXp(),
                    $evo,
                    $types
                );
                break;
            }
            $i++;
        }
        echo "<br>";
        echo "<button name='send' type='submit'>Supprimer</button>";
        echo "</form>";

        if (isset($_POST['send'])) {
            unset($_SESSION['pkmns'][$i]);
            header("Location: ./index.php?page=list");
        }
    }

?>