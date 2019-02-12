<?php

    class Pokemon {
        private $id;
        private $name;
        private $xp;
        private $isEvolution;
        private $types;

        public function __construct($id, $name, $xp, $isEvolution, $types) {
            $this->id = $id;
            $this->name = $name;
            $this->xp = $xp;
            $this->isEvolution = $isEvolution;
            $this->types = $types;
        }

        public function getId() {
            return $this->id;
        }

        public function getName() {
            return $this->name;
        }

        public function getXp() {
            return $this->xp;
        }

        public function getIsEvolution() {
            return $this->isEvolution;
        }

        public function getTypes() {
            return $this->types;
        }

        public function __toString() {
            $str_types = $this->types[0];
            if (isset($this->types[1])) {
                $tr_types .= sprintf(", %s", $this->$types[1]);
            }
            return sprintf('%1$s - %2$s', $this->name, $str_types);
        }
    }