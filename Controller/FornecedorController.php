<?php
    require_once __DIR__ . '/../Model/Fornecedor.php';

    class FornecedorController {
        
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
            $fornecedor = new Fornecedor(null, null, null, null, null);
            
            $listaFornecedores = $fornecedor->listarFornecedores($_SESSION['id']);

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
            $id_usuario = $_SESSION['id'];

            if(empty($nomeEmpresa) || empty($cnpj) || empty($email) || empty($telefone)){
                echo "<p>Todos os campos são obrigatórios.</p>";
                header("Refresh: 2; URL=../Controller/FornecedorController.php?acao=prepararCadastro");
                exit();
            }

            $fornecedor = new Fornecedor($nomeEmpresa, $cnpj, $email, $telefone, $id_usuario);
            $fornecedor->salvar();

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

            $fornecedor = new Fornecedor(null, null, null, null, null);
            $fornecedor->excluirFornecedor($id_usuario, $id_fornecedor);

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
                $fornecedorModel = new Fornecedor(null, null, null, null, null);
                $dadosFornecedor = $fornecedorModel->buscarPorId($_GET['id_fornecedor'], $_SESSION['id']);

                require_once __DIR__ . '/../View/Fornecedor/EditarFornecedor.php';
            }
        }

        public function atualizarFornecedor(){
            session_start();

            if(!isset($_SESSION['id'])){
                header("Location: ../index.php");
                exit();
            }

            $fornecedor = new Fornecedor(null, null, null, null, null);

            $fornecedor ->atualizar($_POST['id'], $_POST['nome_empresa'], $_POST['cnpj'], $_POST['email'], $_POST['telefone'], $_SESSION['id']);
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