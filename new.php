<?php

    if (!isset($_SESSION)) {
        session_start();
    }

    if (isset($_POST['submit'])) {
        var_dump($_POST);
        if (intval($_POST['id']) > 0) {
            if (!empty($_POST['name']) && !empty($_POST['xp'])) {
                $evo = ($_POST['evo'] == "base") ? false : true;
                $types = [strtoupper($_POST['type1'])];
                if (isset($_POST['multiple'])) {
                    array_push($types, strtoupper($_POST['type2']));
                }
                $pkmn = new Pokemon(
                    $_POST['id'],
                    $_POST['name'],
                    $_POST['xp'],
                    $evo,
                    $types
                );
                array_push($_SESSION['pkmns'], $pkmn);
                echo $pkmn;
            } else {
                echo "Saisissez tous les champs";
            }
        } else {
            echo "Mauvaise saisie de l'id";
        }
    }

?>

<form method="POST">
    <input type="text" name="id" placeholder="ID"><br>
    <input type="text" name="name" placeholder="Nom"><br>
    <input type="text" name="xp" placeholder="XP"><br>
    <input type="radio" name="evo" value="base" checked="checked">
    <label>De base</label>
    <input type="radio" name="evo" value="evo">
    <label>Evolution</label>
    <br>
    <label>Plusieurs types ?</label>
    <input type="checkbox" name="multiple" checked="checked">
    <br>
    <?php
        for ($i = 1; $i <= 2; $i++) {
            echo sprintf('<select name="type%d">', $i);
            $count = 0;
            foreach ($_SESSION['types'] as $key => $type) {
                if ($count == 0) {
                    echo sprintf('<option value="%1$s" selected="selected">%2$s</option>', $key, ucfirst($key));
                    $count++;
                } else {
                    echo sprintf('<option value="%1$s">%2$s</option>', $key, ucfirst($key));
                }
            }
            echo "</select>";
        }
    ?> 
    <br>
    <button name="submit" type="submit">Valider</button>
</form>