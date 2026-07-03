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

            $cliente = $this->usuarioDAO->buscarPorId($_SESSION['id']);

            /** @var Cliente $cliente */
            $cliente->setResponsavelTecnico($_POST['responsavel_tecnico']);
            $cliente->setEmail($_POST['email']);

            if (!empty($_POST['senha'])) {
                $cliente->setSenha($_POST['senha']);
            }

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
         * Realiza a exclusão da conta do cliente logado, limpa a sessão e 
         * redireciona para a tela de login.
         */
        public function excluirPerfil(){
            session_start();

            if(!isset($_SESSION['id']) || $_SESSION['tipo'] != 'cliente'){
                header("Location: ../index.php");
                exit();
            }

            // Remove o usuário do banco de dados (as chaves estrangeiras em CASCADE cuidam do resto)
            $this->usuarioDAO->excluir($_SESSION['id']);

            // Limpa e destroi as variáveis de sessão de forma segura
            session_unset();
            session_destroy();

            // Redireciona corretamente para a View de Login
            header("Location: ../View/Login.php");
            exit();
        }

        /**
         * Mantém a sessão sincronizada com os dados atuais do usuário.
         * Mapeia o 'responsavel_tecnico' para a variável de sessão usada nas Views.
         */
        private function sincronizarSessao(Cliente $cliente): void {
            $_SESSION['nome'] = $cliente->getNomeEmpresa(); 
            $_SESSION['email'] = $cliente->getEmail();
            $_SESSION['foto_perfil'] = $cliente->getFotoPerfil();
        }

    }

    // Bloco de Roteamento Corrigido
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
            case 'excluirPerfil':
                $controller->excluirPerfil();
                break;
            default:
                $controller->dashboard();
                break;
        }
    }
?>