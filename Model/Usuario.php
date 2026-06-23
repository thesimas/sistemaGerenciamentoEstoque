<?php
    abstract class Usuario {

        protected $id;
        protected $nome;
        protected $email;
        protected $senha;
        protected $fotoPerfil;

        public function __construct($nome, $email, $senha, $fotoPerfil = null){
            $this->nome = $nome;
            $this->email = $email;
            $this->senha = $senha;
            $this->fotoPerfil = $fotoPerfil;
        }

        // Esse método abstrato vai ser implementado nas classes filhas.
        abstract public function enderecoPaginaInicial();

        public function getId(){
            return $this->id;
        }

        public function setId($id){
            $this->id = $id;
        }

        public function getNome(){
            return $this->nome;
        }

        public function setNome($nome){
            $this->nome = $nome;
        }

        public function getEmail(){
            return $this->email;
        }

        public function setEmail($email){
            $this->email = $email;
        }

        // Necessário para a DAO conseguir gerar o hash / validar login.
        public function getSenha(){
            return $this->senha;
        }

        public function setSenha($senha){
            $this->senha = $senha;
        }

        public function getFotoPerfil(){
            return $this->fotoPerfil;
        }

        public function setFotoPerfil($fotoPerfil){
            $this->fotoPerfil = $fotoPerfil;
        }
    }
?>