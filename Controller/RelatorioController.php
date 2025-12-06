<?php
require_once __DIR__ . '/../Model/Relatorio.php';

class RelatorioController {

    public function prepararMenu(){
        session_start();
        if(!isset($_SESSION['id'])){ header("Location: ../index.php"); exit(); }
        
        require_once __DIR__ . '/../View/Relatorio/MenuRelatorios.php';
    }

    public function gerarTotalEstoque(){
        session_start();
        if(!isset($_SESSION['id'])){ header("Location: ../index.php"); exit(); }

        $relatorio = new Relatorio();
        
        $listaEstoque = $relatorio->gerarTotalEstoque($_SESSION['id']);

        
        require_once __DIR__ . '/../View/Relatorio/ResultadoFinanceiro.php';
    }

    public function gerarHistorico(){
        session_start();
        if(!isset($_SESSION['id'])){ header("Location: ../index.php"); exit(); }

        $inicio = $_POST['data_inicio'];
        $fim    = $_POST['data_fim'];

        $relatorio = new Relatorio();
        
        $listaMovimentacoes = $relatorio->gerarHistoricoMovimentacoesPorData($inicio, $fim, $_SESSION['id']);

        require_once __DIR__ . '/../View/Relatorio/ResultadoHistorico.php';
    }

    public function gerarMaisMovimentados(){
        session_start();
        if(!isset($_SESSION['id'])){ header("Location: ../index.php"); exit(); }

        $tipo = $_POST['tipo']; 

        $relatorio = new Relatorio();
        
        $listaRanking = $relatorio->geraProdutosMaisVendidos($_SESSION['id'], $tipo);

        require_once __DIR__ . '/../View/Relatorio/ResultadoRanking.php';
    }
}

if(isset($_REQUEST['acao'])){
    $controller = new RelatorioController();
    switch($_REQUEST['acao']){
        case 'menu':
            $controller->prepararMenu();
            break;
        case 'gerarTotalEstoque':
            $controller->gerarTotalEstoque();
            break;
        case 'gerarHistorico':
            $controller->gerarHistorico();
            break;
        case 'gerarMaisMovimentados':
            $controller->gerarMaisMovimentados();
            break;
        default:
            $controller->prepararMenu();
    }
}