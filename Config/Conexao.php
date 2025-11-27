<?php
    class Conexao {

        public static function Conectar(){
            $hostname = "localhost";
            $usuario = "root";
            $senha = "";
            $database = "sistema_estoque";

            try {
                $conexao = new PDO("mysql:host=$hostname;dbname=$database", $usuario, $senha);
                $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            }catch(PDOException $erro){
                echo "<p>Erro na conexão: " . $erro->getMessage() . "</p>";
                die;
            }
            
            return $conexao;
        }
    }
?>