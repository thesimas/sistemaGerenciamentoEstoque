<?php 
    require_once __DIR__ . "/../Model/Administrador.php";

    class AdminController {

        public function listarUsuarios(){
            session_start();

            if(!isset($_SESSION['id']) || $_SESSION['tipo'] != 'admin'){
                header("Location: ../index.php");
                exit();
            }

            $adminModel = new Administrador(null, null, null);

            $listaUsuarios = $adminModel->listarUsuarios($_SESSION['id']);

            require_once __DIR__ . '/../View/Admin/ListarUsuarios.php';
        }

        public function excluirUsuario(){
            session_start();

            if(!isset($_SESSION['id']) || $_SESSION['tipo'] != 'admin'){
                header("Location: ../index.php");
                exit();
            }

            $id_usuarioASerExcluido = $_GET['id'];

            $adminModel = new Administrador(null, null, null);

            $adminModel->excluirUsuario($id_usuarioASerExcluido);

            header("Location: AdminController.php?acao=listar");
            exit();
        }

    }

    if(isset($_REQUEST['acao'])){
        $controller = new AdminController();

        switch($_REQUEST['acao']){
            case 'listar':
                $controller->listarUsuarios();
                break;
            case 'excluirUsuario':
                $controller->excluirUsuario();
                break;
            default:
                $controller->listarUsuarios();
                break;
        }
    }
?>