<?php require_once "./DB.php"; ?>
<!DOCTYPE html>
<html>
    <body>
        <label>Nombre de Pokémons : <?php echo DB::countPokemon(); ?></label>
        <br/>
        <label>Nombre de Pokémons par type :</label>
        <br/>
        <table>
            <tr>
                <th>Type</th>
                <th>Nombre</th>
            </tr>
            <?php
                foreach (DB::countTypes() as $type) {
                    echo "<tr>";
                    echo sprintf("<td>%s</td>", $type["libelle"]);
                    echo sprintf("<td>%d</td>", $type["nb"]);
                    echo "</tr>";
                }
            ?>
        </table>
        <br/>
        <?php
            echo sprintf(
                '<label>Nombre de Pokémons de base : %1$d et d\'évolutions : %2$d</label>',
                DB::countPokemonEvolution(false),
                DB::countPokemonEvolution(true)
            );
        ?>
    </body>
</html>