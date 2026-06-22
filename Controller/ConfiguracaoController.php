<?php 
    require_once __DIR__ . '/../Model/Configuracao.php';
    class ConfiguracaoController{

        public function buscarConfiguracoes(){
            session_start();
        
            if(!isset($_SESSION['id']) || $_SESSION['tipo'] != 'cliente'){ 
                header("Location: ../index.php"); 
                exit(); 
            }
        }

        public function atualizarConfiguracoes(){}

    }

    //Roteamento
    if(isset($_REQUEST['acao'])){
        $controller = new ConfiguracaoController();
        switch($_REQUEST['acao']){
            case 'buscarConfiguracoes':
                $controller->buscarConfiguracoes();
                break;
            case 'atualizarConfiguracoes':
                $contador->atualizarConfiguracoes();
                break;
        }
    }

?> 