<?php
    require_once __DIR__ . '/../Model/DAO/UsuarioDAO.php';
    require_once __DIR__ . '/../Model/Cliente.php';

    class ClienteController {

        private UsuarioDAO $usuarioDAO;

        public function __construct() {
            $this->usuarioDAO = new UsuarioDAO();
        }

        public function dashboard(){
            session_start();

            if(!isset($_SESSION['id']) || $_SESSION['tipo'] != 'cliente'){
                header("Location: ../index.php");
                exit();
            }

            $cliente = $this->usuarioDAO->buscarPorId($_SESSION["id"]);
            $this->sincronizarSessao($cliente);

            require_once __DIR__ . '/../View/Cliente/Dashboard.php';
        }

        public function perfil(){
            session_start();

            if(!isset($_SESSION['id']) || $_SESSION['tipo'] != 'cliente'){
                header("Location: ../index.php");
                exit();
            }

            $dadosCliente = $this->usuarioDAO->buscarPorId($_SESSION['id']);
            $this->sincronizarSessao($dadosCliente);

            require_once __DIR__ . '/../View/Cliente/Perfil.php';
        }

        public function prepararEdicaoPerfil(){
            session_start();

            if(!isset($_SESSION['id']) || $_SESSION['tipo'] != 'cliente'){
                header("Location: ../index.php");
                exit();
            }

            $dadosCliente = $this->usuarioDAO->buscarPorId($_SESSION['id']);

            require_once __DIR__ . '/../View/Cliente/EditarPerfil.php';
        }

        public function atualizarPerfil(){
            session_start();

            if(!isset($_SESSION['id']) || $_SESSION['tipo'] != 'cliente'){
                header("Location: ../index.php");
                exit();
            }

            // Buscamos o objeto já existente para preservar campos não enviados no form
            // (ex.: nomeEmpresa) e para não sobrescrever a senha com hash vazio.
            $cliente = $this->usuarioDAO->buscarPorId($_SESSION['id']);

            $cliente->setNome($_POST['nome']);
            $cliente->setEmail($_POST['email']);

            if (!empty($_POST['senha'])) {
                $cliente->setSenha($_POST['senha']);
            }
            // Se a senha vier vazia, mantemos o hash atual — a DAO sempre faz
            // password_hash() do que estiver em getSenha(), então é importante
            // não reduzi-lo a uma senha vazia aqui.

            $fotoPerfil = $_FILES['foto_perfil'] ?? null;

            if($fotoPerfil && $fotoPerfil['error'] === UPLOAD_ERR_OK){
                $extensao = strtolower(pathinfo($fotoPerfil['name'], PATHINFO_EXTENSION));
                $nomeFoto = uniqid('foto_perfil_') . '.' . $extensao;
                $diretorioDestino = __DIR__ . '/../View/Assets/Imagens/Uploads/';

                if(!is_dir($diretorioDestino)){
                    mkdir($diretorioDestino, 0755, true);
                }

                $caminhoCompleto = $diretorioDestino . $nomeFoto;

                if(move_uploaded_file($fotoPerfil['tmp_name'], $caminhoCompleto)){
                    $cliente->setFotoPerfil($nomeFoto);
                }
            }

            $this->usuarioDAO->atualizarPerfil($cliente);

            header("Location: ../Controller/ClienteController.php?acao=perfil");
            exit();
        }

        /**
         * Mantém a sessão sincronizada com os dados atuais do usuário.
         * Centralizado aqui porque dashboard() e perfil() faziam a mesma coisa
         * repetida em três blocos de if/else.
         */
        private function sincronizarSessao(Cliente $cliente): void {
            $_SESSION['nome'] = $cliente->getNome();
            $_SESSION['email'] = $cliente->getEmail();
            $_SESSION['foto_perfil'] = $cliente->getFotoPerfil();
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
            default:
                $controller->dashboard();
                break;
        }
    }
?>
