<?php 
    
    require_once __DIR__ . '/Usuario.php'; 
    
    class Categoria {
        private $id;
        private $nome; 
        private Usuario $usuario; 

        public function __construct($id, $nome, Usuario $usuario){
            $this->id = $id;
            $this->nome = $nome;
            $this->usuario = $usuario;
        }

        public function getId(){
            return $this->id;
        }

        public function getNome() {
            return $this->nome;
        }

        public function getUsuario(): Usuario {
            return $this->usuario;
        }

        public function setId($id) {
            $this->id = $id;
        }

        public function setNome($nome) {
            $this->nome = $nome;
        }

        public function setUsuario(Usuario $usuario) {
            $this->usuario = $usuario;
        }
    }
?>