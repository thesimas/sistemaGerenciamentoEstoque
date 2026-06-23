<?php
require_once __DIR__ . '/Usuario.php';

class Fornecedor {
    private $id;
    private $nomeEmpresa;
    private $cnpj;
    private $email;
    private $telefone;
    private Usuario $usuario;

    public function __construct($nomeEmpresa, $cnpj, $email, $telefone, Usuario $usuario) {
        $this->nomeEmpresa = $nomeEmpresa;
        $this->cnpj = $cnpj;
        $this->email = $email;
        $this->telefone = $telefone;
        $this->usuario = $usuario;
    }
    
    public function getId() {
        return $this->id;
    }

    public function getNomeEmpresa() {
        return $this->nomeEmpresa;
    }

    public function getCnpj() {
        return $this->cnpj;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getTelefone() {
        return $this->telefone;
    }

    public function getUsuario(): Usuario {
        return $this->usuario;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setNomeEmpresa($nomeEmpresa) {
        $this->nomeEmpresa = $nomeEmpresa;
    }

    public function setCnpj($cnpj) {
        $this->cnpj = $cnpj;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setTelefone($telefone) {
        $this->telefone = $telefone;
    }

    public function setUsuario(Usuario $usuario) {
        $this->usuario = $usuario;
    }
}
?>