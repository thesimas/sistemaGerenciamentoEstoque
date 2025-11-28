<?php
        require_once __DIR__ . "/../Model/Cliente.php";
        require_once __DIR__ . "/../Model/Administrador.php";

        class AutenticaController {

            public function logar(){
                $email = $_POST['email'];
                $senha = $_POST['senha'];

                // Chamando um método estático para verificar o login, pelo fato dele ser estático, não há necessidade de instaciar um objeto, para posteriormente chamar o método.

                $dadosUsuario = Usuario::verificaLogin($email, $senha);

                if($dadosUsuario){
                    session_start();

                    $_SESSION['id'] = $dadosUsuario['id'];
                    $_SESSION['nome'] = $dadosUsuario['nome'];
                    $_SESSION['email'] = $dadosUsuario['email'];
                    $_SESSION['tipo'] = $dadosUsuario['tipo'];

                    $usuario = null;

                    if($dadosUsuario['tipo'] === 'admin'){
                    $usuario = new Administrador($dadosUsuario['nome'], $dadosUsuario['email'], $dadosUsuario['senha']);
                    } else {
                        $usuario = new Cliente($dadosUsuario['nome'], $dadosUsuario['email'], $dadosUsuario['senha'], null);
                    }

                    $endereco = $usuario->enderecoPaginaInicial();

                    header("Location: " . $endereco);
                    exit();
                } else {
                    echo "<p>Login ou senha incorretos. Tente novamente.</p>"; // Corrigir esse echo, pois ele irá direcionar para uma página em branco. 
                    header("Refresh: 3");
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