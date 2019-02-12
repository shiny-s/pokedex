<?php
    if (!isset($_SESSION)) {
        session_start();
        var_dump($_SESSION);
    }
?>

<!DOCTYPE html>
<html>
    <body>
        <label>Nombre de Pokémons : <?php echo sizeof($_SESSION['pkmns']); ?></label>
        <br/>
        <label>Nombre de Pokémons par type :</label>
        <br/>
        <table>
            <tr>
                <th>Type</th>
                <th>Nombre</th>
            </tr>
            <?php
                foreach ($_SESSION['types'] as $key => $number) {
                    echo "<tr>";
                    echo sprintf("<td>%s</td>", $key);
                    echo sprintf("<td>%d</td>", $number);
                    echo "</tr>";
                }
            ?>
        </table>
        <br/>
        <?php
            echo sprintf(
                '<label>Nombre de Pokémons de base : %1$d et d\'évolutions : %2$d</label>',
                $_SESSION['nbBases'],
                $_SESSION['nbEvos']
            );
        ?>
    </body>
</html>