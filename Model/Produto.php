<?php
    class Produto {
        private $id;
        private $sku;
        private $nome;
        private $descricao;
        private $preco;
        private $quantidade;
        private $estoqueMinimo;
        private $id_usuario;
        private $id_fornecedor;
        private $id_categoria;

        public function __construct($sku, $nome, $descricao, $preco, $quantidade, $estoqueMinimo, $id_usuario, $id_fornecedor, $id_categoria){
            $this->sku = $sku;
            $this->nome = $nome;
            $this->descricao = $descricao;
            $this->preco = $preco;
            $this->quantidade = $quantidade;
            $this->estoqueMinimo = $estoqueMinimo;
            $this->id_usuario = $id_usuario;
            $this->id_fornecedor = $id_fornecedor;
            $this->id_categoria = $id_categoria;
        }

        public function salvar(){
            $conexao = Conexao::Conectar();

            $consultaSQL = "INSERT INTO produtos (sku, nome, descricao, preco, quantidade, estoque_minimo, id_usuario, id_fornecedor, id_categoria) 
                            VALUES (:sku, :nome, :descricao, :preco, :quantidade, :estoqueMinimo, :id_usuario, :id_fornecedor, :id_categoria)";
        
            $declaracao = $conexao->prepare($consultaSQL);
            $declaracao->bindParam(':sku', $this->sku);
            $declaracao->bindParam(':nome', $this->nome);
            $declaracao->bindParam(':descricao', $this->descricao);
            $declaracao->bindParam(':preco', $this->preco);
            $declaracao->bindParam(':quantidade', $this->quantidade);
            $declaracao->bindParam(':estoqueMinimo', $this->estoqueMinimo);
            $declaracao->bindParam(':id_usuario', $this->id_usuario);
            $declaracao->bindParam(':id_fornecedor', $this->id_fornecedor);
            $declaracao->bindParam(':id_categoria', $this->id_categoria);

            $declaracao->execute();

            return $conexao->lastInsertId();
        }

        public function listarPorUsuario($id_usuario){
            $conexao = Conexao::Conectar();

            $consultaSQL = "SELECT * FROM produtos WHERE id_usuario = :id_usuario";

            $declaracao = $conexao->prepare($consultaSQL);
            $declaracao->bindValue(":id_usuario", $id_usuario);
            $declaracao->execute();

            return $declaracao->fetchAll(PDO::FETCH_ASSOC);
        }

        public function atualizarQuantidade($id_produto, $novaQuantidade, $id_usuario){
            $conexao = Conexao::Conectar();

            $consultaSQL = "UPDATE produtos SET quantidade = :quantidade WHERE id = :id AND id_usuario = :id_usuario";

            $declaracao = $conexao->prepare($consultaSQL);
            $declaracao->bindValue(":quantidade", $novaQuantidade);
            $declaracao->bindValue(":id", $id_produto);
            $declaracao->bindValue(":id_usuario", $id_usuario);

            return $declaracao->execute();
        }

        public function excluirProduto($id_produto, $id_usuario){
            $conexao = Conexao::Conectar();

            $consultaSQL = "DELETE FROM produtos WHERE id = :id AND id_usuario = :id_usuario";

            $declaracao = $conexao->prepare($consultaSQL);
            $declaracao->bindValue(":id", $id_produto);
            $declaracao->bindValue(":id_usuario", $id_usuario);

            return $declaracao->execute();
        }

        public function buscarPorId($id_produto, $id_usuario){
            $conexao = Conexao::Conectar();

            $consultaSQL = "SELECT * FROM produtos WHERE id = :id AND id_usuario = :id_usuario";

            $declaracao = $conexao->prepare($consultaSQL);
            $declaracao->bindValue(":id", $id_produto);
            $declaracao->bindValue(":id_usuario", $id_usuario);
            $declaracao->execute();

            return $declaracao->fetch(PDO::FETCH_ASSOC);
        }

        public function atualizar($id, $sku, $nome, $descricao, $preco, $estoqueMinimo, $id_cat, $id_forn, $id_usuario){
            $conexao = Conexao::Conectar();
            
            // Nota: Não atualizamos 'quantidade' aqui, apenas via movimentação!
            $sql = "UPDATE produtos SET sku=:sku, nome=:nome, descricao=:descricao, preco=:preco, 
                    estoque_minimo=:minimo, id_categoria=:cat, id_fornecedor=:forn 
                    WHERE id=:id AND id_usuario=:id_usuario";
            
            $declaracao = $conexao->prepare($sql);
            $declaracao->bindValue(":sku", $sku);
            $declaracao->bindValue(":nome", $nome);
            $declaracao->bindValue(":descricao", $descricao);
            $declaracao->bindValue(":preco", $preco);
            $declaracao->bindValue(":minimo", $estoqueMinimo);
            $declaracao->bindValue(":cat", $id_cat);
            $declaracao->bindValue(":forn", $id_forn);
            $declaracao->bindValue(":id", $id);
            $declaracao->bindValue(":id_usuario", $id_usuario);
            return $declaracao->execute();
        }

        public function getId(){
            return $this->id;
        }

        public function getSku(){
            return $this->sku;
        }

        public function getNome(){
            return $this->nome;
        }
        public function getDescricao(){
            return $this->descricao;
        }
        public function getPreco(){
            return $this->preco;
        }
        public function getQuantidade(){
            return $this->quantidade;
        }
        public function getEstoqueMinimo(){
            return $this->estoqueMinimo;
        }
        public function getIdUsuario(){
            return $this->id_usuario;
        }
        public function getIdFornecedor(){
            return $this->id_fornecedor;
        }
        public function getIdCategoria(){
            return $this->id_categoria;
        }

    }

?>