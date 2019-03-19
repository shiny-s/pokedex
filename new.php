<?php

    require_once "./Pokemon.php";
    require_once "./DB.php";

    if (isset($_POST['submit'])) {
        if (!empty($_POST['name']) && !empty($_POST['xp'])) {
            $evo = ($_POST['evo'] == "base") ? (int)false : (int)true;
            $types = [$_POST['type1']];
            if (isset($_POST['multiple'])) {
                array_push($types, $_POST['type2']);
            }
            $pkmn = new Pokemon(
                $_POST['id'],
                $_POST['name'],
                $_POST['xp'],
                $evo,
                $types
            );
            if (DB::insertPokemon($pkmn)) {
                echo $pkmn;
            } else {
                echo "Une erreur est survenue";
            }
        } else {
            echo "Saisissez tous les champs";
        }
    }

?>

<form method="POST">
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
        $types = DB::getTypes();
        for ($i = 1; $i <= 2; $i++) {
            echo sprintf('<select name="type%d">', $i);
            $count = 0;
            foreach ($types as $type) {
                if ($count == 0) {
                    echo sprintf('<option value="%1$s" selected="selected">%1$s</option>', $type['libelle']);
                    $count++;
                } else {
                    echo sprintf('<option value="%1$s">%1$s</option>', $type['libelle']);
                }
            }
            echo "</select>";
        }
    ?> 
    <br>
    <button name="submit" type="submit">Valider</button>
</form>