<?php
    require_once __DIR__ . '/Usuario.php';

    class Cliente extends Usuario {
        private $nomeEmpresa;
        private $responsavelTecnico;

        public function __construct($nome, $email, $senha, $nomeEmpresa, $responsavelTecnico = null, $fotoPerfil = null) {
        parent::__construct($nome, $email, $senha, $fotoPerfil);
        $this->nomeEmpresa = $nomeEmpresa;
        $this->responsavelTecnico = $responsavelTecnico;
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

        public function getResponsavelTecnico(){ 
            return $this->responsavelTecnico; 
        }
        public function setResponsavelTecnico($responsavelTecnico){ 
            $this->responsavelTecnico = $responsavelTecnico; 
        }
    }
?>