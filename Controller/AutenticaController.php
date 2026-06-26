<?php
        require_once __DIR__ . '/../Model/DAO/UsuarioDAO.php';

        class AutenticaController {

            private UsuarioDAO $usuarioDAO;

            public function __construct() {
                $this->usuarioDAO = new UsuarioDAO();
            }

            public function logar(){
                session_start();

                $email = $_POST['email'];
                $senha = $_POST['senha'];

                // A DAO já devolve o objeto certo (Cliente ou Administrador),
                // hidratado e com senha verificada — sem arrays soltos no meio do caminho.
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

        // Lógica do roteamento por meio da variavel acao presente nos forms.

        if(isset($_REQUEST['acao'])){

            $controller = new AutenticaController();

            switch($_REQUEST['acao']){
                case 'logar':
                $controller->logar();
                break;
                case 'logout':
                    $controller->logout();
                    break;
            }
        }

?>
