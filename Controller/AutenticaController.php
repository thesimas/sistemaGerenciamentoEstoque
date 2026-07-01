<?php
    require_once __DIR__ . '/../Model/DAO/UsuarioDAO.php';

    class AutenticaController {

        private UsuarioDAO $usuarioDAO;

        public function __construct() {
            $this->usuarioDAO = new UsuarioDAO();
        }

        public function cadastrar(){
            session_start();

            if($this->usuarioDAO->emailExiste($_POST['email'])){
                $_SESSION['erro_cadastro'] = "Este email já está cadastrado.";
                header("Location: ../View/Cadastro.php");
                exit();
            }

            $foto = null;

            if(isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] == UPLOAD_ERR_OK){
                $extensao = strtolower(pathinfo($_FILES['foto_perfil']['name'], PATHINFO_EXTENSION));
                $nomeFoto = uniqid("perfil_") . "." . $extensao;
                $destino = __DIR__ . "/../View/Assets/Imagens/Uploads/";

                if(!is_dir($destino)){
                    mkdir($destino, 0755, true);
                }

                move_uploaded_file($_FILES['foto_perfil']['tmp_name'], $destino.$nomeFoto);
                $foto = $nomeFoto;
            }

            $cliente = new Cliente(
                $_POST['responsavel_tecnico'], 
                $_POST['email'],
                $_POST['senha'],
                $_POST['nome_empresa'],
                $_POST['responsavel_tecnico'], 
                $foto
            );

            $id = $this->usuarioDAO->inserir($cliente);
            $cliente->setId($id);

            $_SESSION['id'] = $cliente->getId();
            $_SESSION['nome'] = $cliente->getResponsavelTecnico();
            $_SESSION['email'] = $cliente->getEmail();
            $_SESSION['foto_perfil'] = $cliente->getFotoPerfil();
            $_SESSION['tipo'] = "cliente";

            header("Location: ".$cliente->enderecoPaginaInicial());
            exit();
        }

        public function logar(){
            session_start();

            $email = $_POST['email'];
            $senha = $_POST['senha'];

            $usuario = $this->usuarioDAO->verificarLogin($email, $senha);

            if($usuario){
                $_SESSION['id'] = $usuario->getId();
                $_SESSION['nome'] = $usuario->getNome(); 
                $_SESSION['email'] = $usuario->getEmail();
                $_SESSION['foto_perfil'] = $usuario->getFotoPerfil();
                $_SESSION['tipo'] = ($usuario instanceof Administrador) ? 'admin' : 'cliente';

                $endereco = $usuario->enderecoPaginaInicial();
                header("Location: " . $endereco);
                exit();
            } else {
                $_SESSION['erro_login'] = "Email ou senha inválidos.";
                header("Location: ../View/Login.php");
                exit();
            }
        }

        public function logout(){
            session_start();
            session_unset();
            session_destroy();
            header("Location: ../View/Login.php");
            exit();
        }
    }

    if(isset($_REQUEST['acao'])){
        $controller = new AutenticaController();
        switch($_REQUEST['acao']){
            case 'logar':
                $controller->logar();
                break;
            case 'logout':
                $controller->logout();
                break;
            case 'cadastrar':
                $controller->cadastrar();
                break;
            default:
                http_response_code(400);
                echo "Ação inválida.";
                break;
        }
    }
?>