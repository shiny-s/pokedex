<?php

    require "./Pokemon.php";
    if (($handle = fopen("./pokemon.csv", "r")) != false) {
        if (!isset($_SESSION)) {
            session_start();
        }
        if (empty($_SESSION)) {
            $_SESSION['pkmns'] = [];
            $_SESSION['types'] = [
                "acier" => 0,
                "combat" => 0,
                "dragon" => 0,
                "eau" => 0,
                "electrik" => 0,
                "fee" => 0,
                "feu" => 0,
                "glace" => 0,
                "insecte" => 0,
                "normal" => 0,
                "plante" => 0,
                "poison" => 0,
                "psy" => 0,
                "roche" => 0,
                "sol" => 0,
                "spectre" => 0,
                "tenebres" => 0,
                "vol" => 0
            ];
            $_SESSION['nbBases'] = 0;
            $_SESSION['nbEvos'] = 0;
            while (($data = fgetcsv($handle)) != false) {
                if ($data[3] == "n") {
                    $isEvo = false;
                    $_SESSION['nbBases']++;
                } else {
                    $isEvo = true;
                    $_SESSION['nbEvos']++;
                }
                $types = [$data[4]];
                if (!empty($data[5])) {
                    array_push($types, $data[5]);
                }
                foreach ($_SESSION['types'] as $key => $type) {
                    foreach ($types as $t) {
                        if (strtoupper($key) == $t) {
                            $_SESSION['types'][$key]++;
                        }
                    }
                }
                $pkmn = new Pokemon($data[0], $data[1], $data[2], $isEvo, $types);
                array_push($_SESSION['pkmns'], $pkmn);
            }
            fclose($handle);
        }
    }

    include "./menu.php";

    if (empty($_GET)) {
        include "./home.php";
    } else {
        switch($_GET['page']) {
            case "home":
                include "./home.php";
                break;
            case "list":
                include "./list.php";
                break;
            case "new":
                include "./new.php";
                break;
            default:
                break;
        }
    }

?>