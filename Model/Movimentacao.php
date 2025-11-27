<?php
    require_once __DIR__ . '/../Config/Conexao.php';
    class Movimentacao {  
        private $id;
        private $tipo;
        private $quantidade;
        private $data;
        private $motivo;
        private $id_produto;
        private $id_usuario;

        public function __construct($tipo, $quantidade, $data, $motivo, $id_produto, $id_usuario){
            $this->tipo = $tipo;
            $this->quantidade = $quantidade;
            $this->data = $data;
            $this->motivo = $motivo;
            $this->id_produto = $id_produto;
            $this->id_usuario = $id_usuario;
        }

        public function registrar(){
            $conexao = Conexao::Conectar();

            $consultaSQL = "INSERT INTO movimentacoes (tipo, quantidade, data_movimentacao, motivo, id_produto, id_usuario) 
                VALUES (:tipo, :quantidade, :data, :motivo, :id_produto, :id_usuario)";
        
            $declaracao = $conexao->prepare($consultaSQL);
            $declaracao->bindParam(':tipo', $this->tipo);
            $declaracao->bindParam(':quantidade', $this->quantidade);
            $declaracao->bindParam(':data', $this->data);
            $declaracao->bindParam(':motivo', $this->motivo);
            $declaracao->bindParam(':id_produto', $this->id_produto);
            $declaracao->bindParam(':id_usuario', $this->id_usuario);
            
            return $declaracao->execute();
        }

        public function listarPorUsuario($id_usuario){
            $conexao = Conexao::Conectar();

            $consultaSQL = "SELECT 
                        p.sku, 
                        p.nome, 
                        m.tipo, 
                        m.quantidade, 
                        m.data_movimentacao, 
                        m.motivo 
                    FROM movimentacoes m
                    JOIN produtos p ON m.id_produto = p.id
                    WHERE p.id_usuario = :id_usuario
                    ORDER BY m.data_movimentacao DESC";

            $declaracao = $conexao->prepare($consultaSQL);
            $declaracao->bindValue(":id_usuario", $id_usuario);
            $declaracao->execute();

            return $declaracao->fetchAll(PDO::FETCH_ASSOC);
        }

        public function getId(){
            return $this->id;
        }

        public function getTipo(){
            return $this->tipo;
        }

        public function getQuantidade(){
            return $this->quantidade;
        }

        public function getData(){
            return $this->data;
        }

        public function getMotivo(){
            return $this->motivo;
        }

        public function getIdProduto(){
            return $this->id_produto;
        }
    }
?>