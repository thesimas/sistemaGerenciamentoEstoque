<?php
    require_once __DIR__ . '/../Model/Cliente.php';

    class ClienteController {

        public function dashboard(){
            session_start();
        
            if(!isset($_SESSION['id']) || $_SESSION['tipo'] != 'cliente'){ 
                header("Location: ../index.php"); 
                exit(); 
            }
            
            require_once __DIR__ . '/../View/Cliente/Dashboard.php';
        }

        // Futuras implementações:
        public function perfil(){
            echo "<h1>Página de Perfil em construção...</h1>";
        }

        public function configuracoes(){
            echo "<h1>Página de Configurações em construção...</h1>";
        }
    }

    if(isset($_REQUEST['acao'])){
        $controller = new ClienteController();
        switch($_REQUEST['acao']){
            case 'dashboard':
                $controller->dashboard();
                break;
            case 'perfil':
                $controller->perfil();
                break;
            case 'configuracoes':
                $controller->configuracoes();
                break;
        }
    }
?>