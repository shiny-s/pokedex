<?php

    require_once "./DB.php";

    echo "<ul>";
    foreach (DB::getPokemon() as $pokemon) {
        echo "<li>";
        echo sprintf('<a href="./detail.php?pokemon=%1$d">%2$s</a>', $pokemon['id'], $pokemon['nom']);
        echo "</li>";
    }
    echo "</ul>";