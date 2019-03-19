<?php

    require_once "./DB.php";
    include "./menu.php";

    /* Done once */
    // DB::initTypes();
    // DB::initPokemon();

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