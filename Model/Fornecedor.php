<?php
    require_once __DIR__ . '/../Config/Conexao.php';
    
    class Fornecedor {
        private $id;
        private $nomeEmpresa;
        private $cnpj;
        private $email;
        private $telefone;
        private $id_usuario;

        public function __construct($nomeEmpresa, $cnpj, $email, $telefone, $id_usuario){
            $this->nomeEmpresa = $nomeEmpresa;
            $this->cnpj = $cnpj;
            $this->email = $email;
            $this->telefone = $telefone;
            $this->id_usuario = $id_usuario;
        }

        // Criar o salvar, listar, excluir fornecedor;
        public function salvar(){
            $conexao = Conexao::Conectar();

            $consultaSql = "INSERT INTO fornecedores (nome_empresa, cnpj, email, telefone, id_usuario) 
                            VALUES (:nome_empresa, :cnpj, :email, :telefone, :id_usuario)";         

            $declaracao = $conexao->prepare($consultaSql);
            $declaracao->bindValue(':nome_empresa', $this->nomeEmpresa);
            $declaracao->bindValue(':cnpj', $this->cnpj);
            $declaracao->bindValue(':email', $this->email);
            $declaracao->bindValue(':telefone', $this->telefone);
            $declaracao->bindValue(':id_usuario', $this->id_usuario);

            return $declaracao->execute();
        }

        public function listarFornecedores($id_usuario){
            $conexao = Conexao::Conectar();

            $consultaSql = "SELECT * FROM fornecedores WHERE id_usuario = :id_usuario";

            $declaracao = $conexao->prepare($consultaSql);
            $declaracao->bindValue(':id_usuario', $id_usuario);
            $declaracao->execute();

            return $declaracao -> fetchAll(PDO::FETCH_ASSOC);
        }

        public function excluirFornecedor($id_usuario, $id_fornecedor){
            $conexao = Conexao::Conectar();

            $consultaSql = "DELETE FROM fornecedores WHERE id = :id_fornecedor AND id_usuario = :id_usuario";

            $declaracao = $conexao->prepare($consultaSql);
            $declaracao->bindValue(":id_fornecedor", $id_fornecedor);
            $declaracao->bindValue(":id_usuario", $id_usuario);

            return $declaracao->execute();
        }
        // Para editar FORNECEDOR precisa desses dois métodos: 
        public function buscarPorId($id_fornecedor, $id_usuario){
            $conexao = Conexao::Conectar();
            $consultaSql = "SELECT * FROM fornecedores WHERE id = :id_fornecedor AND id_usuario = :id_usuario";

            $declaracao = $conexao->prepare($consultaSql);
            $declaracao->bindValue(":id_fornecedor", $id_fornecedor);
            $declaracao->bindValue(":id_usuario", $id_usuario);
            $declaracao->execute();
            return $declaracao->fetch(PDO::FETCH_ASSOC);
        }

        public function atualizar($id, $nome, $cnpj, $email, $telefone, $id_usuario){
            $conexao = Conexao::Conectar();
            $consultaSql = "UPDATE fornecedores SET nome_empresa = :nome, cnpj = :cnpj, email = :email, telefone = :telefone 
                    WHERE id = :id AND id_usuario = :id_usuario";

            $declaracao = $conexao->prepare($consultaSql);
            $declaracao->bindValue(":nome", $nome);
            $declaracao->bindValue(":cnpj", $cnpj);
            $declaracao->bindValue(":email", $email);
            $declaracao->bindValue(":telefone", $telefone);
            $declaracao->bindValue(":id", $id);
            $declaracao->bindValue(":id_usuario", $id_usuario);
            return $declaracao->execute();
        }

        public function getId(){
            return $this->id;
        }

        public function getNomeEmpresa(){
            return $this->nomeEmpresa;
        }

        public function getCnpj(){
            return $this->cnpj;
        }

        public function getEmail(){
            return $this->email;
        }

        public function getTelefone(){
            return $this->telefone;
        }

        public function getIdUsuario(){
            return $this->id_usuario;
        }
    }
?>