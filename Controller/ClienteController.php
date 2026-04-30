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

            session_start();

            if(!isset($_SESSION['id']) || $_SESSION['tipo'] != 'cliente'){
                header("Location: ../index.php");
                exit();
            }
            
            $cliente = new Cliente(null, null, null, null);
            $dadosCliente = $cliente->buscarPorId($_SESSION['id']);
            $_SESSION['nome'] = $dadosCliente['nome']; 
            $_SESSION['email'] = $dadosCliente['email']; 

            require_once __DIR__ . '/../View/Cliente/Perfil.php';
        }

        public function prepararEdicaoPerfil(){
            session_start();

            if(!isset($_SESSION['id']) || $_SESSION['tipo'] != 'cliente'){
                header("Location: ../index.php");
                exit();
            }
            
            $cliente = new Cliente(null, null, null, null);
            $dadosCliente = $cliente->buscarPorId($_SESSION['id']);

            require_once __DIR__ . '/../View/Cliente/EditarPerfil.php';
        }

        public function atualizarPerfil(){
            session_start();

            if(!isset($_SESSION['id']) || $_SESSION['tipo'] != 'cliente'){
                header("Location: ../index.php");
                exit();
            }

            $id = $_SESSION['id'];
            $nome = $_POST['nome'];
            $email = $_POST['email'];
            $senha = $_POST['senha'];

            $cliente = new Cliente(null, null, null, null);
            $cliente->atualizarPerfil($id, $nome, $email, $senha);

            header("Location: ../Controller/ClienteController.php?acao=perfil");
            exit();
        }

        public function configuracoes(){

            if(!isset($_SESSION['id']) || $_SESSION['tipo'] != 'cliente'){
                header("Location: ../index.php");
                exit();
            }

            echo "<h1>Página de Configurações em construção...</h1>";
        }

        public function relatorios(){

            if(!isset($_SESSION['id']) || $_SESSION['tipo'] != 'cliente'){
                header("Location: ../index.php");
                exit();
            }
            
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
            case 'prepararEdicaoPerfil':
                $controller->prepararEdicaoPerfil();
                break;
            case 'atualizarPerfil':
                $controller->atualizarPerfil();
                break;
            case 'configuracoes':
                $controller->configuracoes();
                break;
            case 'relatorios':
                $controller -> relatorios();
                break;
        }
    }
?>