<?php
    require_once __DIR__ . '/Usuario.php';
    require_once __DIR__ . '/Fornecedor.php';
    require_once __DIR__ . '/Categoria.php';

    class Produto {
        private $id;
        private $sku;
        private $nome;
        private $descricao;
        private $preco;
        private $quantidade;
        private $estoqueMinimo;
        private Usuario $usuario;
        private ?Fornecedor $fornecedor;
        private ?Categoria $categoria;

        public function __construct($sku, $nome, $descricao, $preco, $quantidade, $estoqueMinimo, Usuario $usuario, ?Fornecedor $fornecedor = null, ?Categoria $categoria = null){
            $this->sku = $sku;
            $this->nome = $nome;
            $this->descricao = $descricao;
            $this->preco = $preco;
            $this->quantidade = $quantidade;
            $this->estoqueMinimo = $estoqueMinimo;
            $this->usuario = $usuario;
            $this->fornecedor = $fornecedor;
            $this->categoria = $categoria;
        }

        public function getId(){
            return $this->id;
        }

        public function setId($id){
            $this->id = $id;
        }

        public function getSku(){
            return $this->sku;
        }

        public function setSku($sku){
            $this->sku = $sku;
        }

        public function getNome(){
            return $this->nome;
        }

        public function setNome($nome){
            $this->nome = $nome;
        }

        public function getDescricao(){
            return $this->descricao;
        }

        public function setDescricao($descricao){
            $this->descricao = $descricao;
        }

        public function getPreco(){
            return $this->preco;
        }

        public function setPreco($preco){
            $this->preco = $preco;
        }

        public function getQuantidade(){
            return $this->quantidade;
        }

        public function setQuantidade($quantidade){
            $this->quantidade = $quantidade;
        }

        public function getEstoqueMinimo(){
            return $this->estoqueMinimo;
        }

        public function setEstoqueMinimo($estoqueMinimo){
            $this->estoqueMinimo = $estoqueMinimo;
        }

        public function getUsuario(): Usuario {
            return $this->usuario;
        }

        public function setUsuario(Usuario $usuario){
            $this->usuario = $usuario;
        }

        public function getFornecedor(): ?Fornecedor {
            return $this->fornecedor;
        }

        public function setFornecedor(?Fornecedor $fornecedor){
            $this->fornecedor = $fornecedor;
        }

        public function getCategoria(): ?Categoria {
            return $this->categoria;
        }

        public function setCategoria(?Categoria $categoria){
            $this->categoria = $categoria;
        }

        /**
         * Regra de negócio que antes vivia espalhada em relatórios/telas:
         * agora pertence à própria entidade.
         */
        public function estoqueBaixo(): bool {
            return $this->quantidade <= $this->estoqueMinimo;
        }
    }
?>