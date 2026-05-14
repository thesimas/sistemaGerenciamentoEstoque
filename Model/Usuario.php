<?php
    require_once __DIR__ . '/../Config/Conexao.php';
    abstract class Usuario {

        protected $id;
        protected $nome;
        protected $email;
        protected $senha;

        public function __construct($nome, $email, $senha){
            $this->nome = $nome;
            $this->email = $email;
            $this->senha = $senha;
        }

        abstract public function enderecoPaginaInicial(); // Esse método abstrado vai ser implementado nas classes filhas.

        public static function verificaLogin($email, $senha){
            $conexao = Conexao::Conectar();

            $consultaSQL = "SELECT * FROM usuarios WHERE email = :email";

            $declaracao = $conexao->prepare($consultaSQL);
            $declaracao->bindValue(":email", $email);
            $declaracao->execute();

            if($declaracao->rowCount() > 0){
                $usuario = $declaracao->fetch(PDO::FETCH_ASSOC);
                if(password_verify($senha, $usuario['senha'])){
                    return $usuario;
                }
            }
            return false;
        }

        public function getId(){ 
            return $this->id; 
        }
        public function getNome(){ 
            return $this->nome; 
        }
        public function getEmail(){ 
            return $this->email; 
        }

    }
?>