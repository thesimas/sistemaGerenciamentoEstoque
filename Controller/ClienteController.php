<?php
    require_once __DIR__ . '/../Model/Cliente.php';

    class ClienteController {

        public function dashboard(){
            session_start();
        
            if(!isset($_SESSION['id']) || $_SESSION['tipo'] != 'cliente'){ 
                header("Location: ../index.php"); 
                exit(); 
            }

            $cliente = new Cliente(null, null, null, null);
            $dadosCliente = $cliente->buscarPorId($_SESSION["id"]);
            $_SESSION['nome'] = $dadosCliente['nome']; 
            $_SESSION['email'] = $dadosCliente['email']; 
            $_SESSION['foto_perfil'] = $dadosCliente['foto_perfil']; 
            if($dadosCliente['foto_perfil']){
                $_SESSION['foto_perfil'] = $dadosCliente['foto_perfil'];
            } else {
                $_SESSION['foto_perfil'] = null; 
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
            $_SESSION['foto_perfil'] = $dadosCliente['foto_perfil']; 
            if($dadosCliente['foto_perfil']){
                $_SESSION['foto_perfil'] = $dadosCliente['foto_perfil'];
            } else {
                $_SESSION['foto_perfil'] = null; 
            }

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
            $fotoPerfil = $_FILES['foto_perfil'];

            if($fotoPerfil['error'] === UPLOAD_ERR_OK){
                $extensao = strtolower(pathinfo($fotoPerfil['name'], PATHINFO_EXTENSION));
                $nomeFoto = uniqid('foto_perfil_') . '.' . $extensao;
                $diretorioDestino = __DIR__ . '/../View/Assets/Imagens/Uploads/';

                if(!is_dir($diretorioDestino)){
                    mkdir($diretorioDestino, 0755, true);
                }

                $caminhoCompleto = $diretorioDestino . $nomeFoto;

                if(move_uploaded_file($fotoPerfil['tmp_name'], $caminhoCompleto)){
                    $fotoPerfil['name'] = $nomeFoto;
                } else {
                    $fotoPerfil['name'] = null;
                }
            } else {
                $fotoPerfil['name'] = null;
            }

            $cliente = new Cliente(null, null, null, null);
            $cliente->atualizarPerfil($id, $nome, $email, $senha, $fotoPerfil);

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