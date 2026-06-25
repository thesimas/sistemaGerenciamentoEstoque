<?php
    require_once __DIR__ . '/../Model/DAO/UsuarioDAO.php';

    class AdminController {

        private UsuarioDAO $usuarioDAO;

        public function __construct() {
            $this->usuarioDAO = new UsuarioDAO();
        }

        public function listarUsuarios(){
            session_start();

            if(!isset($_SESSION['id']) || $_SESSION['tipo'] != 'admin'){
                header("Location: ../index.php");
                exit();
            }

            // Agora recebemos um array de OBJETOS Usuario (Cliente ou Administrador)
            $listaUsuarios = $this->usuarioDAO->listarTodosExceto($_SESSION['id']);

            require_once __DIR__ . '/../View/Admin/ListarUsuarios.php';
        }

        public function excluirUsuario(){
            session_start();

            if(!isset($_SESSION['id']) || $_SESSION['tipo'] != 'admin'){
                header("Location: ../index.php");
                exit();
            }

            $id_usuarioASerExcluido = $_GET['id'];

            $this->usuarioDAO->excluir($id_usuarioASerExcluido);

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
