<?php
    require_once __DIR__ . '/../Model/Configuracao.php';
    require_once __DIR__ . '/../Model/Cliente.php';
    require_once __DIR__ . '/../Model/DAO/ConfiguracaoDAO.php';

    class ConfiguracaoController {

        private ConfiguracaoDAO $configuracaoDAO;

        public function __construct() {
            $this->configuracaoDAO = new ConfiguracaoDAO();
        }

        public function buscarConfiguracoes(){
            session_start();

            if(!isset($_SESSION['id']) || $_SESSION['tipo'] != 'cliente'){
                header("Location: ../index.php");
                exit();
            }

            $configuracao = $this->configuracaoDAO->buscarPorUsuario($_SESSION['id']);

            if (!$configuracao) {
                // Primeira vez que esse cliente acessa: cria a linha padrão dele.
                $usuarioTenant = new Cliente(null, null, null, null);
                $usuarioTenant->setId($_SESSION['id']);

                $configuracao = new Configuracao(null, $usuarioTenant);
                $this->configuracaoDAO->criar($configuracao);
            }

            require_once __DIR__ . '/../View/Configuracao/Configuracoes.php';
        }

        public function atualizarConfiguracoes(){
            session_start();

            if(!isset($_SESSION['id']) || $_SESSION['tipo'] != 'cliente'){
                header("Location: ../index.php");
                exit();
            }

            // Ainda não há campos de configuração reais na tabela (veja
            // Model/Configuracao.php) — quando existirem, monte o objeto
            // a partir do $_POST aqui e chame a DAO para persistir.

            header("Location: ../Controller/ConfiguracaoController.php?acao=buscarConfiguracoes");
            exit();
        }

    }

    //Roteamento
    if(isset($_REQUEST['acao'])){
        $controller = new ConfiguracaoController();
        switch($_REQUEST['acao']){
            case 'buscarConfiguracoes':
                $controller->buscarConfiguracoes();
                break;
            case 'atualizarConfiguracoes':
                $controller->atualizarConfiguracoes();
                break;
            default:
                $controller->buscarConfiguracoes();
                break;
        }
    }

?>
