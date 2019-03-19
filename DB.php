<?php

    require_once "./Pokemon.php";

    class DB {

        public static function getPDO() {
            try {
                $conn = "mysql:host=localhost;dbname=pokemon";
                $user = "root";
                $pass = "~MQaCXtk6";
                $options = [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                ];
                return new PDO($conn, $user, $pass, $options);
            } catch (\PDOException $e) {
                return null;
            }
        }
    
        public static function initPokemon() {
            if (($handle = fopen("./pokemon.csv", "r")) != false) {
                while (($data = fgetcsv($handle)) != false) {
                    if ($data[3] == "n") {
                        $isEvo = false;
                    } else {
                        $isEvo = true;
                    }
                    $types = [strtolower($data[4])];
                    if (!empty($data[5])) {
                        array_push($types, strtolower($data[5]));
                    }
                    $pkmn = new Pokemon($data[0], $data[1], $data[2], $isEvo, $types);
                    if (!DB::insertPokemon($pkmn)) {
                        return false;
                    }
                }
                fclose($handle);
                return true;
            }
            return false;
        }

        public static function initTypes() {
            $types = array(
                "Acier",
                "Combat",
                "Dragon",
                "Eau",
                "Electrik",
                "Fee",
                "Feu",
                "Glace",
                "Insecte",
                "Normal",
                "Plante",
                "Poison",
                "Psy",
                "Roche",
                "Sol",
                "Spectre",
                "Tenebres",
                "Vol"
            );
            $pdo = DB::getPDO();
            if (!empty($pdo)) {
                $values = "VALUES (?)";
                for ($i = 1; $i < count($types); $i++) {
                    $values .= ", (?)";
                }
                $query = sprintf("INSERT INTO elementary_type (libelle) %s;", $values);
                $stmt = $pdo->prepare($query);
                $stmt->execute($types);
                if ($stmt->rowCount() == count($types)) {
                    return true;
                }
            }
            return false;
        }

        public static function getPokemon() {
            $pdo = DB::getPDO();
            if (!empty($pdo)) {
                return $pdo->query("SELECT id, nom FROM pokemon_type;")->fetchAll();
            }
            return null;
        }
    
        public static function insertPokemon($pokemon) {
            $pdo = DB::getPDO();
            if (!empty($pdo)) {
                $query = "INSERT INTO pokemon_type (nom, evolution, starter, type_courbe_niveau)
                          VALUES (:nom, :evolution, :starter, :type_courbe_niveau);";
                $stmt = $pdo->prepare($query);
                $stmt->bindValue(":nom", $pokemon->getName());
                $stmt->bindValue(":evolution", (int)$pokemon->getIsEvolution());
                $stmt->bindValue(":starter", (int)!$pokemon->getIsEvolution());
                $stmt->bindValue(":type_courbe_niveau", $pokemon->getXp());
                if ($stmt->execute() && $stmt->rowCount() == 1) {
                    $lastPkmn = $pdo->lastInsertId();
                    foreach ($pokemon->getTypes() as $type) {
                        $query = "INSERT INTO pokemon_e_type (id_poke, id_type)
                                  VALUES (:id, (SELECT id FROM elementary_type WHERE libelle = :type));";
                        $stmt = $pdo->prepare($query);
                        $stmt->bindValue(":id", $lastPkmn);
                        $stmt->bindValue(":type", $type);
                        if (!$stmt->execute() || $stmt->rowCount() != 1) {
                            return false;
                        }
                    }
                    return true;
                }
            }
            return false;
        }
    
        public static function countPokemon() {
            $pdo = DB::getPDO();
            if (!empty($pdo)) {
                $count = $pdo->query("SELECT COUNT(*) FROM pokemon_type;")->fetchColumn();
                return $count;
            }
            return -1;
        }

        public static function countTypes() {
            $pdo = DB::getPDO();
            if (!empty($pdo)) {
                $query = "SELECT count(*) as nb, type.libelle 
                          FROM pokemon_e_type 
                          INNER JOIN elementary_type AS type ON type.id = pokemon_e_type.id_type 
                          GROUP BY (type.libelle);";
                return $pdo->query($query)->fetchAll();
            }
            return null;
        }

        public static function countPokemonEvolution($fetchEvolution) {
            $pdo = DB::getPDO();
            if (!empty($pdo)) {
                $column = ($fetchEvolution) ? "evolution" : "starter";
                $query = sprintf("SELECT COUNT(*) 
                                  FROM pokemon_type
                                  WHERE %s = ?;", $column);
                $stmt = $pdo->prepare($query);
                $stmt->bindValue(1, (int)true);
                if ($stmt->execute()) {
                    return $stmt->fetchColumn();
                }
            }
            return -1;
        }

        public static function getPokemonDetails($id) {
            $pdo = DB::getPDO();
            if (!empty($pdo)) {
                $query = "SELECT pokemon.id, pokemon.nom, pokemon.evolution, pokemon.type_courbe_niveau, type.libelle AS type
                          FROM pokemon_type AS pokemon
                          INNER JOIN pokemon_e_type ON pokemon_e_type.id_poke = pokemon.id
                          INNER JOIN elementary_type AS type ON type.id = pokemon_e_type.id_type
                          WHERE pokemon.id = ?;";
                $stmt = $pdo->prepare($query);
                $stmt->bindValue(1, $id);
                if ($stmt->execute()) {
                    return $stmt->fetchAll();
                }
            }
            return null;
        }

        public static function deletePokemon($id) {
            $pdo = DB::getPDO();
            if (!empty($pdo)) {
                try {
                    $pdo->beginTransaction();
                    $query = "DELETE FROM pokemon_e_type WHERE id_poke = ?;";
                    $stmt = $pdo->prepare($query);
                    $stmt->bindValue(1, $id);
                    $stmt->execute();
                    $query = "DELETE FROM pokemon_type WHERE id = ?;";
                    $stmt = $pdo->prepare($query);
                    $stmt->bindValue(1, $id);
                    $stmt->execute();
                    $pdo->commit();
                    return true;
                } catch (\Exception $e) {
                    $pdo->rollback();
                    return false;
                }
            }
            return false;
        }

        public static function getTypes() {
            $pdo = DB::getPDO();
            if (!empty($pdo)) {
                $query = "SELECT * FROM elementary_type;";
                return $pdo->query($query)->fetchAll();
            }
            return null;
        }
    }