<?php
require_once __DIR__ . '/Usuario.php';


class Cliente extends Usuario {
    private $nomeEmpresa;

    public function __construct($nome, $email, $senha, $nomeEmpresa) {
        parent::__construct($nome, $email, $senha);
        $this->nomeEmpresa = $nomeEmpresa;
    }

    public function enderecoPaginaInicial(){
        return "../Controller/ClienteController.php?acao=dashboard";
    }

    public function getNomeEmpresa(){
        return $this->nomeEmpresa;
    }
}
?>