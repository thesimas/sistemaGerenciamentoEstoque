<?php
    require_once __DIR__ . '/Usuario.php';

    class Cliente extends Usuario {
        private $nomeEmpresa;

        public function __construct($nome, $email, $senha, $nomeEmpresa, $fotoPerfil = null) {
            parent::__construct($nome, $email, $senha, $fotoPerfil);
            $this->nomeEmpresa = $nomeEmpresa;
        }

        public function enderecoPaginaInicial(){
            return "../Controller/ClienteController.php?acao=dashboard";
        }

        public function getNomeEmpresa(){
            return $this->nomeEmpresa;
        }

        public function setNomeEmpresa($nomeEmpresa){
            $this->nomeEmpresa = $nomeEmpresa;
        }
    }
?>