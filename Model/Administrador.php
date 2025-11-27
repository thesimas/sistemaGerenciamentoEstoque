<?php 
    require_once __DIR__ . '/Usuario.php';

    class Administrador extends Usuario {

        public function enderecoPaginaInicial(){
            return "../Controller/AdminController.php?acao=listar";
        }

        public function excluirUsuario($idAlvo){
            $conexao = Conexao::Conectar();
            $consultaSql = "DELETE FROM usuarios WHERE id = :id";
            $declaracao = $conexao->prepare($consultaSql);
            $declaracao->bindValue(":id", $idAlvo);

            return $declaracao->execute();
        }

        public function listarUsuarios($idAdmin){
            $conexao = Conexao::Conectar();
            $consultaSql = "SELECT * FROM usuarios WHERE id != :id";
            $declaracao = $conexao->prepare($consultaSql);
            $declaracao->bindValue(':id', $idAdmin);
            $declaracao->execute();

            return $declaracao -> fetchAll(PDO::FETCH_ASSOC);
        }

    }
?>