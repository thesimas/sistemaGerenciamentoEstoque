<?php
    require_once __DIR__ . '/Usuario.php';

    class Administrador extends Usuario {

        public function enderecoPaginaInicial(){
            return "../Controller/AdminController.php?acao=listar";
        }
    }
?>