<?php
require_once __DIR__ . '/Usuario.php';


class Cliente extends Usuario {
    private $nomeEmpresa;

    public function __construct($nome, $email, $senha, $nomeEmpresa) {
        parent::__construct($nome, $email, $senha);
        $this->nomeEmpresa = $nomeEmpresa;
    }

    public function buscarPorId($id){
        $conexao = Conexao::Conectar();
        $sql = "SELECT id, nome, email, senha FROM usuarios WHERE id = :id AND tipo = 'cliente'";
        $stmt = $conexao->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function atualizarPerfil($id, $nome, $email, $senha){
        $conexao = Conexao::Conectar();
        $sql = "UPDATE usuarios SET nome = :nome, email = :email, senha = :senha WHERE id = :id AND tipo = 'cliente'";
        $stmt = $conexao->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->bindValue(':nome', $nome);
        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':senha', $senha);

        return $stmt->execute();
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