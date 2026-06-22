<?php
    require_once __DIR__ . '/../Config/Conexao.php';
    class Configuracao {

        public function buscarConfiguracoes(){
            $conexao = Conexao :: Conectar();
            $sql = "SELECT * FROM configuracoes;";
            $stmt = $conexao->prepare($sql);

            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        public function atualizarConfiguracoes(){
            $conexao = Conexao :: Conectar();
            $sql = "";
            $stmt = $conexao->prepare($sql);

            $stmt->execute();
        }
    }
?>