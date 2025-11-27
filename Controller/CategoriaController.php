<?php
    require_once __DIR__ . '/../Model/Categoria.php';

    class CategoriaController {

        // Esse método irá apenas chamar a VIEW CADASTROCategoria
        public function prepararCadastroCategoria(){
            session_start();
            if(!isset($_SESSION['id'])){ header("Location: ../index.php"); exit(); }
            
            require_once __DIR__ . '/../View/Categoria/CadastrarCategoria.php';
        }

        public function salvarCategoria(){
            session_start();

            if(!isset($_SESSION['id'])){
                header("Location: ../index.php");
                exit();
            }

            $nome = $_POST['nome'];
            $id_usuario = $_SESSION['id'];

            $categoria = new Categoria(null, $nome, $id_usuario);
            $categoria->salvar();

            header("Location: ../Controller/CategoriaController.php?acao=listarCategorias");
            exit();
        }

        public function listarCategorias(){
            session_start();

            if(!isset($_SESSION['id'])){
                header("Location: ../index.php");
                exit();
            }
            $categoria = new Categoria(null, null, null);
            
            $listaCategorias = $categoria->listarCategorias($_SESSION['id']);

            require_once __DIR__ . '/../View/Categoria/ListarCategoria.php';
        }

        public function excluirCategoria(){
            session_start();

            if(!isset($_SESSION['id'])){
                header("Location: ../index.php");
                exit();
            }

            $id_categoria = $_GET['id_categoria'];
            $id_usuario = $_SESSION['id'];

            $categoria = new Categoria(null, null, null);
            $categoria->excluirCategoria($id_usuario, $id_categoria);

            header("Location: ../Controller/CategoriaController.php?acao=listarCategorias");
            exit();
        }

        public function prepararEdicaoCategoria(){
            session_start();

            if(!isset($_SESSION['id'])){
                header("Location: ../index.php");
                exit();
            }

            if(isset($_GET['id_categoria'])){
                $id_categoria = $_GET['id_categoria'];
                $id_usuario = $_SESSION['id'];
            }

            $categoriaExistente = new Categoria(null, null, null);
            $categoriaExistente->buscarPorIdCategoria($id_categoria, $id_usuario);
            
            require_once __DIR__ . '/../View/Categoria/EditarCategoria.php';
        }

        public function atualizarCategoria(){
            session_start();

            if(!isset($_SESSION['id'])){
                header("Location: ../index.php");
                exit();
            }

            $id_categoria = $_POST['id_categoria'];
            $id_usuario = $_SESSION['id'];
            $nome = $_POST['nome'];

            $categoria = new Categoria(null, null, null);
            $categoria->atualizar($id_categoria, $nome, $id_usuario);

            header("Location: ../Controller/CategoriaController.php?acao=listarCategorias");
            exit();
        }
    }

    // Roteamento

    if(isset($_REQUEST['acao'])){
        $controller = new CategoriaController();

        switch($_REQUEST['acao']){
            case 'listarCategorias':
                $controller->listarCategorias();
                break;
            case 'salvarCategoria':
                $controller->salvarCategoria();
                break;
            case 'excluirCategoria':
                $controller->excluirCategoria();
                break;
            case 'prepararEdicao':
                $controller->prepararEdicaoCategoria();
                break;
            case 'atualizarFornecedor':
                $controller->atualizarCategoria();
                break;
            case 'prepararCadastroCategoria':
                $controller->prepararCadastroCategoria();
                break;
        }
    }
?>