<?php
    require_once __DIR__ . '/../Model/Categoria.php';
    require_once __DIR__ . '/../Model/Cliente.php';
    require_once __DIR__ . '/../Model/DAO/CategoriaDAO.php';

    class CategoriaController {

        private CategoriaDAO $categoriaDAO;

        public function __construct() {
            $this->categoriaDAO = new CategoriaDAO();
        }

        public function prepararCadastroCategoria() {
            session_start();
            if(!isset($_SESSION['id'])) { header("Location: ../index.php"); exit(); }
            
            require_once __DIR__ . '/../View/Categoria/CadastrarCategoria.php';
        }

        public function salvarCategoria() {
            session_start();
            if(!isset($_SESSION['id'])) { header("Location: ../index.php"); exit(); }

            $nome = trim($_POST['nome']);
            $id_usuario = (int)$_SESSION['id'];

            $usuarioTenant = new Cliente(null, null, null, null);
            $usuarioTenant->setId($id_usuario);

            $novaCategoria = new Categoria(null, $nome, $usuarioTenant);
            $this->categoriaDAO->inserir($novaCategoria);

            header("Location: ../Controller/CategoriaController.php?acao=listarCategorias&sucesso=" . urlencode("Categoria cadastrada com sucesso!"));
            exit();
        }

        public function listarCategorias() {
            session_start();
            if(!isset($_SESSION['id'])) { header("Location: ../index.php"); exit(); }

            // Agora recebemos um array de OBJETOS Categoria
            $listaCategorias = $this->categoriaDAO->listarPorUsuario($_SESSION['id']);

            require_once __DIR__ . '/../View/Categoria/ListarCategoria.php';
        }

        public function prepararEdicaoCategoria() {
            session_start();
            if(!isset($_SESSION['id'])) { header("Location: ../index.php"); exit(); }

            $id_categoria = (int)$_GET['id_categoria'];
            $id_usuario = (int)$_SESSION['id'];

            // O DAO retorna um OBJETO Categoria completo
            $categoria = $this->categoriaDAO->buscarPorId($id_categoria, $id_usuario);
            
            if (!$categoria) {
                header("Location: ../Controller/CategoriaController.php?acao=listarCategorias&erro=" . urlencode("Categoria não encontrada."));
                exit();
            }

            require_once __DIR__ . '/../View/Categoria/EditarCategoria.php';
        }

        public function atualizarCategoria() {
            session_start();
            if(!isset($_SESSION['id'])) { header("Location: ../index.php"); exit(); }

            $id_categoria = (int)$_POST['id_categoria'];
            $id_usuario = (int)$_SESSION['id'];
            $nome = trim($_POST['nome']);

            $usuarioTenant = new Cliente(null, null, null, null);
            $usuarioTenant->setId($id_usuario);

            // Montamos o objeto para atualizar
            $categoria = new Categoria($id_categoria, $nome, $usuarioTenant);
            $this->categoriaDAO->atualizar($categoria);

            header("Location: ../Controller/CategoriaController.php?acao=listarCategorias&sucesso=" . urlencode("Categoria atualizada com sucesso!"));
            exit();
        }

        public function excluirCategoria() {
            session_start();
            if(!isset($_SESSION['id'])) { header("Location: ../index.php"); exit(); }

            $id_categoria = (int)$_GET['id_categoria'];
            $id_usuario = (int)$_SESSION['id'];

            try {
                $this->categoriaDAO->excluir($id_categoria, $id_usuario);
                header("Location: ../Controller/CategoriaController.php?acao=listarCategorias&sucesso=" . urlencode("Categoria excluída com sucesso!"));
            } catch (PDOException $e) {
                // Captura restrição de chave estrangeira (RESTRICT) do banco de dados
                if ($e->getCode() == 23000) {
                    $mensagem = "Não é possível excluir esta categoria pois existem produtos vinculados a ela.";
                } else {
                    $mensagem = "Erro interno ao tentar excluir a categoria.";
                }
                header("Location: ../Controller/CategoriaController.php?acao=listarCategorias&erro=" . urlencode($mensagem));
            }
            exit();
        }
    }

    // Roteamento estável
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
            case 'prepararEdicaoCategoria':
                $controller->prepararEdicaoCategoria();
                break;
            case 'atualizarCategoria':
                $controller->atualizarCategoria();
                break;
            case 'prepararCadastroCategoria':
                $controller->prepararCadastroCategoria();
                break;
        }
    }
?>