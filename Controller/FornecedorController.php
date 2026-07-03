<?php
    require_once __DIR__ . '/../Model/Fornecedor.php';
    require_once __DIR__ . '/../Model/Cliente.php';
    require_once __DIR__ . '/../Model/DAO/FornecedorDAO.php';

    class FornecedorController {

        private FornecedorDAO $fornecedorDAO;

        public function __construct() {
            $this->fornecedorDAO = new FornecedorDAO();
        }

        // Responsável por chamar a view de cadastro
        public function prepararCadastroFornecedor(){
            session_start();
            if(!isset($_SESSION['id'])){ header("Location: ../index.php"); exit(); }

            require_once __DIR__ . '/../View/Fornecedor/CadastrarFornecedor.php';
        }

        public function listarFornecedores(){
            session_start();

            if(!isset($_SESSION['id'])){
                header("Location: ../index.php");
                exit();
            }

            // Agora recebemos um array de OBJETOS Fornecedor
            $listaFornecedores = $this->fornecedorDAO->listarPorUsuario($_SESSION['id']);

            require_once __DIR__ . '/../View/Fornecedor/ListarFornecedor.php';
        }

        public function salvarFornecedor(){
            session_start();

            if(!isset($_SESSION['id'])){
                header("Location: ../index.php");
                exit();
            }

            $nomeEmpresa = $_POST['nome_empresa'];
            $cnpj = $_POST['cnpj'];
            $email = $_POST['email'];
            $telefone = $_POST['telefone'];

            if(empty($nomeEmpresa) || empty($cnpj) || empty($email) || empty($telefone)){
                $_SESSION['erro'] = "Todos os campos são obrigatórios.";
                header("Location: ../Controller/FornecedorController.php?acao=prepararCadastro");
                exit();
            }

            $usuarioTenant = new Cliente(null, null, null, null);
            $usuarioTenant->setId($_SESSION['id']);

            $fornecedor = new Fornecedor($nomeEmpresa, $cnpj, $email, $telefone, $usuarioTenant);
            $this->fornecedorDAO->inserir($fornecedor);

            header("Location: ../Controller/FornecedorController.php?acao=listarFornecedores");
            exit();
        }

        public function excluirFornecedor(){
            session_start();

            if(!isset($_SESSION['id'])){
                header("Location: ../index.php");
                exit();
            }

            $id_fornecedor = $_GET['id_fornecedor'];
            $id_usuario = $_SESSION['id'];

            $this->fornecedorDAO->excluir($id_fornecedor, $id_usuario);

            header("Location: ../Controller/FornecedorController.php?acao=listarFornecedores");
            exit();
        }

        public function prepararEdicaoFornecedor(){
            session_start();

            if(!isset($_SESSION['id'])){
                header("Location: ../index.php");
                exit();
            }

            if(isset($_GET['id_fornecedor'])){
                // O DAO retorna um OBJETO Fornecedor completo
                $fornecedor = $this->fornecedorDAO->buscarPorId($_GET['id_fornecedor'], $_SESSION['id']);

                if (!$fornecedor) {
                    header("Location: ../Controller/FornecedorController.php?acao=listarFornecedores");
                    exit();
                }

                require_once __DIR__ . '/../View/Fornecedor/EditarFornecedor.php';
            }
        }

        public function atualizarFornecedor(){
            session_start();

            if(!isset($_SESSION['id'])){
                header("Location: ../index.php");
                exit();
            }

            $usuarioTenant = new Cliente(null, null, null, null);
            $usuarioTenant->setId($_SESSION['id']);

            $fornecedor = new Fornecedor(
                $_POST['nome_empresa'],
                $_POST['cnpj'],
                $_POST['email'],
                $_POST['telefone'],
                $usuarioTenant
            );
            $fornecedor->setId($_POST['id']);

            $this->fornecedorDAO->atualizar($fornecedor);

            header("Location: ../Controller/FornecedorController.php?acao=listarFornecedores");
            exit();
        }
    }

    // Roteamento
    if(isset($_REQUEST['acao'])){
        $controller = new FornecedorController();

        switch($_REQUEST['acao']){
            case 'listarFornecedores':
                $controller->listarFornecedores();
                break;
            case 'salvarFornecedor':
                $controller->salvarFornecedor();
                break;
            case 'excluirFornecedor':
                $controller->excluirFornecedor();
                break;
            case 'prepararCadastro':
                $controller->prepararCadastroFornecedor();
                break;
            case 'atualizarFornecedor':
                $controller->atualizarFornecedor();
                break;
            case 'prepararEdicaoFornecedor':
                $controller->prepararEdicaoFornecedor();
                break;
            default:
                $controller->listarFornecedores();
                break;
        }
    }
?>
