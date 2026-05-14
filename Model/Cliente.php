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
        $sql = "SELECT id, nome, email, senha, foto_perfil FROM usuarios WHERE id = :id AND tipo = 'cliente'";
        $stmt = $conexao->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function atualizarPerfil($id, $nome, $email, $senha, $fotoPerfil){
        $conexao = Conexao::Conectar();

        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

        $sql = "UPDATE usuarios SET nome = :nome, email = :email, senha = :senha, foto_perfil = :foto_perfil WHERE id = :id AND tipo = 'cliente'";
        $stmt = $conexao->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->bindValue(':nome', $nome);
        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':senha', $senhaHash);
        $stmt->bindValue(':foto_perfil', $fotoPerfil['name']);

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