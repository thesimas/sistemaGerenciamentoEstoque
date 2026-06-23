<?php
    require_once __DIR__ . '/Produto.php';
    require_once __DIR__ . '/Usuario.php';

    class Movimentacao {
        private $id;
        private $tipo;
        private $quantidade;
        private $data;
        private $motivo;
        private Produto $produto;
        private Usuario $usuario;

        public function __construct($tipo, $quantidade, $data, $motivo, Produto $produto, Usuario $usuario){
            $this->tipo = $tipo;
            $this->quantidade = $quantidade;
            $this->data = $data;
            $this->motivo = $motivo;
            $this->produto = $produto;
            $this->usuario = $usuario;
        }

        public function getId(){
            return $this->id;
        }

        public function setId($id){
            $this->id = $id;
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

        public function getProduto(): Produto {
            return $this->produto;
        }

        public function getUsuario(): Usuario {
            return $this->usuario;
        }

        public function isEntrada(): bool {
            return $this->tipo === 'entrada';
        }

        public function isSaida(): bool {
            return $this->tipo === 'saida';
        }
    }
?>