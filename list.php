<?php

    if (!isset($_SESSION)) {
        session_start();
    }
    echo "<ul>";
    foreach ($_SESSION['pkmns'] as $pokemon) {
        echo sprintf('<li><a href="./detail.php?pokemon=%1$d">%2$s</a></li>', $pokemon->getId(), $pokemon->getName());
    }
    echo "</ul>";

?>